<?php

namespace App\Livewire\Pic;

use App\Enums\JenisLaporan;
use App\Enums\StatusVerifikasi;
use App\Enums\TipeTransaksi;
use App\Models\Laporan;
use App\Models\PeriodeLaporan;
use App\Models\PencatatanLaporan;
use App\Services\LaporanService;
use App\Services\PencatatanLaporanService;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Carbon\Carbon;

class FormLaporan extends Component
{
    public PeriodeLaporan $periode;

    public array  $syncRangeInfo  = [];
    public bool   $hasPencatatan  = false;

    // ─── Tabungan ─────────────────────────────────────────────
    public ?int    $laporanTabId           = null;
    public int     $saldoAwalTab           = 0;
    public         $tambahanStokTab        = 0;
    public         $jumlahDigunakanTab     = 0;
    public         $jmlDibatalkanRusakTab  = 0;
    public         $jmlDibatalkanHilangTab = 0;
    public string  $statusTab              = 'draft';
    public ?string $catatanRevisiTab       = null;
    public ?string $lastSavedTab           = null;

    // ─── Deposito ─────────────────────────────────────────────
    public ?int    $laporanDepId            = null;
    public int     $saldoAwalDep            = 0;
    public         $tambahanStokDep         = 0;
    public         $jumlahDigunakanDep      = 0;
    public         $jmlDibatalkanRusakDep   = 0;
    public         $jmlDibatalkanHilangDep  = 0;
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

        $this->computeSyncInfo();
    }

    private function computeSyncInfo(): void
    {
        $svc      = app(PencatatanLaporanService::class);
        $cabangId = auth()->user()->id_cabang;
        $info     = [];
        $ada      = false;

        foreach (JenisLaporan::cases() as $jenis) {
            $range       = $svc->getSyncRange($this->periode, $cabangId, $jenis);
            $pencatatans = PencatatanLaporan::where('id_cabang', $cabangId)
                ->where('jenis', $jenis)
                ->whereBetween('tanggal_catat', [$range['from'], $range['to']])
                ->get();

            $count = $pencatatans->count();
            if ($count > 0) $ada = true;

            $info[$jenis->value] = [
                'count'          => $count,
                'tambahan_stok'  => $pencatatans->filter(fn($p) => $p->tipe_transaksi === TipeTransaksi::TambahanStok)->sum('jumlah'),
                'digunakan'      => $pencatatans->filter(fn($p) => $p->tipe_transaksi === TipeTransaksi::Digunakan)->sum('jumlah'),
                'batal_rusak'    => $pencatatans->filter(fn($p) => $p->tipe_transaksi === TipeTransaksi::DibatalkanRusak)->sum('jumlah'),
                'batal_hilang'   => $pencatatans->filter(fn($p) => $p->tipe_transaksi === TipeTransaksi::DibatalkanHilang)->sum('jumlah'),
                'from_display'   => $range['from_display'],
                'to_display'     => $range['to_display'],
                'from'           => $range['from'],
                'to'             => $range['to'],
            ];
        }

        $this->syncRangeInfo = $info;
        $this->hasPencatatan = $ada;
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
        return (int)$this->saldoAwalTab
            + (int)$this->tambahanStokTab
            - (int)$this->jumlahDigunakanTab
            - (int)$this->jmlDibatalkanRusakTab
            - (int)$this->jmlDibatalkanHilangTab;
    }

    #[Computed(cache: false)]
    public function saldoAkhirDep(): int
    {
        return (int)$this->saldoAwalDep
            + (int)$this->tambahanStokDep
            - (int)$this->jumlahDigunakanDep
            - (int)$this->jmlDibatalkanRusakDep
            - (int)$this->jmlDibatalkanHilangDep;
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


    // sync
    public function syncFromPencatatan(): void
    {
        $this->resetFlash();

        if ($this->canEditTab && isset($this->syncRangeInfo['tabungan'])) {
            $ag = $this->syncRangeInfo['tabungan'];
            $this->tambahanStokTab        = (int)$ag['tambahan_stok'];
            $this->jumlahDigunakanTab     = (int)$ag['digunakan'];
            $this->jmlDibatalkanRusakTab  = (int)$ag['batal_rusak'];
            $this->jmlDibatalkanHilangTab = (int)$ag['batal_hilang'];
        }

        if ($this->canEditDep && isset($this->syncRangeInfo['deposito'])) {
            $ag = $this->syncRangeInfo['deposito'];
            $this->tambahanStokDep        = (int)$ag['tambahan_stok'];
            $this->jumlahDigunakanDep     = (int)$ag['digunakan'];
            $this->jmlDibatalkanRusakDep  = (int)$ag['batal_rusak'];
            $this->jmlDibatalkanHilangDep = (int)$ag['batal_hilang'];
        }

        $ref   = $this->syncRangeInfo['tabungan'] ?? $this->syncRangeInfo['deposito'] ?? null;
        $range = $ref ? "({$ref['from_display']} – {$ref['to_display']})" : '';

        $this->flashSuccess = "Form berhasil disinkronkan dari data pencatatan {$range}. Data masih bisa diubah sebelum disimpan.";

        $this->computeSyncInfo();
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

    // ─── Updated Hooks (coerce empty → 0) ────────────────────

    public function updatedTambahanStokTab($v): void
    {
        $this->tambahanStokTab = ($v === '' || $v === null) ? 0 : (int)$v;
    }

    public function updatedJumlahDigunakanTab($v): void
    {
        $this->jumlahDigunakanTab = ($v === '' || $v === null) ? 0 : (int)$v;
    }

    public function updatedJmlDibatalkanRusakTab($v): void
    {
        $this->jmlDibatalkanRusakTab = ($v === '' || $v === null) ? 0 : (int)$v;
    }

    public function updatedJmlDibatalkanHilangTab($v): void
    {
        $this->jmlDibatalkanHilangTab = ($v === '' || $v === null) ? 0 : (int)$v;
    }

    public function updatedTambahanStokDep($v): void
    {
        $this->tambahanStokDep = ($v === '' || $v === null) ? 0 : (int)$v;
    }

    public function updatedJumlahDigunakanDep($v): void
    {
        $this->jumlahDigunakanDep = ($v === '' || $v === null) ? 0 : (int)$v;
    }

    public function updatedJmlDibatalkanRusakDep($v): void
    {
        $this->jmlDibatalkanRusakDep = ($v === '' || $v === null) ? 0 : (int)$v;
    }

    public function updatedJmlDibatalkanHilangDep($v): void
    {
        $this->jmlDibatalkanHilangDep = ($v === '' || $v === null) ? 0 : (int)$v;
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
            'tambahan_stok'         => (int)$this->tambahanStokTab,
            'jumlah_digunakan'      => (int)$this->jumlahDigunakanTab,
            'jml_dibatalkan_rusak'  => (int)$this->jmlDibatalkanRusakTab,
            'jml_dibatalkan_hilang' => (int)$this->jmlDibatalkanHilangTab,
        ];
    }

    private function payloadDep(): array
    {
        return [
            'tambahan_stok'         => (int)$this->tambahanStokDep,
            'jumlah_digunakan'      => (int)$this->jumlahDigunakanDep,
            'jml_dibatalkan_rusak'  => (int)$this->jmlDibatalkanRusakDep,
            'jml_dibatalkan_hilang' => (int)$this->jmlDibatalkanHilangDep,
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
