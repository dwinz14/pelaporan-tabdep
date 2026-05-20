<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nik',
        'name',
        'email',
        'password',
        'id_cabang',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
            'is_active'         => 'boolean',
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
}
