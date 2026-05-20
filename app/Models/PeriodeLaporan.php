<?php

namespace App\Models;

use App\Enums\StatusOperasional;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeLaporan extends Model
{
    protected $table = 'periode_laporans';

    protected $fillable = [
        'tanggal_akhir',
        'nama_periode',
        'status_operasional',
        'tgl_verifikasi_operasional',
        'verified_by_operasional',
        'is_current',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_akhir'              => 'date',
            'tgl_verifikasi_operasional' => 'datetime',
            'status_operasional'         => StatusOperasional::class,
            'is_current'                 => 'boolean',
        ];
    }

    // ─── Relations ────────────────────────────────────────────
    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class, 'id_periode');
    }

    public function verifiedByOperasional()
    {
        return $this->belongsTo(User::class, 'verified_by_operasional');
    }

    public function pencatatanLaporans(): HasMany
    {
        return $this->hasMany(PencatatanLaporan::class, 'id_periode');
    }

    // ─── Helpers ──────────────────────────────────────────────
    public function isLocked(): bool
    {
        return $this->status_operasional === StatusOperasional::Verified;
    }

    public function totalCabangAktif(): int
    {
        return $this->laporans()
            ->where('jenis', 'tabungan')
            ->count();
    }

    public function totalVerifiedAkunting(): int
    {
        return $this->laporans()
            ->where('jenis', 'tabungan')
            ->where('status_verifikasi', 'verified_accounting')
            ->count();
    }

    public function semuaCabangVerified(): bool
    {
        $total    = $this->totalCabangAktif();
        $verified = $this->totalVerifiedAkunting();

        return $total > 0 && $total === $verified;
    }
}
