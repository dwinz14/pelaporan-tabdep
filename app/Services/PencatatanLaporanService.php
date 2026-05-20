<?php

namespace App\Services;

use App\Enums\JenisLaporan;
use App\Enums\TipeTransaksi;
use App\Models\Laporan;
use App\Models\PencatatanLaporan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PencatatanLaporanService
{
    /**
     * Ambil semua pencatatan untuk cabang & periode tertentu.
     */
    public function getByPeriodeCabang(int $periodeId, int $cabangId): Collection
    {
        return PencatatanLaporan::where('id_cabang', $cabangId)
            ->where('id_periode', $periodeId)
            ->orderBy('tanggal_catat', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Buat pencatatan baru. Validasi: laporan jenis terkait harus masih bisa diedit.
     */
    public function create(array $data): PencatatanLaporan
    {
        $this->assertLaporanCanEdit($data['id_periode'], $data['id_cabang'], $data['jenis']);

        return DB::transaction(function () use ($data) {
            $pencatatan = PencatatanLaporan::create($data);

            activity('pencatatan')
                ->performedOn($pencatatan)
                ->causedBy(auth()->user())
                ->withProperties([
                    'jenis'  => $data['jenis'],
                    'tipe'   => $data['tipe_transaksi'],
                    'jumlah' => $data['jumlah'],
                ])
                ->log("Pencatatan {$data['jenis']} ditambahkan: {$data['tipe_transaksi']} {$data['jumlah']} buku");

            return $pencatatan;
        });
    }

    /**
     * Update pencatatan. Validasi: laporan jenis terkait harus masih bisa diedit.
     */
    public function update(PencatatanLaporan $pencatatan, array $data): PencatatanLaporan
    {
        $this->assertLaporanCanEdit(
            $pencatatan->id_periode,
            $pencatatan->id_cabang,
            $pencatatan->jenis->value
        );

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

    /**
     * Hapus pencatatan. Validasi: laporan jenis terkait harus masih bisa diedit.
     */
    public function delete(PencatatanLaporan $pencatatan): void
    {
        $this->assertLaporanCanEdit(
            $pencatatan->id_periode,
            $pencatatan->id_cabang,
            $pencatatan->jenis->value
        );

        DB::transaction(function () use ($pencatatan) {
            activity('pencatatan')
                ->causedBy(auth()->user())
                ->withProperties([
                    'jenis'  => $pencatatan->jenis->label(),
                    'tipe'   => $pencatatan->tipe_transaksi->label(),
                    'jumlah' => $pencatatan->jumlah,
                ])
                ->log("Pencatatan dihapus: {$pencatatan->tipe_transaksi->label()} {$pencatatan->jumlah} buku");

            $pencatatan->delete();
        });
    }

    /**
     * Kalkulasi agregat pencatatan per periode+cabang.
     * Hasil: array dengan key jenis → tipe → total.
     */
    public function getAgregat(int $periodeId, int $cabangId): array
    {
        $pencatatans = PencatatanLaporan::where('id_cabang', $cabangId)
            ->where('id_periode', $periodeId)
            ->get();

        $result = [];

        foreach (JenisLaporan::cases() as $jenis) {
            $filtered = $pencatatans->where('jenis', $jenis);
            foreach (TipeTransaksi::cases() as $tipe) {
                $result[$jenis->value][$tipe->value] = $filtered
                    ->where('tipe_transaksi', $tipe)
                    ->sum('jumlah');
            }
            $result[$jenis->value]['count'] = $filtered->count();
        }

        return $result;
    }

    // ─── Private ──────────────────────────────────────────────

    private function assertLaporanCanEdit(int $periodeId, int $cabangId, string $jenis): void
    {
        $laporan = Laporan::where('id_periode', $periodeId)
            ->where('id_cabang', $cabangId)
            ->where('jenis', $jenis)
            ->first();

        if (! $laporan) {
            abort(404, 'Laporan tidak ditemukan untuk periode ini.');
        }

        if (! $laporan->status_verifikasi->canEdit()) {
            abort(403, "Laporan {$jenis} periode ini sudah disubmit. Pencatatan tidak dapat diubah.");
        }

        if ($laporan->periode->isLocked()) {
            abort(403, 'Periode sudah diverifikasi final. Data tidak dapat diubah.');
        }
    }
}
