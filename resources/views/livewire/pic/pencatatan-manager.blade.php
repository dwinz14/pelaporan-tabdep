<div>

    {{-- ═══ FLASH ═══ --}}
    @if ($flashSuccess)
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            {{ $flashSuccess }}
        </div>
    @endif

    @if ($flashError)
        <div x-data="{ show: true }" x-show="show" x-transition
            class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            {{ $flashError }}
        </div>
    @endif

    {{-- ═══ FILTER & TOOLBAR ═══ --}}
    <div class="flex items-center gap-3 mb-5 flex-wrap">

        {{-- Pilih Periode --}}
        <div class="flex-1 min-w-48">
            <select wire:model.live="filterPeriodeId"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">— Pilih Periode —</option>
                @foreach ($this->availablePeriodes as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->nama_periode }}
                        {{ $p->is_current ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Jenis --}}
        <div class="flex rounded-lg border border-gray-200 overflow-hidden">
            @foreach (['' => 'Semua', 'tabungan' => 'Tabungan', 'deposito' => 'Deposito'] as $val => $label)
                <button type="button" wire:click="$set('filterJenis', '{{ $val }}')"
                    class="px-3 py-2 text-xs font-medium transition-colors
                           {{ $filterJenis === $val ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="ml-auto">
            @if ($this->canAddNew)
                <button type="button" wire:click="openCreateForm"
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm
                           font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pencatatan
                </button>
            @elseif($filterPeriodeId)
                <span class="text-xs text-gray-400 italic">Laporan sudah disubmit — pencatatan terkunci</span>
            @endif
        </div>
    </div>

    @if ($filterPeriodeId)

        {{-- ═══ RINGKASAN AGREGAT ═══ --}}
        @if (!empty($agregat))
            <div class="grid grid-cols-2 gap-4 mb-5">
                @foreach (['tabungan' => 'Tabungan', 'deposito' => 'Deposito'] as $jenis => $jenisLabel)
                    @if (!empty($agregat[$jenis]))
                        @php $ag = $agregat[$jenis]; @endphp
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            <div
                                class="px-4 py-2.5 border-b border-gray-100 flex items-center justify-between
                                {{ $jenis === 'tabungan' ? 'bg-indigo-50' : 'bg-slate-50' }}">
                                <p
                                    class="text-xs font-semibold {{ $jenis === 'tabungan' ? 'text-indigo-800' : 'text-slate-700' }} uppercase tracking-wide">
                                    {{ $jenisLabel }}
                                </p>
                                <span
                                    class="text-xs {{ $jenis === 'tabungan' ? 'text-indigo-500' : 'text-slate-400' }}">
                                    {{ $ag['count'] }} transaksi
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-0 divide-x divide-y divide-gray-100">
                                @foreach ([['tipe' => 'tambahan_stok', 'label' => 'Tambahan Stok', 'color' => 'text-emerald-700', 'sign' => '+'], ['tipe' => 'digunakan', 'label' => 'Digunakan', 'color' => 'text-blue-700', 'sign' => '−'], ['tipe' => 'dibatalkan_rusak', 'label' => 'Batal Rusak', 'color' => 'text-orange-700', 'sign' => '−'], ['tipe' => 'dibatalkan_hilang', 'label' => 'Batal Hilang', 'color' => 'text-red-700', 'sign' => '−']] as $item)
                                    <div class="px-4 py-3">
                                        <p class="text-xs text-gray-400 mb-0.5">{{ $item['label'] }}</p>
                                        <p class="font-mono font-bold text-sm {{ $item['color'] }}">
                                            {{ $item['sign'] }} {{ number_format($ag[$item['tipe']] ?? 0) }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            @php $lapStatus = $laporanStatus[$jenis]; @endphp
                            @if ($lapStatus)
                                <div class="px-4 py-2 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-xs text-gray-400">Status Laporan:</span>
                                    <span
                                        class="text-xs font-medium px-2 py-0.5 rounded-full {{ $lapStatus->status_verifikasi->badgeClass() }}">
                                        {{ $lapStatus->status_verifikasi->label() }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- ═══ TABEL PENCATATAN ═══ --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            @if ($this->pencatatanList->isEmpty())
                <div class="p-10 text-center">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-sm text-gray-500 font-medium">Belum ada pencatatan</p>
                    <p class="text-xs text-gray-400 mt-1">Klik "Tambah Pencatatan" untuk mulai mencatat.</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">
                                Tanggal</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                                Jenis</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Tipe Transaksi</th>
                            <th
                                class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                                Jumlah</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Keterangan</th>
                            <th
                                class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($this->pencatatanList as $pencatatan)
                            @php
                                $rowCanEdit =
                                    $pencatatan->jenis->value === 'tabungan' ? $this->tabCanEdit : $this->depCanEdit;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-xs font-mono text-gray-600">
                                    {{ $pencatatan->tanggal_catat->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="text-xs font-medium px-2 py-0.5 rounded-full
                                                 {{ $pencatatan->jenis->value === 'tabungan' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $pencatatan->jenis->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="text-xs font-medium px-2 py-0.5 rounded-full {{ $pencatatan->tipe_transaksi->badgeClass() }}">
                                        {{ $pencatatan->tipe_transaksi->labelShort() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span
                                        class="font-mono font-bold text-sm
                                                 {{ $pencatatan->tipe_transaksi->isAddition() ? 'text-emerald-700' : 'text-gray-900' }}">
                                        {{ $pencatatan->tipe_transaksi->isAddition() ? '+' : '−' }}{{ number_format($pencatatan->jumlah) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    {{ $pencatatan->keterangan ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($rowCanEdit)
                                            <button type="button" wire:click="openEditForm({{ $pencatatan->id }})"
                                                class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                                Edit
                                            </button>
                                            <button type="button" wire:click="openDeleteModal({{ $pencatatan->id }})"
                                                class="text-xs text-red-500 hover:text-red-700 font-medium">
                                                Hapus
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-300">Terkunci</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-sm text-gray-500">Pilih periode di atas untuk melihat pencatatan</p>
        </div>
    @endif


    {{-- ═══════════ MODAL FORM ═══════════ --}}
    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5);" x-data
            x-on:keydown.escape.window="$wire.closeFormModal()">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.stop>

                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">
                            {{ $editingId ? 'Edit Pencatatan' : 'Tambah Pencatatan' }}
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">Satu transaksi/kejadian spesifik</p>
                    </div>
                    <button type="button" wire:click="closeFormModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5 space-y-4">

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5">
                            Tanggal Kejadian <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="formTanggal" max="{{ now()->format('Y-m-d') }}"
                            class="w-full px-3 py-2 border rounded-lg text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   @error('formTanggal') border-red-400 bg-red-50 @enderror
                                   {{ !$errors->has('formTanggal') ? 'border-gray-300' : '' }}">
                        @error('formTanggal')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis + Tipe (2 kolom) --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5">
                                Jenis Buku <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="formJenis"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="tabungan" {{ !$this->tabCanEdit ? 'disabled' : '' }}>
                                    Tabungan {{ !$this->tabCanEdit ? '(sudah submit)' : '' }}
                                </option>
                                <option value="deposito" {{ !$this->depCanEdit ? 'disabled' : '' }}>
                                    Deposito {{ !$this->depCanEdit ? '(sudah submit)' : '' }}
                                </option>
                            </select>
                            @error('formJenis')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5">
                                Tipe Transaksi <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="formTipe"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500
                                       @error('formTipe') border-red-400 @enderror">
                                @foreach (\App\Enums\TipeTransaksi::cases() as $tipe)
                                    <option value="{{ $tipe->value }}">{{ $tipe->labelShort() }}</option>
                                @endforeach
                            </select>
                            @error('formTipe')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Jumlah --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5">
                            Jumlah Buku <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="formJumlah" min="1" max="99999"
                            class="w-full px-3 py-2 border rounded-lg text-sm font-mono
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   @error('formJumlah') border-red-400 bg-red-50 @enderror
                                   {{ !$errors->has('formJumlah') ? 'border-gray-300' : '' }}">
                        @error('formJumlah')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5">
                            Keterangan
                            <span class="text-gray-400 font-normal normal-case tracking-normal">(opsional)</span>
                        </label>
                        <input type="text" wire:model="formKeterangan"
                            placeholder="Contoh: Nasabah Budi Santoso, no. seri ABC123..." maxlength="255"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   @error('formKeterangan') border-red-400 bg-red-50 @enderror">
                        @error('formKeterangan')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeFormModal"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium
                               rounded-lg hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="button" wire:click="saveForm" wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-5 py-2 bg-indigo-600 text-white text-sm font-semibold
                               rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-60">
                        <svg wire:loading.remove wire:target="saveForm" class="w-4 h-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        <svg wire:loading wire:target="saveForm" class="w-4 h-4 animate-spin" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        <span wire:loading.remove wire:target="saveForm">
                            {{ $editingId ? 'Perbarui' : 'Simpan Pencatatan' }}
                        </span>
                        <span wire:loading wire:target="saveForm">Menyimpan...</span>
                    </button>
                </div>

            </div>
        </div>
    @endif


    {{-- ═══════════ MODAL DELETE ═══════════ --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5);"
            x-data x-on:keydown.escape.window="$wire.closeDeleteModal()">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden" @click.stop>

                <div class="px-6 py-5 text-center">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Hapus Pencatatan?</h3>
                    <p class="text-sm text-gray-600 mb-1">Pencatatan berikut akan dihapus permanen:</p>
                    <p class="text-sm font-semibold text-gray-900 bg-gray-100 rounded-lg px-3 py-2 mt-2">
                        {{ $deletingLabel }}
                    </p>
                    <p class="text-xs text-red-600 mt-3">Tindakan ini tidak dapat dibatalkan.</p>
                </div>

                <div class="px-6 pb-5 flex items-center justify-center gap-3">
                    <button type="button" wire:click="closeDeleteModal"
                        class="px-5 py-2 border border-gray-300 text-gray-700 text-sm font-medium
                               rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" wire:click="confirmDelete" wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-5 py-2 bg-red-600 text-white text-sm font-semibold
                               rounded-lg hover:bg-red-700 transition-colors disabled:opacity-60">
                        <span wire:loading.remove wire:target="confirmDelete">Ya, Hapus</span>
                        <span wire:loading wire:target="confirmDelete">Menghapus...</span>
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
