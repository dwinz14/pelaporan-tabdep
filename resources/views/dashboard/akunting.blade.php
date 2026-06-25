<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Welcome & Periode Aktif --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[--color-text-primary] tracking-tight">Halo,
                {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
            <p class="text-sm text-[--color-text-muted] mt-1">Selamat datang di dashboard verifikasi akunting</p>
        </div>

        @if ($periodeAktif)
            <div
                class="flex items-center gap-4 bg-[--color-bg-surface] px-5 py-3 rounded-xl border border-[--color-border-default] shadow-sm">
                <div class="relative flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-[--color-text-muted] mb-0.5">Periode
                        Aktif</p>
                    <p class="text-sm font-semibold text-[--color-text-primary]">{{ $periodeAktif->nama_periode }}</p>
                </div>
                <div class="w-px h-8 bg-[--color-border-default] mx-1"></div>
            </div>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
        <div
            class="bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-sm overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-slate-400 to-slate-500"></div>
            <div class="p-5 flex items-start gap-4">
                <div
                    class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[--color-text-muted]">Total Cabang
                    </p>
                    <p class="text-2xl font-bold font-mono tabular-nums text-[--color-text-primary] mt-1">
                        {{ $totalCabang }}</p>
                </div>
            </div>
        </div>

        <div
            class="bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-sm overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-indigo-400 to-indigo-500"></div>
            <div class="p-5 flex items-start gap-4">
                <div
                    class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-200 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[--color-text-muted]">Menunggu
                        Verifikasi</p>
                    <p class="text-2xl font-bold font-mono tabular-nums text-indigo-600 mt-1">{{ $totalSubmitted }}</p>
                    <p class="text-[11px] text-[--color-text-muted] mt-0.5">cabang, periode aktif</p>
                </div>
            </div>
        </div>

        <div
            class="bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-sm overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-amber-400 to-amber-500"></div>
            <div class="p-5 flex items-start gap-4">
                <div
                    class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[--color-text-muted]">Perlu Revisi
                    </p>
                    <p class="text-2xl font-bold font-mono tabular-nums text-amber-600 mt-1">{{ $totalRevisi }}</p>
                    <p class="text-[11px] text-[--color-text-muted] mt-0.5">cabang, periode aktif</p>
                </div>
            </div>
        </div>

        <div
            class="bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-sm overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-emerald-400 to-emerald-500"></div>
            <div class="p-5 flex items-start gap-4">
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[--color-text-muted]">
                        Terverifikasi</p>
                    <p class="text-2xl font-bold font-mono tabular-nums text-emerald-600 mt-1">{{ $totalVerified }} /
                        {{ $totalCabang }}</p>
                    <p class="text-[11px] text-[--color-text-muted] mt-0.5">periode aktif</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Priority Alert: Ada yg menunggu verifikasi --}}
    @if ($totalSubmitted > 0)
        <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-indigo-800">
                    Ada {{ $totalSubmitted }} cabang menunggu verifikasi Anda
                </p>
                <p class="text-xs text-indigo-600 mt-0.5">Segera verifikasi laporan masuk untuk menjaga kelancaran
                    operasional.</p>
            </div>
            @if ($periodeAktif)
                <a href="{{ route('akunting.periode.show', $periodeAktif) }}"
                    class="ml-auto px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition-colors flex-shrink-0 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Verifikasi Sekarang
                </a>
            @endif
        </div>
    @endif

    {{-- Main Grid: Quick Access + Recent Periods --}}
    <div class="grid md:grid-cols-3 gap-5">

        {{-- Quick Access --}}
        <div class="bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-sm p-5">
            <h3 class="text-sm font-bold text-[--color-text-primary] mb-4">Aksi Cepat</h3>
            <div class="space-y-2.5">
                @if ($periodeAktif)
                    <a href="{{ route('akunting.periode.show', $periodeAktif) }}"
                        class="group flex items-center gap-3 p-3.5 border border-indigo-200 bg-indigo-50/50 rounded-xl hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-150">
                        <div
                            class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm shadow-indigo-200 group-hover:shadow-md group-hover:shadow-indigo-300 transition-shadow">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-indigo-900">Verifikasi Sekarang</p>
                            <p class="text-xs text-indigo-600 truncate mt-0.5">{{ $periodeAktif->nama_periode }}</p>
                        </div>
                        <div
                            class="ml-auto opacity-0 group-hover:opacity-100 -translate-x-1 group-hover:translate-x-0 transition-all duration-200">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @endif

                <a href="{{ route('akunting.periode.index') }}"
                    class="group flex items-center gap-3 p-3.5 border border-[--color-border-default] rounded-xl hover:bg-[--color-bg-subtle] hover:border-[--color-border-strong] transition-all duration-150">
                    <div
                        class="w-9 h-9 rounded-xl bg-[--color-bg-subtle] border border-[--color-border-default] flex items-center justify-center flex-shrink-0 group-hover:bg-[--color-bg-inset] transition-colors">
                        <svg class="w-4 h-4 text-[--color-text-muted]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p
                            class="text-xs font-semibold text-[--color-text-primary] group-hover:text-indigo-700 transition-colors">
                            Semua Periode</p>
                        <p class="text-xs text-[--color-text-muted] mt-0.5">Lihat daftar periode & progress</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Recent Periods --}}
        <div
            class="md:col-span-2 bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[--color-border-default] flex items-center justify-between">
                <h3 class="text-sm font-bold text-[--color-text-primary]">Periode Terbaru</h3>
                @if ($periodeRecent->isNotEmpty())
                    <a href="{{ route('akunting.periode.index') }}"
                        class="flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                        Lihat Semua
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif
            </div>

            @if ($periodeRecent->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-[--color-bg-subtle] border-b border-[--color-border-default]">
                                <th
                                    class="px-5 py-3.5 text-left text-[10px] font-bold text-[--color-text-muted] uppercase tracking-wider">
                                    Periode</th>
                                <th
                                    class="px-5 py-3.5 text-center text-[10px] font-bold text-[--color-text-muted] uppercase tracking-wider">
                                    Progress</th>
                                <th
                                    class="px-5 py-3.5 text-right text-[10px] font-bold text-[--color-text-muted] uppercase tracking-wider w-20">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[--color-border-default]">
                            @foreach ($periodeRecent as $p)
                                @php
                                    $total = $p->total_cabang ?? 0;
                                    $verified = $p->total_verified ?? 0;
                                    $pct = $total > 0 ? round(($verified / $total) * 100) : 0;
                                @endphp
                                <tr class="hover:bg-[--color-bg-subtle] transition-colors duration-150 group">
                                    <td class="px-5 py-4">
                                        <p class="text-xs font-bold text-[--color-text-primary]">
                                            {{ $p->nama_periode }}</p>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="{{ $p->status_operasional->badgeClass() }} text-[10px]">
                                                {{ $p->status_operasional->label() }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3 max-w-xs mx-auto">
                                            <div class="flex-1 bg-[--color-bg-inset] rounded-full h-2 overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-500 {{ $pct == 100 ? 'bg-emerald-500' : 'bg-indigo-500' }}"
                                                    style="width: {{ $pct }}%"></div>
                                            </div>
                                            <span
                                                class="text-xs font-mono tabular-nums font-semibold text-[--color-text-muted] w-16 text-right flex-shrink-0">
                                                {{ $verified }}<span
                                                    class="text-[--color-text-disabled]">/</span>{{ $total }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('akunting.periode.show', $p) }}"
                                            class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors px-2.5 py-1.5 rounded-md hover:bg-indigo-50">
                                            Buka
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-12 text-center">
                    <div
                        class="w-14 h-14 bg-[--color-bg-inset] border border-[--color-border-default] rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-[--color-text-muted]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-[--color-text-primary] mb-1">Belum Ada Periode</p>
                    <p class="text-xs text-[--color-text-muted]">Belum ada periode laporan yang tersedia.</p>
                </div>
            @endif
        </div>

    </div>

</x-app-layout>
