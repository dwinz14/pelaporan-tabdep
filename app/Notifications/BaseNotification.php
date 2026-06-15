<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification
{
    /**
     * Channel yang aktif: database selalu on, mail via config.
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if (
            config('notifications.email_enabled', false)
            && ! empty($notifiable->email)
        ) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Format data untuk channel database.
     * Semua notifikasi wajib implementasi ini.
     */
    abstract public function toDatabase(object $notifiable): array;

    /**
     * Format untuk channel database — delegate ke toDatabase().
     */
    final public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }

    /**
     * Helper: build data array dengan struktur konsisten.
     */
    protected function buildData(
        string $type,
        string $title,
        string $message,
        string $actionUrl,
        string $actionLabel,
        array  $meta = []
    ): array {
        return [
            'type'         => $type,
            'title'        => $title,
            'message'      => $message,
            'action_url'   => $actionUrl,
            'action_label' => $actionLabel,
            'meta'         => $meta,
        ];
    }

    /**
     * Icon dan warna berdasarkan type — digunakan di blade.
     */
    public static function iconFor(string $type): array
    {
        return match ($type) {
            'laporan_submitted'         => ['icon' => 'document-add',   'color' => 'blue'],
            'laporan_revisi'            => ['icon' => 'exclamation',     'color' => 'orange'],
            'laporan_approved'          => ['icon' => 'check-circle',    'color' => 'green'],
            'periode_siap_verifikasi'   => ['icon' => 'shield-check',    'color' => 'emerald'],
            'periode_baru'              => ['icon' => 'calendar',        'color' => 'indigo'],
            'registrasi_baru'           => ['icon' => 'user-add',        'color' => 'amber'],
            'registrasi_disetujui'      => ['icon' => 'check-circle',    'color' => 'green'],
            'registrasi_ditolak'        => ['icon' => 'x-circle',        'color' => 'red'],
            default                     => ['icon' => 'bell',            'color' => 'gray'],
        };
    }
}
