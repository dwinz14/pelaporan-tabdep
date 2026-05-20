<?php

namespace App\Enums;

enum StatusVerifikasi: string
{
    case Draft              = 'draft';
    case Submitted          = 'submitted';
    case VerifiedAccounting = 'verified_accounting';
    case RevisionRequested  = 'revision_requested';

    public function label(): string
    {
        return match ($this) {
            self::Draft              => 'Draft',
            self::Submitted          => 'Menunggu Verifikasi',
            self::VerifiedAccounting => 'Terverifikasi',
            self::RevisionRequested  => 'Perlu Revisi',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Draft              => 'bg-gray-100 text-gray-700',
            self::Submitted          => 'bg-blue-100 text-blue-800',
            self::VerifiedAccounting => 'bg-emerald-100 text-emerald-800',
            self::RevisionRequested  => 'bg-orange-100 text-orange-800',
        };
    }

    public function canEdit(): bool
    {
        return in_array($this, [self::Draft, self::RevisionRequested]);
    }
}
