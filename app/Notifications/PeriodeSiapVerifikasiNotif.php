<?php

namespace App\Notifications;

use App\Models\PeriodeLaporan;
use Illuminate\Notifications\Messages\MailMessage;

class PeriodeSiapVerifikasiNotif extends BaseNotification
{
    public function __construct(
        private readonly PeriodeLaporan $periode,
    ) {}

    public function toDatabase(object $notifiable): array
    {
        return $this->buildData(
            type: 'periode_siap_verifikasi',
            title: 'Periode Siap Verifikasi Final',
            message: "{$this->periode->nama_periode} telah diverifikasi oleh semua cabang dan akunting. "
                . "Silakan lakukan verifikasi final.",
            actionUrl: route('kepala.periode.show', $this->periode),
            actionLabel: 'Verifikasi Final',
            meta: [
                'periode_id' => $this->periode->id,
            ],
        );
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Periode Siap Verifikasi Final: {$this->periode->nama_periode}")
            ->greeting("Halo, {$notifiable->name}")
            ->line("{$this->periode->nama_periode} siap untuk verifikasi final.")
            ->line("Semua cabang telah diverifikasi oleh akunting pusat.")
            ->action('Verifikasi Final', route('kepala.periode.show', $this->periode))
            ->salutation('SI Stok Tab-Dep');
    }
}
