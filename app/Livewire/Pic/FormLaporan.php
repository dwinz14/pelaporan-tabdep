<?php

namespace App\Livewire\Pic;

use App\Enums\JenisLaporan;
use App\Enums\StatusVerifikasi;
use App\Models\Laporan;
use App\Models\PeriodeLaporan;
use App\Services\LaporanService;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\PencatatanLaporan;
use App\Services\PencatatanLaporanService;

class FormLaporan extends Component
{
    public PeriodeLaporan $periode;

    // ─── Tabungan ─────────────────────────────────────────────
    public ?int    $laporanTabId           = null;
    public int     $saldoAwalTab           = 0;
    public int     $tambahanStokTab        = 0;
    public int     $jumlahDigunakanTab     = 0;
    public int     $jmlDibatalkanRusakTab  = 0;
    public int     $jmlDibatalkanHilangTab = 0;
    public string  $statusTab              = 'draft';
    public ?string $catatanRevisiTab       = null;
    public ?string $lastSavedTab           = null;

    // ─── Deposito ─────────────────────────────────────────────
    public ?int    $laporanDepId            = null;
    public int     $saldoAwalDep            = 0;
    public int     $tambahanStokDep         = 0;
    public int     $jumlahDigunakanDep      = 0;
    public int     $jmlDibatalkanRusakDep   = 0;
    public int     $jmlDibatalkanHilangDep  = 0;
    public string  $statusDep              = 'draft';
    public ?string $catatanRevisiDep       = null;
    public ?string $lastSavedDep           = null;

    // ─── Modal Submit ─────────────────────────────────────────
    public bool    $showSubmitModal    = false;
    public string  $pendingSubmitJenis = ''; // 'tabungan' | 'deposito'

    // ─── UI Flash ─────────────────────────────────────────────
    public ?string $flashSuccess = null;
    public ?string $flashError   = null;

    // ─── Mount ────────────────────────────────────────────────

    public function mount(PeriodeLaporan $periode): void
    {
        $this->periode = $periode;
        $cabangId      = auth()->user()->id_cabang;

        $this->loadLaporan($cabangId, JenisLaporan::Tabungan);
        $this->loadLaporan($cabangId, JenisLaporan::Deposito);
    }

    private function loadLaporan(int $cabangId, JenisLaporan $jenis): void
    {
        $laporan = Laporan::where('id_periode', $this->periode->id)
            ->where('id_cabang', $cabangId)
            ->where('jenis', $jenis)
            ->first();

        if (! $laporan) return;

        if ($jenis === JenisLaporan::Tabungan) {
            $this->laporanTabId           = $laporan->id;
            $this->saldoAwalTab           = $laporan->saldo_awal;
            $this->tambahanStokTab        = $laporan->tambahan_stok;
            $this->jumlahDigunakanTab     = $laporan->jumlah_digunakan;
            $this->jmlDibatalkanRusakTab  = $laporan->jml_dibatalkan_rusak;
            $this->jmlDibatalkanHilangTab = $laporan->jml_dibatalkan_hilang;
            $this->statusTab              = $laporan->status_verifikasi->value;
            $this->catatanRevisiTab       = $laporan->catatan_revisi;
            $this->lastSavedTab           = $laporan->updated_at
                ->locale('id')->isoFormat('D MMM YYYY, HH:mm');
        } else {
            $this->laporanDepId            = $laporan->id;
            $this->saldoAwalDep            = $laporan->saldo_awal;
            $this->tambahanStokDep         = $laporan->tambahan_stok;
            $this->jumlahDigunakanDep      = $laporan->jumlah_digunakan;
            $this->jmlDibatalkanRusakDep   = $laporan->jml_dibatalkan_rusak;
            $this->jmlDibatalkanHilangDep  = $laporan->jml_dibatalkan_hilang;
            $this->statusDep               = $laporan->status_verifikasi->value;
            $this->catatanRevisiDep        = $laporan->catatan_revisi;
            $this->lastSavedDep            = $laporan->updated_at
                ->locale('id')->isoFormat('D MMM YYYY, HH:mm');
        }
    }

    // ─── Computed ─────────────────────────────────────────────

    #[Computed(cache: false)]
    public function saldoAkhirTab(): int
    {
        return $this->saldoAwalTab
            + $this->tambahanStokTab
            - $this->jumlahDigunakanTab
            - $this->jmlDibatalkanRusakTab
            - $this->jmlDibatalkanHilangTab;
    }

    #[Computed(cache: false)]
    public function saldoAkhirDep(): int
    {
        return $this->saldoAwalDep
            + $this->tambahanStokDep
            - $this->jumlahDigunakanDep
            - $this->jmlDibatalkanRusakDep
            - $this->jmlDibatalkanHilangDep;
    }

    #[Computed(cache: false)]
    public function canEditTab(): bool
    {
        return ! $this->periode->isLocked()
            && StatusVerifikasi::from($this->statusTab)->canEdit();
    }

    #[Computed(cache: false)]
    public function canEditDep(): bool
    {
        return ! $this->periode->isLocked()
            && StatusVerifikasi::from($this->statusDep)->canEdit();
    }

    #[Computed(cache: false)]
    public function hasPencatatan(): bool
    {
        return PencatatanLaporan::where('id_cabang', auth()->user()->id_cabang)
            ->where('id_periode', $this->periode->id)
            ->exists();
    }

    #[Computed(cache: false)]
    public function pencatatanAgregat(): array
    {
        if (! $this->hasPencatatan) return [];

        return app(PencatatanLaporanService::class)
            ->getAgregat($this->periode->id, auth()->user()->id_cabang);
    }

    // sync
    public function syncFromPencatatan(): void
    {
        $this->resetFlash();

        if (! $this->hasPencatatan) {
            $this->flashError = 'Tidak ada pencatatan untuk periode ini.';
            return;
        }

        $ag = app(PencatatanLaporanService::class)
            ->getAgregat($this->periode->id, auth()->user()->id_cabang);

        // Sync Tabungan (hanya jika masih bisa diedit)
        if ($this->canEditTab) {
            $this->tambahanStokTab        = $ag['tabungan']['tambahan_stok']      ?? 0;
            $this->jumlahDigunakanTab     = $ag['tabungan']['digunakan']           ?? 0;
            $this->jmlDibatalkanRusakTab  = $ag['tabungan']['dibatalkan_rusak']    ?? 0;
            $this->jmlDibatalkanHilangTab = $ag['tabungan']['dibatalkan_hilang']   ?? 0;
        }

        // Sync Deposito (hanya jika masih bisa diedit)
        if ($this->canEditDep) {
            $this->tambahanStokDep        = $ag['deposito']['tambahan_stok']      ?? 0;
            $this->jumlahDigunakanDep     = $ag['deposito']['digunakan']           ?? 0;
            $this->jmlDibatalkanRusakDep  = $ag['deposito']['dibatalkan_rusak']    ?? 0;
            $this->jmlDibatalkanHilangDep = $ag['deposito']['dibatalkan_hilang']   ?? 0;
        }

        $this->flashSuccess = 'Form berhasil disinkronkan dari pencatatan. Periksa data lalu simpan atau submit.';
    }

    // ─── Save Draft ───────────────────────────────────────────

    public function saveDraftTabungan(): void
    {
        $this->resetFlash();
        if (! $this->canEditTab || ! $this->laporanTabId) return;

        $this->validateTab();

        app(LaporanService::class)->saveDraft(
            Laporan::findOrFail($this->laporanTabId),
            $this->payloadTab()
        );

        $this->lastSavedTab = now()->locale('id')->isoFormat('D MMM YYYY, HH:mm');
        $this->flashSuccess  = 'Catatan tabungan berhasil disimpan.';
    }

    public function saveDraftDeposito(): void
    {
        $this->resetFlash();
        if (! $this->canEditDep || ! $this->laporanDepId) return;

        $this->validateDep();

        app(LaporanService::class)->saveDraft(
            Laporan::findOrFail($this->laporanDepId),
            $this->payloadDep()
        );

        $this->lastSavedDep = now()->locale('id')->isoFormat('D MMM YYYY, HH:mm');
        $this->flashSuccess  = 'Catatan deposito berhasil disimpan.';
    }

    // ─── Modal Submit ─────────────────────────────────────────

    public function openSubmitModal(string $jenis): void
    {
        $this->resetFlash();

        if ($jenis === 'tabungan') {
            if (! $this->canEditTab || ! $this->laporanTabId) return;
            $this->validateTab();
            if ($this->saldoAkhirTab < 0) {
                $this->flashError = 'Saldo akhir tabungan tidak boleh negatif.';
                return;
            }
        } else {
            if (! $this->canEditDep || ! $this->laporanDepId) return;
            $this->validateDep();
            if ($this->saldoAkhirDep < 0) {
                $this->flashError = 'Saldo akhir deposito tidak boleh negatif.';
                return;
            }
        }

        $this->pendingSubmitJenis = $jenis;
        $this->showSubmitModal    = true;
    }

    public function closeSubmitModal(): void
    {
        $this->showSubmitModal    = false;
        $this->pendingSubmitJenis = '';
    }

    public function confirmSubmit(): void
    {
        if ($this->pendingSubmitJenis === 'tabungan') {
            $this->doSubmitTabungan();
        } else {
            $this->doSubmitDeposito();
        }

        $this->showSubmitModal    = false;
        $this->pendingSubmitJenis = '';
    }

    // ─── Internal Submit ──────────────────────────────────────

    private function doSubmitTabungan(): void
    {
        app(LaporanService::class)->submit(
            Laporan::findOrFail($this->laporanTabId),
            $this->payloadTab()
        );

        $this->statusTab    = StatusVerifikasi::Submitted->value;
        $this->lastSavedTab = now()->locale('id')->isoFormat('D MMM YYYY, HH:mm');
        $this->flashSuccess = 'Laporan tabungan berhasil disubmit ke akunting.';
    }

    private function doSubmitDeposito(): void
    {
        app(LaporanService::class)->submit(
            Laporan::findOrFail($this->laporanDepId),
            $this->payloadDep()
        );

        $this->statusDep    = StatusVerifikasi::Submitted->value;
        $this->lastSavedDep = now()->locale('id')->isoFormat('D MMM YYYY, HH:mm');
        $this->flashSuccess = 'Laporan deposito berhasil disubmit ke akunting.';
    }

    // ─── Helpers ──────────────────────────────────────────────

    private function validateTab(): void
    {
        $this->validate([
            'tambahanStokTab'        => ['required', 'integer', 'min:0'],
            'jumlahDigunakanTab'     => ['required', 'integer', 'min:0'],
            'jmlDibatalkanRusakTab'  => ['required', 'integer', 'min:0'],
            'jmlDibatalkanHilangTab' => ['required', 'integer', 'min:0'],
        ], [
            'tambahanStokTab.min'        => 'Tambahan stok tidak boleh negatif.',
            'jumlahDigunakanTab.min'     => 'Jumlah digunakan tidak boleh negatif.',
            'jmlDibatalkanRusakTab.min'  => 'Jumlah batal rusak tidak boleh negatif.',
            'jmlDibatalkanHilangTab.min' => 'Jumlah batal hilang tidak boleh negatif.',
        ]);
    }

    private function validateDep(): void
    {
        $this->validate([
            'tambahanStokDep'        => ['required', 'integer', 'min:0'],
            'jumlahDigunakanDep'     => ['required', 'integer', 'min:0'],
            'jmlDibatalkanRusakDep'  => ['required', 'integer', 'min:0'],
            'jmlDibatalkanHilangDep' => ['required', 'integer', 'min:0'],
        ], [
            'tambahanStokDep.min'        => 'Tambahan stok tidak boleh negatif.',
            'jumlahDigunakanDep.min'     => 'Jumlah digunakan tidak boleh negatif.',
            'jmlDibatalkanRusakDep.min'  => 'Jumlah batal rusak tidak boleh negatif.',
            'jmlDibatalkanHilangDep.min' => 'Jumlah batal hilang tidak boleh negatif.',
        ]);
    }

    private function payloadTab(): array
    {
        return [
            'tambahan_stok'         => $this->tambahanStokTab,
            'jumlah_digunakan'      => $this->jumlahDigunakanTab,
            'jml_dibatalkan_rusak'  => $this->jmlDibatalkanRusakTab,
            'jml_dibatalkan_hilang' => $this->jmlDibatalkanHilangTab,
        ];
    }

    private function payloadDep(): array
    {
        return [
            'tambahan_stok'         => $this->tambahanStokDep,
            'jumlah_digunakan'      => $this->jumlahDigunakanDep,
            'jml_dibatalkan_rusak'  => $this->jmlDibatalkanRusakDep,
            'jml_dibatalkan_hilang' => $this->jmlDibatalkanHilangDep,
        ];
    }

    private function resetFlash(): void
    {
        $this->flashSuccess = null;
        $this->flashError   = null;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.pic.form-laporan');
    }
}
