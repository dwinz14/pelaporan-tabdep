<div class="relative" x-data="{ open: @entangle('isOpen') }" wire:poll.{{ $pollInterval }}s="$refresh">

    {{-- Bell Button --}}
    <button type="button" wire:click="toggleOpen"
        class="relative flex items-center justify-center w-9 h-9 rounded-lg
               text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">

        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        {{-- Badge --}}
        @if ($this->unreadCount > 0)
            <span
                class="absolute -top-0.5 -right-0.5 flex items-center justify-center
                         min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px]
                         font-bold rounded-full leading-none">
                {{ $this->unreadCount > 99 ? '99+' : $this->unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1" @click.away="open = false; $wire.close()"
        class="absolute right-0 top-full mt-2 w-96 bg-white rounded-xl shadow-xl
                border border-gray-200 overflow-hidden z-50"
        style="display: none;">

        {{-- Header --}}
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                @if ($this->unreadCount > 0)
                    <span class="text-xs bg-red-100 text-red-700 font-semibold px-1.5 py-0.5 rounded-full">
                        {{ $this->unreadCount }} baru
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-2">
                @if ($this->unreadCount > 0)
                    <button type="button" wire:click="markAllAsRead"
                        class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                        Tandai semua dibaca
                    </button>
                @endif
            </div>
        </div>

        {{-- List --}}
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
            @forelse($this->recentNotifications as $notif)
                @php
                    $data = $notif->data;
                    $isUnread = is_null($notif->read_at);
                    $style = \App\Notifications\BaseNotification::iconFor($data['type'] ?? '');
                @endphp

                <div
                    class="flex items-start gap-3 px-4 py-3 {{ $isUnread ? 'bg-indigo-50/50' : '' }}
                            hover:bg-gray-50 transition-colors group">

                    {{-- Icon --}}
                    <div
                        class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center
                                {{ match ($style['color']) {
                                    'blue' => 'bg-blue-100',
                                    'orange' => 'bg-orange-100',
                                    'green' => 'bg-green-100',
                                    'emerald' => 'bg-emerald-100',
                                    'indigo' => 'bg-indigo-100',
                                    'amber' => 'bg-amber-100',
                                    'red' => 'bg-red-100',
                                    default => 'bg-gray-100',
                                } }}">
                        @include('components.notif-icon', [
                            'icon' => $style['icon'],
                            'color' => $style['color'],
                        ])
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-900 leading-snug">
                            {{ $data['title'] ?? '' }}
                            @if ($isUnread)
                                <span
                                    class="w-1.5 h-1.5 bg-indigo-500 rounded-full inline-block ml-1 align-middle"></span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5 leading-snug line-clamp-2">
                            {{ $data['message'] ?? '' }}
                        </p>
                        <p class="text-xs text-gray-300 mt-1">
                            {{ $notif->created_at->diffForHumans() }}
                        </p>
                    </div>

                    {{-- Action --}}
                    @if (!empty($data['action_url']))
                        <a href="{{ $data['action_url'] }}" wire:click="markAsRead('{{ $notif->id }}')"
                            @click="open = false"
                            class="text-xs text-indigo-600 hover:text-indigo-800 font-medium
                                  flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                            {{ $data['action_label'] ?? 'Buka' }} →
                        </a>
                    @endif
                </div>
            @empty
                <div class="px-4 py-10 text-center">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="text-sm text-gray-400 font-medium">Tidak ada notifikasi</p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        @if ($this->recentNotifications->isNotEmpty())
            <div class="px-4 py-2.5 border-t border-gray-100 bg-gray-50 text-center">
                <a href="{{ route('notifications.index') }}" @click="open = false"
                    class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                    Lihat semua notifikasi →
                </a>
            </div>
        @endif

    </div>
</div>
