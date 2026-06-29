<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Breadcrumb + Meta --}}
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.periode.index') }}" class="hover:text-indigo-600 transition-colors">
                Manajemen Periode
            </a>
            <span>›</span>
            <span class="text-gray-900 font-medium">{{ $periode->nama_periode }}</span>
        </div>

        <div class="flex items-center gap-3">
            {{-- Status Badge --}}
            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $periode->status_operasional->badgeClass() }}">
                {{ $periode->status_operasional->label() }}
            </span>

            @if ($periode->is_current)
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-indigo-100 text-indigo-800">
                    ● Periode Aktif
                </span>
            @endif

            <a href="{{ route('admin.periode.index') }}"
                class="text-xs text-gray-500 hover:text-gray-700 transition-colors">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- Info Periode --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-5 flex items-center flex-wrap gap-6">
        <div>
            <p class="text-xs text-gray-400">Tanggal Periode</p>
            <p class="font-semibold text-gray-900 text-sm">
                {{ $periode->tanggal_akhir->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </p>
        </div>

        @if ($periode->isLocked() && $periode->tgl_verifikasi_operasional)
            <div>
                <p class="text-xs text-gray-400">Diverifikasi Final</p>
                <p class="font-semibold text-gray-900 text-sm">
                    {{ $periode->tgl_verifikasi_operasional->locale('id')->isoFormat('D MMMM Y') }}
                    @if ($periode->verifiedByOperasional)
                        · {{ $periode->verifiedByOperasional->name }}
                    @endif
                </p>
            </div>
        @endif

        {{-- Info Mode Read-Only --}}
        <div class="ml-auto flex items-center gap-2 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                    clip-rule="evenodd" />
            </svg>
            <p class="text-xs text-gray-500">Mode Pantau — Admin tidak dapat mengubah data laporan</p>
        </div>
    </div>

    {{-- Pivot Component (readonly) --}}
    <livewire:akunting.pivot-periode :periode="$periode" :readonly="true" />

</x-app-layout>
