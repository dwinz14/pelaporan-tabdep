@props(['label'])

<div class="px-3 pt-5 pb-1.5">
    <div class="flex items-center gap-2">
        <span class="h-px flex-1 bg-white/[0.06]"></span>
        <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest whitespace-nowrap">
            {{ $label }}
        </p>
        <span class="h-px flex-1 bg-white/[0.06]"></span>
    </div>
</div>
