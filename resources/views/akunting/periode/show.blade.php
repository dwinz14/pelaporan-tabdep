<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Minimal Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-[--color-text-muted] mb-3">
        <a href="{{ route('akunting.periode.index') }}" class="hover:text-indigo-600 transition-colors">Daftar Periode</a>
        <svg class="w-3 h-3 text-[--color-text-disabled]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="font-medium text-[--color-text-primary]">{{ $periode->nama_periode }}</span>
    </div>

    {{-- Compact Info Bar --}}
    <div
        class="flex items-center justify-between gap-3 mb-5 px-5 py-3 bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-sm">
        <div class="flex items-center gap-3 text-sm min-w-0">
            <span
                class="font-semibold text-[--color-text-primary] whitespace-nowrap">{{ $periode->tanggal_akhir->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            <span
                class="{{ $periode->status_operasional->badgeClass() }} whitespace-nowrap">{{ $periode->status_operasional->label() }}</span>
            @if ($periode->isLocked() && $periode->tgl_verifikasi_operasional)
                <span class="text-xs text-[--color-text-muted] hidden md:inline">Final ·
                    {{ $periode->tgl_verifikasi_operasional->locale('id')->isoFormat('D MMMM Y') }}</span>
            @elseif(!$periode->isLocked())
                <span class="text-xs text-amber-600 font-medium hidden md:inline">Menunggu verifikasi akunting</span>
            @endif
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('akunting.periode.export', $periode) }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-[--color-border-default] text-[--color-text-secondary] text-xs font-medium rounded-lg hover:bg-[--color-bg-subtle] hover:border-[--color-border-strong] transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export
            </a>
        </div>
    </div>

    {{-- Livewire Component --}}
    <livewire:akunting.pivot-periode :periode="$periode" />

</x-app-layout>
