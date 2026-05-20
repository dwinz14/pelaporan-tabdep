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

    {{-- ═══ BANNER SINKRONISASI DARI PENCATATAN ═══ --}}
    @if ($this->hasPencatatan && ($this->canEditTab || $this->canEditDep))
        <div class="mb-4 p-4 bg-indigo-50 border border-indigo-200 rounded-xl flex items-start justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-indigo-800">Ada data pencatatan tersedia</p>
                    <p class="text-xs text-indigo-600 mt-0.5">
                        @php
                            $ag = $this->pencatatanAgregat;
                            $tabCount = $ag['tabungan']['count'] ?? 0;
                            $depCount = $ag['deposito']['count'] ?? 0;
                        @endphp
                        {{ $tabCount }} transaksi tabungan · {{ $depCount }} transaksi deposito.
                        Sinkronkan untuk mengisi form otomatis dari data pencatatan.
                    </p>
                </div>
            </div>
            <button type="button" wire:click="syncFromPencatatan" wire:loading.attr="disabled"
                class="flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-xs font-semibold
                   rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-60 flex-shrink-0">
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
                <span wire:loading.remove wire:target="syncFromPencatatan">🔄 Sinkronkan</span>
                <span wire:loading wire:target="syncFromPencatatan">Memproses...</span>
            </button>
        </div>
    @elseif(!$this->hasPencatatan && ($this->canEditTab || $this->canEditDep))
        <div class="mb-4 p-3 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-between gap-3">
            <p class="text-xs text-gray-500">
                Belum ada pencatatan untuk periode ini. Anda bisa mengisi form secara manual atau
                <a href="{{ route('pic.pencatatan.index') }}" class="text-indigo-600 hover:underline font-medium">
                    tambah pencatatan
                </a> terlebih dahulu.
            </p>
        </div>
    @endif

    {{-- ═══ TABS ═══ --}}
    <div x-data="{ tab: 'tabungan' }">

        {{-- Tab Nav --}}
        <div class="flex gap-1 mb-4">
            <button type="button" @click="tab = 'tabungan'"
                :class="tab === 'tabungan'
                    ?
                    'bg-white border-gray-200 text-indigo-700 font-semibold shadow-sm' :
                    'bg-gray-100 border-transparent text-gray-500 hover:text-gray-700'"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border rounded-lg text-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Tabungan
                <span
                    class="text-xs px-1.5 py-0.5 rounded-full font-medium
                             {{ \App\Enums\StatusVerifikasi::from($statusTab)->badgeClass() }}">
                    {{ \App\Enums\StatusVerifikasi::from($statusTab)->label() }}
                </span>
            </button>

            <button type="button" @click="tab = 'deposito'"
                :class="tab === 'deposito'
                    ?
                    'bg-white border-gray-200 text-indigo-700 font-semibold shadow-sm' :
                    'bg-gray-100 border-transparent text-gray-500 hover:text-gray-700'"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border rounded-lg text-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Deposito
                <span
                    class="text-xs px-1.5 py-0.5 rounded-full font-medium
                             {{ \App\Enums\StatusVerifikasi::from($statusDep)->badgeClass() }}">
                    {{ \App\Enums\StatusVerifikasi::from($statusDep)->label() }}
                </span>
            </button>
        </div>

        {{-- ══════════════ TAB TABUNGAN ══════════════ --}}
        <div x-show="tab === 'tabungan'" x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

            {{-- Catatan Revisi --}}
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

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

                {{-- Header --}}
                <div class="px-5 py-3 bg-indigo-50 border-b border-indigo-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-indigo-800">Pencatatan Stok Buku Tabungan</p>
                        <p class="text-xs text-indigo-500 mt-0.5">Catat setiap perubahan stok secara berkala</p>
                    </div>
                    @if ($this->lastSavedTab)
                        <div class="flex items-center gap-1.5 text-xs text-indigo-500">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tersimpan: {{ $this->lastSavedTab }}
                        </div>
                    @endif
                </div>

                <div class="p-5 space-y-5">

                    {{-- Saldo Awal --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Saldo Awal</p>
                            <p class="text-xs text-gray-400 mt-0.5">Dari periode sebelumnya, tidak dapat diubah</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-900 font-mono">
                            {{ number_format($this->saldoAwalTab) }}
                        </p>
                    </div>

                    {{-- Input Grid --}}
                    <div class="grid grid-cols-2 gap-4">

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                Tambahan Stok
                            </label>
                            <p class="text-xs text-gray-400">Buku baru yang diterima cabang</p>
                            <input type="number" wire:model.live="tambahanStokTab" min="0"
                                {{ !$this->canEditTab ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                            @error('tambahanStokTab')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                Jumlah Digunakan
                            </label>
                            <p class="text-xs text-gray-400">Buku yang sudah diberikan ke nasabah</p>
                            <input type="number" wire:model.live="jumlahDigunakanTab" min="0"
                                {{ !$this->canEditTab ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                            @error('jumlahDigunakanTab')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                Batal — Rusak/Salah Cetak
                            </label>
                            <p class="text-xs text-gray-400">Buku yang dibatalkan karena rusak</p>
                            <input type="number" wire:model.live="jmlDibatalkanRusakTab" min="0"
                                {{ !$this->canEditTab ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                Batal — Hilang
                            </label>
                            <p class="text-xs text-gray-400">Buku yang hilang/tidak dapat ditemukan</p>
                            <input type="number" wire:model.live="jmlDibatalkanHilangTab" min="0"
                                {{ !$this->canEditTab ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                        </div>
                    </div>

                    {{-- Saldo Akhir --}}
                    <div
                        class="p-4 rounded-xl border-2
                                {{ $this->saldoAkhirTab < 0 ? 'bg-red-50 border-red-300' : 'bg-emerald-50 border-emerald-300' }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-semibold uppercase tracking-wide
                                          {{ $this->saldoAkhirTab < 0 ? 'text-red-600' : 'text-emerald-700' }}">
                                    Saldo Akhir
                                </p>
                                <p
                                    class="text-xs {{ $this->saldoAkhirTab < 0 ? 'text-red-500' : 'text-emerald-500' }} mt-0.5">
                                    Dihitung otomatis dari data di atas
                                </p>
                            </div>
                            <p
                                class="text-3xl font-bold font-mono
                                      {{ $this->saldoAkhirTab < 0 ? 'text-red-700' : 'text-emerald-800' }}">
                                {{ number_format($this->saldoAkhirTab) }}
                            </p>
                        </div>

                        {{-- Formula --}}
                        <div
                            class="mt-3 pt-3 border-t {{ $this->saldoAkhirTab < 0 ? 'border-red-200' : 'border-emerald-200' }}">
                            <p
                                class="text-xs {{ $this->saldoAkhirTab < 0 ? 'text-red-500' : 'text-emerald-600' }} font-mono">
                                {{ number_format($this->saldoAwalTab) }}
                                + {{ number_format($this->tambahanStokTab) }}
                                − {{ number_format($this->jumlahDigunakanTab) }}
                                − {{ number_format($this->jmlDibatalkanRusakTab) }}
                                − {{ number_format($this->jmlDibatalkanHilangTab) }}
                                =
                                <strong>{{ number_format($this->saldoAkhirTab) }}</strong>
                            </p>
                            @if ($this->saldoAkhirTab < 0)
                                <p class="text-xs text-red-600 font-semibold mt-1">
                                    ⚠ Saldo tidak boleh negatif. Periksa kembali data yang diinput.
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    @if ($this->canEditTab)
                        <div class="flex items-center gap-3 pt-1">

                            {{-- Simpan Catatan --}}
                            <button type="button" wire:click="saveDraftTabungan" wire:loading.attr="disabled"
                                class="flex items-center gap-2 px-5 py-2.5 border-2 border-indigo-300
                                       text-indigo-700 text-sm font-semibold rounded-lg
                                       hover:bg-indigo-50 transition-colors disabled:opacity-60">
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

                            {{-- Submit Laporan --}}
                            <button type="button" wire:click="openSubmitModal('tabungan')"
                                wire:loading.attr="disabled" @if ($this->saldoAkhirTab < 0) disabled @endif
                                class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white
                                       text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Submit Laporan
                            </button>

                        </div>
                        <p class="text-xs text-gray-400">
                            Simpan catatan kapan saja. Submit hanya dilakukan saat pelaporan wajib.
                        </p>
                    @else
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-xs text-gray-500">
                                Status: <strong>{{ \App\Enums\StatusVerifikasi::from($statusTab)->label() }}</strong>
                                — Laporan tidak dapat diedit saat ini.
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ══════════════ TAB DEPOSITO ══════════════ --}}
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

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

                {{-- Header --}}
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-700">Pencatatan Stok Buku Deposito</p>
                        <p class="text-xs text-slate-400 mt-0.5">Catat setiap perubahan stok secara berkala</p>
                    </div>
                    @if ($this->lastSavedDep)
                        <div class="flex items-center gap-1.5 text-xs text-slate-400">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tersimpan: {{ $this->lastSavedDep }}
                        </div>
                    @endif
                </div>

                <div class="p-5 space-y-5">

                    {{-- Saldo Awal --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Saldo Awal</p>
                            <p class="text-xs text-gray-400 mt-0.5">Dari periode sebelumnya, tidak dapat diubah</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-900 font-mono">
                            {{ number_format($this->saldoAwalDep) }}
                        </p>
                    </div>

                    {{-- Input Grid --}}
                    <div class="grid grid-cols-2 gap-4">

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Tambahan
                                Stok</label>
                            <p class="text-xs text-gray-400">Buku baru yang diterima cabang</p>
                            <input type="number" wire:model.live="tambahanStokDep" min="0"
                                {{ !$this->canEditDep ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Jumlah
                                Digunakan</label>
                            <p class="text-xs text-gray-400">Buku yang sudah diberikan ke nasabah</p>
                            <input type="number" wire:model.live="jumlahDigunakanDep" min="0"
                                {{ !$this->canEditDep ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Batal —
                                Rusak/Salah Cetak</label>
                            <p class="text-xs text-gray-400">Buku yang dibatalkan karena rusak</p>
                            <input type="number" wire:model.live="jmlDibatalkanRusakDep" min="0"
                                {{ !$this->canEditDep ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Batal —
                                Hilang</label>
                            <p class="text-xs text-gray-400">Buku yang hilang/tidak dapat ditemukan</p>
                            <input type="number" wire:model.live="jmlDibatalkanHilangDep" min="0"
                                {{ !$this->canEditDep ? 'disabled' : '' }}
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                        </div>
                    </div>

                    {{-- Saldo Akhir --}}
                    <div
                        class="p-4 rounded-xl border-2
                                {{ $this->saldoAkhirDep < 0 ? 'bg-red-50 border-red-300' : 'bg-emerald-50 border-emerald-300' }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-semibold uppercase tracking-wide
                                          {{ $this->saldoAkhirDep < 0 ? 'text-red-600' : 'text-emerald-700' }}">
                                    Saldo Akhir
                                </p>
                                <p
                                    class="text-xs {{ $this->saldoAkhirDep < 0 ? 'text-red-500' : 'text-emerald-500' }} mt-0.5">
                                    Dihitung otomatis dari data di atas
                                </p>
                            </div>
                            <p
                                class="text-3xl font-bold font-mono
                                      {{ $this->saldoAkhirDep < 0 ? 'text-red-700' : 'text-emerald-800' }}">
                                {{ number_format($this->saldoAkhirDep) }}
                            </p>
                        </div>
                        <div
                            class="mt-3 pt-3 border-t {{ $this->saldoAkhirDep < 0 ? 'border-red-200' : 'border-emerald-200' }}">
                            <p
                                class="text-xs {{ $this->saldoAkhirDep < 0 ? 'text-red-500' : 'text-emerald-600' }} font-mono">
                                {{ number_format($this->saldoAwalDep) }}
                                + {{ number_format($this->tambahanStokDep) }}
                                − {{ number_format($this->jumlahDigunakanDep) }}
                                − {{ number_format($this->jmlDibatalkanRusakDep) }}
                                − {{ number_format($this->jmlDibatalkanHilangDep) }}
                                = <strong>{{ number_format($this->saldoAkhirDep) }}</strong>
                            </p>
                            @if ($this->saldoAkhirDep < 0)
                                <p class="text-xs text-red-600 font-semibold mt-1">
                                    ⚠ Saldo tidak boleh negatif. Periksa kembali data yang diinput.
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Buttons --}}
                    @if ($this->canEditDep)
                        <div class="flex items-center gap-3 pt-1">

                            <button type="button" wire:click="saveDraftDeposito" wire:loading.attr="disabled"
                                class="flex items-center gap-2 px-5 py-2.5 border-2 border-indigo-300
                                       text-indigo-700 text-sm font-semibold rounded-lg
                                       hover:bg-indigo-50 transition-colors disabled:opacity-60">
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
                                wire:loading.attr="disabled" @if ($this->saldoAkhirDep < 0) disabled @endif
                                class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white
                                       text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Submit Laporan
                            </button>

                        </div>
                        <p class="text-xs text-gray-400">
                            Simpan catatan kapan saja. Submit hanya dilakukan saat pelaporan wajib.
                        </p>
                    @else
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-xs text-gray-500">
                                Status: <strong>{{ \App\Enums\StatusVerifikasi::from($statusDep)->label() }}</strong>
                                — Laporan tidak dapat diedit saat ini.
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>{{-- end x-data tab --}}

    {{-- ═══════════════ CUSTOM SUBMIT MODAL ═══════════════ --}}
    @if ($this->showSubmitModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5);"
            x-data x-on:keydown.escape.window="$wire.closeSubmitModal()">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.stop
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">

                {{-- Modal Header --}}
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">
                                Konfirmasi Submit Laporan
                                {{ $this->pendingSubmitJenis === 'tabungan' ? 'Tabungan' : 'Deposito' }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $periode->nama_periode }}</p>
                        </div>
                    </div>
                </div>

                {{-- Modal Body: Ringkasan Data --}}
                <div class="px-6 py-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                        Ringkasan Data yang Akan Disubmit
                    </p>

                    @if ($this->pendingSubmitJenis === 'tabungan')
                        @php
                            $rows = [
                                ['label' => 'Saldo Awal', 'val' => $this->saldoAwalTab, 'type' => 'neutral'],
                                ['label' => 'Tambahan Stok', 'val' => $this->tambahanStokTab, 'type' => 'plus'],
                                ['label' => 'Jumlah Digunakan', 'val' => $this->jumlahDigunakanTab, 'type' => 'minus'],
                                [
                                    'label' => 'Batal (rusak/salah)',
                                    'val' => $this->jmlDibatalkanRusakTab,
                                    'type' => 'minus',
                                ],
                                [
                                    'label' => 'Batal (hilang)',
                                    'val' => $this->jmlDibatalkanHilangTab,
                                    'type' => 'minus',
                                ],
                            ];
                            $this->saldoFinal = $this->saldoAkhirTab;
                        @endphp
                    @else
                        @php
                            $rows = [
                                ['label' => 'Saldo Awal', 'val' => $this->saldoAwalDep, 'type' => 'neutral'],
                                ['label' => 'Tambahan Stok', 'val' => $this->tambahanStokDep, 'type' => 'plus'],
                                ['label' => 'Jumlah Digunakan', 'val' => $this->jumlahDigunakanDep, 'type' => 'minus'],
                                [
                                    'label' => 'Batal (rusak/salah)',
                                    'val' => $this->jmlDibatalkanRusakDep,
                                    'type' => 'minus',
                                ],
                                [
                                    'label' => 'Batal (hilang)',
                                    'val' => $this->jmlDibatalkanHilangDep,
                                    'type' => 'minus',
                                ],
                            ];
                            $this->saldoFinal = $this->saldoAkhirDep;
                        @endphp
                    @endif

                    <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
                        @foreach ($rows as $row)
                            <div
                                class="flex items-center justify-between px-4 py-2.5 border-b border-gray-200 last:border-0">
                                <span class="text-sm text-gray-600">{{ $row['label'] }}</span>
                                <div class="flex items-center gap-1.5">
                                    @if ($row['type'] === 'plus' && $row['val'] > 0)
                                        <span class="text-xs text-emerald-600 font-mono">+</span>
                                    @elseif($row['type'] === 'minus' && $row['val'] > 0)
                                        <span class="text-xs text-red-500 font-mono">−</span>
                                    @endif
                                    <span class="font-mono font-semibold text-sm text-gray-900">
                                        {{ number_format($row['val']) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        {{-- Saldo Akhir --}}
                        <div
                            class="flex items-center justify-between px-4 py-3
                                    {{ $this->saldoFinal >= 0 ? 'bg-emerald-50' : 'bg-red-50' }}">
                            <span
                                class="text-sm font-bold {{ $this->saldoFinal >= 0 ? 'text-emerald-800' : 'text-red-800' }}">
                                Saldo Akhir
                            </span>
                            <span
                                class="font-mono font-bold text-lg {{ $this->saldoFinal >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                                {{ number_format($this->saldoFinal) }}
                            </span>
                        </div>
                    </div>

                    {{-- Warning --}}
                    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-xs text-amber-700">
                            Pastikan semua data sudah benar. Setelah disubmit, laporan
                            <strong>tidak dapat diubah</strong> kecuali akunting meminta revisi.
                        </p>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeSubmitModal"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium
                               rounded-lg hover:bg-gray-100 transition-colors">
                        Batal, Cek Lagi
                    </button>
                    <button type="button" wire:click="confirmSubmit" wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white
                               text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors
                               disabled:opacity-60">
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
