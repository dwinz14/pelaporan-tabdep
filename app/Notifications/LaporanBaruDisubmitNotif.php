<?php

namespace App\Notifications;

use App\Models\Laporan;
use Illuminate\Notifications\Messages\MailMessage;

class LaporanBaruDisubmitNotif extends BaseNotification
{
    public function __construct(
        private readonly Laporan $laporan,
    ) {}

    public function toDatabase(object $notifiable): array
    {
        return $this->buildData(
            type: 'laporan_submitted',
            title: 'Laporan Baru Disubmit',
            message: "Laporan {$this->laporan->jenis->label()} cabang {$this->laporan->cabang->kode_cabang} "
                . "({$this->laporan->cabang->nama_cabang}) untuk {$this->laporan->periode->nama_periode} "
                . "telah disubmit dan menunggu verifikasi.",
            actionUrl: route('akunting.periode.show', $this->laporan->id_periode),
            actionLabel: 'Verifikasi Laporan',
            meta: [
                'laporan_id'   => $this->laporan->id,
                'cabang_kode'  => $this->laporan->cabang->kode_cabang,
                'jenis'        => $this->laporan->jenis->value,
                'periode_id'   => $this->laporan->id_periode,
            ],
        );
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Laporan {$this->laporan->jenis->label()} Baru dari Cabang {$this->laporan->cabang->kode_cabang}")
            ->greeting("Halo, {$notifiable->name}")
            ->line("Laporan {$this->laporan->jenis->label()} dari cabang {$this->laporan->cabang->nama_cabang} "
                . "untuk {$this->laporan->periode->nama_periode} telah disubmit.")
            ->action('Buka & Verifikasi', route('akunting.periode.show', $this->laporan->id_periode))
            ->line('Silakan verifikasi laporan tersebut.')
            ->salutation('SI Stok Tab-Dep');
    }
}
