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

    {{-- ═══ BANNER SINKRONISASI ═══ --}}
    @if ($this->hasPencatatan && ($this->canEditTab || $this->canEditDep))
        <div class="mb-5 p-4 bg-indigo-50 border border-indigo-200 rounded-xl">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-indigo-800">Data pencatatan tersedia</p>
                        <div class="mt-1 space-y-0.5">
                            @foreach (['tabungan', 'deposito'] as $j)
                                @if (isset($syncRangeInfo[$j]) && $syncRangeInfo[$j]['count'] > 0)
                                    @php $s = $syncRangeInfo[$j]; @endphp
                                    <p class="text-xs text-indigo-600">
                                        <strong class="font-semibold">{{ ucfirst($j) }}:</strong>
                                        {{ $s['count'] }} transaksi
                                        ({{ $s['from_display'] }} – {{ $s['to_display'] }})
                                        &middot; +{{ number_format((int) $s['tambahan_stok'], 0, ',', '.') }} masuk
                                        &minus;{{ number_format((int) $s['digunakan'] + (int) $s['batal_rusak'] + (int) $s['batal_hilang'], 0, ',', '.') }}
                                        keluar
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <button type="button" wire:click="syncFromPencatatan" wire:loading.attr="disabled"
                    class="btn-primary text-xs px-4 py-2 flex-shrink-0">
                    <svg wire:loading.remove wire:target="syncFromPencatatan" class="w-3.5 h-3.5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <svg wire:loading wire:target="syncFromPencatatan" class="w-3.5 h-3.5 animate-spin" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <span wire:loading.remove wire:target="syncFromPencatatan">Sinkronkan</span>
                    <span wire:loading wire:target="syncFromPencatatan">Memproses...</span>
                </button>
            </div>
        </div>
    @elseif(!$this->hasPencatatan && ($this->canEditTab || $this->canEditDep))
        <div
            class="mb-5 p-4 bg-[--color-bg-subtle] border border-[--color-border-default] rounded-lg text-xs text-[--color-text-muted] flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Belum ada pencatatan untuk rentang periode ini. Isi form manual atau
                <a href="{{ route('pic.pencatatan.index') }}"
                    class="text-indigo-600 hover:underline font-medium">tambah pencatatan</a>
                terlebih dahulu.
            </span>
        </div>
    @endif

    {{-- ═══ TABS ═══ --}}
    <div x-data="{ tab: 'tabungan' }">

        {{-- Tab Nav --}}
        <div class="flex gap-1.5 mb-5 bg-[--color-bg-subtle] p-1 rounded-xl bg-indigo-50 border border-indigo-100">
            <button type="button" @click="tab = 'tabungan'"
                :class="tab === 'tabungan'
                    ?
                    'bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-xl overflow-hidden border-t-4 border-t-indigo-600 text-indigo-700 font-semibold shadow-sm' :
                    'bg-transparent border-transparent text-[--color-text-muted] hover:text-[--color-text-secondary]'"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border rounded-lg text-sm transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Tabungan
                <span
                    class="text-xs px-1.5 py-0.5 rounded-md font-medium border {{ \App\Enums\StatusVerifikasi::from($this->statusTab)->badgeClass() }}">
                    {{ \App\Enums\StatusVerifikasi::from($this->statusTab)->label() }}
                </span>
            </button>

            <button type="button" @click="tab = 'deposito'"
                :class="tab === 'deposito'
                    ?
                    'bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-xl overflow-hidden border-t-4 border-t-slate-600 text-indigo-700 font-semibold shadow-sm' :
                    'bg-transparent border-transparent text-[--color-text-muted] hover:text-[--color-text-secondary]'"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border rounded-lg text-sm transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Deposito
                <span
                    class="text-xs px-1.5 py-0.5 rounded-md font-medium border {{ \App\Enums\StatusVerifikasi::from($this->statusDep)->badgeClass() }}">
                    {{ \App\Enums\StatusVerifikasi::from($this->statusDep)->label() }}
                </span>
            </button>
        </div>

        {{-- ══════════════ TABUNGAN ══════════════ --}}
        <div x-show="tab === 'tabungan'" x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

            @if ($this->statusTab === 'revision_requested' && $this->catatanRevisiTab)
                <div class="mb-4 p-4 bg-orange-50 border-l-4 border-orange-400 rounded-r-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-orange-500 flex-shrink-0 mt-0.5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-xs font-semibold text-orange-800 mb-0.5">Catatan Revisi dari Akunting:</p>
                            <p class="text-sm text-orange-700">{{ $this->catatanRevisiTab }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div
                class="bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-xl overflow-hidden border-t-4 border-t-indigo-600">

                <div
                    class="px-6 py-5 border-b border-[--color-border-default] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-[--color-text-primary]">Pencatatan Stok Buku Tabungan
                            </h2>
                            <p class="text-sm text-[--color-text-muted] mt-0.5">Catat setiap perubahan stok secara
                                berkala</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div x-data="{ help: false }" class="relative flex-shrink-0">
                            <button @mouseenter="help = true" @mouseleave="help = false" type="button"
                                class="w-7 h-7 rounded-lg flex items-center justify-center text-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                                aria-label="Bantuan pencatatan">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="help" @mouseenter="help = true" @mouseleave="help = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="absolute z-50 w-72 p-3 bg-white rounded-xl shadow-xl border border-[--color-border-default] text-xs"
                                style="top: calc(100% + 8px); right: 0;">
                                <div
                                    class="absolute -top-1 right-3 w-2 h-2 bg-white border-l border-t border-[--color-border-default] rotate-45">
                                </div>
                                <p class="font-semibold text-[--color-text-primary] mb-1.5">Cara Pencatatan</p>
                                <ul class="space-y-1.5 text-[--color-text-secondary]">
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-indigo-400 mt-0.5 flex-shrink-0">•</span>
                                        <span>Perbarui angka setiap ada perubahan stok</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-indigo-400 mt-0.5 flex-shrink-0">•</span>
                                        <span><strong>Simpan Catatan</strong> untuk menyimpan draft — bisa kapan
                                            saja</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-indigo-400 mt-0.5 flex-shrink-0">•</span>
                                        <span><strong>Submit Laporan</strong> hanya saat pelaporan wajib</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-indigo-400 mt-0.5 flex-shrink-0">•</span>
                                        <span>Saldo akhir otomatis — pastikan tidak negatif</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @if ($this->lastSavedTab)
                            <div
                                class="flex items-center gap-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $this->lastSavedTab }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-5 space-y-5">

                    {{-- Saldo Awal --}}
                    <div
                        class="flex items-center justify-between p-4 bg-[--color-bg-subtle] rounded-lg border border-[--color-border-default]">
                        <div>
                            <p class="text-xs font-semibold text-[--color-text-primary] uppercase tracking-wider">Saldo
                                Awal</p>
                            <p class="text-xs text-[--color-text-secondary] mt-0.5">Dari periode sebelumnya, tidak
                                dapat
                                diubah</p>
                        </div>
                        <p class="text-2xl font-bold text-[--color-text-primary] font-mono">
                            {{ number_format((int) $this->saldoAwalTab, 0, ',', '.') }}</p>
                    </div>

                    {{-- Input Grid --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div
                            class="space-y-1.5 p-3 rounded-lg border-l-[3px] border-emerald-400 bg-emerald-50/30 transition-all duration-150">
                            <label
                                class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                Tambahan Stok
                            </label>
                            <p class="text-xs text-emerald-600/70">Buku baru yang diterima cabang</p>
                            <input type="number" wire:model.live="tambahanStokTab" min="0"
                                {{ !$this->canEditTab ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-[--color-border-default] rounded-lg text-sm font-mono text-right text-[--color-text-primary] bg-white
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                       disabled:bg-[--color-bg-inset] disabled:text-[--color-text-muted] disabled:cursor-not-allowed
                                       transition-all duration-150">
                            @error('tambahanStokTab')
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

                        <div
                            class="space-y-1.5 p-3 rounded-lg border-l-[3px] border-amber-400 bg-amber-50/30 transition-all duration-150">
                            <label
                                class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                                Jumlah Digunakan
                            </label>
                            <p class="text-xs text-amber-600/70">Buku yang sudah diberikan ke nasabah</p>
                            <input type="number" wire:model.live="jumlahDigunakanTab" min="0"
                                {{ !$this->canEditTab ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-[--color-border-default] rounded-lg text-sm font-mono text-right text-[--color-text-primary] bg-white
                                       focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                       disabled:bg-[--color-bg-inset] disabled:text-[--color-text-muted] disabled:cursor-not-allowed
                                       transition-all duration-150">
                            @error('jumlahDigunakanTab')
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

                        <div
                            class="space-y-1.5 p-3 rounded-lg border-l-[3px] border-red-400 bg-red-50/30 transition-all duration-150">
                            <label
                                class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>
                                Batal &mdash; Rusak/Salah Cetak
                            </label>
                            <p class="text-xs text-red-600/70">Buku yang dibatalkan karena rusak</p>
                            <input type="number" wire:model.live="jmlDibatalkanRusakTab" min="0"
                                {{ !$this->canEditTab ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-[--color-border-default] rounded-lg text-sm font-mono text-right text-[--color-text-primary] bg-white
                                       focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                                       disabled:bg-[--color-bg-inset] disabled:text-[--color-text-muted] disabled:cursor-not-allowed
                                       transition-all duration-150">
                        </div>

                        <div
                            class="space-y-1.5 p-3 rounded-lg border-l-[3px] border-red-400 bg-red-50/30 transition-all duration-150">
                            <label
                                class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>
                                Batal &mdash; Hilang
                            </label>
                            <p class="text-xs text-red-600/70">Buku yang hilang/tidak dapat ditemukan</p>
                            <input type="number" wire:model.live="jmlDibatalkanHilangTab" min="0"
                                {{ !$this->canEditTab ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-[--color-border-default] rounded-lg text-sm font-mono text-right text-[--color-text-primary] bg-white
                                       focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                                       disabled:bg-[--color-bg-inset] disabled:text-[--color-text-muted] disabled:cursor-not-allowed
                                       transition-all duration-150">
                        </div>
                    </div>

                    {{-- Saldo Akhir --}}
                    @php $saldoTab = (int)$this->saldoAkhirTab; @endphp
                    <div
                        class="relative p-5 rounded-xl border-2 overflow-hidden shadow-lg {{ $saldoTab < 0 ? 'bg-red-50 border-red-300 shadow-red-200/30' : 'bg-emerald-50 border-emerald-300 shadow-emerald-200/30' }}">
                        <div
                            class="absolute top-0 left-0 right-0 h-1 {{ $saldoTab < 0 ? 'bg-gradient-to-r from-red-400 to-red-500' : 'bg-gradient-to-r from-emerald-400 to-emerald-500' }}">
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $saldoTab < 0 ? 'bg-red-100' : 'bg-emerald-100' }}">
                                    <svg class="w-5 h-5 {{ $saldoTab < 0 ? 'text-red-600' : 'text-emerald-600' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-semibold uppercase tracking-wider {{ $saldoTab < 0 ? 'text-red-600' : 'text-emerald-700' }}">
                                        Saldo Akhir
                                    </p>
                                    <p
                                        class="text-xs mt-0.5 {{ $saldoTab < 0 ? 'text-red-500' : 'text-emerald-500' }}">
                                        Dihitung otomatis dari data di atas
                                    </p>
                                </div>
                            </div>
                            <p
                                class="text-4xl font-bold font-mono tracking-tight {{ $saldoTab < 0 ? 'text-red-700' : 'text-emerald-800' }}">
                                {{ number_format($saldoTab, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t {{ $saldoTab < 0 ? 'border-red-200' : 'border-emerald-200' }}">
                            <div class="flex items-center justify-between">
                                <p
                                    class="text-xs font-mono {{ $saldoTab < 0 ? 'text-red-500' : 'text-emerald-600' }}">
                                    {{ number_format((int) $this->saldoAwalTab, 0, ',', '.') }}
                                    + {{ number_format((int) $this->tambahanStokTab, 0, ',', '.') }}
                                    &minus; {{ number_format((int) $this->jumlahDigunakanTab, 0, ',', '.') }}
                                    &minus; {{ number_format((int) $this->jmlDibatalkanRusakTab, 0, ',', '.') }}
                                    &minus; {{ number_format((int) $this->jmlDibatalkanHilangTab, 0, ',', '.') }}
                                </p>
                                <span
                                    class="text-sm font-bold font-mono {{ $saldoTab < 0 ? 'text-red-700' : 'text-emerald-700' }}">
                                    = {{ number_format($saldoTab, 0, ',', '.') }}
                                </span>
                            </div>
                            @if ($saldoTab < 0)
                                <div
                                    class="mt-3 flex items-center gap-1.5 text-xs text-red-700 font-semibold bg-red-100/50 p-2 rounded-lg">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Saldo tidak boleh negatif. Periksa kembali data yang diinput.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Buttons --}}
                    @if ($this->canEditTab)
                        <div class="flex items-center gap-3 pt-1">
                            <button type="button" wire:click="saveDraftTabungan" wire:loading.attr="disabled"
                                class="btn-secondary">
                                <svg wire:loading.remove wire:target="saveDraftTabungan" class="w-4 h-4"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                <svg wire:loading wire:target="saveDraftTabungan" class="w-4 h-4 animate-spin"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                <span wire:loading.remove wire:target="saveDraftTabungan">Simpan Catatan</span>
                                <span wire:loading wire:target="saveDraftTabungan">Menyimpan...</span>
                            </button>

                            <button type="button" wire:click="openSubmitModal('tabungan')"
                                wire:loading.attr="disabled" @if ($saldoTab < 0) disabled @endif
                                class="btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Submit Laporan
                            </button>
                        </div>
                        <p class="text-xs text-[--color-text-muted]">Simpan catatan kapan saja. Submit hanya saat
                            pelaporan wajib.</p>
                    @else
                        <div
                            class="p-3 bg-[--color-bg-subtle] border border-[--color-border-default] rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4 text-[--color-text-muted] flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-xs text-[--color-text-muted]">
                                Status:
                                <strong>{{ \App\Enums\StatusVerifikasi::from($this->statusTab)->label() }}</strong>
                                &mdash; Laporan tidak dapat diedit saat ini.
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ══════════════ DEPOSITO ══════════════ --}}
        <div x-show="tab === 'deposito'" x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

            @if ($this->statusDep === 'revision_requested' && $this->catatanRevisiDep)
                <div class="mb-4 p-4 bg-orange-50 border-l-4 border-orange-400 rounded-r-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-orange-500 flex-shrink-0 mt-0.5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-xs font-semibold text-orange-800 mb-0.5">Catatan Revisi dari Akunting:</p>
                            <p class="text-sm text-orange-700">{{ $this->catatanRevisiDep }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div
                class="bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-xl overflow-hidden border-t-4 border-t-slate-600">

                <div
                    class="px-6 py-5 border-b border-[--color-border-default] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-11 h-11 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-[--color-text-primary]">Pencatatan Stok Buku Deposito
                            </h2>
                            <p class="text-sm text-[--color-text-muted] mt-0.5">Catat setiap perubahan stok secara
                                berkala</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div x-data="{ help: false }" class="relative flex-shrink-0">
                            <button @mouseenter="help = true" @mouseleave="help = false" type="button"
                                class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all"
                                aria-label="Bantuan pencatatan">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="help" @mouseenter="help = true" @mouseleave="help = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="absolute z-50 w-72 p-3 bg-white rounded-xl shadow-xl border border-[--color-border-default] text-xs"
                                style="top: calc(100% + 8px); right: 0;">
                                <div
                                    class="absolute -top-1 right-3 w-2 h-2 bg-white border-l border-t border-[--color-border-default] rotate-45">
                                </div>
                                <p class="font-semibold text-[--color-text-primary] mb-1.5">Cara Pencatatan</p>
                                <ul class="space-y-1.5 text-[--color-text-secondary]">
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-slate-400 mt-0.5 flex-shrink-0">•</span>
                                        <span>Perbarui angka setiap ada perubahan stok</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-slate-400 mt-0.5 flex-shrink-0">•</span>
                                        <span><strong>Simpan Catatan</strong> untuk menyimpan draft — bisa kapan
                                            saja</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-slate-400 mt-0.5 flex-shrink-0">•</span>
                                        <span><strong>Submit Laporan</strong> hanya saat pelaporan wajib</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-slate-400 mt-0.5 flex-shrink-0">•</span>
                                        <span>Saldo akhir otomatis — pastikan tidak negatif</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @if ($this->lastSavedDep)
                            <div
                                class="flex items-center gap-1.5 text-xs font-medium text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $this->lastSavedDep }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-5 space-y-5">

                    {{-- Saldo Awal --}}
                    <div
                        class="flex items-center justify-between p-4 bg-[--color-bg-subtle] rounded-lg border border-[--color-border-default]">
                        <div>
                            <p class="text-xs font-semibold text-[--color-text-primary] uppercase tracking-wider">Saldo
                                Awal</p>
                            <p class="text-xs text-[--color-text-secondary] mt-0.5">Dari periode sebelumnya, tidak
                                dapat
                                diubah</p>
                        </div>
                        <p class="text-2xl font-bold text-[--color-text-primary] font-mono">
                            {{ number_format((int) $this->saldoAwalDep, 0, ',', '.') }}</p>
                    </div>

                    {{-- Input Grid --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div
                            class="space-y-1.5 p-3 rounded-lg border-l-[3px] border-emerald-400 bg-emerald-50/30 transition-all duration-150">
                            <label
                                class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                Tambahan Stok
                            </label>
                            <p class="text-xs text-emerald-600/70">Buku baru yang diterima cabang</p>
                            <input type="number" wire:model.live="tambahanStokDep" min="0"
                                {{ !$this->canEditDep ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-[--color-border-default] rounded-lg text-sm font-mono text-right text-[--color-text-primary] bg-white
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                       disabled:bg-[--color-bg-inset] disabled:text-[--color-text-muted] disabled:cursor-not-allowed
                                       transition-all duration-150">
                        </div>

                        <div
                            class="space-y-1.5 p-3 rounded-lg border-l-[3px] border-amber-400 bg-amber-50/30 transition-all duration-150">
                            <label
                                class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                                Jumlah Digunakan
                            </label>
                            <p class="text-xs text-amber-600/70">Buku yang sudah diberikan ke nasabah</p>
                            <input type="number" wire:model.live="jumlahDigunakanDep" min="0"
                                {{ !$this->canEditDep ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-[--color-border-default] rounded-lg text-sm font-mono text-right text-[--color-text-primary] bg-white
                                       focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                       disabled:bg-[--color-bg-inset] disabled:text-[--color-text-muted] disabled:cursor-not-allowed
                                       transition-all duration-150">
                        </div>

                        <div
                            class="space-y-1.5 p-3 rounded-lg border-l-[3px] border-red-400 bg-red-50/30 transition-all duration-150">
                            <label
                                class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>
                                Batal &mdash; Rusak/Salah Cetak
                            </label>
                            <p class="text-xs text-red-600/70">Buku yang dibatalkan karena rusak</p>
                            <input type="number" wire:model.live="jmlDibatalkanRusakDep" min="0"
                                {{ !$this->canEditDep ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-[--color-border-default] rounded-lg text-sm font-mono text-right text-[--color-text-primary] bg-white
                                       focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                                       disabled:bg-[--color-bg-inset] disabled:text-[--color-text-muted] disabled:cursor-not-allowed
                                       transition-all duration-150">
                        </div>

                        <div
                            class="space-y-1.5 p-3 rounded-lg border-l-[3px] border-red-400 bg-red-50/30 transition-all duration-150">
                            <label
                                class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>
                                Batal &mdash; Hilang
                            </label>
                            <p class="text-xs text-red-600/70">Buku yang hilang/tidak dapat ditemukan</p>
                            <input type="number" wire:model.live="jmlDibatalkanHilangDep" min="0"
                                {{ !$this->canEditDep ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-[--color-border-default] rounded-lg text-sm font-mono text-right text-[--color-text-primary] bg-white
                                       focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                                       disabled:bg-[--color-bg-inset] disabled:text-[--color-text-muted] disabled:cursor-not-allowed
                                       transition-all duration-150">
                        </div>
                    </div>

                    {{-- Saldo Akhir --}}
                    @php $saldoDep = (int)$this->saldoAkhirDep; @endphp
                    <div
                        class="relative p-5 rounded-xl border-2 overflow-hidden shadow-lg {{ $saldoDep < 0 ? 'bg-red-50 border-red-300 shadow-red-200/30' : 'bg-emerald-50 border-emerald-300 shadow-emerald-200/30' }}">
                        <div
                            class="absolute top-0 left-0 right-0 h-1 {{ $saldoDep < 0 ? 'bg-gradient-to-r from-red-400 to-red-500' : 'bg-gradient-to-r from-emerald-400 to-emerald-500' }}">
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $saldoDep < 0 ? 'bg-red-100' : 'bg-emerald-100' }}">
                                    <svg class="w-5 h-5 {{ $saldoDep < 0 ? 'text-red-600' : 'text-emerald-600' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-semibold uppercase tracking-wider {{ $saldoDep < 0 ? 'text-red-600' : 'text-emerald-700' }}">
                                        Saldo Akhir
                                    </p>
                                    <p
                                        class="text-xs mt-0.5 {{ $saldoDep < 0 ? 'text-red-500' : 'text-emerald-500' }}">
                                        Dihitung otomatis dari data di atas
                                    </p>
                                </div>
                            </div>
                            <p
                                class="text-4xl font-bold font-mono tracking-tight {{ $saldoDep < 0 ? 'text-red-700' : 'text-emerald-800' }}">
                                {{ number_format($saldoDep, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t {{ $saldoDep < 0 ? 'border-red-200' : 'border-emerald-200' }}">
                            <div class="flex items-center justify-between">
                                <p
                                    class="text-xs font-mono {{ $saldoDep < 0 ? 'text-red-500' : 'text-emerald-600' }}">
                                    {{ number_format((int) $this->saldoAwalDep, 0, ',', '.') }}
                                    + {{ number_format((int) $this->tambahanStokDep, 0, ',', '.') }}
                                    &minus; {{ number_format((int) $this->jumlahDigunakanDep, 0, ',', '.') }}
                                    &minus; {{ number_format((int) $this->jmlDibatalkanRusakDep, 0, ',', '.') }}
                                    &minus; {{ number_format((int) $this->jmlDibatalkanHilangDep, 0, ',', '.') }}
                                </p>
                                <span
                                    class="text-sm font-bold font-mono {{ $saldoDep < 0 ? 'text-red-700' : 'text-emerald-700' }}">
                                    = {{ number_format($saldoDep, 0, ',', '.') }}
                                </span>
                            </div>
                            @if ($saldoDep < 0)
                                <div
                                    class="mt-3 flex items-center gap-1.5 text-xs text-red-700 font-semibold bg-red-100/50 p-2 rounded-lg">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Saldo tidak boleh negatif. Periksa kembali data yang diinput.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Buttons --}}
                    @if ($this->canEditDep)
                        <div class="flex items-center gap-3 pt-1">
                            <button type="button" wire:click="saveDraftDeposito" wire:loading.attr="disabled"
                                class="btn-secondary">
                                <svg wire:loading.remove wire:target="saveDraftDeposito" class="w-4 h-4"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                <svg wire:loading wire:target="saveDraftDeposito" class="w-4 h-4 animate-spin"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                <span wire:loading.remove wire:target="saveDraftDeposito">Simpan Catatan</span>
                                <span wire:loading wire:target="saveDraftDeposito">Menyimpan...</span>
                            </button>

                            <button type="button" wire:click="openSubmitModal('deposito')"
                                wire:loading.attr="disabled" @if ($saldoDep < 0) disabled @endif
                                class="btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Submit Laporan
                            </button>
                        </div>
                        <p class="text-xs text-[--color-text-muted]">Simpan catatan kapan saja. Submit hanya saat
                            pelaporan wajib.</p>
                    @else
                        <div
                            class="p-3 bg-[--color-bg-subtle] border border-[--color-border-default] rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4 text-[--color-text-muted] flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-xs text-[--color-text-muted]">
                                Status:
                                <strong>{{ \App\Enums\StatusVerifikasi::from($this->statusDep)->label() }}</strong>
                                &mdash; Laporan tidak dapat diedit saat ini.
                            </p>
                        </div>
                    @endif

                </div>
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

        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(2px);" x-data
            x-on:keydown.escape.window="$wire.closeSubmitModal()"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2" @click.stop>

                {{-- Modal Header --}}
                <div class="px-6 py-5 border-b border-[--color-border-default]">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-[--color-text-primary]">
                                Konfirmasi Submit {{ $isTab ? 'Tabungan' : 'Deposito' }}
                            </h3>
                            <p class="text-xs text-[--color-text-muted] mt-0.5">{{ $this->periode->nama_periode }}</p>
                        </div>
                        <button type="button" wire:click="closeSubmitModal"
                            class="text-[--color-text-muted] hover:text-[--color-text-secondary] hover:bg-[--color-bg-subtle] rounded-lg p-1 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Ringkasan --}}
                <div class="px-6 py-5">
                    <p class="text-xs font-semibold text-[--color-text-muted] uppercase tracking-wider mb-3">
                        Ringkasan Data
                    </p>

                    <div
                        class="bg-[--color-bg-subtle] rounded-xl overflow-hidden border border-[--color-border-default]">
                        @foreach ([['label' => 'Saldo Awal', 'val' => $saldoAwal, 'type' => 'neutral'], ['label' => 'Tambahan Stok', 'val' => $tambahan, 'type' => 'plus'], ['label' => 'Jumlah Digunakan', 'val' => $digunakan, 'type' => 'minus'], ['label' => 'Batal (rusak/salah)', 'val' => $batalRusak, 'type' => 'minus'], ['label' => 'Batal (hilang)', 'val' => $batalHilang, 'type' => 'minus']] as $row)
                            <div
                                class="flex items-center justify-between px-4 py-2.5 border-b border-[--color-border-default] last:border-0">
                                <span class="text-sm text-[--color-text-secondary]">{{ $row['label'] }}</span>
                                <div class="flex items-center gap-1.5">
                                    @if ($row['type'] === 'plus' && $row['val'] > 0)
                                        <span class="text-xs text-emerald-600 font-mono">+</span>
                                    @elseif($row['type'] === 'minus' && $row['val'] > 0)
                                        <span class="text-xs text-red-500 font-mono">&minus;</span>
                                    @endif
                                    <span class="font-mono font-semibold text-sm text-[--color-text-primary]">
                                        {{ number_format($row['val'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        <div
                            class="flex items-center justify-between px-4 py-3 {{ $saldoFinal >= 0 ? 'bg-emerald-50' : 'bg-red-50' }}">
                            <span
                                class="text-sm font-bold {{ $saldoFinal >= 0 ? 'text-emerald-800' : 'text-red-800' }}">
                                Saldo Akhir
                            </span>
                            <span
                                class="font-mono font-bold text-lg {{ $saldoFinal >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                                {{ number_format($saldoFinal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-xs text-amber-700">
                            Pastikan semua data sudah benar. Setelah disubmit, laporan
                            <strong class="font-semibold">tidak dapat diubah</strong> kecuali akunting meminta revisi.
                        </p>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div
                    class="px-6 py-4 bg-[--color-bg-subtle] border-t border-[--color-border-default] flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeSubmitModal" class="btn-secondary">
                        Batal, Cek Lagi
                    </button>
                    <button type="button" wire:click="confirmSubmit" wire:loading.attr="disabled"
                        class="btn-primary">
                        <svg wire:loading.remove wire:target="confirmSubmit" class="w-4 h-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg wire:loading wire:target="confirmSubmit" class="w-4 h-4 animate-spin" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        <span wire:loading.remove wire:target="confirmSubmit">Ya, Submit Laporan</span>
                        <span wire:loading wire:target="confirmSubmit">Memproses...</span>
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
