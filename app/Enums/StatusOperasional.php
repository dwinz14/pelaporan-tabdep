<?php

namespace App\Enums;

enum StatusOperasional: string
{
    case Pending  = 'pending';
    case Verified = 'verified';

    public function label(): string
    {
        return match ($this) {
            self::Pending  => 'Proses Verifikasi',
            self::Verified => 'Final',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending  => 'bg-amber-100 text-amber-800',
            self::Verified => 'bg-emerald-100 text-emerald-800',
        };
    }
}
