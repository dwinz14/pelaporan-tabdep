<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;

class RegistrasiBaruNotif extends BaseNotification
{
    public function __construct(
        private readonly User $pendaftar,
    ) {}

    public function toDatabase(object $notifiable): array
    {
        return $this->buildData(
            type: 'registrasi_baru',
            title: 'Pendaftaran Akun Baru',
            message: "{$this->pendaftar->name} ({$this->pendaftar->nik}) dari cabang "
                . "{$this->pendaftar->cabang?->nama_cabang} mendaftar sebagai PIC Cabang. "
                . "Tinjau dan berikan persetujuan.",
            actionUrl: route('admin.registrasi.index'),
            actionLabel: 'Tinjau Pendaftaran',
            meta: [
                'user_id'     => $this->pendaftar->id,
                'user_nik'    => $this->pendaftar->nik,
                'cabang_kode' => $this->pendaftar->cabang?->kode_cabang,
            ],
        );
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Pendaftaran Akun Baru: {$this->pendaftar->name}")
            ->greeting("Halo, {$notifiable->name}")
            ->line("{$this->pendaftar->name} ({$this->pendaftar->nik}) mendaftar sebagai PIC Cabang.")
            ->line("Cabang: {$this->pendaftar->cabang?->nama_cabang}")
            ->action('Tinjau Pendaftaran', route('admin.registrasi.index'))
            ->salutation('SI Stok Tab-Dep');
    }
}
