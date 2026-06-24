<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Welcome & Info Header --}}
    @if (auth()->user()->cabang)
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[--color-text-primary] tracking-tight">Halo,
                    {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
                <p class="text-sm text-[--color-text-muted] mt-1 flex items-center gap-2">
                    <span
                        class="inline-flex items-center justify-center px-1.5 py-0.5 bg-indigo-100 text-indigo-700 rounded text-[10px] font-bold font-mono">{{ auth()->user()->cabang->kode_cabang }}</span>
                    {{ auth()->user()->cabang->nama_cabang }}
                </p>
            </div>

            @if ($periode)
                <div
                    class="flex items-center gap-4 bg-[--color-bg-surface] px-5 py-3 rounded-xl border border-[--color-border-default] shadow-sm">
                    <div class="relative flex h-3 w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-[--color-text-muted] mb-0.5">
                            Periode Aktif</p>
                        <p class="text-sm font-semibold text-[--color-text-primary]">{{ $periode->nama_periode }}</p>
                    </div>
                    <div class="w-px h-8 bg-slate-200 mx-1"></div>
                </div>
            @endif
        </div>
    @endif

    @if ($periode)
        {{-- Priority Actions (CTA) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
            <a href="{{ route('pic.pencatatan.index') }}"
                class="relative overflow-hidden group flex items-center gap-4 p-5 bg-white border border-[--color-border-default] hover:border-indigo-300 rounded-2xl transition-all duration-200 shadow-sm hover:shadow-md">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-indigo-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                </div>
                <div
                    class="w-12 h-12 bg-indigo-50 group-hover:bg-indigo-100 group-hover:scale-110 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 z-10">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div class="z-10">
                    <p
                        class="text-base font-bold text-[--color-text-primary] group-hover:text-indigo-700 transition-colors">
                        Catat Transaksi</p>
                    <p class="text-xs text-[--color-text-muted] mt-0.5">Input kejadian penggunaan stok harian</p>
                </div>
                <div
                    class="ml-auto z-10 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('pic.laporan.edit', $periode) }}"
                class="relative overflow-hidden group flex items-center gap-4 p-5 bg-indigo-600 border border-indigo-500 rounded-2xl transition-all duration-200 shadow-sm shadow-indigo-200 hover:shadow-lg hover:shadow-indigo-300">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-indigo-700/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                </div>
                <div
                    class="w-12 h-12 bg-white/20 group-hover:bg-white/30 group-hover:scale-110 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 z-10">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="z-10">
                    <p class="text-base font-bold text-white">Input Laporan Wajib</p>
                    <p class="text-xs text-indigo-100 mt-0.5">Selesaikan pelaporan stok periode ini</p>
                </div>
                <div
                    class="ml-auto z-10 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                    <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
        </div>

        {{-- Status Cards --}}
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-sm font-bold text-[--color-text-primary]">Status Saldo Periode Berjalan</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-10">
            @php $tab = $laporanAktif->get('tabungan'); @endphp
            <div
                class="bg-white rounded-2xl border {{ $tab?->status_verifikasi === \App\Enums\StatusVerifikasi::VerifiedAccounting ? 'border-emerald-300 bg-emerald-50/10' : 'border-[--color-border-default]' }} shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <div class="px-5 py-4 {{ $tab ? 'border-b border-slate-100' : '' }} flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center border border-indigo-100">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-[--color-text-primary]">Tabungan</p>
                    </div>
                    @if ($tab)
                        <span
                            class="text-[10px] uppercase tracking-wider font-bold px-2 py-1 rounded-md border {{ $tab->status_verifikasi->badgeClass() }}">
                            {{ $tab->status_verifikasi->label() }}
                        </span>
                    @else
                        <span
                            class="text-[10px] uppercase font-bold tracking-wider px-2 py-1 rounded-md border bg-slate-100 text-slate-500 border-slate-200">Data
                            Kosong</span>
                    @endif
                </div>

                @if ($tab)
                    <div class="p-5">
                        <div class="mb-5 flex items-end justify-between">
                            <div>
                                <p
                                    class="text-[10px] uppercase tracking-wider font-bold text-[--color-text-muted] mb-1">
                                    Saldo Akhir</p>
                                <p class="text-3xl font-bold font-mono text-[--color-text-primary] tracking-tight">
                                    {{ number_format($tab->saldo_akhir, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-3 gap-2 bg-[--color-bg-subtle] rounded-lg border border-[--color-border-default] p-3">
                            <div class="text-center border-r border-[--color-border-default]">
                                <p class="text-[10px] uppercase tracking-wider font-semibold text-[--color-text-muted]">
                                    Awal</p>
                                <p class="text-xs font-bold font-mono text-slate-700 mt-0.5">
                                    {{ number_format($tab->saldo_awal, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-center border-r border-[--color-border-default]">
                                <p class="text-[10px] uppercase tracking-wider font-semibold text-[--color-text-muted]">
                                    Masuk</p>
                                <p class="text-xs font-bold font-mono text-emerald-600 mt-0.5">
                                    +{{ number_format($tab->tambahan_stok, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-[10px] uppercase tracking-wider font-semibold text-[--color-text-muted]">
                                    Keluar</p>
                                <p class="text-xs font-bold font-mono text-red-500 mt-0.5">
                                    -{{ number_format($tab->jumlah_digunakan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @php $dep = $laporanAktif->get('deposito'); @endphp
            <div
                class="bg-white rounded-2xl border {{ $dep?->status_verifikasi === \App\Enums\StatusVerifikasi::VerifiedAccounting ? 'border-emerald-300 bg-emerald-50/10' : 'border-[--color-border-default]' }} shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <div class="px-5 py-4 {{ $dep ? 'border-b border-slate-100' : '' }} flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center border border-slate-200">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-[--color-text-primary]">Deposito</p>
                    </div>
                    @if ($dep)
                        <span
                            class="text-[10px] uppercase tracking-wider font-bold px-2 py-1 rounded-md border {{ $dep->status_verifikasi->badgeClass() }}">
                            {{ $dep->status_verifikasi->label() }}
                        </span>
                    @else
                        <span
                            class="text-[10px] uppercase font-bold tracking-wider px-2 py-1 rounded-md border bg-slate-100 text-slate-500 border-slate-200">Data
                            Kosong</span>
                    @endif
                </div>

                @if ($dep)
                    <div class="p-5">
                        <div class="mb-5 flex items-end justify-between">
                            <div>
                                <p
                                    class="text-[10px] uppercase tracking-wider font-bold text-[--color-text-muted] mb-1">
                                    Saldo Akhir</p>
                                <p class="text-3xl font-bold font-mono text-[--color-text-primary] tracking-tight">
                                    {{ number_format($dep->saldo_akhir, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-3 gap-2 bg-[--color-bg-subtle] rounded-lg border border-[--color-border-default] p-3">
                            <div class="text-center border-r border-[--color-border-default]">
                                <p
                                    class="text-[10px] uppercase tracking-wider font-semibold text-[--color-text-muted]">
                                    Awal</p>
                                <p class="text-xs font-bold font-mono text-slate-700 mt-0.5">
                                    {{ number_format($dep->saldo_awal, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-center border-r border-[--color-border-default]">
                                <p
                                    class="text-[10px] uppercase tracking-wider font-semibold text-[--color-text-muted]">
                                    Masuk</p>
                                <p class="text-xs font-bold font-mono text-emerald-600 mt-0.5">
                                    +{{ number_format($dep->tambahan_stok, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-center">
                                <p
                                    class="text-[10px] uppercase tracking-wider font-semibold text-[--color-text-muted]">
                                    Keluar</p>
                                <p class="text-xs font-bold font-mono text-red-500 mt-0.5">
                                    -{{ number_format($dep->jumlah_digunakan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        {{-- Jika Tidak Ada Periode Aktif --}}
        <div class="bg-white rounded-2xl border border-[--color-border-default] shadow-sm p-10 text-center mb-10">
            <div
                class="w-20 h-20 bg-[--color-bg-inset] border border-[--color-border-default] rounded-3xl flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-[--color-text-muted]" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-[--color-text-primary] mb-2">Belum Ada Periode Aktif</h2>
            <p class="text-sm text-[--color-text-muted] max-w-md mx-auto">Belum ada periode laporan yang berjalan saat
                ini.</p>
        </div>
    @endif

    {{-- Riwayat Singkat --}}
    @if ($riwayatSingkat->isNotEmpty())
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold text-[--color-text-primary]">Riwayat Laporan Sebelumnya</h2>
            <a href="{{ route('pic.riwayat.index') }}"
                class="flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="bg-white rounded-xl border border-[--color-border-default] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[--color-bg-subtle] border-b border-[--color-border-default]">
                            <th
                                class="px-5 py-3.5 text-left text-[10px] font-bold text-[--color-text-muted] uppercase tracking-wider">
                                Periode</th>
                            <th
                                class="px-5 py-3.5 text-center text-[10px] font-bold text-[--color-text-muted] uppercase tracking-wider w-32">
                                Tabungan</th>
                            <th
                                class="px-5 py-3.5 text-center text-[10px] font-bold text-[--color-text-muted] uppercase tracking-wider w-32">
                                Deposito</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[--color-border-default]">
                        @foreach ($riwayatSingkat as $p)
                            @php
                                $t = $p->laporans->firstWhere('jenis', \App\Enums\JenisLaporan::Tabungan);
                                $d = $p->laporans->firstWhere('jenis', \App\Enums\JenisLaporan::Deposito);
                            @endphp
                            <tr class="hover:bg-[--color-bg-subtle] transition-colors duration-150 group">
                                <td class="px-5 py-4">
                                    <p class="text-xs font-bold text-[--color-text-primary]">{{ $p->nama_periode }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span
                                            class="text-[10px] text-[--color-text-muted] font-mono">{{ $p->tanggal_akhir->format('d/m/Y') }}</span>
                                        <span
                                            class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded border {{ $p->status_operasional->badgeClass() }}">
                                            {{ $p->status_operasional->label() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if ($t)
                                        <p class="font-mono text-sm font-bold text-[--color-text-primary]">
                                            {{ number_format($t->saldo_akhir, 0, ',', '.') }}</p>
                                        <span
                                            class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded border {{ $t->status_verifikasi->badgeClass() }} mt-1 inline-block">
                                            {{ $t->status_verifikasi->label() }}
                                        </span>
                                    @else
                                        <span class="text-[--color-text-disabled] text-xs font-mono">&mdash;</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if ($d)
                                        <p class="font-mono text-sm font-bold text-[--color-text-primary]">
                                            {{ number_format($d->saldo_akhir, 0, ',', '.') }}</p>
                                        <span
                                            class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded border {{ $d->status_verifikasi->badgeClass() }} mt-1 inline-block">
                                            {{ $d->status_verifikasi->label() }}
                                        </span>
                                    @else
                                        <span class="text-[--color-text-disabled] text-xs font-mono">&mdash;</span>
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
