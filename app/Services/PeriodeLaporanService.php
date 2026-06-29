<?php

namespace App\Services;

use App\Contracts\Repositories\LaporanRepositoryInterface;
use App\Contracts\Repositories\PeriodeLaporanRepositoryInterface;
use App\Enums\JenisLaporan;
use App\Enums\StatusOperasional;
use App\Enums\StatusVerifikasi;
use App\Models\Cabang;
use App\Models\Laporan;
use App\Models\PeriodeLaporan;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PeriodeLaporanService
{
    public function __construct(
        private readonly PeriodeLaporanRepositoryInterface $repo,
        private readonly LaporanRepositoryInterface $laporanRepo,
        private readonly NotificationService $notifService,
    ) {}

    public function list(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->repo->paginate($perPage, $filters);
    }

    public function getCurrent(): ?PeriodeLaporan
    {
        return $this->repo->getCurrent();
    }

    public function findById(int $id): PeriodeLaporan
    {
        return $this->repo->findById($id);
    }

    public function isDuplicate(string $tanggal): bool
    {
        return $this->repo->existsByTanggal($tanggal);
    }

    /**
     * Generate periode baru beserta semua laporan untuk tiap cabang aktif.
     */
    public function generate(string $tanggalAkhir): PeriodeLaporan
    {
        return DB::transaction(function () use ($tanggalAkhir) {
            $tgl = Carbon::parse($tanggalAkhir);

            // Reset semua is_current ke false
            $this->repo->resetAllCurrent();

            // Buat periode baru
            $periode = $this->repo->create([
                'tanggal_akhir'      => $tgl->format('Y-m-d'),
                'nama_periode'       => 'Periode ' . $tgl->locale('id')->isoFormat('D MMMM Y'),
                'status_operasional' => StatusOperasional::Pending,
                'is_current'         => true,
            ]);

            // Cari periode sebelumnya (untuk saldo_awal)
            $prevPeriode = PeriodeLaporan::where('id', '<', $periode->id)
                ->orderBy('id', 'desc')
                ->first();

            // Generate laporan untuk setiap cabang aktif × setiap jenis
            $cabangs = Cabang::where('is_active', true)->orderBy('kode_cabang')->get();

            foreach ($cabangs as $cabang) {
                foreach (JenisLaporan::cases() as $jenis) {
                    $saldoAwal = 0;

                    if ($prevPeriode) {
                        $prevLaporan = $this->laporanRepo->findByCabangPeriodeJenis(
                            $cabang->id,
                            $prevPeriode->id,
                            $jenis,
                        );
                        $saldoAwal = $prevLaporan?->saldo_akhir ?? 0;
                    }

                    $this->laporanRepo->create([
                        'id_cabang'             => $cabang->id,
                        'id_periode'            => $periode->id,
                        'jenis'                 => $jenis,
                        'saldo_awal'            => $saldoAwal,
                        'tambahan_stok'         => 0,
                        'jumlah_digunakan'      => 0,
                        'jml_dibatalkan_rusak'  => 0,
                        'jml_dibatalkan_hilang' => 0,
                        'saldo_akhir'           => $saldoAwal,
                        'status_verifikasi'     => StatusVerifikasi::Draft,
                    ]);
                }
            }

            activity('periode')
                ->performedOn($periode)
                ->causedBy(auth()->user())
                ->log("Periode {$periode->nama_periode} berhasil di-generate ({$cabangs->count()} cabang)");

            $this->notifService->notifyPeriodeBaru($periode);
            return $periode;
        });
    }

    /**
     * Kepala Operasional melakukan verifikasi final periode.
     */
    public function finalVerify(PeriodeLaporan $periode): PeriodeLaporan
    {
        if ($periode->isLocked()) {
            abort(422, 'Periode ini sudah diverifikasi final sebelumnya.');
        }

        if (! $periode->semuaCabangVerified()) {
            abort(422, 'Belum semua cabang terverifikasi oleh akunting. Pastikan semua laporan sudah disetujui.');
        }

        return DB::transaction(function () use ($periode) {
            $updated = $this->repo->update($periode, [
                'status_operasional'         => StatusOperasional::Verified,
                'tgl_verifikasi_operasional' => now(),
                'verified_by_operasional'    => auth()->id(),
                'is_current'                 => false,
            ]);

            activity('periode')
                ->performedOn($updated)
                ->causedBy(auth()->user())
                ->log("Periode {$periode->nama_periode} diverifikasi final oleh Kepala Operasional");

            return $updated;
        });
    }

    /**
     * Saran generate periode berikutnya.
     * Logika: tanggal_akhir periode terbaru + 14 hari.
     * Muncul ketika: today >= (tanggal_saran - 1 hari) DAN periode tersebut belum ada.
     */
    public function getSuggestedNextPeriode(): ?array
    {
        $latest = PeriodeLaporan::orderBy('tanggal_akhir', 'desc')->first();

        if (! $latest) return null;

        $nextDate = $latest->tanggal_akhir->copy()->addDays(14);

        // Jangan tampilkan jika periode berikutnya sudah ada
        if ($this->repo->existsByTanggal($nextDate->format('Y-m-d'))) {
            return null;
        }

        // Mulai tampilkan di H-1 (satu hari sebelum tanggal periode)
        $suggestionStart = $nextDate->copy()->subDay()->startOfDay();

        if (now()->startOfDay()->lt($suggestionStart)) {
            return null;
        }

        $isOverdue = now()->startOfDay()->gt($nextDate->copy()->startOfDay());

        return [
            'tanggal'      => $nextDate->format('Y-m-d'),
            'display'      => $nextDate->locale('id')->isoFormat('dddd, D MMMM Y'),
            'nama_periode' => 'Periode ' . $nextDate->locale('id')->isoFormat('D MMMM Y'),
            'is_overdue'   => $isOverdue,
            'prev_periode' => $latest->nama_periode,
            'days_diff'    => $isOverdue
                ? now()->startOfDay()->diffInDays($nextDate->copy()->startOfDay())
                : null,
        ];
    }
}
