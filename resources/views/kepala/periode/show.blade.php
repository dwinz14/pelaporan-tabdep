<x-app-layout :title="$title" :subtitle="$subtitle">

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- ═══ BREADCRUMB & UTILITY NAVIGATION ═══ --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('kepala.periode.index') }}"
                    class="text-slate-500 hover:text-indigo-600 font-medium transition-colors flex items-center gap-1.5 group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Dashboard Verifikasi
                </a>
                <span class="text-slate-300 font-light">/</span>
                <span class="text-slate-950 font-semibold">{{ $periode->nama_periode }}</span>
            </nav>

            <div class="flex items-center gap-3">
                {{-- Status Badge Premium --}}
                @php
                    $isLocked = $periode->isLocked();
                    $badgeStyle = $isLocked
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                        : 'bg-amber-50 text-amber-700 border-amber-200';
                @endphp
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border tracking-wider uppercase {{ $badgeStyle }}">
                    <span
                        class="w-1.5 h-1.5 rounded-full {{ $isLocked ? 'bg-emerald-500' : 'bg-amber-500 animate-pulse' }}"></span>
                    {{ $periode->status_operasional->label() }}
                </span>

                <a href="{{ route('kepala.periode.index') }}"
                    class="text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200/80 px-3.5 py-2 rounded-xl transition-all">
                    Kembali
                </a>
            </div>
        </div>

        {{-- ═══ FLOATING HERO CARD (Info & Action Panel) ═══ --}}
        <div
            class="relative bg-gradient-to-br from-white via-indigo-50/20 to-indigo-100/30 rounded-2xl shadow-[0_8px_24px_-10px_rgba(79,70,229,0.08)] border border-white/60 p-4 sm:p-5 overflow-hidden">

            {{-- Decorative glow --}}
            <div
                class="absolute top-0 right-0 w-[220px] h-[220px] bg-indigo-300/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4 pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                {{-- Left --}}
                <div class="space-y-3">

                    <div>
                        <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">
                            Detail Periode Aktif
                        </span>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">
                            {{ $periode->nama_periode }}
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 lg:gap-4">

                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center border border-indigo-100/50">
                                <svg class="w-4 h-4" ...></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                                    Tanggal Laporan Akhir
                                </p>
                                <p class="text-xs font-semibold text-slate-800">
                                    {{ $periode->tanggal_akhir->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                </p>
                            </div>
                        </div>

                        @if ($isLocked)
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center border border-emerald-100/50">
                                    <svg class="w-4 h-4" ...></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                                        Diverifikasi Final Oleh
                                    </p>
                                    <p class="text-xs font-semibold text-slate-800">
                                        {{ $periode->verifiedByOperasional?->name ?? 'Sistem' }}
                                        <span class="text-[10px] text-slate-400 font-normal">
                                            ·
                                            {{ $periode->tgl_verifikasi_operasional?->locale('id')->isoFormat('D MMM Y') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Right --}}
                <div class="lg:w-auto w-full lg:max-w-sm">

                    @if (!$isLocked && $periode->semuaCabangVerified())
                        <div
                            class="bg-emerald-50/80 backdrop-blur-sm border border-emerald-200/60 p-4 rounded-xl flex flex-col gap-3 shadow-sm">

                            <div class="flex items-start gap-2.5">
                                <div
                                    class="w-7 h-7 rounded-full bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" ...></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-emerald-900">
                                        Seluruh Cabang Terverifikasi
                                    </h4>
                                    <p class="text-[11px] text-emerald-700/80 mt-0.5 leading-snug">
                                        Periode siap dikunci permanen.
                                    </p>
                                </div>
                            </div>

                            <button
                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-lg
                           hover:bg-emerald-700 active:scale-[0.98] transition-all shadow-[0_4px_14px_rgba(16,185,129,0.25)]">
                                Verifikasi Final
                            </button>

                        </div>
                    @elseif(!$isLocked)
                        <div
                            class="bg-amber-50/80 border border-amber-200/60 p-4 rounded-xl flex items-start gap-3 shadow-sm">
                            <div
                                class="w-7 h-7 rounded-full bg-amber-500/10 text-amber-600 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5" ...></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-amber-900">
                                    Verifikasi Belum Siap
                                </h4>
                                <p class="text-[11px] text-amber-800/80 leading-snug">
                                    Menunggu semua cabang selesai.
                                </p>
                            </div>
                        </div>
                    @else
                        <div
                            class="bg-slate-100 border border-slate-200 p-4 rounded-xl flex items-start gap-3 shadow-sm">
                            <div
                                class="w-7 h-7 rounded-full bg-slate-500/10 text-slate-600 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5" ...></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">
                                    Periode Dikunci
                                </h4>
                                <p class="text-[11px] text-slate-500 leading-snug">
                                    Data bersifat read-only.
                                </p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ═══ LIVEWIRE PIVOT TABLE CONTAINER ═══ --}}
        <div class="bg-white rounded-3xl border border-slate-300/80 shadow-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Pivot Pelaporan Stok Buku Tabungan & Deposito</h3>
                    <p class="text-xs text-slate-500 mt-1">Struktur rekapitulasi data komparatif antar seluruh kantor
                        cabang penempatan.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-600">
                        Akses Read-Only
                    </span>
                </div>
            </div>

            <div class="p-6 bg-slate-50/50">
                {{-- Pivot Table (reuse Livewire) --}}
                <livewire:akunting.pivot-periode :periode="$periode" />
            </div>
        </div>

    </div>

</x-app-layout>
