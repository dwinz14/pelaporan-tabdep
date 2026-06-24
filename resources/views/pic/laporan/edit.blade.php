<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div class="flex items-center gap-2 text-sm text-[--color-text-muted]">
            <a href="{{ route('pic.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-[--color-text-primary] font-semibold">{{ $periode->nama_periode }}</span>
        </div>

        <div class="flex items-center gap-3">
            @if ($periode->isLocked())
                <span class="badge-neutral">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    Periode Final
                </span>
            @elseif($periode->is_current)
                <span class="badge-success">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full dot-pulse"></span>
                    Periode Aktif
                </span>
            @endif
            <span class="text-xs text-[--color-text-muted] font-mono">
                {{ $periode->tanggal_akhir->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </span>
        </div>
    </div>

    {{-- Info Box: Locked --}}
    @if ($periode->isLocked())
        <div class="mb-6 p-4 bg-[--color-bg-subtle] border border-[--color-border-default] rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 text-[--color-text-muted] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-[--color-text-primary]">Periode sudah diverifikasi final</p>
                <p class="text-xs text-[--color-text-muted] mt-0.5">Data bersifat <strong>read-only</strong>. Tidak ada perubahan yang dapat dilakukan.</p>
            </div>
        </div>
    @endif

    {{-- Livewire Form Component --}}
    <livewire:pic.form-laporan :periode="$periode" />

</x-app-layout>
