<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Toolbar --}}
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-2">
            @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
            @if ($unread > 0)
                <span class="text-sm text-gray-600">
                    <span class="font-semibold text-indigo-600">{{ $unread }}</span> belum dibaca
                </span>
            @else
                <span class="text-sm text-gray-400">Semua notifikasi sudah dibaca</span>
            @endif
        </div>

        <div class="flex items-center gap-2">
            @if ($unread > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="text-xs text-indigo-600 hover:text-indigo-800 font-medium px-3 py-1.5
                               border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif

            @if ($notifications->isNotEmpty())
                <form method="POST" action="{{ route('notifications.destroy-all') }}"
                    onsubmit="return confirm('Hapus semua notifikasi?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="text-xs text-red-500 hover:text-red-700 font-medium px-3 py-1.5
                               border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                        Hapus semua
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Notification List --}}
    @if ($notifications->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
            <p class="text-base font-semibold text-gray-600">Tidak ada notifikasi</p>
            <p class="text-sm text-gray-400 mt-1">Notifikasi akan muncul di sini saat ada aktivitas baru.</p>
        </div>
    @else
        <div class="space-y-2">
            @foreach ($notifications as $notif)
                @php
                    $data = $notif->data;
                    $isUnread = is_null($notif->read_at);
                    $style = \App\Notifications\BaseNotification::iconFor($data['type'] ?? '');
                @endphp

                <div
                    class="bg-white rounded-xl border {{ $isUnread ? 'border-indigo-200 bg-indigo-50/30' : 'border-gray-200' }} overflow-hidden
                            hover:shadow-sm transition-shadow">

                    <div class="flex items-start gap-4 p-4">

                        {{-- Icon --}}
                        <div
                            class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center
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
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 flex items-center gap-1.5">
                                        {{ $data['title'] ?? '' }}
                                        @if ($isUnread)
                                            <span
                                                class="w-1.5 h-1.5 bg-indigo-500 rounded-full inline-block flex-shrink-0"></span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 mt-0.5 leading-relaxed">
                                        {{ $data['message'] ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1.5">
                                        {{ $notif->created_at->locale('id')->isoFormat('D MMMM Y, HH:mm') }}
                                        · {{ $notif->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    @if (!empty($data['action_url']))
                                        <a href="{{ route('notifications.read', $notif->id) }}"
                                            class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold
                                                   px-3 py-1.5 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors whitespace-nowrap">
                                            {{ $data['action_label'] ?? 'Buka' }} →
                                        </a>
                                    @endif

                                    <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-gray-300 hover:text-red-500 transition-colors rounded"
                                            title="Hapus notifikasi">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Unread indicator strip --}}
                    @if ($isUnread)
                        <div class="h-0.5 bg-indigo-500 w-full"></div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($notifications->hasPages())
            <div class="mt-4 flex items-center justify-between">
                <p class="text-xs text-gray-500">
                    {{ $notifications->firstItem() }}–{{ $notifications->lastItem() }}
                    dari {{ $notifications->total() }} notifikasi
                </p>
                {{ $notifications->links() }}
            </div>
        @endif
    @endif

</x-app-layout>
