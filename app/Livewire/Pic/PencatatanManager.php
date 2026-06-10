<?php

namespace App\Livewire\Pic;

use App\Enums\JenisLaporan;
use App\Enums\TipeTransaksi;
use App\Models\PencatatanLaporan;
use App\Services\PencatatanLaporanService;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PencatatanManager extends Component
{
    // ─── Filter Log ───────────────────────────────────────────
    public string $filterJenis  = '';
    public string $filterTipe   = '';
    public string $filterDari   = '';
    public string $filterSampai = '';

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
        $this->filterDari   = now()->subDays(30)->format('Y-m-d');
        $this->filterSampai = now()->format('Y-m-d');
        $this->formTanggal  = now()->format('Y-m-d');
    }

    // ─── Computed ─────────────────────────────────────────────

    #[Computed(cache: false)]
    public function stokTerkini(): array
    {
        return app(PencatatanLaporanService::class)
            ->getStokTerkini(auth()->user()->id_cabang);
    }

    #[Computed(cache: false)]
    public function stokRealtime(): array
    {
        return app(PencatatanLaporanService::class)
            ->getStokRealtime(auth()->user()->id_cabang);
    }


    #[Computed(cache: false)]
    public function lockDates(): array
    {
        $svc = app(PencatatanLaporanService::class);
        $id  = auth()->user()->id_cabang;

        return [
            'tabungan' => $svc->getLockDate($id, JenisLaporan::Tabungan)?->format('Y-m-d'),
            'deposito' => $svc->getLockDate($id, JenisLaporan::Deposito)?->format('Y-m-d'),
        ];
    }

    #[Computed(cache: false)]
    public function logList()
    {
        return app(PencatatanLaporanService::class)->getLog(
            auth()->user()->id_cabang,
            [
                'jenis'   => $this->filterJenis  ?: null,
                'tipe'    => $this->filterTipe   ?: null,
                'dari'    => $this->filterDari,
                'sampai'  => $this->filterSampai,
            ]
        );
    }

    #[Computed(cache: false)]
    public function formMinDate(): ?string
    {
        if (! $this->formJenis) return null;

        $lockDate = $this->lockDates[$this->formJenis] ?? null;

        return $lockDate
            ? Carbon::parse($lockDate)->addDay()->format('Y-m-d')
            : null;
    }

    #[Computed(cache: false)]
    public function formDateLocked(): bool
    {
        if (! $this->formTanggal || ! $this->formJenis) return false;

        $lockDate = $this->lockDates[$this->formJenis] ?? null;

        if (! $lockDate) return false;

        return $this->formTanggal <= $lockDate;
    }

    #[Computed(cache: false)]
    public function formDateLockMsg(): ?string
    {
        if (! $this->formDateLocked) return null;

        $lockDate = $this->lockDates[$this->formJenis];
        $jenis    = JenisLaporan::from($this->formJenis)->label();

        return "Tanggal " . Carbon::parse($this->formTanggal)->format('d/m/Y')
            . " sudah terkunci. Laporan {$jenis} s/d "
            . Carbon::parse($lockDate)->format('d/m/Y') . " sudah disubmit.";
    }

    // ─── Filter Actions ───────────────────────────────────────

    public function resetFilter(): void
    {
        $this->filterJenis  = '';
        $this->filterTipe   = '';
        $this->filterDari   = now()->subDays(30)->format('Y-m-d');
        $this->filterSampai = now()->format('Y-m-d');
    }

    // ─── Form Modal ───────────────────────────────────────────

    public function openCreateForm(): void
    {
        $this->resetFlash();
        $this->editingId      = null;
        $this->formTanggal    = now()->format('Y-m-d');
        $this->formJenis      = 'tabungan';
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

        // Cek apakah masih bisa diedit
        $lockDate = $this->lockDates[$p->jenis->value] ?? null;
        if ($lockDate && $p->tanggal_catat->format('Y-m-d') <= $lockDate) {
            $this->flashError = 'Pencatatan ini tidak bisa diedit karena sudah masuk periode yang disubmit.';
            return;
        }

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
            'formTanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
            'formJenis.required'          => 'Jenis buku wajib dipilih.',
            'formTipe.required'           => 'Tipe transaksi wajib dipilih.',
            'formJumlah.required'         => 'Jumlah wajib diisi.',
            'formJumlah.min'              => 'Jumlah minimal 1.',
        ]);

        // Cek lock date
        if ($this->formDateLocked) {
            $this->flashError = $this->formDateLockMsg;
            return;
        }

        $svc  = app(PencatatanLaporanService::class);
        $data = [
            'id_cabang'      => auth()->user()->id_cabang,
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
            $svc->update($p, $data);
            $this->flashSuccess = 'Pencatatan berhasil diperbarui.';
        } else {
            $svc->create($data);
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

        // Cek apakah masih bisa dihapus
        $lockDate = $this->lockDates[$p->jenis->value] ?? null;
        if ($lockDate && $p->tanggal_catat->format('Y-m-d') <= $lockDate) {
            $this->flashError = 'Pencatatan ini tidak bisa dihapus karena sudah masuk periode yang disubmit.';
            return;
        }

        $this->deletingId    = $id;
        $this->deletingLabel = $p->jenis->label() . ' — '
            . $p->tipe_transaksi->labelShort() . ' '
            . number_format($p->jumlah) . ' buku'
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
