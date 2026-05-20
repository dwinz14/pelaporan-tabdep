<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Laporan;

class Cabang extends Model
{
    protected $table = 'cabangs';

    protected $fillable = [
        'kode_cabang',
        'nama_cabang',
        'alamat',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_cabang');
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'id_cabang');
    }

    public function pencatatanLaporans()
    {
        return $this->hasMany(PencatatanLaporan::class, 'id_cabang');
    }

    // Scope untuk hanya cabang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
