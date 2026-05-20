<?php

namespace App\Models;

use App\Enums\JenisLaporan;
use App\Enums\TipeTransaksi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PencatatanLaporan extends Model
{
    protected $table = 'pencatatan_laporans';

    protected $fillable = [
        'id_cabang',
        'id_periode',
        'jenis',
        'tipe_transaksi',
        'jumlah',
        'keterangan',
        'tanggal_catat',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'jenis'          => JenisLaporan::class,
            'tipe_transaksi' => TipeTransaksi::class,
            'tanggal_catat'  => 'date',
            'jumlah'         => 'integer',
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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
