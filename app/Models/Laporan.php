<?php

namespace App\Models;

use App\Enums\JenisLaporan;
use App\Enums\StatusVerifikasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    protected $table = 'laporans';

    protected $fillable = [
        'id_cabang',
        'id_periode',
        'jenis',
        'saldo_awal',
        'tambahan_stok',
        'jumlah_digunakan',
        'jml_dibatalkan_rusak',
        'jml_dibatalkan_hilang',
        'saldo_akhir',
        'status_verifikasi',
        'tgl_submit',
        'tgl_verifikasi_akunting',
        'verified_by_akunting',
        'catatan_revisi',
    ];

    protected function casts(): array
    {
        return [
            'jenis'                  => JenisLaporan::class,
            'status_verifikasi'      => StatusVerifikasi::class,
            'tgl_submit'             => 'datetime',
            'tgl_verifikasi_akunting' => 'datetime',
        ];
    }

    // ─── Relations ────────────────────────────────────────────
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'id_cabang');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeLaporan::class, 'id_periode');
    }

    public function verifiedByAkunting(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_akunting');
    }

    // ─── Helpers ──────────────────────────────────────────────
    public function hitungSaldoAkhir(): int
    {
        return $this->saldo_awal
            + $this->tambahan_stok
            - $this->jumlah_digunakan
            - $this->jml_dibatalkan_rusak
            - $this->jml_dibatalkan_hilang;
    }

    public function canEdit(): bool
    {
        return $this->status_verifikasi->canEdit()
            && ! $this->periode->isLocked();
    }
}
