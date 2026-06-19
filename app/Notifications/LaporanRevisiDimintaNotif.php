<?php

namespace App\Notifications;

use App\Models\Laporan;
use Illuminate\Notifications\Messages\MailMessage;

class LaporanRevisiDimintaNotif extends BaseNotification
{
    public function __construct(
        private readonly Laporan $laporan,
    ) {}

    public function toDatabase(object $notifiable): array
    {
        return $this->buildData(
            type: 'laporan_revisi',
            title: 'Laporan Diminta Revisi',
            message: "Laporan {$this->laporan->jenis->label()} Anda untuk {$this->laporan->periode->nama_periode} "
                . "diminta revisi oleh akunting. Catatan: {$this->laporan->catatan_revisi}",
            actionUrl: route('pic.laporan.edit', $this->laporan->id_periode),
            actionLabel: 'Perbaiki Laporan',
            meta: [
                'laporan_id'      => $this->laporan->id,
                'jenis'           => $this->laporan->jenis->value,
                'periode_id'      => $this->laporan->id_periode,
                'catatan_revisi'  => $this->laporan->catatan_revisi,
            ],
        );
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Revisi Laporan {$this->laporan->jenis->label()} — {$this->laporan->periode->nama_periode}")
            ->greeting("Halo, {$notifiable->name}")
            ->line("Laporan {$this->laporan->jenis->label()} Anda untuk {$this->laporan->periode->nama_periode} diminta revisi.")
            ->line("Catatan akunting: *{$this->laporan->catatan_revisi}*")
            ->action('Perbaiki Laporan', route('pic.laporan.edit', $this->laporan->id_periode))
            ->salutation('SI Stok Tab-Dep');
    }
}
