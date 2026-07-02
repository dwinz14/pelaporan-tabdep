@props(['label', 'icon', 'activeOn' => null])

@php
    $isActive = $activeOn ? request()->routeIs($activeOn) : false;
    $defaultOpen = $isActive;
    $iconMap = [
        'grid' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />',
    ];
    $iconPath = $iconMap[$icon] ?? '';
@endphp

<div x-data="{ open: @json($defaultOpen) }" class="space-y-0.5">
    <button type="button" @click="open = !open"
        class="group w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 relative overflow-hidden
               {{ $isActive
                   ? 'bg-gradient-to-r from-indigo-500/90 to-indigo-600 text-white font-semibold shadow-lg shadow-indigo-500/20'
                   : 'text-slate-400 font-medium hover:text-white hover:bg-white/5' }}">
        @if ($isActive)
            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-5 bg-white/70 rounded-r-full"></span>
        @endif
        <span
            class="w-7 h-7 flex items-center justify-center rounded-lg flex-shrink-0 transition-all duration-200
                   {{ $isActive ? 'bg-white/20' : 'bg-white/[0.04] group-hover:bg-white/10' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $iconPath !!}
            </svg>
        </span>
        <span class="truncate flex-1 text-left">{{ $label }}</span>
        <svg class="w-3.5 h-3.5 transition-transform duration-200 flex-shrink-0"
             :class="{ 'rotate-180': open }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-collapse.duration.200ms>
        <div class="ml-3 border-l border-white/[0.06] pl-3 space-y-0.5">
            {{ $slot }}
        </div>
    </div>
</div>
