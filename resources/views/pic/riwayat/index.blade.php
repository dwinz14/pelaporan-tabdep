<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('pic.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
        <span class="text-gray-300">›</span>
        <span class="text-gray-900 font-medium">Riwayat Laporan</span>
    </div>

    @if ($periodes->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
            <svg class="w-14 h-14 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-base font-medium text-gray-500">Belum ada riwayat laporan</p>
            <p class="text-sm text-gray-400 mt-1">Data akan muncul setelah Anda mengirim laporan pertama.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($periodes as $periode)
                @php
                    $tab = $periode->laporans->firstWhere('jenis', \App\Enums\JenisLaporan::Tabungan);
                    $dep = $periode->laporans->firstWhere('jenis', \App\Enums\JenisLaporan::Deposito);

                    $allVerified =
                        $tab?->status_verifikasi === \App\Enums\StatusVerifikasi::VerifiedAccounting &&
                        $dep?->status_verifikasi === \App\Enums\StatusVerifikasi::VerifiedAccounting;
                    $hasRevisi =
                        $tab?->status_verifikasi === \App\Enums\StatusVerifikasi::RevisionRequested ||
                        $dep?->status_verifikasi === \App\Enums\StatusVerifikasi::RevisionRequested;

                    // Ringkasan status untuk header
                    $statusSummary = $allVerified ? 'Terverifikasi' : ($hasRevisi ? 'Perlu Revisi' : 'Menunggu');
                    $summaryColor = $allVerified
                        ? 'bg-emerald-100 text-emerald-700'
                        : ($hasRevisi
                            ? 'bg-orange-100 text-orange-700'
                            : 'bg-gray-100 text-gray-600');

                    $borderColor = $allVerified
                        ? 'border-emerald-200'
                        : ($hasRevisi
                            ? 'border-orange-200'
                            : 'border-gray-200');
                    $headerBg = $allVerified ? 'bg-emerald-50/50' : ($hasRevisi ? 'bg-orange-50/50' : 'bg-gray-50/50');
                @endphp

                {{-- Card Periode (Expandable) --}}
                <div x-data="{ open: {{ $periode->is_current ? 'true' : 'false' }} }"
                    class="bg-white rounded-2xl border {{ $borderColor }} shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
                    {{-- Header (Clickable) --}}
                    <div @click="open = !open" @keydown.enter.prevent="open = !open"
                        @keydown.space.prevent="open = !open" role="button" tabindex="0" :aria-expanded="open"
                        class="flex items-center justify-between px-5 py-4 cursor-pointer {{ $headerBg }} select-none">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $periode->nama_periode }}
                                </p>
                                @if ($periode->is_current)
                                    <span
                                        class="shrink-0 text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-medium flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>Aktif
                                    </span>
                                @endif
                            </div>
                            <span class="shrink-0 text-xs {{ $summaryColor }} px-2 py-0.5 rounded-full font-medium">
                                {{ $statusSummary }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3 ml-4 shrink-0">
                            <span class="text-xs text-gray-400 hidden sm:inline">
                                {{ $periode->tanggal_akhir->isoFormat('D MMM Y') }}
                            </span>
                            <span
                                class="text-xs {{ $periode->status_operasional->badgeClass() }} px-2 py-0.5 rounded-full font-medium">
                                {{ $periode->status_operasional->label() }}
                            </span>

                            {{-- Tombol Edit/Lihat (tidak ikut toggle) --}}
                            @if (!$periode->isLocked() && ($tab?->status_verifikasi?->canEdit() || $dep?->status_verifikasi?->canEdit()))
                                <a href="{{ route('pic.laporan.edit', $periode) }}" @click.stop
                                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg transition-colors">
                                    Edit
                                </a>
                            @else
                                <a href="{{ route('pic.laporan.edit', $periode) }}" @click.stop
                                    class="text-xs font-medium text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 px-2.5 py-1 rounded-lg transition-colors">
                                    Lihat
                                </a>
                            @endif

                            {{-- Chevron --}}
                            <svg :class="open ? 'rotate-180' : ''"
                                class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    {{-- Detail Expandable Content --}}
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2" class="border-t border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-5">
                            {{-- Tabungan --}}
                            <div class="bg-gray-50/70 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Tabungan</p>
                                    @if ($tab)
                                        <span
                                            class="text-xs {{ $tab->status_verifikasi->badgeClass() }} px-2 py-0.5 rounded-full font-medium">
                                            {{ $tab->status_verifikasi->label() }}
                                        </span>
                                    @endif
                                </div>

                                @if ($tab)
                                    @if ($tab->status_verifikasi === \App\Enums\StatusVerifikasi::RevisionRequested && $tab->catatan_revisi)
                                        <div
                                            class="mb-3 p-2.5 bg-orange-50 border border-orange-200 rounded-lg text-xs text-orange-700">
                                            <span class="font-semibold">Catatan Revisi:</span><br>
                                            {{ $tab->catatan_revisi }}
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                        <span class="text-gray-500">Saldo Awal</span>
                                        <span
                                            class="font-mono text-right font-medium text-gray-800">{{ number_format($tab->saldo_awal) }}</span>

                                        <span class="text-gray-500">+ Tambahan</span>
                                        <span
                                            class="font-mono text-right text-emerald-600">{{ number_format($tab->tambahan_stok) }}</span>

                                        <span class="text-gray-500">− Digunakan</span>
                                        <span
                                            class="font-mono text-right text-red-500">{{ number_format($tab->jumlah_digunakan) }}</span>

                                        <span class="text-gray-500">− Batal Rusak</span>
                                        <span
                                            class="font-mono text-right text-red-400">{{ number_format($tab->jml_dibatalkan_rusak) }}</span>

                                        <span class="text-gray-500">− Batal Hilang</span>
                                        <span
                                            class="font-mono text-right text-red-400">{{ number_format($tab->jml_dibatalkan_hilang) }}</span>

                                        <span class="font-semibold text-gray-700 pt-2 border-t border-gray-200">Saldo
                                            Akhir</span>
                                        <span
                                            class="font-mono font-bold text-gray-900 text-right pt-2 border-t border-gray-200">{{ number_format($tab->saldo_akhir) }}</span>
                                    </div>

                                    @if ($tab->tgl_submit)
                                        <p class="text-xs text-gray-400 mt-3 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $tab->tgl_submit->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                        </p>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-400 italic">Belum ada data tabungan</p>
                                @endif
                            </div>

                            {{-- Deposito --}}
                            <div class="bg-gray-50/70 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Deposito</p>
                                    @if ($dep)
                                        <span
                                            class="text-xs {{ $dep->status_verifikasi->badgeClass() }} px-2 py-0.5 rounded-full font-medium">
                                            {{ $dep->status_verifikasi->label() }}
                                        </span>
                                    @endif
                                </div>

                                @if ($dep)
                                    @if ($dep->status_verifikasi === \App\Enums\StatusVerifikasi::RevisionRequested && $dep->catatan_revisi)
                                        <div
                                            class="mb-3 p-2.5 bg-orange-50 border border-orange-200 rounded-lg text-xs text-orange-700">
                                            <span class="font-semibold">Catatan Revisi:</span><br>
                                            {{ $dep->catatan_revisi }}
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                        <span class="text-gray-500">Saldo Awal</span>
                                        <span
                                            class="font-mono text-right font-medium text-gray-800">{{ number_format($dep->saldo_awal) }}</span>

                                        <span class="text-gray-500">+ Tambahan</span>
                                        <span
                                            class="font-mono text-right text-emerald-600">{{ number_format($dep->tambahan_stok) }}</span>

                                        <span class="text-gray-500">− Digunakan</span>
                                        <span
                                            class="font-mono text-right text-red-500">{{ number_format($dep->jumlah_digunakan) }}</span>

                                        <span class="text-gray-500">− Batal Rusak</span>
                                        <span
                                            class="font-mono text-right text-red-400">{{ number_format($dep->jml_dibatalkan_rusak) }}</span>

                                        <span class="text-gray-500">− Batal Hilang</span>
                                        <span
                                            class="font-mono text-right text-red-400">{{ number_format($dep->jml_dibatalkan_hilang) }}</span>

                                        <span class="font-semibold text-gray-700 pt-2 border-t border-gray-200">Saldo
                                            Akhir</span>
                                        <span
                                            class="font-mono font-bold text-gray-900 text-right pt-2 border-t border-gray-200">{{ number_format($dep->saldo_akhir) }}</span>
                                    </div>

                                    @if ($dep->tgl_submit)
                                        <p class="text-xs text-gray-400 mt-3 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $dep->tgl_submit->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                        </p>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-400 italic">Belum ada data deposito</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($periodes->hasPages())
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-gray-500">
                    Menampilkan {{ $periodes->firstItem() }}–{{ $periodes->lastItem() }} dari
                    {{ $periodes->total() }} periode
                </p>
                <div class="text-sm">
                    {{ $periodes->links() }}
                </div>
            </div>
        @endif
    @endif

</x-app-layout>
