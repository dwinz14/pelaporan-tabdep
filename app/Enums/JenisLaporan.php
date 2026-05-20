<?php

namespace App\Enums;

enum JenisLaporan: string
{
    case Tabungan = 'tabungan';
    case Deposito = 'deposito';

    public function label(): string
    {
        return match ($this) {
            self::Tabungan => 'Tabungan',
            self::Deposito => 'Deposito',
        };
    }
}
