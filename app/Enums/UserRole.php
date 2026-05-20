<?php

namespace App\Enums;

enum UserRole: string
{
    case PicCabang         = 'pic_cabang';
    case Akunting          = 'akunting';
    case KepalaOperasional = 'kepala_operasional';
    case SuperAdmin        = 'super_admin';

    public function label(): string
    {
        return match ($this) {
            self::PicCabang         => 'PIC Cabang',
            self::Akunting          => 'Akunting Pusat',
            self::KepalaOperasional => 'Kepala Operasional',
            self::SuperAdmin        => 'Super Admin',
        };
    }

    public function dashboardRoute(): string
    {
        return match ($this) {
            self::PicCabang         => 'pic.dashboard',
            self::Akunting          => 'akunting.dashboard',
            self::KepalaOperasional => 'kepala.dashboard',
            self::SuperAdmin        => 'admin.dashboard',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PicCabang         => 'sky',
            self::Akunting          => 'emerald',
            self::KepalaOperasional => 'amber',
            self::SuperAdmin        => 'rose',
        };
    }
}
