<x-app-layout :title="$title" :subtitle="$subtitle">

    <div class="max-w-7xl mx-auto pb-6">

        {{-- Header Navigation & Title --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <div
                    class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0 shadow-inner">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Siklus Laporan</h2>
                    <p class="text-xs text-slate-500">Kelola periode pelaporan dan pantau progres verifikasi cabang.</p>
                </div>
            </div>

            {{-- Tombol Generate Utama --}}
            <a href="{{ route('admin.periode.create') }}"
                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition-all shadow shadow-indigo-200 focus:ring-4 focus:ring-indigo-100 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Generate Periode Baru
            </a>
        </div>

        {{-- ═══ BANNER SARAN PERIODE ═══ --}}
        @if ($suggestion)
            <div x-data="{ dismissed: false }" x-show="!dismissed" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-4 rounded-xl overflow-hidden shadow-sm relative border {{ $suggestion['is_overdue'] ? 'border-rose-200 bg-rose-50/50' : 'border-indigo-200 bg-indigo-50/50' }}">

                {{-- Decorative background element --}}
                <div
                    class="absolute right-0 top-0 h-full w-1/3 bg-white opacity-20 transform skew-x-12 translate-x-10 pointer-events-none">
                </div>

                <div class="px-4 py-3 flex flex-col md:flex-row md:items-center justify-between gap-3 relative z-10">
                    <div class="flex items-start md:items-center gap-3">
                        {{-- Icon --}}
                        <div
                            class="w-9 h-9 flex-shrink-0 rounded-xl flex items-center justify-center shadow-inner
                                {{ $suggestion['is_overdue'] ? 'bg-rose-100 text-rose-600' : 'bg-indigo-100 text-indigo-600' }}">
                            @if ($suggestion['is_overdue'])
                                <svg class="w-4.5 h-4.5 animate-pulse" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            @else
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            @endif
                        </div>

                        {{-- Teks --}}
                        <div class="flex-1 min-w-0">
                            @if ($suggestion['is_overdue'])
                                <h3 class="text-sm font-black text-rose-900 tracking-tight">Perhatian: Jadwal Periode
                                    Terlewati!</h3>
                                <p class="text-xs text-rose-700 mt-0.5 leading-relaxed">
                                    Berdasarkan siklus terakhir (<strong
                                        class="font-bold">{{ $suggestion['prev_periode'] }}</strong>), periode
                                    <strong
                                        class="font-bold bg-white/60 px-1 py-0.5 rounded">{{ $suggestion['nama_periode'] }}</strong>
                                    ({{ $suggestion['display'] }}) seharusnya sudah dibuat
                                    <span class="text-rose-600 font-bold underline">{{ $suggestion['days_diff'] }} hari
                                        yang lalu</span>.
                                </p>
                            @else
                                <h3 class="text-sm font-black text-indigo-900 tracking-tight">Saran Sistem: Jadwal
                                    Tepat Waktu</h3>
                                <p class="text-xs text-indigo-700 mt-0.5 leading-relaxed">
                                    Berdasarkan siklus sebelumnya (<strong
                                        class="font-bold">{{ $suggestion['prev_periode'] }}</strong>), ini adalah saat
                                    yang tepat untuk membuka periode
                                    <strong
                                        class="font-bold bg-white/60 px-1 py-0.5 rounded">{{ $suggestion['nama_periode'] }}</strong>
                                    untuk tanggal ({{ $suggestion['display'] }}).
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div
                        class="flex items-center gap-2 flex-shrink-0 md:pl-3 md:border-l {{ $suggestion['is_overdue'] ? 'border-rose-200' : 'border-indigo-200' }}">
                        <form method="POST" action="{{ route('admin.periode.generate-suggested') }}">
                            @csrf
                            <input type="hidden" name="tanggal_akhir" value="{{ $suggestion['tanggal'] }}">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5
                                       {{ $suggestion['is_overdue'] ? 'bg-rose-600 hover:bg-rose-700 shadow-rose-200' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200' }}
                                       text-white text-xs font-bold rounded-lg shadow-sm transition-all focus:ring-4 focus:outline-none">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Generate Sekarang
                            </button>
                        </form>

                        <button type="button" @click="dismissed = true" title="Tutup info ini"
                            class="p-1.5 rounded-lg transition-colors bg-white/50 hover:bg-white
                                   {{ $suggestion['is_overdue'] ? 'text-rose-500 hover:text-rose-700 border border-rose-200' : 'text-indigo-500 hover:text-indigo-700 border border-indigo-200' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- ═══ FILTER BAR ═══ --}}
        <div
            class="mb-4 bg-white p-3 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3">
            <form method="GET" action="{{ route('admin.periode.index') }}"
                class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                <div
                    class="flex items-center gap-1.5 w-full sm:w-auto text-xs font-bold text-slate-500 uppercase tracking-wider pl-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter:
                </div>

                <div class="grid grid-cols-2 sm:flex gap-2 w-full sm:w-auto">
                    <select name="tahun"
                        class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer w-full sm:w-36">
                        <option value="">Semua Tahun</option>
                        @foreach ($tahunList as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>Tahun
                                {{ $t }}</option>
                        @endforeach
                    </select>

                    <select name="status"
                        class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer w-full sm:w-44">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Proses Verifikasi
                        </option>
                        <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Final / Selesai
                        </option>
                    </select>
                </div>

                <div class="flex items-center gap-1.5 w-full sm:w-auto">
                    <button type="submit"
                        class="w-full sm:w-auto px-4 py-1.5 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-900 transition-colors shadow-sm whitespace-nowrap">
                        Terapkan
                    </button>
                    @if ($tahun || $status)
                        <a href="{{ route('admin.periode.index') }}"
                            class="w-full sm:w-auto px-3 py-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors text-center whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="text-xs font-medium text-slate-500 px-1">
                Menampilkan <span class="font-bold text-slate-800">{{ $periodes->total() }}</span> siklus
            </div>
        </div>

        {{-- ═══ DATA TABLE ═══ --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Info
                                Periode</th>
                            <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-32">
                                Tanggal Berakhir</th>
                            <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-60">
                                Progres Verifikasi Cabang</th>
                            <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-36">Status
                            </th>
                            <th
                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center w-28">
                                Indikator</th>
                            <th
                                class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right w-28">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($periodes as $periode)
                            <tr
                                class="group transition-colors {{ $periode->is_current ? 'bg-indigo-50/40 hover:bg-indigo-50/60' : 'hover:bg-slate-50/80' }}">

                                {{-- Nama Periode --}}
                                <td class="px-4 py-3">
                                    <p class="text-sm font-black text-slate-800 mb-0.5">{{ $periode->nama_periode }}
                                    </p>
                                    <div class="flex items-center gap-1 text-[10px] font-medium text-slate-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $periode->total_verified ?? 0 }} dari {{ $periode->total_cabang ?? 0 }}
                                        Cabang Selesai
                                    </div>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold font-mono bg-slate-100 text-slate-600 border border-slate-200">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $periode->tanggal_akhir->format('d/m/Y') }}
                                    </span>
                                </td>

                                {{-- Progress Bar --}}
                                <td class="px-5 py-3">
                                    @php
                                        $total = $periode->total_cabang ?? 0;
                                        $verified = $periode->total_verified ?? 0;
                                        $pct = $total > 0 ? round(($verified / $total) * 100) : 0;
                                        $isComplete = $pct === 100;
                                    @endphp
                                    <div class="flex items-center justify-between mb-0.5">
                                        <span
                                            class="text-[9px] font-bold uppercase tracking-wider {{ $isComplete ? 'text-emerald-600' : 'text-indigo-600' }}">
                                            {{ $isComplete ? 'Selesai' : 'Diproses' }}
                                        </span>
                                        <span
                                            class="text-[10px] font-bold {{ $isComplete ? 'text-emerald-700' : 'text-slate-700' }}">{{ $pct }}%</span>
                                    </div>
                                    <div
                                        class="w-full bg-slate-200 rounded-full h-2 overflow-hidden border border-slate-300/50">
                                        <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $isComplete ? 'bg-emerald-500' : 'bg-indigo-500' }} relative"
                                            style="width: {{ $pct }}%">
                                            @if (!$isComplete && $pct > 10)
                                                <div
                                                    class="absolute inset-0 bg-white/20 w-1/2 translate-x-full skew-x-12 animate-[shimmer_2s_infinite]">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold border {{ $periode->status_operasional->badgeClass() }}">
                                        {{ $periode->status_operasional->label() }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if ($periode->is_current)
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black bg-indigo-100 text-indigo-700 border border-indigo-200 shadow-sm">
                                            <span class="relative flex h-1.5 w-1.5">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-1.5 w-1.5 bg-indigo-600"></span>
                                            </span>
                                            AKTIF
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-xs font-bold">—</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('admin.periode.show', $periode) }}"
                                        class="inline-flex items-center justify-center gap-1 px-3 py-1.5 bg-white hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 text-[10px] text-slate-700 hover:text-indigo-700 font-bold rounded-lg transition-all shadow-sm group-hover:shadow focus:ring-2 focus:ring-indigo-100">
                                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-indigo-500 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Buka Detail
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-7 h-7 text-slate-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-800 mb-0.5">Belum Ada Siklus Periode</h3>
                                        <p class="text-xs text-slate-500 mb-4">Sistem ini membutuhkan periode untuk
                                            mulai mencatat dan menerima laporan dari cabang.</p>
                                        <a href="{{ route('admin.periode.create') }}"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 text-indigo-700 font-bold rounded-lg hover:bg-indigo-100 transition-colors text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2.5" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Generate Periode Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($periodes->hasPages())
                <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50">
                    {{ $periodes->links() }}
                </div>
            @endif
        </div>

    </div>

    <style>
        @keyframes shimmer {
            100% {
                transform: translateX(100%) skewX(-12deg);
            }
        }
    </style>

</x-app-layout>
