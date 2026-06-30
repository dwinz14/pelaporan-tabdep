<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Wrapper Utama dengan Alpine.js untuk sistem Tab --}}
    <div class="max-w-6xl mx-auto space-y-4" x-data="{ activeTab: 'pending' }" x-cloak>

        {{-- ═══ HEADER & TAB NAVIGATION ═══ --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Verifikasi Final Periode</h1>
                <p class="text-xs text-slate-500 mt-1">Kelola, pantau, dan verifikasi akhir pelaporan stok dari seluruh
                    cabang.</p>
            </div>

            {{-- Modern Segmented Control Tabs --}}
            <div
                class="bg-slate-200/60 p-1 rounded-xl flex flex-wrap sm:flex-nowrap gap-1 border border-slate-200 shadow-inner w-full md:w-auto">
                {{-- Tab: Menunggu --}}
                <button @click="activeTab = 'pending'"
                    :class="activeTab === 'pending' ? 'bg-white text-indigo-700 shadow-[0_2px_8px_rgb(0,0,0,0.08)] font-bold' :
                        'text-slate-500 hover:text-slate-800 hover:bg-slate-200/50 font-medium'"
                    class="flex-1 md:flex-none flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-xs transition-all duration-300 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Menunggu Tindakan
                    @if ($periodeSiap->count() > 0)
                        <span
                            class="inline-flex items-center justify-center px-1.5 py-0.5 ml-0.5 text-[10px] font-bold rounded-full bg-rose-100 text-rose-600 border border-rose-200">
                            {{ $periodeSiap->count() }}
                        </span>
                    @endif
                </button>

                {{-- Tab: Riwayat --}}
                <button @click="activeTab = 'history'"
                    :class="activeTab === 'history' ?
                        'bg-white text-emerald-700 shadow-[0_2px_8px_rgb(0,0,0,0.08)] font-bold' :
                        'text-slate-500 hover:text-slate-800 hover:bg-slate-200/50 font-medium'"
                    class="flex-1 md:flex-none flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-xs transition-all duration-300 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Riwayat Selesai
                    @if ($periodeSelesai->count() > 0)
                        <span
                            class="inline-flex items-center justify-center px-1.5 py-0.5 ml-0.5 text-[10px] font-bold rounded-full bg-slate-100 text-slate-500 border border-slate-200">
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
                <div
                    class="bg-white/60 backdrop-blur-sm rounded-xl border border-slate-200 border-dashed p-8 text-center shadow-sm">
                    <div
                        class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner ring-4 ring-white">
                        <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Semua Sudah Tuntas!</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">Tidak ada periode yang memerlukan verifikasi final
                        saat ini. Daftar akan muncul di sini setelah cabang diverifikasi oleh tim Akunting.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-3">
                    @foreach ($periodeSiap as $periode)
                        @php
                            $total = $periode->total_cabang ?? 0;
                            $verified = $periode->total_verified ?? 0;
                            $siap = $total > 0 && $total === $verified;
                            $pct = $total > 0 ? round(($verified / $total) * 100) : 0;
                        @endphp

                        {{-- Task Card --}}
                        <div
                            class="group bg-white rounded-xl border transition-all duration-300 {{ $siap ? 'border-emerald-200 shadow-sm ring-1 ring-emerald-50' : 'border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-200' }} overflow-hidden">
                            <div
                                class="p-4 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">

                                {{-- Card Left: Info --}}
                                <div class="flex items-start gap-3 flex-1 min-w-0">
                                    <div
                                        class="w-10 h-10 rounded-full shrink-0 flex items-center justify-center {{ $siap ? 'bg-emerald-100 text-emerald-600' : 'bg-indigo-50 text-indigo-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="text-sm font-bold text-slate-900 truncate">
                                                {{ $periode->nama_periode }}</h3>
                                            @if ($siap)
                                                <span
                                                    class="inline-flex items-center gap-1 text-[10px] bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-md font-bold border border-emerald-200">
                                                    <span
                                                        class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    Tindakan Diperlukan
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 text-[10px] bg-amber-50 text-amber-700 px-2 py-0.5 rounded-md font-bold border border-amber-200">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Menunggu Akunting
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs font-medium text-slate-500 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
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
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="font-semibold text-slate-700">Progress Cabang</span>
                                        <span
                                            class="font-bold {{ $siap ? 'text-emerald-600' : 'text-indigo-600' }}">{{ $verified }}
                                            / {{ $total }}</span>
                                    </div>
                                    <div
                                        class="w-full bg-slate-100 rounded-full h-2 shadow-inner overflow-hidden border border-slate-200/50">
                                        <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $siap ? 'bg-gradient-to-r from-emerald-400 to-emerald-500' : 'bg-gradient-to-r from-indigo-400 to-indigo-500' }}"
                                            style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>

                                {{-- Card Right: Actions --}}
                                <div
                                    class="flex items-center gap-2 w-full lg:w-auto pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                                    <a href="{{ route('kepala.periode.show', $periode) }}"
                                        class="flex-1 lg:flex-none text-center px-4 py-2 border border-slate-300 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-300 transition-all active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-slate-100">
                                        Detail
                                    </a>

                                    @if ($siap)
                                        <form method="POST" action="{{ route('kepala.periode.finalize', $periode) }}"
                                            class="flex-1 lg:flex-none" x-data="{ showFinalModal: false }">
                                            @csrf @method('PATCH')
                                            <button type="button" @click="showFinalModal = true"
                                                class="w-full text-center px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 shadow-sm transition-all active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-emerald-500/30 flex items-center justify-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Finalisasi
                                            </button>

                                            {{-- ═══ MODAL KONFIRMASI FINALISASI ═══ --}}
                                            <div x-show="showFinalModal"
                                                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                                style="background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(2px);"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                x-on:keydown.escape.window="showFinalModal = false">
                                                <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden"
                                                    x-transition:enter="transition ease-out duration-200 delay-100"
                                                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                    x-transition:leave="transition ease-in duration-150"
                                                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                                    x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                                    @click.stop>
                                                    <div
                                                        class="px-5 py-4 border-b border-slate-100 flex items-center gap-3">
                                                        <div
                                                            class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-5 h-5 text-amber-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <h3 class="font-semibold text-slate-900 text-sm">Konfirmasi
                                                                Finalisasi</h3>
                                                            <p class="text-xs text-slate-500 mt-0.5">Tindakan ini tidak
                                                                dapat dibatalkan.</p>
                                                        </div>
                                                    </div>
                                                    <div class="px-5 py-4">
                                                        <p class="text-sm text-slate-700">Apakah Anda yakin ingin
                                                            melakukan Verifikasi Final pada periode
                                                            <strong>{{ $periode->nama_periode }}</strong>?
                                                        </p>
                                                        <div
                                                            class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 flex items-start gap-2">
                                                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            <span>Semua data stok cabang akan dikunci permanen dan tidak
                                                                dapat diubah lagi.</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                                                        <button type="button" @click="showFinalModal = false"
                                                            class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                                                            Batal
                                                        </button>
                                                        <button type="submit"
                                                            class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm shadow-emerald-200 flex items-center gap-1.5">
                                                            <svg class="w-3.5 h-3.5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Ya, Finalisasi
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <button disabled
                                            class="flex-1 lg:flex-none text-center px-4 py-2 bg-slate-100 text-slate-400 text-xs font-bold rounded-lg cursor-not-allowed border border-slate-200">
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
                    class="bg-white/60 backdrop-blur-sm rounded-xl border border-slate-200 border-dashed p-8 text-center shadow-sm">
                    <div
                        class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner ring-4 ring-white">
                        <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Belum Ada Riwayat</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">Periode yang telah Anda verifikasi final akan
                        muncul dan diarsipkan secara aman di sini.</p>
                </div>
            @else
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200">
                                    <th class="px-4 py-3 font-bold text-slate-500 uppercase tracking-wider text-[10px]">
                                        Informasi Periode</th>
                                    <th
                                        class="px-4 py-3 font-bold text-slate-500 uppercase tracking-wider text-[10px] w-32">
                                        Tgl Akhir Laporan</th>
                                    <th class="px-4 py-3 font-bold text-slate-500 uppercase tracking-wider text-[10px]">
                                        Waktu Finalisasi</th>
                                    <th
                                        class="px-4 py-3 font-bold text-slate-500 uppercase tracking-wider text-[10px] text-right w-24">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($periodeSelesai as $periode)
                                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-7 h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <span
                                                    class="font-bold text-slate-900 text-xs">{{ $periode->nama_periode }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                {{ $periode->tanggal_akhir->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-xs font-medium text-slate-600">
                                                    {{ $periode->tgl_verifikasi_operasional?->locale('id')->isoFormat('D MMM Y') ?? '—' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('kepala.periode.show', $periode) }}"
                                                class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                                                Detail
                                                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5"
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
