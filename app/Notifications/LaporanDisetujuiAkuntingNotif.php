<?php

namespace App\Notifications;

use App\Models\Laporan;
use Illuminate\Notifications\Messages\MailMessage;

class LaporanDisetujuiAkuntingNotif extends BaseNotification
{
    public function __construct(
        private readonly Laporan $laporan,
    ) {}

    public function toDatabase(object $notifiable): array
    {
        return $this->buildData(
            type: 'laporan_approved',
            title: 'Laporan Disetujui',
            message: "Laporan {$this->laporan->jenis->label()} Anda untuk {$this->laporan->periode->nama_periode} "
                . "telah diverifikasi dan disetujui oleh akunting pusat.",
            actionUrl: route('pic.riwayat.index'),
            actionLabel: 'Lihat Riwayat',
            meta: [
                'laporan_id' => $this->laporan->id,
                'jenis'      => $this->laporan->jenis->value,
                'periode_id' => $this->laporan->id_periode,
            ],
        );
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("✓ Laporan {$this->laporan->jenis->label()} Disetujui")
            ->greeting("Halo, {$notifiable->name}")
            ->line("Laporan {$this->laporan->jenis->label()} Anda untuk {$this->laporan->periode->nama_periode} telah disetujui.")
            ->action('Lihat Riwayat', route('pic.riwayat.index'))
            ->salutation('SI Stok Tab-Dep');
    }
}
