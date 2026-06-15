<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Laporan;
use App\Models\PeriodeLaporan;
use App\Models\User;
use App\Notifications\LaporanBaruDisubmitNotif;
use App\Notifications\LaporanDisetujuiAkuntingNotif;
use App\Notifications\LaporanRevisiDimintaNotif;
use App\Notifications\PeriodeBaruTersediaNotif;
use App\Notifications\PeriodeSiapVerifikasiNotif;
use App\Notifications\RegistrasiBaruNotif;
use App\Notifications\RegistrasiDisetujuiNotif;
use App\Notifications\RegistrasiDitolakNotif;
use Illuminate\Support\Collection;

class NotificationService
{
    // ─────────────────────────────────────────────────────────
    // LAPORAN
    // ─────────────────────────────────────────────────────────

    /**
     * Dikirim ke semua Akunting saat PIC submit laporan.
     */
    public function notifyLaporanSubmitted(Laporan $laporan): void
    {
        $this->getAkuntingUsers()->each(
            fn($u) => $u->notify(new LaporanBaruDisubmitNotif($laporan))
        );
    }

    /**
     * Dikirim ke PIC cabang saat akunting minta revisi.
     */
    public function notifyLaporanRevisi(Laporan $laporan): void
    {
        $pic = $this->getPicByCabang($laporan->id_cabang);
        if ($pic) {
            $pic->notify(new LaporanRevisiDimintaNotif($laporan));
        }
    }

    /**
     * Dikirim ke PIC cabang saat akunting setujui laporan.
     */
    public function notifyLaporanDisetujui(Laporan $laporan): void
    {
        $pic = $this->getPicByCabang($laporan->id_cabang);
        if ($pic) {
            $pic->notify(new LaporanDisetujuiAkuntingNotif($laporan));
        }
    }

    /**
     * Cek apakah semua laporan periode sudah verified.
     * Jika ya, kirim notifikasi ke Kepala Operasional.
     */
    public function notifyIfPeriodeSiapVerifikasi(Laporan $laporan): void
    {
        $periode = $laporan->periode;

        if (! $periode->semuaCabangVerified()) return;

        $this->getKepalaUsers()->each(
            fn($u) => $u->notify(new PeriodeSiapVerifikasiNotif($periode))
        );
    }

    // ─────────────────────────────────────────────────────────
    // PERIODE
    // ─────────────────────────────────────────────────────────

    /**
     * Dikirim ke semua PIC aktif saat periode baru di-generate.
     */
    public function notifyPeriodeBaru(PeriodeLaporan $periode): void
    {
        $this->getAllActivePic()->each(
            fn($u) => $u->notify(new PeriodeBaruTersediaNotif($periode))
        );
    }

    // ─────────────────────────────────────────────────────────
    // REGISTRASI
    // ─────────────────────────────────────────────────────────

    /**
     * Dikirim ke semua Super Admin saat ada registrasi baru.
     */
    public function notifyRegistrasiBaru(User $pendaftar): void
    {
        $this->getSuperAdminUsers()->each(
            fn($u) => $u->notify(new RegistrasiBaruNotif($pendaftar))
        );
    }

    /**
     * Dikirim ke user yang mendaftar saat disetujui.
     */
    public function notifyRegistrasiDisetujui(User $user): void
    {
        $user->notify(new RegistrasiDisetujuiNotif());
    }

    /**
     * Dikirim ke user yang mendaftar saat ditolak.
     */
    public function notifyRegistrasiDitolak(User $user, string $catatan): void
    {
        $user->notify(new RegistrasiDitolakNotif($catatan));
    }

    // ─────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────

    private function getAkuntingUsers(): Collection
    {
        return User::where('role', UserRole::Akunting)
            ->where('is_active', true)
            ->get();
    }

    private function getKepalaUsers(): Collection
    {
        return User::where('role', UserRole::KepalaOperasional)
            ->where('is_active', true)
            ->get();
    }

    private function getSuperAdminUsers(): Collection
    {
        return User::where('role', UserRole::SuperAdmin)
            ->where('is_active', true)
            ->get();
    }

    private function getAllActivePic(): Collection
    {
        return User::where('role', UserRole::PicCabang)
            ->where('is_active', true)
            ->get();
    }

    private function getPicByCabang(int $cabangId): ?User
    {
        return User::where('role', UserRole::PicCabang)
            ->where('id_cabang', $cabangId)
            ->where('is_active', true)
            ->first();
    }
}
