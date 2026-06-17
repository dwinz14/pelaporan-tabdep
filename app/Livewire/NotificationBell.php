<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;

class NotificationBell extends Component
{
    public bool $isOpen = false;

    // Polling interval dari config (default 30s)
    public int $pollInterval;

    public function mount(): void
    {
        $this->pollInterval = (int) config('notifications.poll_interval', 30);
    }

    #[Computed(cache: false)]
    public function unreadCount(): int
    {
        return auth()->user()->unreadNotifications()->count();
    }

    #[Computed(cache: false)]
    public function recentNotifications()
    {
        return auth()->user()
            ->notifications()
            ->latest()
            ->take(8)
            ->get();
    }

    public function toggleOpen(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function markAsRead(string $id): void
    {
        $notif = auth()->user()->notifications()->find($id);
        if ($notif) {
            $notif->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.notification-bell');
    }
}
