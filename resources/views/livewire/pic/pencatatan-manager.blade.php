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
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 6000)"
            class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            {{ $flashError }}
        </div>
    @endif

    {{-- ═══ STOK REAL-TIME MONITORING ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
        @foreach (['tabungan' => 'Tabungan', 'deposito' => 'Deposito'] as $jenisKey => $jenisLabel)
            @php $rt = $this->stokRealtime[$jenisKey] ?? null; @endphp
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

                {{-- ── Header ── --}}
                <div
                    class="px-4 py-3 {{ $jenisKey === 'tabungan' ? 'bg-indigo-600' : 'bg-slate-700' }} flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white/15">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </span>
                        <p class="text-xs font-semibold text-white uppercase tracking-wide">{{ $jenisLabel }}</p>
                    </div>
                    @if ($rt && $rt['lock_date'])
                        <span class="flex items-center gap-1 text-xs text-white/70">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Terkunci s/d {{ $rt['lock_date'] }}
                        </span>
                    @else
                        <span class="text-xs text-white/60">Belum ada periode disubmit</span>
                    @endif
                </div>

                @if ($rt)
                    {{-- ── Hero: Stok Real-Time ── --}}
                    <div class="px-4 pt-5 pb-3">
                        <div class="flex items-end justify-between">
                            <div>
                                <p
                                    class="text-xs text-gray-400 uppercase tracking-wide font-medium flex items-center gap-1.5">
                                    <span class="relative flex h-2 w-2">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $jenisKey === 'tabungan' ? 'bg-indigo-400' : 'bg-slate-400' }} opacity-75"></span>
                                        <span
                                            class="relative inline-flex rounded-full h-2 w-2 {{ $jenisKey === 'tabungan' ? 'bg-indigo-500' : 'bg-slate-500' }}"></span>
                                    </span>
                                    Stok Real-Time Sekarang
                                </p>
                                <p class="text-4xl font-bold text-gray-900 font-mono mt-1.5 tracking-tight">
                                    {{ number_format($rt['stok_sekarang']) }}
                                </p>
                                {{-- <p class="text-xs text-gray-400 mt-1">
                                    Basis: Saldo per {{ $rt['saldo_at'] ?? '—' }}
                                    <span class="text-gray-300">({{ $rt['saldo_label'] }})</span>
                                </p> --}}
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-300 font-medium">Saldo Awal</p>
                                <p class="text-lg font-bold font-mono text-gray-500">
                                    {{ number_format($rt['saldo_base']) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- ── Masuk / Keluar Total (sejak lock) ── --}}
                    <div class="px-4 pb-3">
                        <div class="grid grid-cols-2 gap-3">
                            {{-- Masuk --}}
                            {{-- <div class="bg-emerald-50 rounded-lg px-3 py-2.5 border border-emerald-100">
                                <p class="text-xs text-emerald-600 font-medium mb-0.5">
                                    ↑ Masuk sejak {{ $rt['since'] }}
                                </p>
                                <p class="text-xl font-bold font-mono text-emerald-700">
                                    +{{ number_format($rt['masuk_total']) }}
                                </p>
                            </div> --}}
                            {{-- Keluar --}}
                            {{-- <div class="bg-red-50 rounded-lg px-3 py-2.5 border border-red-100">
                                <p class="text-xs text-red-500 font-medium mb-0.5">
                                    ↓ Keluar sejak {{ $rt['since'] }}
                                </p>
                                <p class="text-xl font-bold font-mono text-red-600">
                                    −{{ number_format($rt['keluar_total']) }}
                                </p>
                            </div> --}}
                        </div>

                        {{-- Detail breakdown keluar --}}
                        @if ($rt['keluar_total'] > 0)
                            <div class="mt-2 flex items-center gap-3 text-xs text-gray-400 pl-1">
                                <span>Detail keluar:</span>
                                @if ($rt['detail_keluar']['digunakan'] > 0)
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                                        Digunakan {{ number_format($rt['detail_keluar']['digunakan']) }}
                                    </span>
                                @endif
                                @if ($rt['detail_keluar']['batal_rusak'] > 0)
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span>
                                        Batal Rusak {{ number_format($rt['detail_keluar']['batal_rusak']) }}
                                    </span>
                                @endif
                                @if ($rt['detail_keluar']['batal_hilang'] > 0)
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                        Batal Hilang {{ number_format($rt['detail_keluar']['batal_hilang']) }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- ── Bulan Ini ── --}}
                    <div class="mx-4 mb-3 bg-gray-50 rounded-lg border border-gray-100 px-3 py-2.5">
                        <p
                            class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $rt['nama_bulan'] }}
                        </p>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="text-center">
                                <p class="text-xs text-gray-400">Masuk</p>
                                <p class="text-sm font-bold font-mono text-emerald-600">
                                    +{{ number_format($rt['masuk_bulan_ini']) }}
                                </p>
                            </div>
                            <div class="text-center border-x border-gray-200">
                                <p class="text-xs text-gray-400">Keluar</p>
                                <p class="text-sm font-bold font-mono text-red-500">
                                    −{{ number_format($rt['keluar_bulan_ini']) }}
                                </p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-400">Net</p>
                                <p
                                    class="text-sm font-bold font-mono {{ $rt['net_bulan_ini'] >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $rt['net_bulan_ini'] >= 0 ? '+' : '' }}{{ number_format($rt['net_bulan_ini']) }}
                                </p>
                            </div>
                        </div>
                        @if ($rt['keluar_bulan_ini'] > 0)
                            <div
                                class="mt-1.5 pt-1.5 border-t border-gray-200 flex items-center gap-3 text-xs text-gray-400">
                                @if ($rt['detail_bulan_ini']['digunakan'] > 0)
                                    <span>Digunakan {{ $rt['detail_bulan_ini']['digunakan'] }}</span>
                                @endif
                                @if ($rt['detail_bulan_ini']['batal_rusak'] > 0)
                                    <span>Batal Rusak {{ $rt['detail_bulan_ini']['batal_rusak'] }}</span>
                                @endif
                                @if ($rt['detail_bulan_ini']['batal_hilang'] > 0)
                                    <span>Batal Hilang {{ $rt['detail_bulan_ini']['batal_hilang'] }}</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- ── Perubahan Terakhir (mini-timeline) ── --}}
                    @if (count($rt['perubahan_terakhir']) > 0)
                        <div class="px-4 pb-3">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                Perubahan Terakhir
                            </p>
                            <div class="space-y-1">
                                @foreach ($rt['perubahan_terakhir'] as $ch)
                                    <div
                                        class="flex items-center gap-2 text-xs py-1 px-2 rounded hover:bg-gray-50 transition-colors">
                                        <span
                                            class="text-gray-300 font-mono w-10 flex-shrink-0">{{ $ch['tanggal'] }}</span>
                                        <span
                                            class="font-bold font-mono w-12 text-right flex-shrink-0 {{ $ch['is_masuk'] ? 'text-emerald-600' : 'text-red-500' }}">
                                            {{ $ch['is_masuk'] ? '+' : '−' }}{{ number_format($ch['jumlah']) }}
                                        </span>
                                        <span
                                            class="px-1.5 py-0.5 rounded-full text-xs font-medium {{ $ch['badge'] }} flex-shrink-0">
                                            {{ $ch['tipe'] }}
                                        </span>
                                        <span class="text-gray-400 truncate">
                                            {{ $ch['keterangan'] ?? '' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ── Footer: pencatatan belum lapor ── --}}
                    @if ($rt['pencatatan_count'] > 0)
                        <div class="px-4 py-2.5 bg-gray-50 border-t border-gray-100">
                            <p class="text-xs text-gray-400 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $rt['pencatatan_count'] }} pencatatan belum dilaporkan
                            </p>
                        </div>
                    @endif
                @else
                    <div class="px-4 py-8 text-center">
                        <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-xs text-gray-400">Data tidak tersedia</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- ═══ LOG TOOLBAR ═══ --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        {{-- Header + Tambah --}}
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between gap-3">
            <p class="text-sm font-semibold text-gray-900">Log Pencatatan</p>
            <button type="button" wire:click="openCreateForm"
                class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold
                       rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pencatatan
            </button>
        </div>

        {{-- Filter --}}
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-end gap-3 flex-wrap">
            {{-- Filter Jenis --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Jenis</label>
                <select wire:model.live="filterJenis"
                    class="px-2.5 py-1.5 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua</option>
                    <option value="tabungan">Tabungan</option>
                    <option value="deposito">Deposito</option>
                </select>
            </div>

            {{-- Filter Tipe --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Tipe Transaksi</label>
                <select wire:model.live="filterTipe"
                    class="px-2.5 py-1.5 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua</option>
                    @foreach (\App\Enums\TipeTransaksi::cases() as $tipe)
                        <option value="{{ $tipe->value }}">{{ $tipe->labelShort() }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Dari --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Dari</label>
                <input type="date" wire:model.live="filterDari"
                    class="px-2.5 py-1.5 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- Sampai --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Sampai</label>
                <input type="date" wire:model.live="filterSampai"
                    class="px-2.5 py-1.5 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <button type="button" wire:click="resetFilter"
                class="px-3 py-1.5 border border-gray-300 text-gray-500 text-xs rounded-lg hover:bg-gray-100 transition-colors">
                Reset
            </button>

            <span class="text-xs text-gray-400 ml-auto">{{ $this->logList->count() }} transaksi</span>
        </div>

        {{-- Tabel Log --}}
        @if ($this->logList->isEmpty())
            <div class="p-10 text-center">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-sm text-gray-500 font-medium">Tidak ada pencatatan</p>
                <p class="text-xs text-gray-400 mt-1">pada rentang tanggal yang dipilih.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 w-28">Tanggal</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 w-24">Jenis</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500">Tipe</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 w-24">Jumlah</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500">Keterangan</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($this->logList as $p)
                        @php
                            $this->lockDate = $this->lockDates[$p->jenis->value] ?? null;
                            $this->isLocked = $this->lockDate && $p->tanggal_catat->format('Y-m-d') <= $this->lockDate;
                        @endphp
                        <tr class="{{ $this->isLocked ? 'bg-gray-50/70' : 'hover:bg-gray-50' }} transition-colors">
                            <td class="px-4 py-3">
                                <span class="text-xs font-mono text-gray-700">
                                    {{ $p->tanggal_catat->format('d/m/Y') }}
                                </span>
                                @if ($this->isLocked)
                                    <span class="ml-1 text-gray-300" title="Terkunci — periode sudah disubmit">
                                        🔒
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="text-xs font-medium px-1.5 py-0.5 rounded-full
                                             {{ $p->jenis->value === 'tabungan' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $p->jenis->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="text-xs font-medium px-2 py-0.5 rounded-full {{ $p->tipe_transaksi->badgeClass() }}">
                                    {{ $p->tipe_transaksi->labelShort() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span
                                    class="font-mono font-bold text-sm
                                             {{ $p->tipe_transaksi->isAddition() ? 'text-emerald-700' : 'text-red-600' }}">
                                    {{ $p->tipe_transaksi->isAddition() ? '+' : '−' }}{{ number_format($p->jumlah) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-400 max-w-xs truncate">
                                {{ $p->keterangan ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    @if (!$this->isLocked)
                                        <button type="button" wire:click="openEditForm({{ $p->id }})"
                                            class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                            Edit
                                        </button>
                                        <button type="button" wire:click="openDeleteModal({{ $p->id }})"
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


    {{-- ═══════════ MODAL FORM ═══════════ --}}
    @if ($this->showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5);"
            x-data x-on:keydown.escape.window="$wire.closeFormModal()">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.stop>

                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">
                            {{ $this->editingId ? 'Edit Pencatatan' : 'Tambah Pencatatan' }}
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">Satu entri = satu kejadian spesifik</p>
                    </div>
                    <button type="button" wire:click="closeFormModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-4">

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5">
                            Tanggal Kejadian <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model.live="formTanggal" max="{{ now()->format('Y-m-d') }}"
                            @if ($this->formMinDate) min="{{ $this->formMinDate }}" @endif
                            class="w-full px-3 py-2.5 border rounded-lg text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   @error('formTanggal') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                        @error('formTanggal')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Lock warning realtime --}}
                        @if ($this->formDateLocked)
                            <div
                                class="mt-1.5 p-2 bg-red-50 border border-red-200 rounded text-xs text-red-700 flex items-start gap-1.5">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $this->formDateLockMsg }}
                            </div>
                        @elseif($this->formMinDate)
                            <p class="mt-1 text-xs text-gray-400">
                                Tanggal tersedia: {{ \Carbon\Carbon::parse($this->formMinDate)->format('d/m/Y') }} –
                                hari ini
                            </p>
                        @endif
                    </div>

                    {{-- Jenis + Tipe --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5">
                                Jenis Buku <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="formJenis"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @foreach (\App\Enums\JenisLaporan::cases() as $j)
                                    <option value="{{ $j->value }}">{{ $j->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5">
                                Tipe Transaksi <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="formTipe"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @foreach (\App\Enums\TipeTransaksi::cases() as $tipe)
                                    <option value="{{ $tipe->value }}">{{ $tipe->labelShort() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Jumlah --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5">
                            Jumlah Buku <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="formJumlah" min="1" max="99999"
                            class="w-full px-3 py-2.5 border rounded-lg text-sm font-mono
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   @error('formJumlah') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                        @error('formJumlah')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1.5">
                            Keterangan <span
                                class="text-gray-400 font-normal normal-case tracking-normal">(opsional)</span>
                        </label>
                        <input type="text" wire:model="formKeterangan" maxlength="255"
                            placeholder="Contoh: Nasabah Budi Santoso, Seri No. ABC001..."
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeFormModal"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="button" wire:click="saveForm" wire:loading.attr="disabled"
                        @if ($this->formDateLocked) disabled @endif
                        class="flex items-center gap-2 px-5 py-2 bg-indigo-600 text-white text-sm font-semibold
                               rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
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
                        <span wire:loading.remove
                            wire:target="saveForm">{{ $this->editingId ? 'Perbarui' : 'Simpan' }}</span>
                        <span wire:loading wire:target="saveForm">Menyimpan...</span>
                    </button>
                </div>

            </div>
        </div>
    @endif


    {{-- ═══════════ MODAL DELETE ═══════════ --}}
    @if ($this->showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5);"
            x-data x-on:keydown.escape.window="$wire.closeDeleteModal()">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden" @click.stop>

                <div class="px-6 py-6 text-center">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Hapus Pencatatan?</h3>
                    <div class="bg-gray-100 rounded-lg px-4 py-2.5 text-sm font-medium text-gray-800 mb-3">
                        {{ $this->deletingLabel }}
                    </div>
                    <p class="text-xs text-red-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>

                <div class="px-6 pb-6 flex items-center justify-center gap-3">
                    <button type="button" wire:click="closeDeleteModal"
                        class="px-5 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" wire:click="confirmDelete" wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-5 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors disabled:opacity-60">
                        <span wire:loading.remove wire:target="confirmDelete">Ya, Hapus</span>
                        <span wire:loading wire:target="confirmDelete">Menghapus...</span>
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
