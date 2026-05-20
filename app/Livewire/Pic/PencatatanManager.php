<?php

namespace App\Livewire\Pic;

use App\Enums\JenisLaporan;
use App\Enums\TipeTransaksi;
use App\Models\Laporan;
use App\Models\PencatatanLaporan;
use App\Models\PeriodeLaporan;
use App\Services\PencatatanLaporanService;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PencatatanManager extends Component
{
    // ─── Filter ───────────────────────────────────────────────
    public ?int   $filterPeriodeId = null;
    public string $filterJenis     = '';

    // ─── Form Modal ───────────────────────────────────────────
    public bool   $showFormModal   = false;
    public ?int   $editingId       = null;
    public string $formTanggal     = '';
    public string $formJenis       = 'tabungan';
    public string $formTipe        = 'digunakan';
    public int    $formJumlah      = 1;
    public string $formKeterangan  = '';

    // ─── Delete Modal ─────────────────────────────────────────
    public bool   $showDeleteModal = false;
    public ?int   $deletingId      = null;
    public string $deletingLabel   = '';

    // ─── Flash ────────────────────────────────────────────────
    public ?string $flashSuccess   = null;
    public ?string $flashError     = null;

    // ─── Mount ────────────────────────────────────────────────

    public function mount(): void
    {
        $this->formTanggal = now()->format('Y-m-d');

        // Default ke periode aktif
        $aktif = PeriodeLaporan::where('is_current', true)->first();
        if ($aktif) {
            $this->filterPeriodeId = $aktif->id;
            return;
        }

        // Fallback ke periode terbaru yang ada laporan untuk cabang ini
        $latest = PeriodeLaporan::whereHas(
            'laporans',
            fn($q) =>
            $q->where('id_cabang', auth()->user()->id_cabang)
        )->orderBy('tanggal_akhir', 'desc')->first();

        if ($latest) {
            $this->filterPeriodeId = $latest->id;
        }
    }

    // ─── Computed ─────────────────────────────────────────────

    #[Computed(cache: false)]
    public function availablePeriodes()
    {
        return PeriodeLaporan::whereHas(
            'laporans',
            fn($q) =>
            $q->where('id_cabang', auth()->user()->id_cabang)
        )
            ->orderBy('tanggal_akhir', 'desc')
            ->get(['id', 'nama_periode', 'tanggal_akhir', 'is_current', 'status_operasional']);
    }

    #[Computed(cache: false)]
    public function pencatatanList()
    {
        if (! $this->filterPeriodeId) return collect();

        return PencatatanLaporan::where('id_cabang', auth()->user()->id_cabang)
            ->where('id_periode', $this->filterPeriodeId)
            ->when($this->filterJenis, fn($q) => $q->where('jenis', $this->filterJenis))
            ->orderBy('tanggal_catat', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[Computed(cache: false)]
    public function agregat(): array
    {
        if (! $this->filterPeriodeId) return [];

        return app(PencatatanLaporanService::class)
            ->getAgregat($this->filterPeriodeId, auth()->user()->id_cabang);
    }

    #[Computed(cache: false)]
    public function laporanStatus(): array
    {
        if (! $this->filterPeriodeId) {
            return ['tabungan' => null, 'deposito' => null];
        }

        $laporans = Laporan::with('periode')
            ->where('id_periode', $this->filterPeriodeId)
            ->where('id_cabang', auth()->user()->id_cabang)
            ->get()
            ->keyBy(fn($l) => $l->jenis->value);

        return [
            'tabungan' => $laporans->get('tabungan'),
            'deposito' => $laporans->get('deposito'),
        ];
    }

    #[Computed(cache: false)]
    public function tabCanEdit(): bool
    {
        $lap = $this->laporanStatus['tabungan'];
        return $lap && $lap->status_verifikasi->canEdit() && ! $lap->periode->isLocked();
    }

    #[Computed(cache: false)]
    public function depCanEdit(): bool
    {
        $lap = $this->laporanStatus['deposito'];
        return $lap && $lap->status_verifikasi->canEdit() && ! $lap->periode->isLocked();
    }

    #[Computed(cache: false)]
    public function canAddNew(): bool
    {
        return $this->tabCanEdit || $this->depCanEdit;
    }

    // ─── Form Modal ───────────────────────────────────────────

    public function openCreateForm(): void
    {
        $this->resetFlash();

        if (! $this->canAddNew) {
            $this->flashError = 'Laporan periode ini sudah disubmit. Tidak bisa menambah pencatatan.';
            return;
        }

        $this->editingId      = null;
        $this->formTanggal    = now()->format('Y-m-d');
        $this->formJenis      = $this->tabCanEdit ? 'tabungan' : 'deposito';
        $this->formTipe       = 'digunakan';
        $this->formJumlah     = 1;
        $this->formKeterangan = '';
        $this->showFormModal  = true;
    }

    public function openEditForm(int $id): void
    {
        $this->resetFlash();

        $p = PencatatanLaporan::where('id_cabang', auth()->user()->id_cabang)
            ->findOrFail($id);

        $this->editingId      = $id;
        $this->formTanggal    = $p->tanggal_catat->format('Y-m-d');
        $this->formJenis      = $p->jenis->value;
        $this->formTipe       = $p->tipe_transaksi->value;
        $this->formJumlah     = $p->jumlah;
        $this->formKeterangan = $p->keterangan ?? '';
        $this->showFormModal  = true;
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->editingId     = null;
        $this->resetValidation();
    }

    public function saveForm(): void
    {
        $this->resetFlash();

        $this->validate([
            'formTanggal'    => ['required', 'date', 'before_or_equal:today'],
            'formJenis'      => ['required', 'in:tabungan,deposito'],
            'formTipe'       => ['required', 'in:tambahan_stok,digunakan,dibatalkan_rusak,dibatalkan_hilang'],
            'formJumlah'     => ['required', 'integer', 'min:1', 'max:99999'],
            'formKeterangan' => ['nullable', 'string', 'max:255'],
        ], [
            'formTanggal.required'        => 'Tanggal wajib diisi.',
            'formTanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'formJenis.required'          => 'Jenis buku wajib dipilih.',
            'formTipe.required'           => 'Tipe transaksi wajib dipilih.',
            'formJumlah.required'         => 'Jumlah wajib diisi.',
            'formJumlah.min'              => 'Jumlah minimal 1.',
            'formJumlah.max'              => 'Jumlah terlalu besar.',
        ]);

        $service = app(PencatatanLaporanService::class);

        $data = [
            'id_cabang'      => auth()->user()->id_cabang,
            'id_periode'     => $this->filterPeriodeId,
            'jenis'          => $this->formJenis,
            'tipe_transaksi' => $this->formTipe,
            'jumlah'         => $this->formJumlah,
            'keterangan'     => $this->formKeterangan ?: null,
            'tanggal_catat'  => $this->formTanggal,
            'created_by'     => auth()->id(),
        ];

        if ($this->editingId) {
            $p = PencatatanLaporan::where('id_cabang', auth()->user()->id_cabang)
                ->findOrFail($this->editingId);
            $service->update($p, $data);
            $this->flashSuccess = 'Pencatatan berhasil diperbarui.';
        } else {
            $service->create($data);
            $this->flashSuccess = 'Pencatatan berhasil ditambahkan.';
        }

        $this->showFormModal = false;
        $this->editingId     = null;
    }

    // ─── Delete Modal ─────────────────────────────────────────

    public function openDeleteModal(int $id): void
    {
        $p = PencatatanLaporan::where('id_cabang', auth()->user()->id_cabang)
            ->findOrFail($id);

        $this->deletingId    = $id;
        $this->deletingLabel = $p->tipe_transaksi->labelShort()
            . ' ' . $p->jenis->label()
            . ' — ' . number_format($p->jumlah) . ' buku'
            . ' (' . $p->tanggal_catat->format('d/m/Y') . ')';

        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId      = null;
    }

    public function confirmDelete(): void
    {
        $this->resetFlash();

        $p = PencatatanLaporan::where('id_cabang', auth()->user()->id_cabang)
            ->findOrFail($this->deletingId);

        app(PencatatanLaporanService::class)->delete($p);

        $this->showDeleteModal = false;
        $this->deletingId      = null;
        $this->flashSuccess    = 'Pencatatan berhasil dihapus.';
    }

    // ─── Helpers ──────────────────────────────────────────────

    private function resetFlash(): void
    {
        $this->flashSuccess = null;
        $this->flashError   = null;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.pic.pencatatan-manager');
    }
}
