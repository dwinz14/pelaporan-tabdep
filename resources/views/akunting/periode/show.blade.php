<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Top Bar: Breadcrumb & Actions --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-3">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('akunting.periode.index') }}" class="hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Daftar Periode
            </a>
            <span class="text-gray-300">›</span>
            <span class="text-gray-900 font-medium truncate">{{ $periode->nama_periode }}</span>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $periode->status_operasional->badgeClass() }}">
                {{ $periode->status_operasional->label() }}
            </span>

            <a href="{{ route('akunting.periode.export', $periode) }}"
                class="inline-flex items-center gap-1.5 px-3 py-2 border border-emerald-300 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-lg hover:bg-emerald-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export
            </a>

            <a href="{{ route('akunting.periode.index') }}"
                class="text-xs text-gray-500 hover:text-gray-700 transition-colors font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
        <div class="flex flex-wrap items-center gap-x-8 gap-y-2">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tanggal Periode</p>
                    <p class="font-semibold text-gray-900 text-sm">
                        {{ $periode->tanggal_akhir->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </p>
                </div>
            </div>

            @if ($periode->isLocked() && $periode->tgl_verifikasi_operasional)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Verifikasi Final</p>
                        <p class="font-semibold text-gray-900 text-sm">
                            {{ $periode->tgl_verifikasi_operasional->locale('id')->isoFormat('D MMMM Y, HH:mm') }}
                        </p>
                    </div>
                </div>
            @elseif(!$periode->isLocked())
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <p class="font-semibold text-amber-700 text-sm">Belum diverifikasi</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Livewire Table Component --}}
    <livewire:akunting.pivot-periode :periode="$periode" />

</x-app-layout>
