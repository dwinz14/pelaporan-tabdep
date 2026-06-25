@php
    $status = $laporan->status_verifikasi;
    $locked = $laporan->periode->isLocked();
@endphp

<div class="flex flex-col items-center gap-2">
    {{-- Status Badge --}}
    @php
        $badgeMap = [
            \App\Enums\StatusVerifikasi::Draft->value => 'badge-neutral',
            \App\Enums\StatusVerifikasi::Submitted->value => 'badge-info',
            \App\Enums\StatusVerifikasi::VerifiedAccounting->value => 'badge-success',
            \App\Enums\StatusVerifikasi::RevisionRequested->value => 'badge-revision',
        ];
        $badgeClass = $badgeMap[$status->value] ?? 'badge-neutral';
    @endphp
    <span class="{{ $badgeClass }} whitespace-nowrap">
        {{ $status->label() }}
    </span>

    {{-- Action Buttons (hanya jika submitted dan tidak locked) --}}
    @if ($status === \App\Enums\StatusVerifikasi::Submitted && !$locked)
        <div class="flex items-center gap-1.5">
            <button type="button" wire:click="openApprove({{ $laporan->id }})" wire:loading.attr="disabled"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 text-white text-xs font-semibold rounded-lg
                       hover:bg-emerald-700 transition-colors disabled:opacity-60 shadow-sm shadow-emerald-200/50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                Setujui
            </button>
            <button type="button" wire:click="openRevisi({{ $laporan->id }})"
                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-orange-200 text-orange-700 text-xs font-medium rounded-lg
                       hover:bg-orange-50 transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Revisi
            </button>
        </div>
    @endif

    {{-- Info verifikasi --}}
    @if ($status === \App\Enums\StatusVerifikasi::VerifiedAccounting && $laporan->tgl_verifikasi_akunting)
        <p class="text-xs text-[--color-text-muted] text-center font-mono">
            {{ $laporan->tgl_verifikasi_akunting->format('d/m') }}
        </p>
    @endif
</div>
