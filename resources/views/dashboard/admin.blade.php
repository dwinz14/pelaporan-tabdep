<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- ═══ TOP STATS WIDGETS ═══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        {{-- Stat: Cabang Aktif --}}
        <div
            class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex items-center gap-5 transition-transform hover:-translate-y-1 duration-300">
            <div
                class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider font-bold mb-1">Cabang Aktif</p>
                <p class="text-3xl font-black text-slate-900 font-mono leading-none">{{ $totalCabang }}</p>
            </div>
        </div>

        {{-- Stat: Total User --}}
        <div
            class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex items-center gap-5 transition-transform hover:-translate-y-1 duration-300">
            <div
                class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider font-bold mb-1">Total User</p>
                <p class="text-3xl font-black text-slate-900 font-mono leading-none">{{ $totalUser }}</p>
            </div>
        </div>

        {{-- Stat: Total Periode --}}
        <div
            class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex items-center gap-5 transition-transform hover:-translate-y-1 duration-300">
            <div
                class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider font-bold mb-1">Total Periode</p>
                <p class="text-3xl font-black text-slate-900 font-mono leading-none">{{ $totalPeriode }}</p>
            </div>
        </div>

        {{-- Stat: Periode Aktif (Highlighted) --}}
        <div
            class="rounded-3xl p-6 shadow-lg shadow-indigo-900/10 flex flex-col justify-center relative overflow-hidden bg-slate-900 border border-slate-800 transition-transform hover:-translate-y-1 duration-300">
            {{-- Glow background --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="relative z-5 flex items-start gap-4">
                <div
                    class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] text-slate-400 uppercase tracking-wider font-bold mb-1">Periode Aktif Saat Ini
                    </p>
                    @if ($periodeAktif)
                        <p class="text-lg font-black text-white leading-tight tracking-wide">
                            {{ $periodeAktif->nama_periode }}</p>
                    @else
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-400/10 text-amber-400 border border-amber-400/20 mt-1">
                            Belum Ada Periode Aktif
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ MAIN DASHBOARD CONTENT ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Column 1: Quick Actions --}}
        <div class="flex flex-col gap-6">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Aksi Cepat</h3>
                </div>
                <div class="p-4 space-y-2">
                    @php
                        $actions = [
                            [
                                'route' => 'admin.cabang.index',
                                'label' => 'Kelola Cabang',
                                'sub' => $totalCabang . ' cabang terdaftar',
                                'icon' =>
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />',
                            ],
                            [
                                'route' => 'admin.user.index',
                                'label' => 'Kelola User',
                                'sub' => $totalUser . ' pengguna sistem',
                                'icon' =>
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />',
                            ],
                            [
                                'route' => 'admin.periode.create',
                                'label' => 'Generate Periode',
                                'sub' => 'Buka periode pelaporan baru',
                                'icon' =>
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />',
                            ],
                            [
                                'route' => 'admin.audit.index',
                                'label' => 'Audit Trail',
                                'sub' => 'Log aktivitas seluruh sistem',
                                'icon' =>
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />',
                            ],
                        ];
                    @endphp

                    @foreach ($actions as $item)
                        <a href="{{ route($item['route']) }}"
                            class="group flex items-center justify-between p-3.5 rounded-2xl hover:bg-indigo-50 border border-transparent hover:border-indigo-100 transition-all duration-200">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 group-hover:bg-white group-hover:text-indigo-600 group-hover:shadow-sm flex items-center justify-center transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">{!! $item['icon'] !!}</svg>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-bold text-slate-800 group-hover:text-indigo-700 transition-colors">
                                        {{ $item['label'] }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $item['sub'] }}</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Column 2 & 3: Saldo Terkini --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex-1 flex flex-col">
                {{-- Header --}}
                <div
                    class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            Overview Saldo Cabang
                        </h3>
                        <p class="text-sm text-slate-500 mt-1">Status dan saldo stok fisik periode terakhir per cabang.
                        </p>
                    </div>
                </div>

                {{-- Table Area --}}
                <div class="flex-1 flex flex-col">
                    @if ($saldoTerkini->isEmpty())
                        <div class="py-16 flex flex-col items-center justify-center text-center px-4 flex-1">
                            <div
                                class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Data Laporan</h3>
                            <p class="text-sm text-slate-500 max-w-sm">Data saldo akan muncul di sini ketika ada cabang
                                yang melakukan pelaporan.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100">
                                        <th
                                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">
                                            Cabang</th>
                                        <th
                                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right whitespace-nowrap">
                                            Tabungan</th>
                                        <th
                                            class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right whitespace-nowrap">
                                            Deposito</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($saldoTerkini as $row)
                                        <tr class="hover:bg-slate-50/80 transition-colors group">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <span
                                                        class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 text-slate-500 font-mono text-xs font-bold border border-slate-200">
                                                        {{ $row['cabang']->kode_cabang }}
                                                    </span>
                                                    <span
                                                        class="font-semibold text-slate-700 group-hover:text-indigo-600 transition-colors">
                                                        {{ $row['cabang']->nama_cabang }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex flex-col items-end gap-1.5">
                                                    <span class="font-mono text-base font-bold text-slate-800">
                                                        {{ number_format($row['saldo_tab'], 0, ',', '.') }}
                                                    </span>
                                                    @if ($row['status_tab'])
                                                        <span
                                                            class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $row['status_tab']->badgeClass() }}">
                                                            {{ $row['status_tab']->label() }}
                                                        </span>
                                                    @else
                                                        <span class="text-[10px] font-medium text-slate-400">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex flex-col items-end gap-1.5">
                                                    <span class="font-mono text-base font-bold text-slate-800">
                                                        {{ number_format($row['saldo_dep'], 0, ',', '.') }}
                                                    </span>
                                                    @if ($row['status_dep'])
                                                        <span
                                                            class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $row['status_dep']->badgeClass() }}">
                                                            {{ $row['status_dep']->label() }}
                                                        </span>
                                                    @else
                                                        <span class="text-[10px] font-medium text-slate-400">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
