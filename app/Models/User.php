<?php

namespace App\Models;

use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nik',
        'name',
        'email',
        'password',
        'id_cabang',
        'role',
        'is_active',
        'registration_status',
        'catatan_penolakan',
        'registered_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'password'            => 'hashed',
            'role'                => UserRole::class,
            'is_active'           => 'boolean',
            'registration_status' => RegistrationStatus::class,
            'registered_at'       => 'datetime',
        ];
    }

    // ─── Relations ────────────────────────────────────────────

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang');
    }

    // ─── Role Helpers ─────────────────────────────────────────

    public function isPicCabang(): bool
    {
        return $this->role === UserRole::PicCabang;
    }

    public function isAkunting(): bool
    {
        return $this->role === UserRole::Akunting;
    }

    public function isKepalaOperasional(): bool
    {
        return $this->role === UserRole::KepalaOperasional;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    public function dashboardRoute(): string
    {
        return $this->role->dashboardRoute();
    }

    public function roleLabel(): string
    {
        return $this->role->label();
    }

    // ─── Registration Helpers ─────────────────────────────────

    public function isPending(): bool
    {
        return $this->registration_status === RegistrationStatus::Pending;
    }

    public function isApproved(): bool
    {
        return $this->registration_status === RegistrationStatus::Approved;
    }

    public function isRejected(): bool
    {
        return $this->registration_status === RegistrationStatus::Rejected;
    }

    public function isSelfRegistered(): bool
    {
        return $this->registered_at !== null;
    }
}
