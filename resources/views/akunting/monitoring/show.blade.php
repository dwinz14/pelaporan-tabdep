<x-app-layout :title="$title" :subtitle="$subtitle">
@php
    $cabang      = $detail['cabang'];
    $saldo       = $detail['saldo'];
    $pencatatan  = $detail['pencatatan_30_hari'];
    $pergerakan  = $detail['pergerakan_harian'];
    $riwayat     = $detail['riwayat_periode'];
    $bTab        = $detail['breakdown_tab'];
    $bDep        = $detail['breakdown_dep'];
    $periodeAktif = $detail['periode_aktif'];
    $laporanAktif = $detail['laporan_aktif'];

    $laporanTab = $laporanAktif->get('tabungan');
    $laporanDep = $laporanAktif->get('deposito');
@endphp

    <div class="max-w-7xl mx-auto pb-8 space-y-5 antialiased font-sans">
        
        {{-- ═══ HEADER & BREADCRUMB ═══ --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden">
            <!-- Dekorasi aksen tipis di background -->
            <div class="absolute top-0 right-0 w-64 h-full bg-gradient-to-l from-gray-50 to-transparent -z-10"></div>
            
            <div>
                <nav class="flex text-sm text-gray-500 font-medium mb-1.5">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <a href="{{ route('akunting.monitoring.index') }}" class="hover:text-blue-600 transition-colors inline-flex items-center">
                                Monitoring Stok
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                <span class="text-gray-800 font-semibold tracking-tight">{{ $cabang->kode_cabang }} — {{ $cabang->nama_cabang }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <p class="text-[11px] text-gray-400">Pembaruan data pergerakan fisik buku secara real-time.</p>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('akunting.monitoring.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                    Kembali
                </a>
                <button onclick="window.location.reload()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-all shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Segarkan
                </button>
            </div>
        </div>

        {{-- ═══ HERO SECTION: SALDO TERKINI ═══ --}}
        <div class="grid lg:grid-cols-2 gap-5">

            {{-- Card Tabungan --}}
            <div class="bg-gradient-to-br from-white to-blue-50/30 rounded-xl border-x border-b border-gray-200 border-t-4 border-t-blue-500 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden relative">
                <!-- Ornamen visual halus -->
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-100/50 rounded-full blur-2xl pointer-events-none"></div>

                <div class="p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg shadow-inner">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Stok Tabungan</h2>
                                <p class="text-[10px] text-gray-400">Estimasi Fisik Terkini</p>
                            </div>
                        </div>
                        @if($laporanTab)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100 shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 animate-pulse"></span>
                                {{ $laporanTab->status_verifikasi->label() }}
                            </span>
                        @endif
                    </div>

                    <div class="my-4">
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight tabular-nums">
                            {{ number_format($saldo['tabungan']['terkini'], 0, ',', '.') }}
                        </h1>
                        <div class="flex items-center gap-1.5 mt-1.5 text-xs text-gray-500">
                            <span>Laporan Terakhir: <strong class="text-gray-700">{{ number_format($saldo['tabungan']['base'], 0, ',', '.') }}</strong></span>
                            @if($saldo['tabungan']['lock_at'])
                                <span class="text-gray-300">•</span>
                                <span>Per {{ \Carbon\Carbon::parse($saldo['tabungan']['lock_at'])->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Footer Stats --}}
                <div class="bg-white/60 backdrop-blur-sm border-t border-blue-100/50 p-4 grid grid-cols-3 divide-x divide-gray-100">
                    <div class="px-2 text-center">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase mb-1">Masuk</p>
                        <p class="text-sm font-bold text-emerald-600 tabular-nums">
                            +{{ number_format($saldo['tabungan']['masuk'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="px-2 text-center">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase mb-1">Keluar</p>
                        <p class="text-sm font-bold text-rose-600 tabular-nums">
                            -{{ number_format($saldo['tabungan']['keluar'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="px-2 text-center">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase mb-1">Selisih</p>
                        @php $netTab = $saldo['tabungan']['masuk'] - $saldo['tabungan']['keluar']; @endphp
                        <p class="text-sm font-bold tabular-nums {{ $netTab >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $netTab >= 0 ? '+' : '' }}{{ number_format($netTab, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Card Deposito --}}
            <div class="bg-gradient-to-br from-white to-slate-50/50 rounded-xl border-x border-b border-gray-200 border-t-4 border-t-slate-700 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden relative">
                <!-- Ornamen visual halus -->
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-slate-200/40 rounded-full blur-2xl pointer-events-none"></div>

                <div class="p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 bg-slate-100 text-slate-700 rounded-lg shadow-inner">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Stok Deposito</h2>
                                <p class="text-[10px] text-gray-400">Estimasi Fisik Terkini</p>
                            </div>
                        </div>
                        @if($laporanDep)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500 mr-1.5 animate-pulse"></span>
                                {{ $laporanDep->status_verifikasi->label() }}
                            </span>
                        @endif
                    </div>

                    <div class="my-4">
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight tabular-nums">
                            {{ number_format($saldo['deposito']['terkini'], 0, ',', '.') }}
                        </h1>
                        <div class="flex items-center gap-1.5 mt-1.5 text-xs text-gray-500">
                            <span>Laporan Terakhir: <strong class="text-gray-700">{{ number_format($saldo['deposito']['base'], 0, ',', '.') }}</strong></span>
                            @if($saldo['deposito']['lock_at'])
                                <span class="text-gray-300">•</span>
                                <span>Per {{ \Carbon\Carbon::parse($saldo['deposito']['lock_at'])->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Footer Stats --}}
                <div class="bg-white/60 backdrop-blur-sm border-t border-slate-100/50 p-4 grid grid-cols-3 divide-x divide-gray-100">
                    <div class="px-2 text-center">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase mb-1">Masuk</p>
                        <p class="text-sm font-bold text-emerald-600 tabular-nums">
                            +{{ number_format($saldo['deposito']['masuk'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="px-2 text-center">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase mb-1">Keluar</p>
                        <p class="text-sm font-bold text-rose-600 tabular-nums">
                            -{{ number_format($saldo['deposito']['keluar'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="px-2 text-center">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase mb-1">Selisih</p>
                        @php $netDep = $saldo['deposito']['masuk'] - $saldo['deposito']['keluar']; @endphp
                        <p class="text-sm font-bold tabular-nums {{ $netDep >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $netDep >= 0 ? '+' : '' }}{{ number_format($netDep, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ MODERN CHART: PERGERAKAN 14 HARI ═══ --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 bg-gray-50/50 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="p-1.5 bg-white border border-gray-200 rounded text-gray-500 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Trend Pergerakan (14 Hari)</h3>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white px-2.5 py-1.5 rounded-md border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-gray-600">
                        <span class="w-2 h-2 bg-emerald-500 rounded-sm"></span> Masuk
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-gray-600">
                        <span class="w-2 h-2 bg-rose-400 rounded-sm"></span> Keluar
                    </div>
                </div>
            </div>

            @php
                $maxVal = max(1, collect($pergerakan)->max(fn ($d) => max(
                    $d['masuk_tab'] + $d['masuk_dep'],
                    $d['keluar_tab'] + $d['keluar_dep']
                )));
            @endphp

            <div class="p-5 grid lg:grid-cols-2 gap-6 lg:gap-10">
                {{-- Chart Tabungan --}}
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">Tabungan</p>
                        <div class="border-t border-dashed border-gray-200 flex-grow mx-3"></div>
                    </div>
                    <div class="flex items-end gap-1.5 h-24 relative">
                        {{-- Background Grid Lines --}}
                        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                            <div class="border-t border-gray-50 w-full h-0"></div>
                            <div class="border-t border-gray-50 w-full h-0"></div>
                            <div class="border-t border-gray-100 w-full h-0"></div>
                        </div>

                        @foreach($pergerakan as $hari)
                            @php
                                $masukPct  = $maxVal > 0 ? ($hari['masuk_tab']  / $maxVal) * 100 : 0;
                                $keluarPct = $maxVal > 0 ? ($hari['keluar_tab'] / $maxVal) * 100 : 0;
                                $hasData   = $hari['masuk_tab'] > 0 || $hari['keluar_tab'] > 0;
                            @endphp
                            <div class="flex-1 flex flex-col items-center gap-1 group relative z-10 cursor-pointer h-full justify-end">
                                <div class="w-full max-w-[16px] flex items-end justify-center gap-[1px] h-20 relative">
                                    <div class="flex-1 bg-emerald-500 rounded-t-sm transition-all duration-300 group-hover:brightness-110" style="height: {{ max($masukPct, $hasData ? 2 : 0) }}%"></div>
                                    <div class="flex-1 bg-rose-400 rounded-t-sm transition-all duration-300 group-hover:brightness-110" style="height: {{ max($keluarPct, $hasData ? 2 : 0) }}%"></div>
                                </div>
                                <p class="text-[9px] text-gray-400 {{ $hari['tanggal'] === now()->format('Y-m-d') ? 'font-bold text-blue-600 bg-blue-50 px-1 rounded' : '' }} whitespace-nowrap">
                                    {{ $hari['label'] }}
                                </p>

                                {{-- Tooltip --}}
                                @if($hasData)
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 bg-gray-900 text-white text-[11px] rounded-md px-2.5 py-1.5 opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50 shadow-lg transform translate-y-1 group-hover:translate-y-0">
                                        <p class="font-semibold text-gray-300 mb-0.5">{{ \Carbon\Carbon::parse($hari['tanggal'])->format('d M Y') }}</p>
                                        <div class="flex justify-between gap-3">
                                            <span class="text-emerald-400">Masuk: +{{ $hari['masuk_tab'] }}</span>
                                            <span class="text-rose-400">Keluar: -{{ $hari['keluar_tab'] }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Chart Deposito --}}
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">Deposito</p>
                        <div class="border-t border-dashed border-gray-200 flex-grow mx-3"></div>
                    </div>
                    <div class="flex items-end gap-1.5 h-24 relative">
                        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                            <div class="border-t border-gray-50 w-full h-0"></div>
                            <div class="border-t border-gray-50 w-full h-0"></div>
                            <div class="border-t border-gray-100 w-full h-0"></div>
                        </div>

                        @foreach($pergerakan as $hari)
                            @php
                                $masukPct  = $maxVal > 0 ? ($hari['masuk_dep']  / $maxVal) * 100 : 0;
                                $keluarPct = $maxVal > 0 ? ($hari['keluar_dep'] / $maxVal) * 100 : 0;
                                $hasData   = $hari['masuk_dep'] > 0 || $hari['keluar_dep'] > 0;
                            @endphp
                            <div class="flex-1 flex flex-col items-center gap-1 group relative z-10 cursor-pointer h-full justify-end">
                                <div class="w-full max-w-[16px] flex items-end justify-center gap-[1px] h-20 relative">
                                    <div class="flex-1 bg-emerald-400 rounded-t-sm transition-all duration-300 group-hover:brightness-110" style="height: {{ max($masukPct, $hasData ? 2 : 0) }}%"></div>
                                    <div class="flex-1 bg-rose-300 rounded-t-sm transition-all duration-300 group-hover:brightness-110" style="height: {{ max($keluarPct, $hasData ? 2 : 0) }}%"></div>
                                </div>
                                <p class="text-[9px] text-gray-400 {{ $hari['tanggal'] === now()->format('Y-m-d') ? 'font-bold text-slate-700 bg-slate-100 px-1 rounded' : '' }} whitespace-nowrap">
                                    {{ $hari['label'] }}
                                </p>

                                @if($hasData)
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 bg-gray-900 text-white text-[11px] rounded-md px-2.5 py-1.5 opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50 shadow-lg transform translate-y-1 group-hover:translate-y-0">
                                        <p class="font-semibold text-gray-300 mb-0.5">{{ \Carbon\Carbon::parse($hari['tanggal'])->format('d M Y') }}</p>
                                        <div class="flex justify-between gap-3">
                                            <span class="text-emerald-400">Masuk: +{{ $hari['masuk_dep'] }}</span>
                                            <span class="text-rose-400">Keluar: -{{ $hari['keluar_dep'] }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ SPLIT GRID: BREAKDOWN & HISTORY ═══ --}}
        <div class="grid lg:grid-cols-2 gap-5">

            {{-- Kolom Kiri: Breakdown 30 Hari --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col overflow-hidden">
                <div class="px-5 py-4 bg-gray-50/50 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800">Rincian Transaksi (30 Hari)</h3>
                </div>
                
                <div class="p-5 grid sm:grid-cols-2 gap-5 flex-grow">
                    {{-- Breakdown Tabungan --}}
                    <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:border-blue-200 transition-colors">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            <p class="text-xs font-bold text-gray-700 uppercase tracking-wide">Tabungan</p>
                        </div>
                        <ul class="space-y-2.5">
                            @foreach([
                                ['label' => 'Tambahan Stok', 'value' => $bTab['tambahan'],  'color' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'sign' => '+'],
                                ['label' => 'Digunakan',      'value' => $bTab['digunakan'], 'color' => 'text-blue-700',    'bg' => 'bg-blue-50',    'sign' => '-'],
                                ['label' => 'Batal Rusak',    'value' => $bTab['rusak'],     'color' => 'text-amber-700',  'bg' => 'bg-amber-50',  'sign' => '-'],
                                ['label' => 'Batal Hilang',   'value' => $bTab['hilang'],    'color' => 'text-rose-700',     'bg' => 'bg-rose-50',     'sign' => '-'],
                            ] as $item)
                                <li class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">{{ $item['label'] }}</span>
                                    <span class="font-mono font-bold text-xs {{ $item['color'] }} {{ $item['bg'] }} px-1.5 py-0.5 rounded">
                                        {{ $item['sign'] }}{{ number_format($item['value'], 0, ',', '.') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3 pt-3 border-t border-dashed border-gray-200 flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-600">Total</span>
                            <span class="text-sm font-black text-gray-900 tabular-nums">{{ number_format($bTab['total'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Breakdown Deposito --}}
                    <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:border-slate-300 transition-colors">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                            <p class="text-xs font-bold text-gray-700 uppercase tracking-wide">Deposito</p>
                        </div>
                        <ul class="space-y-2.5">
                            @foreach([
                                ['label' => 'Tambahan Stok', 'value' => $bDep['tambahan'],  'color' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'sign' => '+'],
                                ['label' => 'Digunakan',      'value' => $bDep['digunakan'], 'color' => 'text-blue-700',    'bg' => 'bg-blue-50',    'sign' => '-'],
                                ['label' => 'Batal Rusak',    'value' => $bDep['rusak'],     'color' => 'text-amber-700',  'bg' => 'bg-amber-50',  'sign' => '-'],
                                ['label' => 'Batal Hilang',   'value' => $bDep['hilang'],    'color' => 'text-rose-700',     'bg' => 'bg-rose-50',     'sign' => '-'],
                            ] as $item)
                                <li class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">{{ $item['label'] }}</span>
                                    <span class="font-mono font-bold text-xs {{ $item['color'] }} {{ $item['bg'] }} px-1.5 py-0.5 rounded">
                                        {{ $item['sign'] }}{{ number_format($item['value'], 0, ',', '.') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3 pt-3 border-t border-dashed border-gray-200 flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-600">Total</span>
                            <span class="text-sm font-black text-gray-900 tabular-nums">{{ number_format($bDep['total'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Riwayat Periode --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col overflow-hidden">
                <div class="px-5 py-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-800">Riwayat 5 Periode Laporan</h3>
                    <div class="text-[10px] text-gray-400 font-medium">History</div>
                </div>
                
                <div class="flex-grow flex flex-col">
                    @if($riwayat->isEmpty())
                        <div class="flex-grow flex flex-col items-center justify-center p-8 text-center">
                            <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p class="text-xs font-medium text-gray-400">Belum ada riwayat laporan periode.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-100 flex-grow">
                            @foreach($riwayat as $r)
                                <li class="px-5 py-3 hover:bg-gray-50/80 transition-colors">
                                    <div class="flex items-center justify-between mb-2.5">
                                        <p class="text-sm font-bold text-gray-800">{{ $r['periode']->nama_periode }}</p>
                                        <span class="text-[9px] uppercase tracking-wider {{ $r['periode']->status_operasional->badgeClass() }} px-2 py-0.5 rounded font-bold border">
                                            {{ $r['periode']->status_operasional->label() }}
                                        </span>
                                    </div>
                                    <div class="flex gap-3">
                                        @foreach([
                                            ['label' => 'Tabungan', 'icon' => 'bg-blue-100 text-blue-600 border-blue-100', 'laporan' => $r['tabungan']],
                                            ['label' => 'Deposito', 'icon' => 'bg-slate-100 text-slate-600 border-slate-100', 'laporan' => $r['deposito']],
                                        ] as $item)
                                            @if($item['laporan'])
                                                <div class="flex-1 bg-white border border-gray-100 rounded-lg p-2.5 shadow-sm">
                                                    <div class="flex items-center justify-between mb-1.5">
                                                        <div class="flex items-center gap-1.5">
                                                            <div class="w-1.5 h-1.5 rounded-full {{ explode(' ', $item['icon'])[0] }}"></div>
                                                            <span class="text-[9px] text-gray-500 font-bold uppercase tracking-wide">{{ $item['label'] }}</span>
                                                        </div>
                                                        <span class="text-[9px] {{ $item['laporan']->status_verifikasi->badgeClass() }} px-1.5 py-0.5 rounded font-medium">
                                                            {{ $item['laporan']->status_verifikasi->label() }}
                                                        </span>
                                                    </div>
                                                    <p class="font-mono text-sm font-black text-gray-900 tabular-nums">{{ number_format($item['laporan']->saldo_akhir, 0, ',', '.') }}</p>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══ DATA TABLE: LOG PENCATATAN ═══ --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 bg-gray-50/50 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h3 class="text-sm font-bold text-gray-800">Log Transaksi Berjalan (30 Hari)</h3>
                <div class="px-2.5 py-1 bg-white border border-gray-200 rounded-md shadow-sm">
                    <span class="text-xs font-bold text-gray-700">{{ $pencatatan->count() }}</span>
                    <span class="text-[11px] text-gray-500"> Entri Data</span>
                </div>
            </div>

            @if($pencatatan->isEmpty())
                <div class="flex flex-col items-center justify-center p-10 text-center">
                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-sm font-semibold text-gray-600">Belum Ada Transaksi</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white border-b border-gray-100">
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider w-28">Waktu</th>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider w-28">Kategori</th>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Aktivitas</th>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-right w-32">Nominal</th>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider w-36">User</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pencatatan as $p)
                                <tr class="hover:bg-blue-50/40 even:bg-gray-50/30 transition-colors group">
                                    <td class="px-5 py-3">
                                        <div class="text-xs font-semibold text-gray-900">{{ $p->tanggal_catat->format('d/m/Y') }}</div>
                                        <div class="text-[10px] text-gray-400 font-mono">{{ $p->tanggal_catat->format('H:i') }}</div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide
                                                     {{ $p->jenis->value === 'tabungan' ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $p->jenis->label() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex text-[10px] font-bold px-2 py-1 rounded shadow-sm border {{ $p->tipe_transaksi->badgeClass() }}">
                                            {{ $p->tipe_transaksi->labelShort() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <span class="font-mono font-black text-sm tabular-nums
                                                     {{ $p->tipe_transaksi->isAddition() ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $p->tipe_transaksi->isAddition() ? '+' : '−' }}{{ number_format($p->jumlah, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-gray-500 max-w-[200px] truncate group-hover:text-gray-900 transition-colors" title="{{ $p->keterangan ?? 'Tidak ada keterangan' }}">
                                        {{ $p->keterangan ?? '—' }}
                                    </td>
                                    <td class="px-5 py-3 text-xs">
                                        <div class="flex items-center gap-2">
                                            <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-[9px] font-bold">
                                                {{ substr($p->createdBy->name ?? '?', 0, 1) }}
                                            </div>
                                            <span class="text-gray-600 font-medium truncate w-24">{{ $p->createdBy->name ?? 'Sistem' }}</span>
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
</x-app-layout>
