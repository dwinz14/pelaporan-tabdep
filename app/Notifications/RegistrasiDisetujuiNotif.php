<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class RegistrasiDisetujuiNotif extends BaseNotification
{
    public function toDatabase(object $notifiable): array
    {
        return $this->buildData(
            type: 'registrasi_disetujui',
            title: 'Pendaftaran Disetujui!',
            message: "Selamat! Akun Anda telah disetujui oleh Super Admin. "
                . "Anda sekarang dapat login dan menggunakan sistem.",
            actionUrl: route('pic.dashboard'),
            actionLabel: 'Buka Dashboard',
        );
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✓ Pendaftaran Akun Anda Disetujui')
            ->greeting("Selamat, {$notifiable->name}!")
            ->line("Akun Anda telah disetujui oleh Super Admin.")
            ->line("Anda sekarang dapat login menggunakan NIK dan password yang sudah Anda daftarkan.")
            ->action('Login Sekarang', route('login'))
            ->salutation('SI Stok Tab-Dep');
    }
}
