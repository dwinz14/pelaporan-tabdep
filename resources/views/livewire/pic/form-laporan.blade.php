<div>
    {{-- ═══ FLASH MESSAGES ═══ --}}
    <div class="fixed top-4 right-4 z-50 flex flex-col gap-2 w-full max-w-sm pointer-events-none">
        @if ($flashSuccess)
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" x-init="setTimeout(() => show = false, 4000)"
                class="pointer-events-auto w-full bg-white border-l-4 border-emerald-500 rounded-lg shadow-lg overflow-hidden"
                role="alert">
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-slate-900">Berhasil</p>
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
                class="pointer-events-auto w-full bg-white border-l-4 border-rose-500 rounded-lg shadow-lg overflow-hidden"
                role="alert">
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-slate-900">Terjadi Kesalahan</p>
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

    {{-- ═══ BANNER SINKRONISASI ═══ --}}
    @if ($this->hasPencatatan && ($this->canEditTab || $this->canEditDep))
        <div class="mb-6 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-2xl shadow-md overflow-hidden relative">
            <div class="absolute right-0 top-0 h-full w-1/3 bg-white opacity-5 transform skew-x-12 translate-x-10">
            </div>
            <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
                <div class="flex items-start gap-4">
                    <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-white">Data Pencatatan Harian Tersedia</h3>
                        <p class="text-sm text-indigo-100 mt-1">Kami menemukan data pencatatan harian yang belum ditarik
                            ke laporan ini.</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach (['tabungan', 'deposito'] as $j)
                                @if (isset($syncRangeInfo[$j]) && $syncRangeInfo[$j]['count'] > 0)
                                    @php $s = $syncRangeInfo[$j]; @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-900/40 text-indigo-50 border border-indigo-400/30">
                                        <span class="font-bold uppercase tracking-wide">{{ $j }}</span>:
                                        +{{ $s['tambahan_stok'] }} Masuk |
                                        &minus;{{ (int) $s['digunakan'] + (int) $s['batal_rusak'] + (int) $s['batal_hilang'] }}
                                        Keluar
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <button type="button" wire:click="syncFromPencatatan" wire:loading.attr="disabled"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white text-indigo-600 hover:bg-indigo-50 px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm focus:ring-2 focus:ring-white/50 focus:outline-none flex-shrink-0 disabled:opacity-75">
                    <svg wire:loading.remove wire:target="syncFromPencatatan" class="w-4 h-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                    <svg wire:loading wire:target="syncFromPencatatan" class="w-4 h-4 animate-spin" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <span wire:loading.remove wire:target="syncFromPencatatan">Tarik Data Sekarang</span>
                    <span wire:loading wire:target="syncFromPencatatan">Memproses...</span>
                </button>
            </div>
        </div>
    @elseif(!$this->hasPencatatan && ($this->canEditTab || $this->canEditDep))
        <div
            class="mb-6 bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-center gap-3 text-sm text-slate-600">
            <div class="bg-white p-1.5 rounded-lg shadow-sm border border-slate-200 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p>Belum ada pencatatan harian di periode ini. Silakan isi form manual di bawah atau <a
                    href="{{ route('pic.pencatatan.index') }}"
                    class="text-indigo-600 font-semibold hover:underline">tambah pencatatan harian</a> terlebih dahulu.
            </p>
        </div>
    @endif

    {{-- ═══ MAIN APP CONTAINER ═══ --}}
    <div x-data="{ tab: 'tabungan' }" class="max-w-5xl mx-auto">

        {{-- Segmented Control Tabs --}}
        <div class="flex justify-center mb-6">
            <div
                class="inline-flex bg-gradient-to-r from-slate-200/80 to-blue-200/80 backdrop-blur shadow-sm border border-slate-200 p-1.5 rounded-2xl gap-1">
                <button type="button" @click="tab = 'tabungan'"
                    :class="tab === 'tabungan' ? 'bg-white text-indigo-700 shadow-sm border-slate-200' :
                        'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50 border-transparent'"
                    class="relative flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 border">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Buku Tabungan
                    <div
                        class="ml-1 w-2 h-2 rounded-full {{ \App\Enums\StatusVerifikasi::from($this->statusTab)->badgeClass() }} border border-white">
                    </div>
                </button>

                <button type="button" @click="tab = 'deposito'"
                    :class="tab === 'deposito' ? 'bg-white text-indigo-700 shadow-sm border-slate-200' :
                        'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50 border-transparent'"
                    class="relative flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 border">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Bilyet Deposito
                    <div
                        class="ml-1 w-2 h-2 rounded-full {{ \App\Enums\StatusVerifikasi::from($this->statusDep)->badgeClass() }} border border-white">
                    </div>
                </button>
            </div>
        </div>

        {{-- ══════════════ TABUNGAN FORM ══════════════ --}}
        <div x-show="tab === 'tabungan'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="bg-white rounded-3xl shadow-xl border border-[--color-border-default] shadow-xl overflow-hidden border-t-4 border-t-indigo-600">

            {{-- Header / Meta Info --}}
            <div
                class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-slate-900">Laporan Stok Tabungan</h2>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ \App\Enums\StatusVerifikasi::from($this->statusTab)->badgeClass() }}">
                            {{ \App\Enums\StatusVerifikasi::from($this->statusTab)->label() }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 mt-1">Isi pergerakan stok buku tabungan untuk periode ini.</p>
                </div>

                @if ($this->lastSavedTab)
                    <div class="flex flex-col sm:items-end">
                        <span class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-0.5">Terkahir
                            Disimpan Draft</span>
                        <span
                            class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-700 bg-slate-100/80 px-3 py-1.5 rounded-lg border border-slate-200">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $this->lastSavedTab }}
                        </span>
                    </div>
                @endif
            </div>

            @if ($this->statusTab === 'revision_requested' && $this->catatanRevisiTab)
                <div class="bg-amber-50 border-y border-amber-200 p-6 flex gap-4">
                    <div class="bg-amber-100 text-amber-600 p-2.5 rounded-full flex-shrink-0 h-fit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-800 uppercase tracking-wide">Catatan Revisi Akunting
                        </h4>
                        <p class="text-amber-700 mt-1 leading-relaxed">{{ $this->catatanRevisiTab }}</p>
                    </div>
                </div>
            @endif

            {{-- The "Ledger" Form Body --}}
            <div class="p-8 space-y-8">

                {{-- 1. Saldo Awal --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-slate-300"></span> Saldo Awal
                        </h3>
                        <p class="text-sm text-slate-500 mt-1">Sisa stok fisik dari periode sebelumnya.</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 px-6 py-3 rounded-xl w-full sm:w-64 text-right">
                        <span
                            class="text-2xl font-bold font-mono text-slate-800">{{ number_format((int) $this->saldoAwalTab, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- 2. Pemasukan --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-6 border-b border-slate-100">
                    <div class="pt-2">
                        <h3
                            class="text-sm font-bold text-emerald-600 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tambahan Stok
                        </h3>
                        <p class="text-sm text-slate-500 mt-1">Buku/bilyet baru yang diterima oleh cabang.</p>
                    </div>
                    <div class="w-full sm:w-64 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-emerald-500 font-mono font-bold">+</span>
                        </div>
                        <input type="number" wire:model.live="tambahanStokTab" min="0"
                            {{ !$this->canEditTab ? 'disabled' : '' }}
                            class="w-full pl-8 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-xl font-mono text-right text-slate-800 transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 disabled:bg-slate-50 disabled:text-slate-400">
                        @error('tambahanStokTab')
                            <p class="text-xs text-rose-500 mt-1.5 text-right">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 3. Pengeluaran (Grouped) --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-6 border-b border-slate-200 border-dashed">
                    <div class="pt-2">
                        <h3 class="text-sm font-bold text-rose-600 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Pengeluaran Stok
                        </h3>
                        <p class="text-sm text-slate-500 mt-1">Rincian buku yang keluar fisik dari cabang.</p>
                    </div>

                    <div class="w-full sm:w-72 space-y-3">
                        <div class="relative group">
                            <label
                                class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold uppercase text-slate-400 group-focus-within:text-rose-500 z-10 transition-colors">Digunakan</label>
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-rose-400 font-mono font-bold">&minus;</span>
                            </div>
                            <input type="number" wire:model.live="jumlahDigunakanTab" min="0"
                                {{ !$this->canEditTab ? 'disabled' : '' }}
                                class="relative w-full pl-8 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-lg font-mono text-right text-slate-800 transition-all focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 disabled:bg-slate-50 disabled:text-slate-400">
                            @error('jumlahDigunakanTab')
                                <p class="text-xs text-rose-500 mt-1 text-right">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative group">
                            <label
                                class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold uppercase text-slate-400 group-focus-within:text-rose-500 z-10 transition-colors">Rusak
                                / Salah Cetak</label>
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-rose-400 font-mono font-bold">&minus;</span>
                            </div>
                            <input type="number" wire:model.live="jmlDibatalkanRusakTab" min="0"
                                {{ !$this->canEditTab ? 'disabled' : '' }}
                                class="relative w-full pl-8 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-lg font-mono text-right text-slate-800 transition-all focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 disabled:bg-slate-50 disabled:text-slate-400">
                        </div>

                        <div class="relative group">
                            <label
                                class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold uppercase text-slate-400 group-focus-within:text-rose-500 z-10 transition-colors">Batal
                                / Hilang</label>
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-rose-400 font-mono font-bold">&minus;</span>
                            </div>
                            <input type="number" wire:model.live="jmlDibatalkanHilangTab" min="0"
                                {{ !$this->canEditTab ? 'disabled' : '' }}
                                class="relative w-full pl-8 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-lg font-mono text-right text-slate-800 transition-all focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 disabled:bg-slate-50 disabled:text-slate-400">
                        </div>
                    </div>
                </div>

                {{-- 4. Saldo Akhir (Result) --}}
                @php $saldoTab = (int)$this->saldoAkhirTab; @endphp
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl transition-colors duration-300 {{ $saldoTab < 0 ? 'bg-rose-50 border border-rose-200' : 'bg-slate-900 shadow-xl' }}">
                    <div>
                        <h3
                            class="text-sm font-bold uppercase tracking-wider flex items-center gap-2 {{ $saldoTab < 0 ? 'text-rose-700' : 'text-slate-400' }}">
                            Total Saldo Akhir Fisik
                        </h3>
                        @if ($saldoTab < 0)
                            <p class="text-sm font-medium text-rose-600 mt-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Saldo tidak valid (negatif).
                            </p>
                        @else
                            <p class="text-sm text-slate-400 mt-1">Dihitung otomatis oleh sistem.</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <span
                            class="text-4xl sm:text-5xl font-black font-mono tracking-tight {{ $saldoTab < 0 ? 'text-rose-700' : 'text-white' }}">
                            {{ number_format($saldoTab, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Footer / Actions --}}
            <div
                class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                @if ($this->canEditTab)
                    <p class="text-xs text-slate-500 font-medium">Pastikan fisik sesuai dengan sistem sebelum submit.
                    </p>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button type="button" wire:click="saveDraftTabungan" wire:loading.attr="disabled"
                            class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 focus:ring-4 focus:ring-slate-100 transition-all flex items-center justify-center gap-2">
                            <svg wire:loading.remove wire:target="saveDraftTabungan" class="w-4 h-4 text-slate-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <svg wire:loading wire:target="saveDraftTabungan"
                                class="w-4 h-4 animate-spin text-slate-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            <span>Simpan Draft</span>
                        </button>

                        <button type="button" wire:click="openSubmitModal('tabungan')" wire:loading.attr="disabled"
                            @if ($saldoTab < 0) disabled @endif
                            class="w-full sm:w-auto px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-200 focus:ring-4 focus:ring-indigo-100 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Submit Laporan</span>
                        </button>
                    </div>
                @else
                    <div class="flex items-center gap-3 text-slate-600 bg-slate-100 px-4 py-3 rounded-xl w-full">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <p class="text-sm">Laporan <strong
                                class="font-semibold text-slate-800">{{ \App\Enums\StatusVerifikasi::from($this->statusTab)->label() }}</strong>
                            dan dikunci. Menunggu tindakan Akunting.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ══════════════ DEPOSITO FORM ══════════════ --}}

        <div x-show="tab === 'deposito'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="bg-white rounded-3xl shadow-xl border-[--color-border-default] shadow-xl overflow-hidden border-t-4 border-t-indigo-600"
            style="display: none;">

            {{-- Header / Meta Info --}}
            <div
                class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-slate-900">Laporan Stok Deposito</h2>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ \App\Enums\StatusVerifikasi::from($this->statusDep)->badgeClass() }}">
                            {{ \App\Enums\StatusVerifikasi::from($this->statusDep)->label() }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 mt-1">Isi pergerakan stok bilyet deposito untuk periode ini.</p>
                </div>

                @if ($this->lastSavedDep)
                    <div class="flex flex-col sm:items-end">
                        <span class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-0.5">Terkahir
                            Disimpan Draft</span>
                        <span
                            class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-700 bg-slate-100/80 px-3 py-1.5 rounded-lg border border-slate-200">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $this->lastSavedDep }}
                        </span>
                    </div>
                @endif
            </div>

            @if ($this->statusDep === 'revision_requested' && $this->catatanRevisiDep)
                <div class="bg-amber-50 border-y border-amber-200 p-6 flex gap-4">
                    <div class="bg-amber-100 text-amber-600 p-2.5 rounded-full flex-shrink-0 h-fit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-800 uppercase tracking-wide">Catatan Revisi Akunting
                        </h4>
                        <p class="text-amber-700 mt-1 leading-relaxed">{{ $this->catatanRevisiDep }}</p>
                    </div>
                </div>
            @endif

            <div class="p-8 space-y-8">
                {{-- 1. Saldo Awal --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-slate-300"></span> Saldo Awal
                        </h3>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 px-6 py-3 rounded-xl w-full sm:w-64 text-right">
                        <span
                            class="text-2xl font-bold font-mono text-slate-800">{{ number_format((int) $this->saldoAwalDep, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- 2. Pemasukan --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-6 border-b border-slate-100">
                    <div class="pt-2">
                        <h3
                            class="text-sm font-bold text-emerald-600 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tambahan Stok
                        </h3>
                    </div>
                    <div class="w-full sm:w-64 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-emerald-500 font-mono font-bold">+</span>
                        </div>
                        <input type="number" wire:model.live="tambahanStokDep" min="0"
                            {{ !$this->canEditDep ? 'disabled' : '' }}
                            class="w-full pl-8 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-xl font-mono text-right text-slate-800 transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 disabled:bg-slate-50 disabled:text-slate-400">
                    </div>
                </div>

                {{-- 3. Pengeluaran --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-6 border-b border-slate-200 border-dashed">
                    <div class="pt-2">
                        <h3 class="text-sm font-bold text-rose-600 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Pengeluaran Stok
                        </h3>
                    </div>

                    <div class="w-full sm:w-72 space-y-3">
                        <div class="relative group">
                            <label
                                class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold uppercase text-slate-400 group-focus-within:text-rose-500 z-10">Digunakan</label>
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-rose-400 font-mono font-bold">&minus;</span>
                            </div>
                            <input type="number" wire:model.live="jumlahDigunakanDep" min="0"
                                {{ !$this->canEditDep ? 'disabled' : '' }}
                                class="relative w-full pl-8 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-lg font-mono text-right text-slate-800 transition-all focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 disabled:bg-slate-50 disabled:text-slate-400">
                        </div>

                        <div class="relative group">
                            <label
                                class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold uppercase text-slate-400 group-focus-within:text-rose-500 z-10">Rusak
                                / Salah Cetak</label>
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-rose-400 font-mono font-bold">&minus;</span>
                            </div>
                            <input type="number" wire:model.live="jmlDibatalkanRusakDep" min="0"
                                {{ !$this->canEditDep ? 'disabled' : '' }}
                                class="relative w-full pl-8 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-lg font-mono text-right text-slate-800 transition-all focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 disabled:bg-slate-50 disabled:text-slate-400">
                        </div>

                        <div class="relative group">
                            <label
                                class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold uppercase text-slate-400 group-focus-within:text-rose-500 z-10">Batal
                                / Hilang</label>
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-rose-400 font-mono font-bold">&minus;</span>
                            </div>
                            <input type="number" wire:model.live="jmlDibatalkanHilangDep" min="0"
                                {{ !$this->canEditDep ? 'disabled' : '' }}
                                class="relative w-full pl-8 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-lg font-mono text-right text-slate-800 transition-all focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 disabled:bg-slate-50 disabled:text-slate-400">
                        </div>
                    </div>
                </div>

                {{-- 4. Saldo Akhir --}}
                @php $saldoDep = (int)$this->saldoAkhirDep; @endphp
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl transition-colors duration-300 {{ $saldoDep < 0 ? 'bg-rose-50 border border-rose-200' : 'bg-slate-900 shadow-xl' }}">
                    <div>
                        <h3
                            class="text-sm font-bold uppercase tracking-wider flex items-center gap-2 {{ $saldoDep < 0 ? 'text-rose-700' : 'text-slate-400' }}">
                            Total Saldo Akhir Fisik
                        </h3>
                        @if ($saldoDep < 0)
                            <p class="text-sm font-medium text-rose-600 mt-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Saldo tidak valid (negatif).
                            </p>
                        @endif
                    </div>
                    <div class="text-right">
                        <span
                            class="text-4xl sm:text-5xl font-black font-mono tracking-tight {{ $saldoDep < 0 ? 'text-rose-700' : 'text-white' }}">
                            {{ number_format($saldoDep, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Footer / Actions --}}
            <div
                class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                @if ($this->canEditDep)
                    <p class="text-xs text-slate-500 font-medium">Pastikan fisik sesuai dengan sistem sebelum submit.
                    </p>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button type="button" wire:click="saveDraftDeposito" wire:loading.attr="disabled"
                            class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 focus:ring-4 focus:ring-slate-100 transition-all flex items-center justify-center gap-2">
                            <svg wire:loading.remove wire:target="saveDraftDeposito" class="w-4 h-4 text-slate-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <svg wire:loading wire:target="saveDraftDeposito"
                                class="w-4 h-4 animate-spin text-slate-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            <span>Simpan Draft</span>
                        </button>

                        <button type="button" wire:click="openSubmitModal('deposito')" wire:loading.attr="disabled"
                            @if ($saldoDep < 0) disabled @endif
                            class="w-full sm:w-auto px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-200 focus:ring-4 focus:ring-indigo-100 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Submit Laporan</span>
                        </button>
                    </div>
                @else
                    <div class="flex items-center gap-3 text-slate-600 bg-slate-100 px-4 py-3 rounded-xl w-full">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <p class="text-sm">Laporan <strong
                                class="font-semibold text-slate-800">{{ \App\Enums\StatusVerifikasi::from($this->statusDep)->label() }}</strong>
                            dan dikunci. Menunggu tindakan Akunting.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>{{-- end tabs --}}


    {{-- ═══════════ CUSTOM SUBMIT MODAL ═══════════ --}}
    @if ($this->showSubmitModal)
        @php
            $isTab = $this->pendingSubmitJenis === 'tabungan';
            $saldoAwal = $isTab ? (int) $this->saldoAwalTab : (int) $this->saldoAwalDep;
            $tambahan = $isTab ? (int) $this->tambahanStokTab : (int) $this->tambahanStokDep;
            $digunakan = $isTab ? (int) $this->jumlahDigunakanTab : (int) $this->jumlahDigunakanDep;
            $batalRusak = $isTab ? (int) $this->jmlDibatalkanRusakTab : (int) $this->jmlDibatalkanRusakDep;
            $batalHilang = $isTab ? (int) $this->jmlDibatalkanHilangTab : (int) $this->jmlDibatalkanHilangDep;
            $saldoFinal = $isTab ? (int) $this->saldoAkhirTab : (int) $this->saldoAkhirDep;
        @endphp

        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
            style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);" x-data
            x-on:keydown.escape.window="$wire.closeSubmitModal()"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-slate-50 rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden relative border border-white/50"
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-8" @click.stop>

                {{-- Modal Header / Receipt Top --}}
                <div class="px-8 pt-8 pb-6 bg-white border-b border-slate-200 border-dashed relative">
                    <div class="absolute -bottom-1.5 left-0 w-full h-3 overflow-hidden flex justify-around opacity-20">
                        @for ($i = 0; $i < 20; $i++)
                            <div class="w-2 h-2 bg-slate-200 rotate-45 transform translate-y-1"></div>
                        @endfor
                    </div>

                    <div class="text-center">
                        <div
                            class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Validasi Final</h3>
                        <p class="text-sm text-slate-500 mt-1">Laporan {{ $isTab ? 'Tabungan' : 'Deposito' }} -
                            {{ $this->periode->nama_periode }}</p>
                    </div>
                </div>

                {{-- Receipt Lines --}}
                <div class="px-8 py-6 bg-slate-50 relative">
                    <div class="space-y-3 font-mono text-sm">
                        @foreach ([['label' => 'Saldo Awal', 'val' => $saldoAwal, 'type' => 'neutral'], ['label' => 'Tambahan Stok', 'val' => $tambahan, 'type' => 'plus'], ['label' => 'Digunakan', 'val' => $digunakan, 'type' => 'minus'], ['label' => 'Batal Rusak', 'val' => $batalRusak, 'type' => 'minus'], ['label' => 'Batal Hilang', 'val' => $batalHilang, 'type' => 'minus']] as $row)
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">{{ str_pad($row['label'], 15, '.') }}</span>
                                <div class="flex items-center">
                                    @if ($row['type'] === 'plus' && $row['val'] > 0)
                                        <span class="text-emerald-500 mr-1">+</span>
                                    @elseif($row['type'] === 'minus' && $row['val'] > 0)
                                        <span class="text-rose-500 mr-1">-</span>
                                    @else
                                        <span class="text-transparent mr-1">+</span>{{-- Spacer --}}
                                    @endif
                                    <span
                                        class="font-semibold text-slate-800 w-16 text-right">{{ number_format($row['val'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 pt-4 border-t-2 border-slate-800 flex justify-between items-center">
                        <span class="font-bold text-slate-900 uppercase tracking-wider text-sm">Total Akhir</span>
                        <span
                            class="font-mono font-black text-xl text-slate-900">{{ number_format($saldoFinal, 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-6 flex gap-2 p-3 bg-amber-100/50 rounded-xl text-amber-800 text-xs">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <p>Setelah disubmit, laporan <strong class="font-bold">tidak dapat diubah</strong> kecuali ada
                            penolakan dari akunting.</p>
                    </div>
                </div>

                {{-- Modal Actions --}}
                <div class="px-6 py-5 bg-white border-t border-slate-200 flex flex-col sm:flex-row gap-3">
                    <button type="button" wire:click="closeSubmitModal"
                        class="w-full sm:w-1/3 px-4 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Kembali
                    </button>
                    <button type="button" wire:click="confirmSubmit" wire:loading.attr="disabled"
                        class="w-full sm:w-2/3 px-4 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="confirmSubmit" class="w-4 h-4 animate-spin" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        <span wire:loading.remove wire:target="confirmSubmit">Submit Final</span>
                        <span wire:loading wire:target="confirmSubmit">Memproses...</span>
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
