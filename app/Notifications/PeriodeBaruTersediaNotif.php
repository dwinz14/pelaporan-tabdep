<?php

namespace App\Notifications;

use App\Models\PeriodeLaporan;
use Illuminate\Notifications\Messages\MailMessage;

class PeriodeBaruTersediaNotif extends BaseNotification
{
    public function __construct(
        private readonly PeriodeLaporan $periode,
    ) {}

    public function toDatabase(object $notifiable): array
    {
        return $this->buildData(
            type: 'periode_baru',
            title: 'Periode Laporan Baru',
            message: "{$this->periode->nama_periode} telah dibuka. Silakan input laporan stok buku tabungan dan deposito Anda.",
            actionUrl: route('pic.laporan.edit', $this->periode),
            actionLabel: 'Input Laporan',
            meta: [
                'periode_id'     => $this->periode->id,
                'tanggal_akhir'  => $this->periode->tanggal_akhir->format('Y-m-d'),
            ],
        );
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Periode Laporan Baru: {$this->periode->nama_periode}")
            ->greeting("Halo, {$notifiable->name}")
            ->line("{$this->periode->nama_periode} telah dibuka.")
            ->line("Silakan input laporan stok buku tabungan dan deposito Anda sebelum batas waktu.")
            ->action('Input Laporan', route('pic.laporan.edit', $this->periode))
            ->salutation('SI Stok Tab-Dep');
    }
}
