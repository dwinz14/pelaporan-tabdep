<div>

    {{-- ═══ FLASH ═══ --}}
    @if ($flashSuccess)
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" x-init="setTimeout(() => show = false, 4000)"
            class="flash-animate mb-5 flex items-start gap-3 p-4 bg-white border border-emerald-200 rounded-xl shadow-sm shadow-emerald-100/50"
            role="alert">
            <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center bg-emerald-100 rounded-lg">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-emerald-700 text-xs uppercase tracking-wide mb-0.5">Berhasil</p>
                <p class="text-slate-600">{{ $flashSuccess }}</p>
            </div>
            <button @click="show = false"
                class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors p-0.5 rounded-md hover:bg-slate-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if ($flashError)
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" x-init="setTimeout(() => show = false, 6000)"
            class="flash-animate mb-5 flex items-start gap-3 p-4 bg-white border border-red-200 rounded-xl shadow-sm shadow-red-100/50"
            role="alert">
            <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center bg-red-100 rounded-lg">
                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-red-700 text-xs uppercase tracking-wide mb-0.5">Terjadi Kesalahan</p>
                <p class="text-slate-600">{{ $flashError }}</p>
            </div>
            <button @click="show = false"
                class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors p-0.5 rounded-md hover:bg-slate-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- ═══ STOK REAL-TIME MONITORING (ACCORDION) ═══ --}}
    <div x-data="{ expanded: false }"
        class="mb-6 bg-white rounded-xl border border-[--color-border-default] shadow-lg overflow-hidden">
        {{-- Header / Summary View --}}
        <div @click="expanded = !expanded"
            class="px-6 py-4 cursor-pointer hover:bg-slate-50 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[--color-text-primary] flex items-center gap-2">
                        Stok Real-Time Saat Ini
                    </h3>
                    <p class="text-xs text-[--color-text-muted] mt-0.5">Klik untuk melihat detail</p>
                </div>
            </div>

            <div class="flex items-center gap-5 sm:gap-6 ml-12 md:ml-0">
                {{-- Quick Summary Tabungan --}}
                @php $rtTab = $this->stokRealtime['tabungan'] ?? null; @endphp
                <div class="text-right">
                    <p class="text-[10px] uppercase tracking-wider text-[--color-text-muted] font-bold mb-0.5">Tabungan
                    </p>
                    <p class="text-xl font-bold text-indigo-600 font-mono">
                        {{ $rtTab ? number_format($rtTab['stok_sekarang'], 0, ',', '.') : '-' }}</p>
                </div>

                <div class="w-px h-8 bg-slate-200"></div>

                {{-- Quick Summary Deposito --}}
                @php $rtDep = $this->stokRealtime['deposito'] ?? null; @endphp
                <div class="text-right">
                    <p class="text-[10px] uppercase tracking-wider text-[--color-text-muted] font-bold mb-0.5">Deposito
                    </p>
                    <p class="text-xl font-bold text-slate-700 font-mono">
                        {{ $rtDep ? number_format($rtDep['stok_sekarang'], 0, ',', '.') : '-' }}</p>
                </div>

                <div class="ml-2 text-slate-400">
                    <svg class="w-5 h-5 transform transition-transform duration-200" :class="{ 'rotate-180': expanded }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Expanded Detail View --}}
        <div x-show="expanded" x-collapse>
            <div class="p-6 border-t border-[--color-border-default] bg-slate-50">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach (['tabungan' => 'Tabungan', 'deposito' => 'Deposito'] as $jenisKey => $jenisLabel)
                        @php $rt = $this->stokRealtime[$jenisKey] ?? null; @endphp

                        <div class="bg-white rounded-lg border border-[--color-border-default] overflow-hidden">
                            {{-- ── Header ── --}}
                            <div
                                class="px-4 py-3 {{ $jenisKey === 'tabungan' ? 'bg-indigo-50 border-b border-indigo-100' : 'bg-slate-100 border-b border-slate-200' }} flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <p
                                        class="text-sm font-bold {{ $jenisKey === 'tabungan' ? 'text-indigo-900' : 'text-slate-800' }} tracking-wide">
                                        {{ $jenisLabel }}</p>
                                </div>
                                @if ($rt && $rt['lock_date'])
                                    <span
                                        class="flex items-center gap-1 text-xs font-medium {{ $jenisKey === 'tabungan' ? 'text-indigo-600' : 'text-slate-600' }}">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Terkunci s/d {{ $rt['lock_date'] }}
                                    </span>
                                @else
                                    <span
                                        class="text-[10px] uppercase font-bold tracking-wider text-[--color-text-muted]">Belum
                                        disubmit</span>
                                @endif
                            </div>

                            @if ($rt)
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <p
                                                class="text-[10px] uppercase tracking-wider text-[--color-text-muted] font-bold mb-0.5">
                                                Saldo Awal</p>
                                            <p class="text-sm font-bold font-mono text-[--color-text-secondary]">
                                                {{ number_format($rt['saldo_base'], 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- ── Bulan Ini Grid ── --}}
                                    <div
                                        class="grid grid-cols-3 gap-2 bg-[--color-bg-subtle] rounded-md border border-[--color-border-default] p-2.5 mb-4">
                                        <div class="text-center">
                                            <p
                                                class="text-[10px] uppercase tracking-wider text-[--color-text-muted] font-semibold">
                                                Masuk</p>
                                            <p class="text-xs font-bold font-mono text-emerald-600">
                                                +{{ number_format($rt['masuk_bulan_ini'], 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-center border-x border-[--color-border-default]">
                                            <p
                                                class="text-[10px] uppercase tracking-wider text-[--color-text-muted] font-semibold">
                                                Keluar</p>
                                            <p class="text-xs font-bold font-mono text-red-500">
                                                -{{ number_format($rt['keluar_bulan_ini'], 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p
                                                class="text-[10px] uppercase tracking-wider text-[--color-text-muted] font-semibold">
                                                Net</p>
                                            <p
                                                class="text-xs font-bold font-mono {{ $rt['net_bulan_ini'] >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                                {{ $rt['net_bulan_ini'] >= 0 ? '+' : '' }}{{ number_format($rt['net_bulan_ini'], 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- ── Perubahan Terakhir (mini-timeline) ── --}}
                                    @if (count($rt['perubahan_terakhir']) > 0)
                                        <div>
                                            <p
                                                class="text-[10px] uppercase tracking-wider text-[--color-text-secondary] font-bold mb-2">
                                                Riwayat Terbaru</p>
                                            <div class="space-y-1">
                                                @foreach (array_slice($rt['perubahan_terakhir'], 0, 3) as $ch)
                                                    <div
                                                        class="flex items-center gap-2 text-xs py-1 px-1.5 rounded bg-white border border-slate-100">
                                                        <span
                                                            class="text-[--color-text-muted] font-mono text-[10px] flex-shrink-0">{{ $ch['tanggal'] }}</span>
                                                        <span
                                                            class="font-bold font-mono text-[10px] w-10 text-right flex-shrink-0 {{ $ch['is_masuk'] ? 'text-emerald-600' : 'text-red-500' }}">
                                                            {{ $ch['is_masuk'] ? '+' : '-' }}{{ number_format($ch['jumlah'], 0, ',', '.') }}
                                                        </span>
                                                        <span
                                                            class="text-[10px] font-medium text-[--color-text-secondary] truncate">
                                                            {{ $ch['tipe'] }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- ── Footer: pencatatan belum lapor ── --}}
                                @if ($rt['pencatatan_count'] > 0)
                                    <div class="px-4 py-2 bg-amber-50 border-t border-amber-100">
                                        <p
                                            class="text-[10px] font-bold uppercase tracking-wider text-amber-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $rt['pencatatan_count'] }} pencatatan tertunda
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="px-4 py-8 text-center bg-white">
                                    <p class="text-xs font-medium text-[--color-text-secondary]">Belum ada data</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    {{-- ═══ LOG PENCATATAN (FOKUS UTAMA) ═══ --}}
    <div
        class="mb-8 bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-xl overflow-hidden border-t-4 border-t-indigo-600">

        {{-- Header + Tambah --}}
        <div
            class="px-6 py-5 border-b border-[--color-border-default] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-[--color-text-primary] flex items-center gap-2">
                    <div
                        class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    Pencatatan Penggunaan
                </h2>
                <p class="text-sm text-[--color-text-muted] mt-1">Catat transaksi penggunaan buku tabungan dan bilyet
                    deposito di sini.</p>
            </div>
            <button type="button" wire:click="openCreateForm"
                class="btn-primary shadow-sm hover:shadow transition-shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pencatatan
            </button>
        </div>

        {{-- Filter --}}
        <div
            class="px-6 py-4 bg-[--color-bg-subtle] border-b border-[--color-border-default] flex items-end gap-4 flex-wrap">
            <div class="space-y-1.5 w-full sm:w-auto flex-1 sm:flex-none min-w-[140px]">
                <label class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider">Jenis
                    Buku</label>
                <select wire:model.live="filterJenis"
                    class="appearance-none w-full px-3 py-2 border border-[--color-border-default] rounded-lg text-sm text-[--color-text-primary] bg-white
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow duration-150">
                    <option value="">Semua Jenis</option>
                    <option value="tabungan">Tabungan</option>
                    <option value="deposito">Deposito</option>
                </select>
            </div>

            <div class="space-y-1.5 w-full sm:w-auto flex-1 sm:flex-none min-w-[140px]">
                <label class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider">Tipe
                    Transaksi</label>
                <select wire:model.live="filterTipe"
                    class="appearance-none w-full px-3 py-2 border border-[--color-border-default] rounded-lg text-sm text-[--color-text-primary] bg-white
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow duration-150">
                    <option value="">Semua Tipe</option>
                    @foreach (\App\Enums\TipeTransaksi::cases() as $tipe)
                        <option value="{{ $tipe->value }}">{{ $tipe->labelShort() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5 w-full sm:w-auto flex-1 sm:flex-none min-w-[140px]">
                <label class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider">Dari
                    Tanggal</label>
                <input type="date" wire:model.live="filterDari"
                    class="w-full px-3 py-2 border border-[--color-border-default] rounded-lg text-sm text-[--color-text-primary] bg-white
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow duration-150">
            </div>

            <div class="space-y-1.5 w-full sm:w-auto flex-1 sm:flex-none min-w-[140px]">
                <label
                    class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider">Sampai
                    Tanggal</label>
                <input type="date" wire:model.live="filterSampai"
                    class="w-full px-3 py-2 border border-[--color-border-default] rounded-lg text-sm text-[--color-text-primary] bg-white
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow duration-150">
            </div>

            <button type="button" wire:click="resetFilter"
                class="px-4 py-2 border border-[--color-border-default] bg-white text-sm font-medium text-[--color-text-secondary] rounded-lg
                       hover:bg-[--color-bg-subtle] hover:text-[--color-text-primary] transition-all duration-150 h-[38px] flex items-center justify-center">
                Reset
            </button>

            <div
                class="w-full sm:w-auto sm:ml-auto text-sm text-[--color-text-muted] font-medium self-center mt-2 sm:mt-0">
                <span class="bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-md">{{ $this->logList->count() }}
                    pencatatan</span>
            </div>
        </div>

        {{-- Tabel Log --}}
        @if ($this->logList->isEmpty())
            <div class="py-20 flex flex-col items-center text-center">
                <div
                    class="w-16 h-16 bg-indigo-50 border border-indigo-100 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <p class="text-base font-semibold text-[--color-text-primary] mb-2">Belum ada pencatatan</p>
                <p class="text-sm text-[--color-text-muted] max-w-sm mb-6">Mulai catat penggunaan stok buku Anda. Klik
                    tombol "Tambah Pencatatan" di atas untuk menambahkan entri pertama.</p>
                <button type="button" wire:click="openCreateForm" class="btn-primary-sm px-4 py-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Pencatatan Baru
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[--color-bg-subtle] border-b border-[--color-border-default]">
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-[--color-text-muted] uppercase tracking-wider w-32">
                                Tanggal
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-[--color-text-muted] uppercase tracking-wider w-28">
                                Jenis
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-[--color-text-muted] uppercase tracking-wider">
                                Tipe
                            </th>
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold text-[--color-text-muted] uppercase tracking-wider w-32">
                                Jumlah
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-[--color-text-muted] uppercase tracking-wider">
                                Keterangan
                            </th>
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold text-[--color-text-muted] uppercase tracking-wider w-32">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[--color-border-default]">
                        @foreach ($this->logList as $p)
                            @php
                                $lockDate = $this->lockDates[$p->jenis->value] ?? null;
                                $isLocked = $lockDate && $p->tanggal_catat->format('Y-m-d') <= $lockDate;
                            @endphp
                            <tr
                                class="{{ $isLocked ? 'bg-[--color-bg-subtle]/30' : 'hover:bg-[--color-bg-subtle]' }} transition-colors duration-150 group">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-mono text-[--color-text-primary]">
                                        {{ $p->tanggal_catat->format('d/m/Y') }}
                                    </span>
                                    @if ($isLocked)
                                        <span class="ml-1.5 text-[--color-text-disabled] cursor-help"
                                            title="Terkunci — periode sudah disubmit">
                                            <svg class="w-3.5 h-3.5 inline" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs font-semibold px-2 py-1 rounded-md border {{ $p->jenis->value === 'tabungan' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                        {{ $p->jenis->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs font-medium px-2.5 py-1 rounded-md border {{ $p->tipe_transaksi->badgeClass() }}">
                                        {{ $p->tipe_transaksi->labelShort() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="font-mono font-bold text-base {{ $p->tipe_transaksi->isAddition() ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $p->tipe_transaksi->isAddition() ? '+' : '-' }}{{ number_format($p->jumlah, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-[--color-text-muted] max-w-sm truncate">
                                    {{ $p->keterangan ?? '&mdash;' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div
                                        class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity focus-within:opacity-100">
                                        @if (!$isLocked)
                                            <button type="button" wire:click="openEditForm({{ $p->id }})"
                                                class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="openDeleteModal({{ $p->id }})"
                                                class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @else
                                            <span
                                                class="text-xs font-medium px-2 py-1 bg-slate-100 text-[--color-text-disabled] rounded">Terkunci</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ═══════════ MODAL FORM ═══════════ --}}
    @if ($this->showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(2px);" x-data
            x-on:keydown.escape.window="$wire.closeFormModal()" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2" @click.stop>

                <div class="px-6 py-5 border-b border-[--color-border-default] flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-[--color-text-primary]">
                            {{ $this->editingId ? 'Edit Pencatatan' : 'Tambah Pencatatan' }}
                        </h3>
                        <p class="text-xs text-[--color-text-muted] mt-0.5">Satu entri = satu kejadian spesifik</p>
                    </div>
                    <button type="button" wire:click="closeFormModal"
                        class="text-[--color-text-muted] hover:text-[--color-text-secondary] hover:bg-[--color-bg-subtle] rounded-lg p-1 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-5">

                    {{-- Tanggal --}}
                    <div class="space-y-1.5">
                        <label
                            class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider">
                            Tanggal Kejadian <span class="text-red-500 font-normal">*</span>
                        </label>
                        <input type="date" wire:model.live="formTanggal" max="{{ now()->format('Y-m-d') }}"
                            @if ($this->formMinDate) min="{{ $this->formMinDate }}" @endif
                            class="w-full px-3 py-2.5 border rounded-lg text-sm text-[--color-text-primary] bg-white placeholder:text-[--color-text-disabled]
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                   @error('formTanggal') border-red-400 bg-red-50 @else border-[--color-border-default] @enderror
                                   transition-shadow duration-150">
                        @error('formTanggal')
                            <p class="text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        @if ($this->formDateLocked)
                            <div
                                class="p-3 bg-red-50 border border-red-200 rounded-lg text-xs text-red-700 flex items-start gap-2">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $this->formDateLockMsg }}
                            </div>
                        @elseif($this->formMinDate)
                            <p class="text-xs text-[--color-text-muted]">
                                Tanggal tersedia: {{ \Carbon\Carbon::parse($this->formMinDate)->format('d/m/Y') }} –
                                hari ini
                            </p>
                        @endif
                    </div>

                    {{-- Jenis + Tipe --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label
                                class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider">
                                Jenis Buku <span class="text-red-500 font-normal">*</span>
                            </label>
                            <select wire:model.live="formJenis"
                                class="appearance-none w-full px-3 py-2.5 border border-[--color-border-default] rounded-lg text-sm text-[--color-text-primary] bg-white
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow duration-150">
                                @foreach (\App\Enums\JenisLaporan::cases() as $j)
                                    <option value="{{ $j->value }}">{{ $j->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label
                                class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider">
                                Tipe Transaksi <span class="text-red-500 font-normal">*</span>
                            </label>
                            <select wire:model="formTipe"
                                class="appearance-none w-full px-3 py-2.5 border border-[--color-border-default] rounded-lg text-sm text-[--color-text-primary] bg-white
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow duration-150">
                                @foreach (\App\Enums\TipeTransaksi::cases() as $tipe)
                                    <option value="{{ $tipe->value }}">{{ $tipe->labelShort() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Jumlah --}}
                    <div class="space-y-1.5">
                        <label
                            class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider">
                            Jumlah Buku <span class="text-red-500 font-normal">*</span>
                        </label>
                        <input type="number" wire:model="formJumlah" min="1" max="99999"
                            class="w-full px-3 py-2.5 border rounded-lg text-sm font-mono text-right text-[--color-text-primary] bg-white
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                   @error('formJumlah') border-red-400 bg-red-50 @else border-[--color-border-default] @enderror
                                   transition-shadow duration-150">
                        @error('formJumlah')
                            <p class="text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="space-y-1.5">
                        <label
                            class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider">
                            Keterangan <span
                                class="text-[--color-text-muted] font-normal normal-case tracking-normal">(opsional)</span>
                        </label>
                        <input type="text" wire:model="formKeterangan" maxlength="255"
                            placeholder="Contoh: Nasabah Budi Santoso, Seri No. ABC001..."
                            class="w-full px-3 py-2.5 border border-[--color-border-default] rounded-lg text-sm text-[--color-text-primary] bg-white placeholder:text-[--color-text-disabled]
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow duration-150">
                    </div>
                </div>

                <div
                    class="px-6 py-4 bg-[--color-bg-subtle] border-t border-[--color-border-default] flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeFormModal" class="btn-secondary text-xs px-4 py-2">
                        Batal
                    </button>
                    <button type="button" wire:click="saveForm" wire:loading.attr="disabled"
                        @if ($this->formDateLocked) disabled @endif class="btn-primary text-xs px-5 py-2">
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(2px);" x-data
            x-on:keydown.escape.window="$wire.closeDeleteModal()"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2" @click.stop>

                <div class="px-6 py-6 text-center">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-[--color-text-primary] mb-3">Hapus Pencatatan?</h3>
                    <div
                        class="bg-[--color-bg-inset] rounded-lg px-4 py-3 text-sm font-medium text-[--color-text-primary] mb-3">
                        {{ $this->deletingLabel }}
                    </div>
                    <p class="text-xs text-red-600 font-medium">Tindakan ini tidak dapat dibatalkan.</p>
                </div>

                <div class="px-6 pb-6 flex items-center justify-center gap-3">
                    <button type="button" wire:click="closeDeleteModal" class="btn-secondary text-xs px-5 py-2">
                        Batal
                    </button>
                    <button type="button" wire:click="confirmDelete" wire:loading.attr="disabled"
                        class="btn-danger text-xs px-5 py-2">
                        <span wire:loading.remove wire:target="confirmDelete">Ya, Hapus</span>
                        <span wire:loading wire:target="confirmDelete">Menghapus...</span>
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
