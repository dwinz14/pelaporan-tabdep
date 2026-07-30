<x-app-layout :title="$title" :subtitle="$subtitle">

@php
    $cabangAktifHariIni = $cabangs->filter(fn ($c) =>
        $c['pencatatan_tab_hari_ini'] > 0 || $c['pencatatan_dep_hari_ini'] > 0
    )->count();
@endphp

    {{-- ═══════════════════════════════════════════════════════════════
         1. HEADER — Title + Timestamp + Refresh
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="hidden sm:flex w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl items-center justify-center shadow-lg shadow-indigo-200 flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Monitoring Stok</h1>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-xs text-emerald-600 font-medium">Live</span>
                        <span class="text-xs text-gray-300">•</span>
                        <span class="text-xs text-gray-400">Data diperbarui: {{ $updatedAt }}</span>
                    </div>
                </div>
            </div>
            <button onclick="window.location.reload()" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-xl hover:bg-indigo-100 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Segarkan Data
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         2. SUMMARY CARDS — 4 Kartu Agregat
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        {{-- ─── Card 1: Total Stok Tabungan ─── --}}
        <div class="group relative bg-white rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute -top-16 -right-16 w-40 h-40 bg-blue-400/[0.06] rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-3">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Stok Tabungan</p>
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md shadow-blue-200 flex-shrink-0">
                        <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002-2h2a2 2 0 002 2M9 5h6m-6 4h6m-6 4h4"/></svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-gray-900 font-mono tracking-tight">{{ number_format($total['total_tab'], 0, ',', '.') }}</p>
                <p class="text-[11px] text-gray-400 mt-1">Estimasi real-time seluruh cabang</p>
                @if($total['masuk_tab_hari_ini'] > 0 || $total['keluar_tab_hari_ini'] > 0)
                    <div class="flex flex-wrap items-center gap-1.5 mt-3 pt-3 border-t border-dashed border-gray-100">
                        @if($total['masuk_tab_hari_ini'] > 0)
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-md border border-emerald-100">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                +{{ number_format($total['masuk_tab_hari_ini'], 0, ',', '.') }}
                            </span>
                        @endif
                        @if($total['keluar_tab_hari_ini'] > 0)
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 bg-rose-50 text-rose-700 text-[10px] font-bold rounded-md border border-rose-100">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                -{{ number_format($total['keluar_tab_hari_ini'], 0, ',', '.') }}
                            </span>
                        @endif
                        <span class="text-[10px] text-gray-400 ml-auto">hari ini</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- ─── Card 2: Total Stok Deposito ─── --}}
        <div class="group relative bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute -top-16 -right-16 w-40 h-40 bg-slate-400/[0.06] rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-3">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Stok Deposito</p>
                    <div class="w-9 h-9 bg-gradient-to-br from-slate-500 to-slate-600 rounded-xl flex items-center justify-center shadow-md shadow-slate-200 flex-shrink-0">
                        <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002-2h2a2 2 0 002 2M9 5h6m-6 4h6m-6 4h4"/></svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-gray-900 font-mono tracking-tight">{{ number_format($total['total_dep'], 0, ',', '.') }}</p>
                <p class="text-[11px] text-gray-400 mt-1">Estimasi real-time seluruh cabang</p>
                @if($total['masuk_dep_hari_ini'] > 0 || $total['keluar_dep_hari_ini'] > 0)
                    <div class="flex flex-wrap items-center gap-1.5 mt-3 pt-3 border-t border-dashed border-gray-100">
                        @if($total['masuk_dep_hari_ini'] > 0)
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-md border border-emerald-100">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                +{{ number_format($total['masuk_dep_hari_ini'], 0, ',', '.') }}
                            </span>
                        @endif
                        @if($total['keluar_dep_hari_ini'] > 0)
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 bg-rose-50 text-rose-700 text-[10px] font-bold rounded-md border border-rose-100">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                -{{ number_format($total['keluar_dep_hari_ini'], 0, ',', '.') }}
                            </span>
                        @endif
                        <span class="text-[10px] text-gray-400 ml-auto">hari ini</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- ─── Card 3: Cabang Aktif ─── --}}
        <div class="group relative bg-white rounded-2xl border border-emerald-100 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute -top-16 -right-16 w-40 h-40 bg-emerald-400/[0.06] rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-3">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Cabang Aktif</p>
                    <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md shadow-emerald-200 flex-shrink-0">
                        <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-gray-900 font-mono tracking-tight">{{ $total['total_cabang_aktif'] }}</p>
                <p class="text-[11px] text-gray-400 mt-1">Cabang dipantau</p>
                <div class="mt-3 pt-3 border-t border-dashed border-gray-100">
                    <div class="flex items-center gap-2 text-[10px] text-gray-400">
                        <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500 transition-all duration-700" style="width: 100%"></div>
                        </div>
                        <span class="font-mono font-medium text-gray-500">100%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Card 4: Ada Pencatatan Hari Ini ─── --}}
        <div class="group relative bg-white rounded-2xl border border-amber-100 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute -top-16 -right-16 w-40 h-40 bg-amber-400/[0.06] rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-3">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Ada Pencatatan</p>
                    <div class="w-9 h-9 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md shadow-amber-200 flex-shrink-0">
                        <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold font-mono tracking-tight {{ $cabangAktifHariIni > 0 ? 'text-emerald-600' : 'text-gray-400' }}">
                    {{ $cabangAktifHariIni }}
                </p>
                <p class="text-[11px] text-gray-400 mt-1">dari {{ $total['total_cabang_aktif'] }} cabang</p>
                @if($cabangAktifHariIni > 0)
                    <div class="mt-3 pt-3 border-t border-dashed border-gray-100">
                        <div class="flex items-center gap-2 text-[10px] text-gray-400">
                            <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                @php $pct = min(100, round(($cabangAktifHariIni / max($total['total_cabang_aktif'], 1)) * 100)); @endphp
                                <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-orange-500 transition-all duration-700" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="font-mono font-medium text-gray-500">{{ $pct }}%</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         3. DATA TABLE — Stok Per Cabang
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-lg border border-[--color-border-default] bg-white shadow-sm">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-slate-800 via-indigo-600 to-sky-500"></div>
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Stok Per Cabang</h3>
                <p class="text-xs text-gray-400 mt-0.5">Klik cabang untuk lihat detail pergerakan</p>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span class="font-bold text-gray-700 tabular-nums">{{ $cabangs->count() }}</span>
                <span class="text-gray-500">Cabang</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="sticky top-0 z-10 bg-gray-50 px-4 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-48">Cabang</th>
                        <th class="sticky top-0 z-10 bg-gray-50 px-4 py-3.5 text-center text-xs font-bold text-blue-600 uppercase tracking-wider border-l border-gray-200 w-36">Stok Tabungan</th>
                        <th class="sticky top-0 z-10 bg-gray-50 px-4 py-3.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider w-28">± Hari Ini</th>
                        <th class="sticky top-0 z-10 bg-gray-50 px-4 py-3.5 text-center text-xs font-bold text-slate-600 uppercase tracking-wider border-l border-gray-200 w-36">Stok Deposito</th>
                        <th class="sticky top-0 z-10 bg-gray-50 px-4 py-3.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider w-28">± Hari Ini</th>
                        <th class="sticky top-0 z-10 bg-gray-50 px-4 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider border-l border-gray-200 w-40">Status Laporan</th>
                        <th class="sticky top-0 z-10 bg-gray-50 px-4 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-16"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($cabangs as $row)
                        @php
                            $hasAktivitasHariIni = $row['pencatatan_tab_hari_ini'] > 0 || $row['pencatatan_dep_hari_ini'] > 0;
                        @endphp
                        <tr class="{{ $loop->odd ? 'bg-gray-50/30' : '' }} {{ $hasAktivitasHariIni ? 'bg-emerald-50/20' : '' }} hover:bg-blue-50/40 transition-colors group">

                            {{-- Cabang --}}
                            <td class="px-4 py-3">
                                <a href="{{ route('akunting.monitoring.show', $row['cabang']) }}" class="flex items-center gap-3 group/cabang">
                                    <div class="relative flex-shrink-0">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                            {{ strtoupper(substr($row['cabang']->kode_cabang, 0, 2)) }}
                                        </div>
                                        @if($hasAktivitasHariIni)
                                            <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white animate-pulse" title="Ada pencatatan hari ini"></span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm group-hover/cabang:text-indigo-600 transition-colors">{{ $row['cabang']->kode_cabang }}</p>
                                        <p class="text-[11px] text-gray-400 leading-tight truncate max-w-[160px]">{{ $row['cabang']->nama_cabang }}</p>
                                    </div>
                                </a>
                            </td>

                            {{-- Stok Tabungan --}}
                            <td class="px-4 py-3 text-center border-l border-gray-100">
                                <p class="font-mono font-bold text-gray-900 text-sm">{{ number_format($row['saldo_tab'], 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($row['masuk_tab_hari_ini'] > 0 || $row['keluar_tab_hari_ini'] > 0)
                                    <div class="inline-flex items-center gap-1">
                                        @if($row['masuk_tab_hari_ini'] > 0)
                                            <span class="font-mono text-xs font-bold text-emerald-600">+{{ number_format($row['masuk_tab_hari_ini'], 0, ',', '.') }}</span>
                                        @endif
                                        @if($row['masuk_tab_hari_ini'] > 0 && $row['keluar_tab_hari_ini'] > 0)
                                            <span class="text-[10px] text-gray-300">/</span>
                                        @endif
                                        @if($row['keluar_tab_hari_ini'] > 0)
                                            <span class="font-mono text-xs font-bold text-rose-600">-{{ number_format($row['keluar_tab_hari_ini'], 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Stok Deposito --}}
                            <td class="px-4 py-3 text-center border-l border-gray-100">
                                <p class="font-mono font-bold text-gray-900 text-sm">{{ number_format($row['saldo_dep'], 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($row['masuk_dep_hari_ini'] > 0 || $row['keluar_dep_hari_ini'] > 0)
                                    <div class="inline-flex items-center gap-1">
                                        @if($row['masuk_dep_hari_ini'] > 0)
                                            <span class="font-mono text-xs font-bold text-emerald-600">+{{ number_format($row['masuk_dep_hari_ini'], 0, ',', '.') }}</span>
                                        @endif
                                        @if($row['masuk_dep_hari_ini'] > 0 && $row['keluar_dep_hari_ini'] > 0)
                                            <span class="text-[10px] text-gray-300">/</span>
                                        @endif
                                        @if($row['keluar_dep_hari_ini'] > 0)
                                            <span class="font-mono text-xs font-bold text-rose-600">-{{ number_format($row['keluar_dep_hari_ini'], 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Status Laporan --}}
                            <td class="px-4 py-3 text-center border-l border-gray-100">
                                <div class="flex items-center justify-center gap-2">
                                    @if($row['status_laporan_tab'])
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold border {{ $row['status_laporan_tab']->badgeClass() }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            {{ $row['status_laporan_tab']->label() }}
                                        </span>
                                    @endif
                                    @if($row['status_laporan_dep'])
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold border {{ $row['status_laporan_dep']->badgeClass() }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                            {{ $row['status_laporan_dep']->label() }}
                                        </span>
                                    @endif
                                    @if(! $row['status_laporan_tab'] && ! $row['status_laporan_dep'])
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Detail --}}
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('akunting.monitoring.show', $row['cabang']) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                                   title="Lihat detail {{ $row['cabang']->kode_cabang }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                {{-- Footer: Total --}}
                <tfoot>
                    <tr class="bg-gradient-to-r from-gray-900 to-gray-800">
                        <td class="px-4 py-3.5 text-xs font-bold text-gray-300 tracking-wide">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                TOTAL <span class="text-white font-black">{{ $total['total_cabang_aktif'] }}</span> CABANG
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-center border-l border-gray-700/50">
                            <span class="font-mono font-black text-white text-sm">{{ number_format($total['total_tab'], 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($total['masuk_tab_hari_ini'] > 0 || $total['keluar_tab_hari_ini'] > 0)
                                <div class="inline-flex items-center gap-1">
                                    @if($total['masuk_tab_hari_ini'] > 0)
                                        <span class="font-mono text-xs font-bold text-emerald-400">+{{ number_format($total['masuk_tab_hari_ini'], 0, ',', '.') }}</span>
                                    @endif
                                    @if($total['masuk_tab_hari_ini'] > 0 && $total['keluar_tab_hari_ini'] > 0)
                                        <span class="text-[10px] text-gray-600">/</span>
                                    @endif
                                    @if($total['keluar_tab_hari_ini'] > 0)
                                        <span class="font-mono text-xs font-bold text-red-400">-{{ number_format($total['keluar_tab_hari_ini'], 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-500 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center border-l border-gray-700/50">
                            <span class="font-mono font-black text-white text-sm">{{ number_format($total['total_dep'], 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($total['masuk_dep_hari_ini'] > 0 || $total['keluar_dep_hari_ini'] > 0)
                                <div class="inline-flex items-center gap-1">
                                    @if($total['masuk_dep_hari_ini'] > 0)
                                        <span class="font-mono text-xs font-bold text-emerald-400">+{{ number_format($total['masuk_dep_hari_ini'], 0, ',', '.') }}</span>
                                    @endif
                                    @if($total['masuk_dep_hari_ini'] > 0 && $total['keluar_dep_hari_ini'] > 0)
                                        <span class="text-[10px] text-gray-600">/</span>
                                    @endif
                                    @if($total['keluar_dep_hari_ini'] > 0)
                                        <span class="font-mono text-xs font-bold text-red-400">-{{ number_format($total['keluar_dep_hari_ini'], 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-500 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 border-l border-gray-700/50" colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</x-app-layout>
