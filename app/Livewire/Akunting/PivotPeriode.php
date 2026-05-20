<?php

namespace App\Livewire\Akunting;

use App\Enums\JenisLaporan;
use App\Enums\StatusVerifikasi;
use App\Models\Cabang;
use App\Models\Laporan;
use App\Models\PeriodeLaporan;
use App\Services\LaporanService;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PivotPeriode extends Component
{
    public PeriodeLaporan $periode;

    // ─── Modal Revisi ─────────────────────────────────────────
    public bool    $showRevisiModal = false;
    public ?int    $revisiLaporanId = null;
    public string  $revisiJenis     = '';
    public string  $revisiCabang    = '';
    public string  $catatanRevisi   = '';

    // ─── Flash ────────────────────────────────────────────────
    public ?string $flashSuccess = null;
    public ?string $flashError   = null;

    public function mount(PeriodeLaporan $periode): void
    {
        $this->periode = $periode;
    }

    // ─── Computed ─────────────────────────────────────────────

    #[Computed(cache: false)]
    public function pivotData()
    {
        $laporans = Laporan::with(['cabang', 'verifiedByAkunting', 'periode'])
            ->where('id_periode', $this->periode->id)
            ->get()
            ->groupBy('id_cabang');

        $cabangs = Cabang::where('is_active', true)
            ->orderBy('kode_cabang')
            ->get();

        return $cabangs->map(function ($cabang) use ($laporans) {
            $group = $laporans->get($cabang->id, collect());
            return [
                'cabang'   => $cabang,
                'tabungan' => $group->first(fn($l) => $l->jenis === JenisLaporan::Tabungan),
                'deposito' => $group->first(fn($l) => $l->jenis === JenisLaporan::Deposito),
            ];
        });
    }

    #[Computed(cache: false)]
    public function progress(): array
    {
        $rows = Laporan::where('id_periode', $this->periode->id)
            ->where('jenis', JenisLaporan::Tabungan)
            ->get();

        $total     = $rows->count();
        $verified  = $rows->where('status_verifikasi', StatusVerifikasi::VerifiedAccounting)->count();
        $submitted = $rows->where('status_verifikasi', StatusVerifikasi::Submitted)->count();
        $revision  = $rows->where('status_verifikasi', StatusVerifikasi::RevisionRequested)->count();
        $draft     = $rows->where('status_verifikasi', StatusVerifikasi::Draft)->count();
        $pct       = $total > 0 ? round($verified / $total * 100) : 0;

        return compact('total', 'verified', 'submitted', 'revision', 'draft', 'pct');
    }

    // ─── Actions ──────────────────────────────────────────────

    public function approve(int $laporanId): void
    {
        $this->resetFlash();

        $laporan = Laporan::with(['cabang', 'periode'])->findOrFail($laporanId);

        $service = app(LaporanService::class);
        $service->verifyAccounting($laporan);

        $this->flashSuccess = "Laporan {$laporan->jenis->label()} cabang {$laporan->cabang->kode_cabang} berhasil disetujui.";

        // Refresh periode untuk update semuaCabangVerified()
        $this->periode->refresh();
    }

    public function openRevisi(int $laporanId): void
    {
        $laporan = Laporan::with('cabang')->findOrFail($laporanId);

        $this->revisiLaporanId = $laporanId;
        $this->revisiJenis     = $laporan->jenis->label();
        $this->revisiCabang    = $laporan->cabang->kode_cabang . ' — ' . $laporan->cabang->nama_cabang;
        $this->catatanRevisi   = '';
        $this->showRevisiModal = true;
    }

    public function submitRevisi(): void
    {
        $this->validate([
            'catatanRevisi' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'catatanRevisi.required' => 'Catatan revisi wajib diisi.',
            'catatanRevisi.min'      => 'Catatan revisi minimal 10 karakter.',
        ]);

        $laporan = Laporan::with(['cabang', 'periode'])->findOrFail($this->revisiLaporanId);

        $service = app(LaporanService::class);
        $service->requestRevision($laporan, $this->catatanRevisi);

        $this->showRevisiModal = false;
        $this->catatanRevisi   = '';
        $this->revisiLaporanId = null;

        $this->flashSuccess = "Permintaan revisi berhasil dikirim ke PIC cabang {$laporan->cabang->kode_cabang}.";
    }

    public function closeModal(): void
    {
        $this->showRevisiModal = false;
        $this->catatanRevisi   = '';
        $this->revisiLaporanId = null;
    }

    private function resetFlash(): void
    {
        $this->flashSuccess = null;
        $this->flashError   = null;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.akunting.pivot-periode');
    }
}
