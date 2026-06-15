<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    | 'database' selalu aktif untuk in-app notification.
    | Set NOTIFICATION_EMAIL_ENABLED=true di .env untuk mengaktifkan email.
    | Pastikan MAIL_* di .env sudah dikonfigurasi sebelum mengaktifkan email.
    */
    'email_enabled' => env('NOTIFICATION_EMAIL_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Polling Interval (detik)
    |--------------------------------------------------------------------------
    | Interval Livewire polling untuk refresh notifikasi.
    | Default 30 detik — aman untuk jaringan LAN lokal.
    */
    'poll_interval' => env('NOTIFICATION_POLL_INTERVAL', 30),
];
