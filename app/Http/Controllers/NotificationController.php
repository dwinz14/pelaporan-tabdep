<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(10);

        return view('notifications.index', [
            'title'         => 'Notifikasi',
            'subtitle'      => 'Semua notifikasi masuk',
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $notif = auth()->user()->notifications()->findOrFail($id);
        $notif->markAsRead();

        // Redirect ke action_url jika ada
        $data = $notif->data;
        if (! empty($data['action_url'])) {
            return redirect($data['action_url']);
        }

        return redirect()->route('notifications.index');
    }

    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }

    public function destroy(string $id): RedirectResponse
    {
        auth()->user()->notifications()->findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Notifikasi dihapus.');
    }

    public function destroyAll(): RedirectResponse
    {
        auth()->user()->notifications()->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Semua notifikasi dihapus.');
    }
}
