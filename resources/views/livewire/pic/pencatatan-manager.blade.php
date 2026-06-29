<div class="max-w-6xl mx-auto pb-10">

    {{-- ═══ FLASH MESSAGES (Modern Toast Style) ═══ --}}
    <div class="fixed top-4 right-4 z-[100] flex flex-col gap-2 w-full max-w-sm pointer-events-none">
        @if ($flashSuccess)
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" x-init="setTimeout(() => show = false, 4000)"
                class="pointer-events-auto w-full bg-white border-l-4 border-emerald-500 rounded-xl shadow-xl overflow-hidden"
                role="alert">
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-bold text-slate-900">Berhasil</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $flashSuccess }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false"
                            class="bg-white rounded-md inline-flex text-slate-400 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if ($flashError)
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" x-init="setTimeout(() => show = false, 6000)"
                class="pointer-events-auto w-full bg-white border-l-4 border-rose-500 rounded-xl shadow-xl overflow-hidden"
                role="alert">
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-bold text-slate-900">Terjadi Kesalahan</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $flashError }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false"
                            class="bg-white rounded-md inline-flex text-slate-400 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ═══ STOK REAL-TIME MONITORING (DASHBOARD WIDGET STYLE) ═══ --}}
    <div x-data="{ expanded: false }"
        class="mb-8 bg-slate-900 rounded-3xl shadow-xl shadow-slate-900/10 overflow-hidden relative border border-slate-800">
        {{-- Decorative background glow --}}
        <div class="absolute top-0 left-1/4 w-96 h-32 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

        {{-- Header / Summary View --}}
        <div @click="expanded = !expanded"
            class="px-6 py-5 cursor-pointer hover:bg-slate-800/50 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10 select-none">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2 tracking-wide">
                        Estimasi Stok Real-Time
                    </h3>
                    <p class="text-sm text-slate-400 mt-0.5">Saldo awal laporan terakhir ± pencatatan bulan ini</p>
                </div>
            </div>

            <div class="flex items-center gap-6 ml-16 md:ml-0">
                {{-- Quick Summary Tabungan --}}
                @php $rtTab = $this->stokRealtime['tabungan'] ?? null; @endphp
                <div class="text-right">
                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Tabungan</p>
                    <p class="text-2xl font-bold text-white font-mono leading-none">
                        {{ $rtTab ? number_format($rtTab['stok_sekarang'], 0, ',', '.') : '-' }}
                    </p>
                </div>

                <div class="w-px h-10 bg-slate-700"></div>

                {{-- Quick Summary Deposito --}}
                @php $rtDep = $this->stokRealtime['deposito'] ?? null; @endphp
                <div class="text-right">
                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Deposito</p>
                    <p class="text-2xl font-bold text-white font-mono leading-none">
                        {{ $rtDep ? number_format($rtDep['stok_sekarang'], 0, ',', '.') : '-' }}
                    </p>
                </div>

                <div
                    class="ml-2 w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 border border-slate-700">
                    <svg class="w-5 h-5 transform transition-transform duration-300"
                        :class="{ 'rotate-180 text-white': expanded }" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Expanded Detail View --}}
        <div x-show="expanded" x-collapse>
            <div class="p-6 pt-0 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-2">
                    @foreach (['tabungan' => 'Buku Tabungan', 'deposito' => 'Bilyet Deposito'] as $jenisKey => $jenisLabel)
                        @php
                            $rt = $this->stokRealtime[$jenisKey] ?? null;
                            $isTab = $jenisKey === 'tabungan';
                        @endphp

                        <div class="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden flex flex-col">
                            {{-- Card Header --}}
                            <div
                                class="px-5 py-4 bg-slate-800/80 border-b border-slate-700 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $isTab ? 'bg-indigo-400' : 'bg-slate-400' }}">
                                    </div>
                                    <p class="text-sm font-bold text-white tracking-wide">{{ $jenisLabel }}</p>
                                </div>
                                @if ($rt && $rt['lock_date'])
                                    <span
                                        class="flex items-center gap-1.5 text-xs font-medium text-slate-400 bg-slate-900 px-2 py-1 rounded-md border border-slate-700/50">
                                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Saldo {{ $rt['lock_date'] }}
                                    </span>
                                @else
                                    <span
                                        class="text-[10px] uppercase font-bold tracking-wider text-amber-400 bg-amber-400/10 px-2 py-1 rounded border border-amber-400/20">Belum
                                        Disubmit</span>
                                @endif
                            </div>

                            @if ($rt)
                                <div class="p-5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-end justify-between mb-5">
                                            <div>
                                                <p
                                                    class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">
                                                    Saldo Laporan Terakhir</p>
                                                <p class="text-lg font-bold font-mono text-slate-200">
                                                    {{ number_format($rt['saldo_base'], 0, ',', '.') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p
                                                    class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">
                                                    Estimasi Saat Ini</p>
                                                <p
                                                    class="text-xl font-bold font-mono {{ $isTab ? 'text-indigo-400' : 'text-white' }}">
                                                    {{ number_format($rt['stok_sekarang'], 0, ',', '.') }}</p>
                                            </div>
                                        </div>

                                        {{-- Mutasi Bulan Ini --}}
                                        <div
                                            class="grid grid-cols-3 gap-0 bg-slate-900 rounded-xl border border-slate-700/50 p-1 mb-5">
                                            <div class="text-center py-2 px-1 rounded-lg bg-slate-800/50">
                                                <p
                                                    class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold mb-1">
                                                    Masuk</p>
                                                <p class="text-sm font-bold font-mono text-emerald-400">
                                                    +{{ number_format($rt['masuk_bulan_ini'], 0, ',', '.') }}</p>
                                            </div>
                                            <div class="text-center py-2 px-1 border-x border-slate-700/50">
                                                <p
                                                    class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold mb-1">
                                                    Keluar</p>
                                                <p class="text-sm font-bold font-mono text-rose-400">
                                                    -{{ number_format($rt['keluar_bulan_ini'], 0, ',', '.') }}</p>
                                            </div>
                                            <div class="text-center py-2 px-1">
                                                <p
                                                    class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold mb-1">
                                                    Net</p>
                                                <p
                                                    class="text-sm font-bold font-mono {{ $rt['net_bulan_ini'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                                    {{ $rt['net_bulan_ini'] >= 0 ? '+' : '' }}{{ number_format($rt['net_bulan_ini'], 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Riwayat Terakhir --}}
                                    @if (count($rt['perubahan_terakhir']) > 0)
                                        <div>
                                            <p
                                                class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-2 flex items-center gap-1.5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Riwayat Terakhir
                                            </p>
                                            <div class="space-y-1.5">
                                                @foreach (array_slice($rt['perubahan_terakhir'], 0, 3) as $ch)
                                                    <div
                                                        class="flex items-center gap-3 text-xs py-1.5 px-3 rounded-lg bg-slate-900 border border-slate-700/50">
                                                        <span
                                                            class="text-slate-500 font-mono text-[10px] flex-shrink-0">{{ $ch['tanggal'] }}</span>
                                                        <span
                                                            class="font-bold font-mono text-[11px] w-12 text-right flex-shrink-0 {{ $ch['is_masuk'] ? 'text-emerald-400' : 'text-rose-400' }}">
                                                            {{ $ch['is_masuk'] ? '+' : '-' }}{{ number_format($ch['jumlah'], 0, ',', '.') }}
                                                        </span>
                                                        <span class="text-[11px] font-medium text-slate-300 truncate">
                                                            {{ $ch['tipe'] }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Peringatan Belum Lapor --}}
                                @if ($rt['pencatatan_count'] > 0)
                                    <div class="px-5 py-3 bg-indigo-900/30 border-t border-indigo-500/20">
                                        <p class="text-xs font-medium text-indigo-300 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Ada <strong class="text-white">{{ $rt['pencatatan_count'] }}</strong> data
                                            siap disinkronisasi ke laporan.
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="p-8 text-center flex-1 flex flex-col items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-600 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm font-medium text-slate-400">Belum ada data awal</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    {{-- ═══ LOG PENCATATAN (MAIN TABLE AREA) ═══ --}}
    <div
        class="bg-white rounded-3xl shadow-xl border border-[--color-border-default] shadow-xl overflow-hidden border-t-4 border-t-indigo-600 relative">

        {{-- Header Bar --}}
        <div
            class="px-8 py-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div>
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    Buku Jurnal Pencatatan
                </h2>
                <p class="text-sm text-slate-500 mt-1">Kelola dan pantau seluruh transaksi fisik secara mendetail.</p>
            </div>
            <button type="button" wire:click="openCreateForm"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-200 focus:ring-4 focus:ring-indigo-100 transition-all flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Catat Transaksi Baru
            </button>
        </div>

        {{-- Filter Bar (Sleek Inline Design) --}}
        <div class="px-8 py-5 border-b border-slate-100 bg-white">
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">

                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Jenis
                            Buku</label>
                        <select wire:model.live="filterJenis"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Semua Jenis</option>
                            <option value="tabungan">Tabungan</option>
                            <option value="deposito">Deposito</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tipe
                            Transaksi</label>
                        <select wire:model.live="filterTipe"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Semua Tipe</option>
                            @foreach (\App\Enums\TipeTransaksi::cases() as $tipe)
                                <option value="{{ $tipe->value }}">{{ $tipe->labelShort() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Dari
                            Tanggal</label>
                        <input type="date" wire:model.live="filterDari"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Sampai
                            Tanggal</label>
                        <input type="date" wire:model.live="filterSampai"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all">
                    </div>
                </div>

                <div class="flex items-center gap-3 lg:pb-0.5">
                    <button type="button" wire:click="resetFilter"
                        class="px-5 py-2.5 border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold text-slate-600 rounded-xl transition-all shadow-sm flex items-center justify-center whitespace-nowrap">
                        Reset Filter
                    </button>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between text-xs font-medium">
                <span
                    class="inline-flex items-center gap-1.5 text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Ditemukan {{ $this->logList->count() }} entri pencatatan
                </span>
            </div>
        </div>

        {{-- Data Table --}}
        @if ($this->logList->isEmpty())
            <div class="py-24 flex flex-col items-center justify-center text-center px-4">
                <div
                    class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Transaksi</h3>
                <p class="text-sm text-slate-500 max-w-sm mb-6">Mulai catat penggunaan atau penambahan fisik buku. Klik
                    tombol "Catat Transaksi Baru" untuk memulai.</p>
                <button type="button" wire:click="openCreateForm"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Pencatatan Pertama
                </button>
            </div>
        @else
            <div class="overflow-x-auto min-h-[300px]">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th
                                class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-36">
                                Tanggal</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-32">
                                Jenis Buku</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">
                                Tipe Transaksi</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right whitespace-nowrap w-32">
                                Jumlah</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-1/3">
                                Keterangan</th>
                            <th
                                class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right whitespace-nowrap w-24">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($this->logList as $p)
                            @php
                                $lockDate = $this->lockDates[$p->jenis->value] ?? null;
                                $isLocked = $lockDate && $p->tanggal_catat->format('Y-m-d') <= $lockDate;
                            @endphp
                            <tr
                                class="group hover:bg-slate-50/80 transition-colors {{ $isLocked ? 'bg-slate-50/50' : '' }}">
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="font-mono text-slate-700 font-medium">{{ $p->tanggal_catat->format('d/m/Y') }}</span>
                                        @if ($isLocked)
                                            <span class="text-slate-400 cursor-help"
                                                title="Terkunci — periode laporan sudah disubmit">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold border {{ $p->jenis->value === 'tabungan' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                        {{ $p->jenis->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium border {{ $p->tipe_transaksi->badgeClass() }}">
                                        {{ $p->tipe_transaksi->labelShort() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span
                                        class="font-mono font-bold text-base {{ $p->tipe_transaksi->isAddition() ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $p->tipe_transaksi->isAddition() ? '+' : '-' }}{{ number_format($p->jumlah, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    <div class="max-w-xs truncate" title="{{ $p->keterangan }}">
                                        {{ $p->keterangan ?: '-' }}
                                    </div>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-right">
                                    @if (!$isLocked)
                                        <div
                                            class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                            <button type="button" wire:click="openEditForm({{ $p->id }})"
                                                class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                title="Edit Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="openDeleteModal({{ $p->id }})"
                                                class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-rose-500"
                                                title="Hapus Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <span
                                            class="inline-flex text-[10px] font-bold uppercase tracking-wider text-slate-400 bg-slate-100 px-2 py-1 rounded">Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ═══════════ MODAL FORM (ADD/EDIT) ═══════════ --}}
    @if ($this->showFormModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
            style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);" x-data
            x-on:keydown.escape.window="$wire.closeFormModal()" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden relative border border-white/50"
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-8" @click.stop>

                {{-- Modal Header --}}
                <div class="px-8 pt-8 pb-5 border-b border-slate-100 flex items-start justify-between bg-slate-50/50">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">
                            {{ $this->editingId ? 'Edit Transaksi' : 'Catat Transaksi Baru' }}
                        </h3>
                        <p class="text-sm text-slate-500 mt-1">Satu entri untuk satu kejadian fisik.</p>
                    </div>
                    <button type="button" wire:click="closeFormModal"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Form Body --}}
                <div class="px-8 py-6 space-y-6">

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                            Tanggal Kejadian <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" wire:model.live="formTanggal" max="{{ now()->format('Y-m-d') }}"
                            @if ($this->formMinDate) min="{{ $this->formMinDate }}" @endif
                            class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all
                                   @error('formTanggal') border-rose-400 bg-rose-50/50 @else border-slate-200 @enderror">

                        @error('formTanggal')
                            <p class="text-sm text-rose-600 mt-1.5 flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        @if ($this->formDateLocked)
                            <div
                                class="mt-2 p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p>{{ $this->formDateLockMsg }}</p>
                            </div>
                        @elseif($this->formMinDate)
                            <p class="text-xs text-slate-500 mt-2 font-medium">
                                Bisa mundur max. sampai:
                                {{ \Carbon\Carbon::parse($this->formMinDate)->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>

                    {{-- Jenis + Tipe --}}
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                                Jenis <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model.live="formJenis"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                                @foreach (\App\Enums\JenisLaporan::cases() as $j)
                                    <option value="{{ $j->value }}">{{ $j->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                                Tipe <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model="formTipe"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                                @foreach (\App\Enums\TipeTransaksi::cases() as $tipe)
                                    <option value="{{ $tipe->value }}">{{ $tipe->labelShort() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Jumlah --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                            Jumlah Fisik <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" wire:model="formJumlah" min="1" max="99999" placeholder="0"
                            class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-2xl font-black font-mono text-right text-slate-800 placeholder:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all
                                   @error('formJumlah') border-rose-400 bg-rose-50/50 @else border-slate-200 @enderror">
                        @error('formJumlah')
                            <p class="text-sm text-rose-600 mt-1.5 flex items-center justify-end gap-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                            Keterangan <span
                                class="text-slate-500 font-medium normal-case tracking-normal">(Opsional)</span>
                        </label>
                        <input type="text" wire:model="formKeterangan" maxlength="255"
                            placeholder="Contoh: Nasabah Budi, No Seri ABC..."
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div
                    class="px-8 py-5 bg-white border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    <button type="button" wire:click="closeFormModal"
                        class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300">
                        Batal
                    </button>
                    <button type="button" wire:click="saveForm" wire:loading.attr="disabled"
                        @if ($this->formDateLocked) disabled @endif
                        class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2 focus:outline-none focus:ring-4 focus:ring-indigo-100 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                        <svg wire:loading.remove wire:target="saveForm" class="w-4 h-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
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
                            wire:target="saveForm">{{ $this->editingId ? 'Simpan Perubahan' : 'Catat Sekarang' }}</span>
                        <span wire:loading wire:target="saveForm">Menyimpan...</span>
                    </button>
                </div>

            </div>
        </div>
    @endif


    {{-- ═══════════ MODAL DELETE ═══════════ --}}
    @if ($this->showDeleteModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
            style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);" x-data
            x-on:keydown.escape.window="$wire.closeDeleteModal()"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-sm overflow-hidden relative border border-white/50"
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-8" @click.stop>

                <div class="px-8 pt-8 pb-6 text-center">
                    <div
                        class="w-16 h-16 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                        <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Hapus Pencatatan?</h3>
                    <p class="text-sm text-slate-500 mb-5">Apakah Anda yakin ingin menghapus data ini? Tindakan ini
                        tidak dapat dibatalkan.</p>

                    <div
                        class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 text-left">
                        {{ $this->deletingLabel }}
                    </div>
                </div>

                <div class="px-8 pb-8 flex flex-col-reverse sm:flex-row items-center gap-3">
                    <button type="button" wire:click="closeDeleteModal"
                        class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300">
                        Batal
                    </button>
                    <button type="button" wire:click="confirmDelete" wire:loading.attr="disabled"
                        class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all flex items-center justify-center gap-2 focus:outline-none focus:ring-4 focus:ring-rose-100">
                        <svg wire:loading wire:target="confirmDelete" class="w-4 h-4 animate-spin" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        <span wire:loading.remove wire:target="confirmDelete">Ya, Hapus</span>
                        <span wire:loading wire:target="confirmDelete">Menghapus...</span>
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
