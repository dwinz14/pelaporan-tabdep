<?php

namespace App\Services;

use App\Contracts\Repositories\LaporanRepositoryInterface;
use App\Enums\StatusVerifikasi;
use App\Models\Laporan;
use Illuminate\Validation\ValidationException;
use App\Services\NotificationService;

class LaporanService
{
    public function __construct(
        private readonly LaporanRepositoryInterface $repo,
        private readonly NotificationService $notifService,
    ) {}

    /**
     * Simpan draft laporan (status tetap draft/revision_requested).
     */
    public function saveDraft(Laporan $laporan, array $data): Laporan
    {
        $this->assertCanEdit($laporan);

        $saldoAkhir = $this->hitungSaldo($laporan->saldo_awal, $data);

        $updated = $this->repo->update($laporan, [
            'tambahan_stok'         => $data['tambahan_stok'],
            'jumlah_digunakan'      => $data['jumlah_digunakan'],
            'jml_dibatalkan_rusak'  => $data['jml_dibatalkan_rusak'],
            'jml_dibatalkan_hilang' => $data['jml_dibatalkan_hilang'],
            'saldo_akhir'           => $saldoAkhir,
        ]);

        activity('laporan')
            ->performedOn($updated)
            ->causedBy(auth()->user())
            ->withProperties([
                'jenis'      => $laporan->jenis->label(),
                'saldo_akhir' => $saldoAkhir,
            ])
            ->log("Draft {$laporan->jenis->label()} cabang {$laporan->cabang->kode_cabang} disimpan");

        return $updated;
    }

    /**
     * Submit laporan ke akunting.
     */
    public function submit(Laporan $laporan, array $data): Laporan
    {
        $this->assertCanEdit($laporan);

        $saldoAkhir = $this->hitungSaldo($laporan->saldo_awal, $data);

        if ($saldoAkhir < 0) {
            throw ValidationException::withMessages([
                'saldo_akhir' => 'Saldo akhir tidak boleh negatif. Periksa kembali data yang diinput.',
            ]);
        }

        $updated = $this->repo->update($laporan, [
            'tambahan_stok'         => $data['tambahan_stok'],
            'jumlah_digunakan'      => $data['jumlah_digunakan'],
            'jml_dibatalkan_rusak'  => $data['jml_dibatalkan_rusak'],
            'jml_dibatalkan_hilang' => $data['jml_dibatalkan_hilang'],
            'saldo_akhir'           => $saldoAkhir,
            'status_verifikasi'     => StatusVerifikasi::Submitted,
            'tgl_submit'            => now(),
            'catatan_revisi'        => null,
        ]);
        $this->notifService->notifyLaporanSubmitted($updated);

        activity('laporan')
            ->performedOn($updated)
            ->causedBy(auth()->user())
            ->withProperties([
                'jenis'      => $laporan->jenis->label(),
                'saldo_akhir' => $saldoAkhir,
            ])
            ->log("Laporan {$laporan->jenis->label()} cabang {$laporan->cabang->kode_cabang} disubmit");

        return $updated;
    }

    /**
     * Akunting menyetujui laporan cabang.
     */
    public function verifyAccounting(Laporan $laporan): Laporan
    {
        if ($laporan->periode->isLocked()) {
            abort(403, 'Periode sudah difinalisasi. Data tidak dapat diubah.');
        }

        if ($laporan->status_verifikasi !== StatusVerifikasi::Submitted) {
            abort(422, 'Hanya laporan berstatus "Submitted" yang bisa disetujui.');
        }

        $updated = $this->repo->update($laporan, [
            'status_verifikasi'       => StatusVerifikasi::VerifiedAccounting,
            'tgl_verifikasi_akunting' => now(),
            'verified_by_akunting'    => auth()->id(),
            'catatan_revisi'          => null,
        ]);
        $this->notifService->notifyLaporanDisetujui($updated);
        $this->notifService->notifyIfPeriodeSiapVerifikasi($updated);

        activity('laporan')
            ->performedOn($updated)
            ->causedBy(auth()->user())
            ->withProperties([
                'jenis'   => $laporan->jenis->label(),
                'cabang'  => $laporan->cabang->kode_cabang,
                'periode' => $laporan->periode->nama_periode,
            ])
            ->log("Laporan {$laporan->jenis->label()} cabang {$laporan->cabang->kode_cabang} diverifikasi akunting");

        return $updated;
    }

    /**
     * Akunting meminta revisi ke PIC cabang.
     */
    public function requestRevision(Laporan $laporan, string $catatan): Laporan
    {
        if ($laporan->periode->isLocked()) {
            abort(403, 'Periode sudah difinalisasi. Data tidak dapat diubah.');
        }

        if ($laporan->status_verifikasi !== StatusVerifikasi::Submitted) {
            abort(422, 'Hanya laporan berstatus "Submitted" yang bisa diminta revisi.');
        }

        $updated = $this->repo->update($laporan, [
            'status_verifikasi' => StatusVerifikasi::RevisionRequested,
            'catatan_revisi'    => trim($catatan),
        ]);
        $this->notifService->notifyLaporanRevisi($updated);

        activity('laporan')
            ->performedOn($updated)
            ->causedBy(auth()->user())
            ->withProperties([
                'jenis'   => $laporan->jenis->label(),
                'cabang'  => $laporan->cabang->kode_cabang,
                'catatan' => $catatan,
            ])
            ->log("Revisi diminta untuk laporan {$laporan->jenis->label()} cabang {$laporan->cabang->kode_cabang}");

        return $updated;
    }

    // ─── Private Helpers ──────────────────────────────────────

    private function hitungSaldo(int $saldoAwal, array $data): int
    {
        return $saldoAwal
            + $data['tambahan_stok']
            - $data['jumlah_digunakan']
            - $data['jml_dibatalkan_rusak']
            - $data['jml_dibatalkan_hilang'];
    }

    private function assertCanEdit(Laporan $laporan): void
    {
        if ($laporan->periode->isLocked()) {
            abort(403, 'Periode sudah difinalisasi. Data tidak dapat diubah.');
        }

        if (! $laporan->status_verifikasi->canEdit()) {
            abort(403, 'Laporan tidak dapat diedit dengan status saat ini.');
        }
    }
}
