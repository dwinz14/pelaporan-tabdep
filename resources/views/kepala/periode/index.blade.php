<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Wrapper Utama dengan Alpine.js untuk sistem Tab --}}
    <div class="max-w-6xl mx-auto space-y-6" x-data="{ activeTab: 'pending' }" x-cloak>

        {{-- ═══ HEADER & TAB NAVIGATION ═══ --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <h1 class="text-2xl sm:text-2xl font-bold text-slate-900 tracking-tight">Verifikasi Final Periode</h1>
                <p class="text-sm text-slate-500 mt-1.5">Kelola, pantau, dan verifikasi akhir pelaporan stok dari seluruh
                    cabang.</p>
            </div>

            {{-- Modern Segmented Control Tabs --}}
            <div
                class="bg-slate-200/60 p-1.5 rounded-2xl flex flex-wrap sm:flex-nowrap gap-1.5 border border-slate-200 shadow-inner w-full md:w-auto">
                {{-- Tab: Menunggu --}}
                <button @click="activeTab = 'pending'"
                    :class="activeTab === 'pending' ? 'bg-white text-indigo-700 shadow-[0_2px_8px_rgb(0,0,0,0.08)] font-bold' :
                        'text-slate-500 hover:text-slate-800 hover:bg-slate-200/50 font-medium'"
                    class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm transition-all duration-300 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Menunggu Tindakan
                    @if ($periodeSiap->count() > 0)
                        <span
                            class="inline-flex items-center justify-center px-2 py-0.5 ml-1 text-xs font-bold rounded-full bg-rose-100 text-rose-600 border border-rose-200">
                            {{ $periodeSiap->count() }}
                        </span>
                    @endif
                </button>

                {{-- Tab: Riwayat --}}
                <button @click="activeTab = 'history'"
                    :class="activeTab === 'history' ?
                        'bg-white text-emerald-700 shadow-[0_2px_8px_rgb(0,0,0,0.08)] font-bold' :
                        'text-slate-500 hover:text-slate-800 hover:bg-slate-200/50 font-medium'"
                    class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm transition-all duration-300 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Riwayat Selesai
                    @if ($periodeSelesai->count() > 0)
                        <span
                            class="inline-flex items-center justify-center px-2 py-0.5 ml-1 text-xs font-bold rounded-full bg-slate-100 text-slate-500 border border-slate-200">
                            {{ $periodeSelesai->count() }}
                        </span>
                    @endif
                </button>
            </div>
        </div>


        {{-- ========================================== --}}
        {{-- TAB 1: MENUNGGU VERIFIKASI FINAL --}}
        {{-- ========================================== --}}
        <div x-show="activeTab === 'pending'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

            @if ($periodeSiap->isEmpty())
                {{-- Premium Empty State --}}
                <div
                    class="bg-white/60 backdrop-blur-sm rounded-[2rem] border border-slate-200 border-dashed p-12 text-center shadow-sm">
                    <div
                        class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner ring-8 ring-white">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Semua Sudah Tuntas!</h3>
                    <p class="text-slate-500 max-w-md mx-auto">Tidak ada periode yang memerlukan verifikasi final saat
                        ini. Daftar akan muncul di sini setelah cabang diverifikasi oleh tim Akunting.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-5">
                    @foreach ($periodeSiap as $periode)
                        @php
                            $total = $periode->total_cabang ?? 0;
                            $verified = $periode->total_verified ?? 0;
                            $siap = $total > 0 && $total === $verified;
                            $pct = $total > 0 ? round(($verified / $total) * 100) : 0;
                        @endphp

                        {{-- Task Card --}}
                        <div
                            class="group bg-white rounded-2xl border transition-all duration-300 {{ $siap ? 'border-emerald-200 shadow-[0_8px_30px_rgb(16,185,129,0.12)] ring-1 ring-emerald-50' : 'border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-200' }} overflow-hidden">
                            <div
                                class="p-6 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">

                                {{-- Card Left: Info --}}
                                <div class="flex items-start gap-4 flex-1 min-w-0">
                                    <div
                                        class="w-12 h-12 rounded-full shrink-0 flex items-center justify-center {{ $siap ? 'bg-emerald-100 text-emerald-600' : 'bg-indigo-50 text-indigo-600' }}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <h3 class="text-lg font-bold text-slate-900 truncate">
                                                {{ $periode->nama_periode }}</h3>
                                            @if ($siap)
                                                <span
                                                    class="inline-flex items-center gap-1 text-xs bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-md font-bold border border-emerald-200">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    Tindakan Diperlukan
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 text-xs bg-amber-50 text-amber-700 px-2.5 py-1 rounded-md font-bold border border-amber-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Menunggu Akunting
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm font-medium text-slate-500 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Periode pelaporan per <span
                                                class="text-slate-700">{{ $periode->tanggal_akhir->locale('id')->isoFormat('D MMMM Y') }}</span>
                                        </p>
                                    </div>
                                </div>

                                {{-- Card Middle: Progress Bar --}}
                                <div class="w-full lg:w-1/3">
                                    <div class="flex items-center justify-between text-sm mb-2">
                                        <span class="font-semibold text-slate-700">Progress Cabang</span>
                                        <span
                                            class="font-bold {{ $siap ? 'text-emerald-600' : 'text-indigo-600' }}">{{ $verified }}
                                            / {{ $total }}</span>
                                    </div>
                                    <div
                                        class="w-full bg-slate-100 rounded-full h-2.5 shadow-inner overflow-hidden border border-slate-200/50">
                                        <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $siap ? 'bg-gradient-to-r from-emerald-400 to-emerald-500' : 'bg-gradient-to-r from-indigo-400 to-indigo-500' }}"
                                            style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>

                                {{-- Card Right: Actions --}}
                                <div
                                    class="flex items-center gap-3 w-full lg:w-auto pt-4 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                                    <a href="{{ route('kepala.periode.show', $periode) }}"
                                        class="flex-1 lg:flex-none text-center px-5 py-2.5 border border-slate-300 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-300 transition-all active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-slate-100">
                                        Detail
                                    </a>

                                    @if ($siap)
                                        <form method="POST" action="{{ route('kepala.periode.finalize', $periode) }}"
                                            class="flex-1 lg:flex-none"
                                            onsubmit="return confirm('Apakah Anda yakin ingin melakukan Verifikasi Final pada periode ini? Semua data stok cabang akan dikunci permanen dan tidak dapat diubah lagi.')">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="w-full text-center px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 shadow-[0_4px_14px_0_rgb(16,185,129,0.3)] transition-all active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-emerald-500/30 flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Finalisasi
                                            </button>
                                        </form>
                                    @else
                                        {{-- Disabled Placeholder for aesthetic --}}
                                        <button disabled
                                            class="flex-1 lg:flex-none text-center px-5 py-2.5 bg-slate-100 text-slate-400 text-sm font-bold rounded-xl cursor-not-allowed border border-slate-200">
                                            Belum Siap
                                        </button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>


        {{-- ========================================== --}}
        {{-- TAB 2: RIWAYAT PERIODE SELESAI --}}
        {{-- ========================================== --}}
        <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-cloak>

            @if ($periodeSelesai->isEmpty())
                <div
                    class="bg-white/60 backdrop-blur-sm rounded-[2rem] border border-slate-200 border-dashed p-12 text-center shadow-sm">
                    <div
                        class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner ring-8 ring-white">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Riwayat</h3>
                    <p class="text-slate-500 max-w-md mx-auto">Periode yang telah Anda verifikasi final akan muncul dan
                        diarsipkan secara aman di sini.</p>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200">
                                    <th class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider text-xs">
                                        Informasi Periode</th>
                                    <th
                                        class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider text-xs w-40">
                                        Tgl Akhir Laporan</th>
                                    <th class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider text-xs">
                                        Waktu Finalisasi</th>
                                    <th
                                        class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider text-xs text-right w-28">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($periodeSelesai as $periode)
                                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <span
                                                    class="font-bold text-slate-900">{{ $periode->nama_periode }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                {{ $periode->tanggal_akhir->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-emerald-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-slate-600">
                                                    {{ $periode->tgl_verifikasi_operasional?->locale('id')->isoFormat('D MMM Y, HH:mm') ?? '—' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('kepala.periode.show', $periode) }}"
                                                class="inline-flex items-center gap-1 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                                                Detail
                                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

    </div>

</x-app-layout>
