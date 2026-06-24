<div>

    {{-- ═══ FLASH MESSAGES ═══ --}}
    @if ($flashSuccess)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            {{ $flashSuccess }}
        </div>
    @endif

    @if ($flashError)
        <div
            class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            {{ $flashError }}
        </div>
    @endif

    {{-- ═══ PROGRESS STATS ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-5">
        {{-- Total --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total</p>
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $this->progress['total'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Kantor Cabang</p>
        </div>

        {{-- Terverifikasi --}}
        <div
            class="bg-white rounded-xl border border-emerald-200 p-4 shadow-sm hover:shadow-md transition-shadow bg-emerald-50/30">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-emerald-600 uppercase tracking-wider">Verifikasi</p>
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-emerald-700">{{ $this->progress['verified'] }}</p>
            <p class="text-xs text-emerald-500 mt-0.5">Terverifikasi</p>
        </div>

        {{-- Menunggu --}}
        <div
            class="bg-white rounded-xl border border-blue-200 p-4 shadow-sm hover:shadow-md transition-shadow bg-blue-50/30">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-blue-600 uppercase tracking-wider">Menunggu</p>
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-blue-700">{{ $this->progress['submitted'] }}</p>
            <p class="text-xs text-blue-500 mt-0.5">Menunggu Verifikasi</p>
        </div>

        {{-- Revisi --}}
        <div
            class="bg-white rounded-xl border border-orange-200 p-4 shadow-sm hover:shadow-md transition-shadow bg-orange-50/30">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-orange-600 uppercase tracking-wider">Revisi</p>
                <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-orange-700">{{ $this->progress['revision'] }}</p>
            <p class="text-xs text-orange-500 mt-0.5">Perlu Revisi</p>
        </div>

        {{-- Draft --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Draft</p>
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-500">{{ $this->progress['draft'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Belum Dikirim</p>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 mb-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <div
                    class="w-9 h-9 rounded-full {{ $this->progress['pct'] === 100 ? 'bg-emerald-100' : 'bg-indigo-100' }} flex items-center justify-center">
                    <svg class="w-5 h-5 {{ $this->progress['pct'] === 100 ? 'text-emerald-600' : 'text-indigo-600' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Progress Verifikasi Akunting</p>
                    <p class="text-xs text-gray-500">{{ $this->progress['verified'] }} dari
                        {{ $this->progress['total'] }} cabang terverifikasi</p>
                </div>
            </div>
            <span
                class="text-lg font-bold {{ $this->progress['pct'] === 100 ? 'text-emerald-600' : 'text-indigo-600' }}">
                {{ $this->progress['pct'] }}%
            </span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
            <div class="h-3 rounded-full transition-all duration-700 ease-out
                        {{ $this->progress['pct'] === 100 ? 'bg-emerald-500' : 'bg-indigo-500' }}"
                style="width: {{ $this->progress['pct'] }}%">
            </div>
        </div>
        @if ($this->progress['pct'] === 100 && !$periode->isLocked())
            <div
                class="mt-3 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-700 flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                Semua cabang terverifikasi. Periode ini siap untuk verifikasi final oleh Kepala Operasional.
            </div>
        @endif
    </div>

    {{-- ═══ LOCKED BANNER ═══ --}}
    @if ($periode->isLocked())
        <div
            class="mb-4 p-3 bg-violet-50 border border-violet-200 rounded-xl text-xs text-violet-800 flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0 text-violet-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                    clip-rule="evenodd" />
            </svg>
            <div>
                <strong>Periode sudah diverifikasi final.</strong>
                <span class="block sm:inline sm:ml-1">Data bersifat read-only dan tidak dapat diubah.</span>
            </div>
        </div>
    @endif

    {{-- ═══ TOOLBAR ═══ --}}
    <div
        class="bg-white rounded-xl border border-gray-200 p-3 mb-4 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari cabang..."
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-48">
            </div>
        </div>
        <div class="text-xs text-gray-500 flex items-center gap-3">
            <span class="inline-flex items-center gap-1">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span> Terverifikasi
            </span>
            <span class="inline-flex items-center gap-1">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span> Menunggu
            </span>
            <span class="inline-flex items-center gap-1">
                <span class="w-2.5 h-2.5 rounded-full bg-orange-400"></span> Revisi
            </span>
        </div>
    </div>

    {{-- ═══ PIVOT TABLE ═══ --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" style="min-width: 1200px;">
                <thead>
                    {{-- Header baris 1: Grup --}}
                    <tr>
                        <th rowspan="2"
                            class="sticky left-0 z-10 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider
                                   bg-gray-50 text-gray-600 border-b border-r border-gray-200 w-36 align-middle">
                            Kantor Cabang
                        </th>
                        <th colspan="7"
                            class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider
                                   bg-indigo-50 text-indigo-700 border-b border-r border-gray-200">
                            Tabungan
                        </th>
                        <th colspan="7"
                            class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider
                                   bg-slate-50 text-slate-700 border-b border-gray-200">
                            Deposito
                        </th>
                    </tr>
                    {{-- Header baris 2: Kolom detail --}}
                    <tr class="bg-gray-50">
                        @foreach (['Saldo Awal', 'Tambahan', 'Digunakan', 'Batal Rusak', 'Batal Hilang', 'Saldo Akhir', 'Status / Aksi'] as $col)
                            <th
                                class="px-3 py-2.5 text-center text-xs font-medium text-gray-500 whitespace-nowrap
                                       {{ $loop->last ? 'border-r border-gray-200 w-28' : 'border-r border-gray-100' }}">
                                {{ $col }}
                            </th>
                        @endforeach
                        @foreach (['Saldo Awal', 'Tambahan', 'Digunakan', 'Batal Rusak', 'Batal Hilang', 'Saldo Akhir', 'Status / Aksi'] as $col)
                            <th
                                class="px-3 py-2.5 text-center text-xs font-medium text-gray-500 whitespace-nowrap
                                       {{ !$loop->last ? 'border-r border-gray-100' : '' }}">
                                {{ $col }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($this->pivotData as $row)
                        @php
                            $tab = $row['tabungan'];
                            $dep = $row['deposito'];
                            $cabang = $row['cabang'];

                            $tabStatus = $tab?->status_verifikasi;
                            $depStatus = $dep?->status_verifikasi;

                            $rowBg = 'bg-white hover:bg-gray-50/80';
                            if (
                                $tabStatus === \App\Enums\StatusVerifikasi::VerifiedAccounting &&
                                $depStatus === \App\Enums\StatusVerifikasi::VerifiedAccounting
                            ) {
                                $rowBg = 'bg-emerald-50/70 hover:bg-emerald-100/70';
                            } elseif (
                                $tabStatus === \App\Enums\StatusVerifikasi::RevisionRequested ||
                                $depStatus === \App\Enums\StatusVerifikasi::RevisionRequested
                            ) {
                                $rowBg = 'bg-orange-50/70 hover:bg-orange-100/70';
                            } elseif (
                                $tabStatus === \App\Enums\StatusVerifikasi::Submitted ||
                                $depStatus === \App\Enums\StatusVerifikasi::Submitted
                            ) {
                                $rowBg = 'bg-blue-50/70 hover:bg-blue-100/70';
                            }
                        @endphp
                        <tr class="{{ $rowBg }} transition-colors duration-150">
                            {{-- Nama Cabang --}}
                            <td class="sticky left-0 z-10 px-4 py-3 bg-inherit border-r border-gray-200">
                                <p class="font-semibold text-gray-900 text-xs font-mono">{{ $cabang->kode_cabang }}
                                </p>
                                <p class="text-gray-500 text-xs leading-tight mt-0.5">{{ $cabang->nama_cabang }}</p>
                            </td>

                            {{-- TABUNGAN --}}
                            @if ($tab)
                                <td
                                    class="px-3 py-3 text-right font-mono text-xs border-r border-gray-100 text-gray-700">
                                    {{ number_format($tab->saldo_awal, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-3 text-right font-mono text-xs border-r border-gray-100 text-emerald-600">
                                    {{ number_format($tab->tambahan_stok, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-3 text-right font-mono text-xs border-r border-gray-100 text-red-500">
                                    {{ number_format($tab->jumlah_digunakan, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-3 text-right font-mono text-xs border-r border-gray-100 text-red-400">
                                    {{ number_format($tab->jml_dibatalkan_rusak, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-3 text-right font-mono text-xs border-r border-gray-100 text-red-400">
                                    {{ number_format($tab->jml_dibatalkan_hilang, 0, ',', '.') }}</td>
                                <td class="px-3 py-3 text-right border-r border-gray-100">
                                    <span
                                        class="font-mono font-bold text-xs {{ $tab->saldo_akhir == 0 ? 'text-gray-400' : 'text-gray-900' }}">
                                        {{ number_format($tab->saldo_akhir, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 border-r border-gray-200">
                                    @include('livewire.akunting._action-cell', ['laporan' => $tab])
                                </td>
                            @else
                                <td colspan="7"
                                    class="px-3 py-3 text-center text-gray-300 text-xs italic border-r border-gray-200">
                                    Belum ada data
                                </td>
                            @endif

                            {{-- DEPOSITO --}}
                            @if ($dep)
                                <td
                                    class="px-3 py-3 text-right font-mono text-xs border-r border-gray-100 text-gray-700">
                                    {{ number_format($dep->saldo_awal, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-3 text-right font-mono text-xs border-r border-gray-100 text-emerald-600">
                                    {{ number_format($dep->tambahan_stok, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-3 text-right font-mono text-xs border-r border-gray-100 text-red-500">
                                    {{ number_format($dep->jumlah_digunakan, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-3 text-right font-mono text-xs border-r border-gray-100 text-red-400">
                                    {{ number_format($dep->jml_dibatalkan_rusak, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-3 text-right font-mono text-xs border-r border-gray-100 text-red-400">
                                    {{ number_format($dep->jml_dibatalkan_hilang, 0, ',', '.') }}</td>
                                <td class="px-3 py-3 text-right border-r border-gray-100">
                                    <span
                                        class="font-mono font-bold text-xs {{ $dep->saldo_akhir == 0 ? 'text-gray-400' : 'text-gray-900' }}">
                                        {{ number_format($dep->saldo_akhir, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-3 py-3">
                                    @include('livewire.akunting._action-cell', ['laporan' => $dep])
                                </td>
                            @else
                                <td colspan="7" class="px-3 py-3 text-center text-gray-300 text-xs italic">
                                    Belum ada data
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="px-6 py-12 text-center">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-sm font-medium text-gray-500">Tidak ada data cabang ditemukan</p>
                                <p class="text-xs text-gray-400 mt-1">Coba sesuaikan pencarian atau filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══ MODAL REVISI ═══ --}}
    @if ($showRevisiModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-data
            x-on:keydown.escape.window="$wire.closeModal()" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md" @click.stop
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">

                {{-- Modal Header --}}
                <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-orange-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Minta Revisi Laporan</h3>
                            <p class="text-xs text-gray-500">{{ $revisiJenis }} · {{ $revisiCabang }}</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="px-5 py-4">
                    <div
                        class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-xl text-xs text-orange-800 flex items-start gap-2">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Catatan ini akan ditampilkan kepada PIC cabang saat mereka membuka form laporan. Tuliskan
                            dengan jelas apa yang perlu diperbaiki.</span>
                    </div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Catatan Revisi <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="catatanRevisi" rows="4"
                        placeholder="Contoh: Saldo akhir tidak sesuai. Jumlah digunakan harap dicek kembali sesuai bukti pemakaian bulan ini..."
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none
                               @error('catatanRevisi') border-red-400 bg-red-50 @enderror">
                    </textarea>
                    @error('catatanRevisi')
                        <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">Minimal 10 karakter.</p>
                </div>

                {{-- Modal Footer --}}
                <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2.5 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Batal
                    </button>
                    <button type="button" wire:click="submitRevisi" wire:loading.attr="disabled"
                        class="px-4 py-2.5 bg-orange-500 text-white text-sm font-medium rounded-lg
                               hover:bg-orange-600 transition-colors disabled:opacity-60 shadow-sm shadow-orange-200">
                        <span wire:loading wire:target="submitRevisi" class="inline-flex items-center gap-1">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Mengirim...
                        </span>
                        <span wire:loading.remove wire:target="submitRevisi">Kirim Permintaan Revisi</span>
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
