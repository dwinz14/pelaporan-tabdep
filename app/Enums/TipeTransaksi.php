<?php

namespace App\Enums;

enum TipeTransaksi: string
{
    case TambahanStok      = 'tambahan_stok';
    case Digunakan         = 'digunakan';
    case DibatalkanRusak   = 'dibatalkan_rusak';
    case DibatalkanHilang  = 'dibatalkan_hilang';

    public function label(): string
    {
        return match ($this) {
            self::TambahanStok     => 'Tambahan Stok',
            self::Digunakan        => 'Digunakan',
            self::DibatalkanRusak  => 'Dibatalkan (Rusak/Salah Cetak)',
            self::DibatalkanHilang => 'Dibatalkan (Hilang)',
        };
    }

    public function labelShort(): string
    {
        return match ($this) {
            self::TambahanStok     => 'Tambahan Stok',
            self::Digunakan        => 'Digunakan',
            self::DibatalkanRusak  => 'Batal Rusak',
            self::DibatalkanHilang => 'Batal Hilang',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::TambahanStok     => 'bg-emerald-100 text-emerald-800',
            self::Digunakan        => 'bg-blue-100 text-blue-800',
            self::DibatalkanRusak  => 'bg-orange-100 text-orange-800',
            self::DibatalkanHilang => 'bg-red-100 text-red-800',
        };
    }

    // Field yang bersesuaian di tabel laporans
    public function laporanField(): string
    {
        return match ($this) {
            self::TambahanStok     => 'tambahan_stok',
            self::Digunakan        => 'jumlah_digunakan',
            self::DibatalkanRusak  => 'jml_dibatalkan_rusak',
            self::DibatalkanHilang => 'jml_dibatalkan_hilang',
        };
    }

    public function isAddition(): bool
    {
        return $this === self::TambahanStok;
    }
}
