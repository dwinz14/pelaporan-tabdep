<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- HEADER SECTION --}}
    @if (auth()->user()->cabang)
        <div class="relative mb-6 bg-white rounded-2xl p-5 md:p-6 border border-slate-200 shadow-xl overflow-hidden">
            {{-- Background Ornament --}}
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-28 h-28 bg-indigo-50 rounded-full blur-2xl opacity-60 pointer-events-none">
            </div>

            <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-1.5">
                        Halo, {{ explode(' ', auth()->user()->name)[0] }} 👋
                    </h1>
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex items-center justify-center px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-md text-[10px] font-bold font-mono border border-indigo-100/80 shadow-xs">
                            {{ auth()->user()->cabang->kode_cabang }}
                        </span>
                        <p class="text-xs font-medium text-slate-500">
                            {{ auth()->user()->cabang->nama_cabang }}
                        </p>
                    </div>
                </div>

                @if ($periode)
                    <div
                        class="flex items-center gap-3 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200/80 self-start sm:self-center">
                        <div class="relative flex h-2.5 w-2.5 flex-shrink-0">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Periode Aktif
                            </p>
                            <p class="text-xs font-bold text-slate-700">{{ $periode->nama_periode }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($periode)
        {{-- DASHBOARD INSIGHTS --}}
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800 tracking-tight">Ringkasan Saldo Berjalan</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
            {{-- KARTU TABUNGAN --}}
            @php $tab = $laporanAktif->get('tabungan'); @endphp
            <div
                class="bg-white rounded-2xl border border-slate-200 shadow-xl hover:shadow-2xl transition-all duration-300 relative overflow-hidden group">
                @if ($tab?->status_verifikasi === \App\Enums\StatusVerifikasi::VerifiedAccounting)
                    <div class="absolute top-0 left-0 w-full h-1 bg-emerald-400"></div>
                @else
                    <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>
                @endif

                <div class="p-5 md:p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-10 h-10 bg-indigo-50/80 rounded-xl flex items-center justify-center border border-indigo-100 group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Tabungan</h3>
                        </div>
                        @if ($tab)
                            <span
                                class="text-[10px] uppercase tracking-wider font-bold px-2 py-1 rounded-md border {{ $tab->status_verifikasi->badgeClass() }}">
                                {{ $tab->status_verifikasi->label() }}
                            </span>
                        @else
                            <span
                                class="text-[10px] uppercase font-bold tracking-wider px-2 py-1 rounded-md bg-slate-100 text-slate-500 border border-slate-200">
                                Data Kosong
                            </span>
                        @endif
                    </div>

                    @if ($tab)
                        <div class="mb-5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Saldo Akhir
                            </p>
                            <div class="flex items-baseline gap-0.5">
                                <span class="text-lg font-bold text-slate-400">Rp</span>
                                <p class="text-3xl md:text-4xl font-extrabold font-mono text-slate-900 tracking-tight">
                                    {{ number_format($tab->saldo_akhir, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <div class="text-center">
                                <p class="text-[9px] uppercase tracking-wider font-bold text-slate-400 mb-0.5">Awal</p>
                                <p class="text-xs font-bold font-mono text-slate-600">
                                    {{ number_format($tab->saldo_awal, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-center border-l border-slate-200">
                                <p
                                    class="text-[9px] uppercase tracking-wider font-bold text-slate-400 mb-0.5 flex items-center justify-center gap-0.5">
                                    <svg class="w-2.5 h-2.5 text-emerald-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                    </svg>
                                    Masuk
                                </p>
                                <p class="text-xs font-bold font-mono text-emerald-600">
                                    +{{ number_format($tab->tambahan_stok, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-center border-l border-slate-200">
                                <p
                                    class="text-[9px] uppercase tracking-wider font-bold text-slate-400 mb-0.5 flex items-center justify-center gap-0.5">
                                    <svg class="w-2.5 h-2.5 text-rose-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                    Keluar
                                </p>
                                <p class="text-xs font-bold font-mono text-rose-600">
                                    -{{ number_format($tab->jumlah_digunakan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- KARTU DEPOSITO --}}
            @php $dep = $laporanAktif->get('deposito'); @endphp
            <div
                class="bg-white rounded-2xl border border-slate-200 shadow-xl hover:shadow-2xl transition-all duration-300 relative overflow-hidden group">
                @if ($dep?->status_verifikasi === \App\Enums\StatusVerifikasi::VerifiedAccounting)
                    <div class="absolute top-0 left-0 w-full h-1 bg-emerald-400"></div>
                @else
                    <div class="absolute top-0 left-0 w-full h-1 bg-slate-800"></div>
                @endif

                <div class="p-5 md:p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center border border-slate-200 group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Deposito</h3>
                        </div>
                        @if ($dep)
                            <span
                                class="text-[10px] uppercase tracking-wider font-bold px-2 py-1 rounded-md border {{ $dep->status_verifikasi->badgeClass() }}">
                                {{ $dep->status_verifikasi->label() }}
                            </span>
                        @else
                            <span
                                class="text-[10px] uppercase font-bold tracking-wider px-2 py-1 rounded-md bg-slate-100 text-slate-500 border border-slate-200">
                                Data Kosong
                            </span>
                        @endif
                    </div>

                    @if ($dep)
                        <div class="mb-5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Saldo Akhir
                            </p>
                            <div class="flex items-baseline gap-0.5">
                                <span class="text-lg font-bold text-slate-400">Rp</span>
                                <p class="text-3xl md:text-4xl font-extrabold font-mono text-slate-900 tracking-tight">
                                    {{ number_format($dep->saldo_akhir, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <div class="text-center">
                                <p class="text-[9px] uppercase tracking-wider font-bold text-slate-400 mb-0.5">Awal</p>
                                <p class="text-xs font-bold font-mono text-slate-600">
                                    {{ number_format($dep->saldo_awal, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-center border-l border-slate-200">
                                <p
                                    class="text-[9px] uppercase tracking-wider font-bold text-slate-400 mb-0.5 flex items-center justify-center gap-0.5">
                                    <svg class="w-2.5 h-2.5 text-emerald-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                    </svg>
                                    Masuk
                                </p>
                                <p class="text-xs font-bold font-mono text-emerald-600">
                                    +{{ number_format($dep->tambahan_stok, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-center border-l border-slate-200">
                                <p
                                    class="text-[9px] uppercase tracking-wider font-bold text-slate-400 mb-0.5 flex items-center justify-center gap-0.5">
                                    <svg class="w-2.5 h-2.5 text-rose-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                    Keluar
                                </p>
                                <p class="text-xs font-bold font-mono text-rose-600">
                                    -{{ number_format($dep->jumlah_digunakan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="mb-4 flex items-center justify-between mt-8">
            <h2 class="text-base font-bold text-slate-800 tracking-tight">Aksi Cepat</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            {{-- Action 1: Catat Transaksi (Secondary) --}}
            <a href="{{ route('pic.pencatatan.index') }}"
                class="group relative bg-white border border-slate-200 hover:border-indigo-400 rounded-2xl p-4 transition-all duration-300 shadow-xs hover:shadow-md flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-slate-800 group-hover:text-indigo-700 transition-colors">Catat
                        Transaksi Harian</h3>
                    <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">Input penggunaan stok harian cabang</p>
                </div>
                <div
                    class="w-7 h-7 rounded-full bg-slate-50 group-hover:bg-indigo-50 flex items-center justify-center transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-indigo-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            {{-- Action 2: Laporan Wajib (Primary) --}}
            <a href="{{ route('pic.laporan.edit', $periode) }}"
                class="group relative bg-slate-900 border border-slate-900 hover:border-indigo-950 hover:bg-indigo-950 rounded-2xl p-4 transition-all duration-300 shadow-xs hover:shadow-md flex items-center gap-4 overflow-hidden">
                {{-- Decorative background gradient --}}
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl transform translate-x-8 -translate-y-8 group-hover:bg-indigo-400/20 transition-all">
                </div>

                <div
                    class="w-12 h-12 bg-white/10 text-white group-hover:bg-white group-hover:text-indigo-950 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 backdrop-blur-xs relative z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="flex-1 relative z-10">
                    <h3 class="text-sm font-bold text-white">Input Laporan Wajib</h3>
                    <p class="text-xs text-slate-300 mt-0.5 line-clamp-1">Selesaikan pelaporan stok periode ini</p>
                </div>
                <div
                    class="w-7 h-7 rounded-full bg-white/10 group-hover:bg-white/20 flex items-center justify-center transition-colors relative z-10">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
        </div>
    @else
        {{-- EMPTY STATE --}}
        <div
            class="bg-white rounded-2xl border border-slate-200 shadow-xs p-8 text-center mb-8 flex flex-col items-center justify-center min-h-[240px]">
            <div
                class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-slate-800 mb-1.5 tracking-tight">Belum Ada Periode Aktif</h2>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Sistem belum mendeteksi adanya periode laporan yang
                berjalan. Silakan tunggu admin membuka periode baru.</p>
        </div>
    @endif

    {{-- HISTORY SECTION --}}
    @if ($riwayatSingkat->isNotEmpty())
        <div class="flex items-center justify-between mb-4 mt-6">
            <h2 class="text-base font-bold text-slate-800 tracking-tight">Riwayat Laporan Sebelumnya</h2>
            <a href="{{ route('pic.riwayat.index') }}"
                class="flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg">
                Lihat Semua
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th
                                class="px-5 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                Periode Laporan</th>
                            <th
                                class="px-5 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider w-36">
                                Tabungan</th>
                            <th
                                class="px-5 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider w-36">
                                Deposito</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($riwayatSingkat as $p)
                            @php
                                $t = $p->laporans->firstWhere('jenis', \App\Enums\JenisLaporan::Tabungan);
                                $d = $p->laporans->firstWhere('jenis', \App\Enums\JenisLaporan::Deposito);
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors duration-150 group">
                                <td class="px-5 py-3.5">
                                    <p
                                        class="text-xs font-bold text-slate-800 mb-0.5 group-hover:text-indigo-600 transition-colors">
                                        {{ $p->nama_periode }}</p>
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-0.5 text-slate-400">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span
                                                class="text-[10px] font-mono font-medium">{{ $p->tanggal_akhir->format('d/m/Y') }}</span>
                                        </div>
                                        <span
                                            class="text-[9px] uppercase font-bold tracking-wider px-1.5 py-0.2 rounded border {{ $p->status_operasional->badgeClass() }}">
                                            {{ $p->status_operasional->label() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-right align-middle">
                                    @if ($t)
                                        <p class="font-mono text-xs font-bold text-slate-800 mb-1">
                                            {{ number_format($t->saldo_akhir, 0, ',', '.') }}</p>
                                        <span
                                            class="text-[9px] uppercase font-bold tracking-wider px-1.5 py-0.2 rounded border {{ $t->status_verifikasi->badgeClass() }} inline-block">
                                            {{ $t->status_verifikasi->label() }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-xs font-mono">&mdash;</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right align-middle">
                                    @if ($d)
                                        <p class="font-mono text-xs font-bold text-slate-800 mb-1">
                                            {{ number_format($d->saldo_akhir, 0, ',', '.') }}</p>
                                        <span
                                            class="text-[9px] uppercase font-bold tracking-wider px-1.5 py-0.2 rounded border {{ $d->status_verifikasi->badgeClass() }} inline-block">
                                            {{ $d->status_verifikasi->label() }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-xs font-mono">&mdash;</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</x-app-layout>
