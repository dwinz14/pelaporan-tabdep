<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Breadcrumb + Back --}}
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('akunting.periode.index') }}" class="hover:text-indigo-600 transition-colors">
                Daftar Periode
            </a>
            <span>›</span>
            <span class="text-gray-900 font-medium">{{ $periode->nama_periode }}</span>
        </div>

        <div class="flex items-center gap-3">
            {{-- Status Badge --}}
            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $periode->status_operasional->badgeClass() }}">
                {{ $periode->status_operasional->label() }}
            </span>

            {{-- Tombol Export --}}
            <a href="{{ route('akunting.periode.export', $periode) }}"
                class="flex items-center gap-1.5 px-3 py-1.5 border border-emerald-300 bg-emerald-50 text-emerald-700
           text-xs font-medium rounded-lg hover:bg-emerald-100 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Excel
            </a>

            <a href="{{ route('akunting.periode.index') }}"
                class="text-xs text-gray-500 hover:text-gray-700 transition-colors">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- Info Periode --}}
    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-5 flex items-center gap-6 flex-wrap">
        <div>
            <p class="text-xs text-gray-500">Tanggal Periode</p>
            <p class="font-semibold text-gray-900 text-sm">
                {{ $periode->tanggal_akhir->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </p>
        </div>
        @if ($periode->isLocked() && $periode->tgl_verifikasi_operasional)
            <div>
                <p class="text-xs text-gray-500">Diverifikasi Final</p>
                <p class="font-semibold text-gray-900 text-sm">
                    {{ $periode->tgl_verifikasi_operasional->locale('id')->isoFormat('D MMMM Y, HH:mm') }}
                </p>
            </div>
        @endif
    </div>

    {{-- Livewire Pivot Component --}}
    <livewire:akunting.pivot-periode :periode="$periode" />

</x-app-layout>
