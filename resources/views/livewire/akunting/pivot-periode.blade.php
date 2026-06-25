<div>

    {{-- ═══ FLASH MESSAGES ═══ --}}
    @if ($flashSuccess)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="mb-4 p-4 bg-white border border-emerald-200 rounded-xl shadow-sm shadow-emerald-100/50 flex items-start gap-3"
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
                <p class="text-[--color-text-secondary] text-sm">{{ $flashSuccess }}</p>
            </div>
            <button @click="show = false" type="button"
                class="flex-shrink-0 text-[--color-text-muted] hover:text-[--color-text-secondary] rounded-md p-0.5 hover:bg-[--color-bg-subtle] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if ($flashError)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="mb-4 p-4 bg-white border border-red-200 rounded-xl shadow-sm shadow-red-100/50 flex items-start gap-3"
            role="alert">
            <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center bg-red-100 rounded-lg">
                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-red-700 text-xs uppercase tracking-wide mb-0.5">Gagal</p>
                <p class="text-[--color-text-secondary] text-sm">{{ $flashError }}</p>
            </div>
            <button @click="show = false" type="button"
                class="flex-shrink-0 text-[--color-text-muted] hover:text-[--color-text-secondary] rounded-md p-0.5 hover:bg-[--color-bg-subtle] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- ═══ PROGRESS STATS ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-3">
        <div class="bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] p-3 shadow-sm">
            <div class="flex items-center justify-between mb-1.5">
                <p class="text-[10px] font-semibold text-[--color-text-muted] uppercase tracking-wider">Total</p>
                <div class="w-7 h-7 rounded-lg bg-[--color-bg-inset] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-[--color-text-secondary]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[--color-text-primary] font-mono">{{ $this->progress['total'] }}</p>
            <p class="text-[10px] text-[--color-text-muted] mt-0.5">Cabang</p>
        </div>

        <div class="bg-[--color-bg-surface] rounded-xl border border-emerald-200 p-3 shadow-sm bg-emerald-50/40">
            <div class="flex items-center justify-between mb-1.5">
                <p class="text-[10px] font-semibold text-emerald-600 uppercase tracking-wider">Verifikasi</p>
                <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-emerald-700 font-mono">{{ $this->progress['verified'] }}</p>
            <p class="text-[10px] text-emerald-500 mt-0.5">Terverifikasi</p>
        </div>

        <div class="bg-[--color-bg-surface] rounded-xl border border-blue-200 p-3 shadow-sm bg-blue-50/40">
            <div class="flex items-center justify-between mb-1.5">
                <p class="text-[10px] font-semibold text-blue-600 uppercase tracking-wider">Menunggu</p>
                <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-blue-700 font-mono">{{ $this->progress['submitted'] }}</p>
            <p class="text-[10px] text-blue-500 mt-0.5">Menunggu</p>
        </div>

        <div class="bg-[--color-bg-surface] rounded-xl border border-orange-200 p-3 shadow-sm bg-orange-50/40">
            <div class="flex items-center justify-between mb-1.5">
                <p class="text-[10px] font-semibold text-orange-600 uppercase tracking-wider">Revisi</p>
                <div class="w-7 h-7 rounded-lg bg-orange-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-orange-700 font-mono">{{ $this->progress['revision'] }}</p>
            <p class="text-[10px] text-orange-500 mt-0.5">Perlu Revisi</p>
        </div>

        <div class="bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] p-3 shadow-sm">
            <div class="flex items-center justify-between mb-1.5">
                <p class="text-[10px] font-semibold text-[--color-text-muted] uppercase tracking-wider">Draft</p>
                <div class="w-7 h-7 rounded-lg bg-[--color-bg-inset] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-[--color-text-muted]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[--color-text-muted] font-mono">{{ $this->progress['draft'] }}</p>
            <p class="text-[10px] text-[--color-text-muted] mt-0.5">Draft</p>
        </div>
    </div>

    {{-- ═══ PIVOT TABLE · PREMIUM GLASS + MICRO-INTERACTIONS ═══ --}}
    <div
        class="bg-[--color-bg-surface] rounded-2xl border border-[--color-border-default] shadow-xl overflow-hidden border-t-4 border-t-indigo-600 text-indigo-7000 shadow-xl shadow-slate-200/50 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-[13px]" style="min-width: 1200px;">
                {{-- Sticky Glass Header --}}
                <thead class="sticky top-0 backdrop-blur-xl bg-white/75 shadow-[0_4px_6px_-4px_rgba(0,0,0,0.05)]">
                    <tr>
                        <th rowspan="2"
                            class="sticky left-0 z-10 px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider bg-indigo-50/95 backdrop-blur-xl text-indigo-950 border-b border-r border-indigo-200/80 w-40 align-middle">
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Kantor Cabang
                            </span>
                        </th>
                        <th colspan="7"
                            class="px-4 py-2.5 text-center text-[11px] font-bold uppercase tracking-wider
                               bg-gradient-to-b from-indigo-100/90 to-indigo-50/50 backdrop-blur-xl text-indigo-700 border-b border-r border-indigo-200/80">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 ring-1 ring-indigo-300"></span>
                                Tabungan
                            </span>
                        </th>
                        <th colspan="7"
                            class="px-4 py-2.5 text-center text-[11px] font-bold uppercase tracking-wider
                               bg-gradient-to-b from-slate-100/90 to-slate-50/50 backdrop-blur-xl text-slate-700 border-b border-slate-200/80">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500 ring-1 ring-slate-300"></span>
                                Deposito
                            </span>
                        </th>
                    </tr>
                    <tr>
                        {{-- Sub-header untuk Tabungan (7 kolom) --}}
                        @foreach (['Saldo Awal', 'Tambahan +', 'Digunakan −', 'Batal Rusak', 'Batal Hilang', 'Saldo Akhir', 'Status / Aksi'] as $col)
                            <th
                                class="px-2 py-2.5 text-center text-[11px] font-semibold whitespace-nowrap border-b border-indigo-200/60 tracking-normal
                   bg-indigo-50/50 backdrop-blur-sm text-indigo-600
                   {{ $loop->last ? 'border-r border-indigo-200/60 w-28' : 'border-r border-indigo-200/60' }}">
                                {{ $col }}
                            </th>
                        @endforeach

                        {{-- Sub-header untuk Deposito (7 kolom) --}}
                        @foreach (['Saldo Awal', 'Tambahan +', 'Digunakan −', 'Batal Rusak', 'Batal Hilang', 'Saldo Akhir', 'Status / Aksi'] as $col)
                            <th
                                class="px-2 py-2.5 text-center text-[11px] font-semibold whitespace-nowrap border-b border-slate-200/60 tracking-normal
                   bg-slate-50/50 backdrop-blur-sm text-slate-600
                   {{ !$loop->last ? 'border-r border-slate-200/60' : '' }}">
                                {{ $col }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($this->pivotData as $i => $row)
                        @php
                            $tab = $row['tabungan'];
                            $dep = $row['deposito'];
                            $cabang = $row['cabang'];

                            $tabStatus = $tab?->status_verifikasi;
                            $depStatus = $dep?->status_verifikasi;

                            $needsAction = false;
                            $hasIssue = false;
                            $rowBase = $i % 2 === 0 ? 'bg-white/80' : 'bg-slate-50/70';
                            $accentClass = '';

                            if (
                                $tabStatus === \App\Enums\StatusVerifikasi::VerifiedAccounting &&
                                $depStatus === \App\Enums\StatusVerifikasi::VerifiedAccounting
                            ) {
                                $rowBase = 'bg-gradient-to-r from-emerald-50/90 to-emerald-50/30';
                                $accentClass = 'border-l-[3px] border-l-emerald-400';
                            } elseif (
                                $tabStatus === \App\Enums\StatusVerifikasi::RevisionRequested ||
                                $depStatus === \App\Enums\StatusVerifikasi::RevisionRequested
                            ) {
                                $rowBase = 'bg-gradient-to-r from-orange-50/90 to-orange-50/30';
                                $accentClass = 'border-l-[3px] border-l-orange-400';
                                $hasIssue = true;
                            } elseif (
                                $tabStatus === \App\Enums\StatusVerifikasi::Submitted ||
                                $depStatus === \App\Enums\StatusVerifikasi::Submitted
                            ) {
                                $rowBase = 'bg-gradient-to-r from-blue-50/90 to-blue-50/30';
                                $accentClass = 'border-l-[3px] border-l-blue-400';
                                $needsAction = true;
                            }
                        @endphp
                        <tr
                            class="{{ $rowBase }} hover:bg-white hover:shadow-sm transition-all duration-200 {{ $accentClass }}">
                            <td
                                class="sticky left-0 z-10 px-4 py-3 bg-indigo-950 backdrop-blur-sm border-r border-indigo-100 shadow-[2px_0_8px_-4px_rgba(99,102,241,0.2)]">
                                <div class="flex items-center gap-2.5">
                                    @if ($needsAction)
                                        <span class="relative flex h-2.5 w-2.5">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                            <span
                                                class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500 ring-2 ring-blue-200"></span>
                                        </span>
                                    @elseif ($hasIssue)
                                        <span class="relative flex h-2.5 w-2.5">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                            <span
                                                class="relative inline-flex rounded-full h-2.5 w-2.5 bg-orange-500 ring-2 ring-orange-200"></span>
                                        </span>
                                    @else
                                        <span class="w-2.5 h-2.5 rounded-full bg-slate-300 flex-shrink-0"></span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-bold text-white text-xs font-mono tracking-tight">
                                            {{ $cabang->kode_cabang }}</p>
                                        <p
                                            class="text-[11px] text-slate-400 leading-tight mt-0.5 truncate max-w-[130px]">
                                            {{ $cabang->nama_cabang }}</p>
                                    </div>
                                </div>
                            </td>

                            @if ($tab)
                                <td
                                    class="px-3 py-2.5 text-right font-mono tabular-nums text-xs text-slate-600 border-r border-slate-200/70 transition-colors duration-150 hover:text-slate-900">
                                    {{ number_format($tab->saldo_awal, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-2.5 text-right font-mono tabular-nums text-xs text-emerald-600 font-semibold border-r border-slate-200/70 transition-colors duration-150 hover:text-emerald-800">
                                    +{{ number_format($tab->tambahan_stok, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-2.5 text-right font-mono tabular-nums text-xs text-rose-600 font-semibold border-r border-slate-200/70 transition-colors duration-150 hover:text-rose-800">
                                    −{{ number_format($tab->jumlah_digunakan, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-2.5 text-right font-mono tabular-nums text-xs text-rose-400 border-r border-slate-200/70 transition-colors duration-150 hover:text-rose-600">
                                    {{ number_format($tab->jml_dibatalkan_rusak, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-2.5 text-right font-mono tabular-nums text-xs text-rose-400 border-r border-slate-200/70 transition-colors duration-150 hover:text-rose-600">
                                    {{ number_format($tab->jml_dibatalkan_hilang, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-2.5 text-right border-r border-slate-200/70 bg-amber-50/40 transition-colors duration-150">
                                    <span
                                        class="font-mono tabular-nums text-sm font-bold {{ $tab->saldo_akhir == 0 ? 'text-slate-300' : 'text-slate-800' }}">
                                        {{ number_format($tab->saldo_akhir, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-2 py-2.5 border-r border-slate-200/70">
                                    @include('livewire.akunting._action-cell', ['laporan' => $tab])
                                </td>
                            @else
                                <td colspan="7"
                                    class="px-3 py-2.5 text-center text-slate-400 text-[11px] italic border-r border-slate-200/70">
                                    —
                                </td>
                            @endif

                            @if ($dep)
                                <td
                                    class="px-3 py-2.5 text-right font-mono tabular-nums text-xs text-slate-600 border-r border-slate-200/70 transition-colors duration-150 hover:text-slate-900">
                                    {{ number_format($dep->saldo_awal, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-2.5 text-right font-mono tabular-nums text-xs text-emerald-600 font-semibold border-r border-slate-200/70 transition-colors duration-150 hover:text-emerald-800">
                                    +{{ number_format($dep->tambahan_stok, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-2.5 text-right font-mono tabular-nums text-xs text-rose-600 font-semibold border-r border-slate-200/70 transition-colors duration-150 hover:text-rose-800">
                                    −{{ number_format($dep->jumlah_digunakan, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-2.5 text-right font-mono tabular-nums text-xs text-rose-400 border-r border-slate-200/70 transition-colors duration-150 hover:text-rose-600">
                                    {{ number_format($dep->jml_dibatalkan_rusak, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-2.5 text-right font-mono tabular-nums text-xs text-rose-400 border-r border-slate-200/70 transition-colors duration-150 hover:text-rose-600">
                                    {{ number_format($dep->jml_dibatalkan_hilang, 0, ',', '.') }}</td>
                                <td
                                    class="px-3 py-2.5 text-right border-r border-slate-200/70 bg-amber-50/40 transition-colors duration-150">
                                    <span
                                        class="font-mono tabular-nums text-sm font-bold {{ $dep->saldo_akhir == 0 ? 'text-slate-300' : 'text-slate-800' }}">
                                        {{ number_format($dep->saldo_akhir, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-2 py-2.5">
                                    @include('livewire.akunting._action-cell', ['laporan' => $dep])
                                </td>
                            @else
                                <td colspan="7" class="px-3 py-2.5 text-center text-slate-400 text-[11px] italic">
                                    —
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="px-6 py-20 text-center">
                                <div
                                    class="w-16 h-16 bg-slate-100/80 backdrop-blur-xl rounded-3xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600 mb-1">Tidak ada data cabang ditemukan
                                </p>
                                <p class="text-xs text-slate-400">Coba sesuaikan pencarian atau filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($this->pivotData->isNotEmpty())
                    @php $totals = $this->columnTotals; @endphp
                    <tfoot>
                        <tr
                            class="bg-gradient-to-b from-slate-100/95 to-slate-50/90 backdrop-blur-xl border-t-[3px] border-indigo-200/50 shadow-[0_-1px_6px_rgba(0,0,0,0.03)]">
                            <td
                                class="sticky left-0 z-10 px-4 py-3 bg-slate-100/95 backdrop-blur-xl border-r border-slate-200/70 shadow-[2px_0_8px_-4px_rgba(0,0,0,0.05)]">
                                <p class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                    Grand Total
                                </p>
                            </td>
                            @foreach (['tab_saldo_awal', 'tab_tambahan', 'tab_digunakan', 'tab_rusak', 'tab_hilang', 'tab_saldo_akhir'] as $key)
                                <td
                                    class="px-3 py-3 text-right font-mono tabular-nums text-xs font-bold border-r border-slate-200/70
                                {{ $key === 'tab_tambahan' ? 'text-emerald-700' : ($key === 'tab_saldo_akhir' ? 'text-slate-800 text-sm bg-amber-50/60' : (in_array($key, ['tab_digunakan', 'tab_rusak', 'tab_hilang']) ? 'text-rose-700' : 'text-slate-700')) }}">
                                    {{ number_format($totals[$key], 0, ',', '.') }}
                                </td>
                            @endforeach
                            <td class="px-2 py-3 border-r border-slate-200/70"></td>
                            @foreach (['dep_saldo_awal', 'dep_tambahan', 'dep_digunakan', 'dep_rusak', 'dep_hilang', 'dep_saldo_akhir'] as $key)
                                <td
                                    class="px-3 py-3 text-right font-mono tabular-nums text-xs font-bold border-r border-slate-200/70
                                {{ $key === 'dep_tambahan' ? 'text-emerald-700' : ($key === 'dep_saldo_akhir' ? 'text-slate-800 text-sm bg-amber-50/60' : (in_array($key, ['dep_digunakan', 'dep_rusak', 'dep_hilang']) ? 'text-rose-700' : 'text-slate-700')) }}">
                                    {{ number_format($totals[$key], 0, ',', '.') }}
                                </td>
                            @endforeach
                            <td class="px-2 py-3"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Unified Progress Bar --}}
    <div
        class="flex items-center gap-4 mb-5 px-5 py-3 bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-sm">
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-1.5">
                <p class="text-xs text-[--color-text-muted]">
                    <span class="font-semibold text-[--color-text-primary]">{{ $this->progress['verified'] }}</span>
                    dari
                    <span class="font-semibold text-[--color-text-primary]">{{ $this->progress['total'] }}</span>
                    cabang terverifikasi
                </p>
                <span
                    class="text-sm font-bold font-mono {{ $this->progress['pct'] === 100 ? 'text-emerald-600' : 'text-indigo-600' }}">
                    {{ $this->progress['pct'] }}%
                </span>
            </div>
            <div class="w-full bg-[--color-bg-inset] rounded-full h-2 overflow-hidden">
                <div class="h-2 rounded-full transition-all duration-700 ease-out
                            {{ $this->progress['pct'] === 100 ? 'bg-emerald-500' : 'bg-indigo-500' }}"
                    style="width: {{ $this->progress['pct'] }}%">
                </div>
            </div>
        </div>
        @if ($this->progress['pct'] === 100 && !$periode->isLocked())
            <div
                class="flex-shrink-0 flex items-center gap-2 text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                Siap Verifikasi Final
            </div>
        @endif
    </div>

    {{-- ═══ LOCKED BANNER ═══ --}}
    @if ($periode->isLocked())
        <div class="mb-4 p-4 bg-[--color-bg-surface] border border-slate-200 rounded-xl shadow-sm flex items-start gap-3"
            role="alert">
            <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center bg-slate-100 rounded-lg">
                <svg class="w-4 h-4 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            <div>
                <p class="font-semibold text-[--color-text-primary] text-xs uppercase tracking-wide mb-0.5">Periode
                    Terkunci</p>
                <p class="text-[--color-text-secondary] text-sm">Periode sudah diverifikasi final. Data bersifat
                    read-only dan tidak dapat diubah.</p>
            </div>
        </div>
    @endif


    {{-- ═══ MODAL APPROVE ═══ --}}
    @if ($showApproveModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data
            x-on:keydown.escape.window="$wire.closeApprove()"
            style="background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(2px);">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden" @click.stop
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                {{-- Header --}}
                <div class="px-6 py-5 border-b border-[--color-border-default] flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-[--color-text-primary]">Setujui Laporan</h3>
                        <p class="text-xs text-[--color-text-muted] mt-0.5">{{ $approveJenis }} ·
                            {{ $approveCabang }}</p>
                    </div>
                    <button type="button" wire:click="closeApprove"
                        class="text-[--color-text-muted] hover:text-[--color-text-secondary] hover:bg-[--color-bg-subtle] rounded-lg p-1 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    <div
                        class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-start gap-2.5">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Setelah disetujui, laporan ini tidak dapat diubah oleh PIC cabang. Pastikan data sudah
                            benar sebelum melanjutkan.</span>
                    </div>
                </div>

                {{-- Footer --}}
                <div
                    class="px-6 py-4 bg-[--color-bg-subtle] border-t border-[--color-border-default] flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeApprove" class="btn-secondary text-sm">
                        Batal
                    </button>
                    <button type="button" wire:click="confirmApprove" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-colors disabled:opacity-60 shadow-sm shadow-emerald-200">
                        <span wire:loading wire:target="confirmApprove" class="inline-flex items-center gap-1">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Memproses...
                        </span>
                        <span wire:loading.remove wire:target="confirmApprove"
                            class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Setujui Laporan
                        </span>
                    </button>
                </div>

            </div>
        </div>
    @endif

    {{-- ═══ MODAL REVISI ═══ --}}
    @if ($showRevisiModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data
            x-on:keydown.escape.window="$wire.closeModal()"
            style="background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(2px);">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden" @click.stop
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                {{-- Header --}}
                <div class="px-6 py-5 border-b border-[--color-border-default] flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-[--color-text-primary]">Minta Revisi Laporan</h3>
                        <p class="text-xs text-[--color-text-muted] mt-0.5">{{ $revisiJenis }} · {{ $revisiCabang }}
                        </p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="text-[--color-text-muted] hover:text-[--color-text-secondary] hover:bg-[--color-bg-subtle] rounded-lg p-1 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
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
                    <label
                        class="block text-xs font-semibold text-[--color-text-secondary] uppercase tracking-wider mb-1.5">
                        Catatan Revisi <span class="text-red-500 font-normal">*</span>
                    </label>
                    <textarea wire:model="catatanRevisi" rows="4"
                        placeholder="Contoh: Saldo akhir tidak sesuai. Jumlah digunakan harap dicek kembali..."
                        class="w-full px-3 py-2.5 border border-[--color-border-default] rounded-lg text-sm text-[--color-text-primary] bg-white placeholder:text-[--color-text-disabled] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none transition-shadow @error('catatanRevisi') border-red-400 bg-red-50 @enderror"></textarea>
                    @error('catatanRevisi')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-[--color-text-muted] mt-1.5">Minimal 10 karakter.</p>
                </div>

                {{-- Footer --}}
                <div
                    class="px-6 py-4 bg-[--color-bg-subtle] border-t border-[--color-border-default] flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeModal" class="btn-secondary text-sm">
                        Batal
                    </button>
                    <button type="button" wire:click="submitRevisi" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 transition-colors disabled:opacity-60 shadow-sm shadow-orange-200">
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
