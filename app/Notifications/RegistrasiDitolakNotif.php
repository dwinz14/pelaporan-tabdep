<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class RegistrasiDitolakNotif extends BaseNotification
{
    public function __construct(
        private readonly string $catatan,
    ) {}

    public function toDatabase(object $notifiable): array
    {
        return $this->buildData(
            type: 'registrasi_ditolak',
            title: 'Pendaftaran Ditolak',
            message: "Pendaftaran akun Anda ditolak oleh Super Admin. Alasan: {$this->catatan}. "
                . "Hubungi Super Admin atau IT Support untuk informasi lebih lanjut.",
            actionUrl: route('login'),
            actionLabel: 'Kembali ke Login',
            meta: [
                'catatan' => $this->catatan,
            ],
        );
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pendaftaran Akun Anda Tidak Disetujui')
            ->greeting("Halo, {$notifiable->name}")
            ->line("Maaf, pendaftaran akun Anda tidak dapat kami setujui.")
            ->line("Alasan: {$this->catatan}")
            ->line("Hubungi Super Admin atau IT Support untuk informasi lebih lanjut.")
            ->salutation('SI Stok Tab-Dep');
    }
}
