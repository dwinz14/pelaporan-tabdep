<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('kepala.periode.index') }}" class="hover:text-indigo-600 transition-colors">
                Dashboard Verifikasi
            </a>
            <span>›</span>
            <span class="text-gray-900 font-medium">{{ $periode->nama_periode }}</span>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $periode->status_operasional->badgeClass() }}">
                {{ $periode->status_operasional->label() }}
            </span>
            <a href="{{ route('kepala.periode.index') }}" class="text-xs text-gray-500 hover:text-gray-700">← Kembali</a>
        </div>
    </div>

    {{-- Info Periode --}}
    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-5 flex items-center justify-between flex-wrap gap-4">
        <div class="flex gap-6 flex-wrap">
            <div>
                <p class="text-xs text-gray-500">Tanggal Periode</p>
                <p class="font-semibold text-gray-900 text-sm">
                    {{ $periode->tanggal_akhir->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </p>
            </div>
            @if ($periode->isLocked())
                <div>
                    <p class="text-xs text-gray-500">Diverifikasi Final Oleh</p>
                    <p class="font-semibold text-gray-900 text-sm">
                        {{ $periode->verifiedByOperasional?->name ?? '—' }}
                        · {{ $periode->tgl_verifikasi_operasional?->locale('id')->isoFormat('D MMM Y') }}
                    </p>
                </div>
            @endif
        </div>

        {{-- Tombol Verifikasi Final --}}
        @if (!$periode->isLocked() && $periode->semuaCabangVerified())
            <form method="POST" action="{{ route('kepala.periode.finalize', $periode) }}"
                onsubmit="return confirm('Verifikasi final periode ini? Semua data akan dikunci dan tidak dapat diubah lagi.')">
                @csrf @method('PATCH')
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg
                           hover:bg-emerald-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Verifikasi Final Periode Ini
                </button>
            </form>
        @elseif(!$periode->isLocked())
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800">
                ⚠ Belum semua cabang terverifikasi akunting. Verifikasi final belum bisa dilakukan.
            </div>
        @endif
    </div>

    {{-- Pivot Table (reuse Livewire, readonly karena periode locked atau kepala hanya bisa lihat) --}}
    <livewire:akunting.pivot-periode :periode="$periode" />

</x-app-layout>
