@php
    $status = $laporan->status_verifikasi;
    $locked = $laporan->periode->isLocked();
    // $readonly diteruskan dari parent component via @include
@endphp

<div class="flex flex-col items-center gap-1.5 min-w-[100px]">
    {{-- Status Badge --}}
    <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium {{ $status->badgeClass() }} whitespace-nowrap">
        {{ $status->label() }}
    </span>

    {{-- Action Buttons: hanya jika submitted, tidak locked, dan tidak readonly --}}
    @if ($status === \App\Enums\StatusVerifikasi::Submitted && !$locked && !($readonly ?? false))
        <button type="button" wire:click="openApprove({{ $laporan->id }})" wire:loading.attr="disabled"
            class="w-full px-2 py-1 bg-emerald-600 text-white text-xs font-medium rounded
                   hover:bg-emerald-700 transition-colors disabled:opacity-60 whitespace-nowrap">
            ✓ Setujui
        </button>
        <button type="button" wire:click="openRevisi({{ $laporan->id }})"
            class="w-full px-2 py-1 bg-orange-500 text-white text-xs font-medium rounded
                   hover:bg-orange-600 transition-colors whitespace-nowrap">
            ↩ Revisi
        </button>
    @endif

    {{-- Info tanggal verifikasi --}}
    @if ($status === \App\Enums\StatusVerifikasi::VerifiedAccounting && $laporan->tgl_verifikasi_akunting)
        <p class="text-xs text-gray-400 text-center">
            {{ $laporan->tgl_verifikasi_akunting->format('d/m/y') }}
        </p>
    @endif
</div>
