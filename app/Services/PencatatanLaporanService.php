<?php

namespace App\Services;

use App\Enums\JenisLaporan;
use App\Enums\StatusVerifikasi;
use App\Enums\TipeTransaksi;
use App\Models\Laporan;
use App\Models\PencatatanLaporan;
use App\Models\PeriodeLaporan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PencatatanLaporanService
{
    // ─────────────────────────────────────────────────────────
    // LOCK DATE LOGIC
    // lock_date = tanggal_akhir periode terbaru yang laporan
    // cabang+jenis-nya sudah submitted atau verified_accounting
    // ─────────────────────────────────────────────────────────

    public function getLockDate(int $cabangId, JenisLaporan $jenis): ?Carbon
    {
        $tanggal = PeriodeLaporan::whereHas(
            'laporans',
            fn($q) =>
            $q->where('id_cabang', $cabangId)
                ->where('jenis', $jenis)
                ->whereIn('status_verifikasi', [
                    StatusVerifikasi::Submitted->value,
                    StatusVerifikasi::VerifiedAccounting->value,
                ])
        )
            ->max('tanggal_akhir');

        return $tanggal ? Carbon::parse($tanggal) : null;
    }

    /**
     * Tanggal tersedia untuk pencatatan jika > lock_date.
     * Jika tidak ada lock_date (belum pernah submit), semua tanggal tersedia.
     */
    public function isDateAvailable(int $cabangId, JenisLaporan $jenis, string $tanggal): bool
    {
        $lockDate = $this->getLockDate($cabangId, $jenis);

        if (! $lockDate) return true;

        return Carbon::parse($tanggal)->gt($lockDate);
    }

    // ─────────────────────────────────────────────────────────
    // STOK TERKINI
    // ─────────────────────────────────────────────────────────

    /**
     * Hitung estimasi stok terkini per jenis.
     * = saldo_akhir laporan terakhir yang submitted/verified
     * + semua pencatatan sejak (lock_date + 1 hari) s/d hari ini
     */
    public function getStokTerkini(int $cabangId): array
    {
        $result = [];

        foreach (JenisLaporan::cases() as $jenis) {
            $lockDate = $this->getLockDate($cabangId, $jenis);

            // Laporan terakhir yang submitted/verified
            $lastLaporan = Laporan::where('id_cabang', $cabangId)
                ->where('jenis', $jenis)
                ->whereIn('status_verifikasi', [
                    StatusVerifikasi::Submitted->value,
                    StatusVerifikasi::VerifiedAccounting->value,
                ])
                ->join('periode_laporans', 'laporans.id_periode', '=', 'periode_laporans.id')
                ->orderBy('periode_laporans.tanggal_akhir', 'desc')
                ->select('laporans.*', 'periode_laporans.nama_periode as nama_periode_ref')
                ->first();

            $saldoBase = $lastLaporan?->saldo_akhir ?? 0;

            // Pencatatan setelah lock_date s/d hari ini
            $query = PencatatanLaporan::where('id_cabang', $cabangId)
                ->where('jenis', $jenis);

            if ($lockDate) {
                $query->where('tanggal_catat', '>', $lockDate->format('Y-m-d'));
            }

            $pencatatans = $query->get();

            $masuk  = $pencatatans->filter(fn($p) => $p->tipe_transaksi === TipeTransaksi::TambahanStok)->sum('jumlah');
            $keluar = $pencatatans->filter(fn($p) => $p->tipe_transaksi !== TipeTransaksi::TambahanStok)->sum('jumlah');

            $result[$jenis->value] = [
                'saldo_base'  => $saldoBase,
                'saldo_label' => $lastLaporan?->nama_periode_ref ?? 'Belum ada laporan',
                'saldo_at'    => $lockDate?->format('d/m/Y'),
                'masuk'       => $masuk,
                'keluar'      => $keluar,
                'terkini'     => $saldoBase + $masuk - $keluar,
                'lock_date'   => $lockDate?->format('d/m/Y'),
                'since'       => $lockDate ? $lockDate->copy()->addDay()->format('d/m/Y') : 'Awal',
                'pencatatan_count' => $pencatatans->count(),
            ];
        }

        return $result;
    }

    // ─────────────────────────────────────────────────────────
    // LOG
    // ─────────────────────────────────────────────────────────

    public function getLog(int $cabangId, array $filters): Collection
    {
        // Default: 30 hari terakhir
        $dari    = $filters['dari']    ?? now()->subDays(30)->format('Y-m-d');
        $sampai  = $filters['sampai']  ?? now()->format('Y-m-d');

        return PencatatanLaporan::where('id_cabang', $cabangId)
            ->when($filters['jenis'] ?? null, fn($q, $v) => $q->where('jenis', $v))
            ->when($filters['tipe']  ?? null, fn($q, $v) => $q->where('tipe_transaksi', $v))
            ->whereBetween('tanggal_catat', [$dari, $sampai])
            ->orderBy('tanggal_catat', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // ─────────────────────────────────────────────────────────
    // SYNC RANGE (untuk sinkronisasi ke laporan)
    // ─────────────────────────────────────────────────────────

    /**
     * Hitung rentang tanggal pencatatan yang relevan untuk sebuah periode laporan.
     * FROM: tanggal_akhir periode submitted sebelumnya + 1 hari
     * TO:   tanggal_akhir periode ini
     */
    public function getSyncRange(PeriodeLaporan $periode, int $cabangId, JenisLaporan $jenis): array
    {
        $prevPeriode = PeriodeLaporan::whereHas(
            'laporans',
            fn($q) =>
            $q->where('id_cabang', $cabangId)
                ->where('jenis', $jenis)
                ->whereIn('status_verifikasi', [
                    StatusVerifikasi::Submitted->value,
                    StatusVerifikasi::VerifiedAccounting->value,
                ])
        )
            ->where('tanggal_akhir', '<', $periode->tanggal_akhir->format('Y-m-d'))
            ->orderBy('tanggal_akhir', 'desc')
            ->first();

        $from = $prevPeriode
            ? Carbon::parse($prevPeriode->tanggal_akhir)->addDay()->format('Y-m-d')
            : '2000-01-01';

        $to = $periode->tanggal_akhir->format('Y-m-d');

        return [
            'from'          => $from,
            'to'            => $to,
            'from_display'  => Carbon::parse($from)->format('d/m/Y'),
            'to_display'    => Carbon::parse($to)->format('d/m/Y'),
            'prev_periode'  => $prevPeriode,
        ];
    }

    // ─────────────────────────────────────────────────────────
    // CRUD
    // ─────────────────────────────────────────────────────────

    public function create(array $data): PencatatanLaporan
    {
        $jenis = JenisLaporan::from($data['jenis']);

        $this->assertCanMutate($data['id_cabang'], $jenis, $data['tanggal_catat']);

        return DB::transaction(function () use ($data, $jenis) {
            $p = PencatatanLaporan::create($data);

            activity('pencatatan')
                ->performedOn($p)
                ->causedBy(auth()->user())
                ->withProperties(['jenis' => $jenis->label(), 'tipe' => $data['tipe_transaksi'], 'jumlah' => $data['jumlah']])
                ->log("Pencatatan {$jenis->label()} ditambahkan: {$data['tipe_transaksi']} {$data['jumlah']} buku");

            return $p;
        });
    }

    public function update(PencatatanLaporan $pencatatan, array $data): PencatatanLaporan
    {
        // Cek tanggal asli masih bisa diedit
        $this->assertCanMutate(
            $pencatatan->id_cabang,
            $pencatatan->jenis,
            $pencatatan->tanggal_catat->format('Y-m-d')
        );

        // Jika jenis atau tanggal berubah, cek juga yang baru
        $newJenis   = JenisLaporan::from($data['jenis']);
        $newTanggal = $data['tanggal_catat'];

        if ($newJenis !== $pencatatan->jenis || $newTanggal !== $pencatatan->tanggal_catat->format('Y-m-d')) {
            $this->assertCanMutate($pencatatan->id_cabang, $newJenis, $newTanggal);
        }

        return DB::transaction(function () use ($pencatatan, $data) {
            $pencatatan->update([
                'jenis'          => $data['jenis'],
                'tipe_transaksi' => $data['tipe_transaksi'],
                'jumlah'         => $data['jumlah'],
                'keterangan'     => $data['keterangan'] ?? null,
                'tanggal_catat'  => $data['tanggal_catat'],
            ]);

            activity('pencatatan')
                ->performedOn($pencatatan)
                ->causedBy(auth()->user())
                ->log("Pencatatan diperbarui: {$pencatatan->tipe_transaksi->label()} {$pencatatan->jumlah} buku");

            return $pencatatan->fresh();
        });
    }

    public function delete(PencatatanLaporan $pencatatan): void
    {
        $this->assertCanMutate(
            $pencatatan->id_cabang,
            $pencatatan->jenis,
            $pencatatan->tanggal_catat->format('Y-m-d')
        );

        DB::transaction(function () use ($pencatatan) {
            activity('pencatatan')
                ->causedBy(auth()->user())
                ->withProperties(['jenis' => $pencatatan->jenis->label(), 'tipe' => $pencatatan->tipe_transaksi->label(), 'jumlah' => $pencatatan->jumlah])
                ->log("Pencatatan dihapus: {$pencatatan->tipe_transaksi->label()} {$pencatatan->jumlah} buku");

            $pencatatan->delete();
        });
    }

    // ─────────────────────────────────────────────────────────
    // PRIVATE
    // ─────────────────────────────────────────────────────────

    private function assertCanMutate(int $cabangId, JenisLaporan $jenis, string $tanggal): void
    {
        if ($this->isDateAvailable($cabangId, $jenis, $tanggal)) return;

        $lockDate = $this->getLockDate($cabangId, $jenis);

        abort(
            403,
            "Tanggal " . Carbon::parse($tanggal)->format('d/m/Y') .
                " sudah terkunci. Laporan {$jenis->label()} s/d " .
                $lockDate->format('d/m/Y') . " sudah disubmit dan tidak dapat diubah."
        );
    }
}
