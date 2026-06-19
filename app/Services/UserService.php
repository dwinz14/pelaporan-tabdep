<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public const DEFAULT_PASSWORD = 'Password@123';

    public function __construct(
        private readonly UserRepositoryInterface $repo,
    ) {}

    public function list(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->repo->paginate($perPage, $filters);
    }

    public function findById(int $id): User
    {
        return $this->repo->findById($id);
    }

    public function create(array $data): User
    {
        $role = UserRole::from($data['role']);

        return $this->repo->create([
            'nik'                 => strtoupper(trim($data['nik'])),
            'name'                => trim($data['name']),
            'email'               => isset($data['email']) ? trim($data['email']) : null,
            'password'            => Hash::make($data['password']),
            'role'                => $role,
            'id_cabang'           => $this->resolveIdCabang($role, $data['id_cabang'] ?? null),
            'is_active'           => true,
            // User dibuat langsung oleh admin = langsung approved
            'registration_status' => \App\Enums\RegistrationStatus::Approved,
            'registered_at'       => null,
        ]);
    }

    public function update(User $user, array $data): User
    {
        $role = UserRole::from($data['role']);

        $payload = [
            'nik'       => strtoupper(trim($data['nik'])),
            'name'      => trim($data['name']),
            'email'     => isset($data['email']) ? trim($data['email']) : null,
            'role'      => $role,
            'id_cabang' => $this->resolveIdCabang($role, $data['id_cabang'] ?? null),
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        return $this->repo->update($user, $payload);
    }

    public function toggleActive(User $user): User
    {
        return $this->repo->toggleActive($user);
    }

    public function resetPassword(User $user): User
    {
        return $this->repo->resetPassword($user, self::DEFAULT_PASSWORD);
    }



    /**
     * Reset password massal ke password default, sesuai array ID user.
     * Super Admin selalu dikecualikan.
     */
    public function bulkResetPassword(array $userIds): int
    {
        $users = User::whereIn('id', $userIds)
            ->where('role', '!=', UserRole::SuperAdmin->value)
            ->where('id', '!=', auth()->id())
            ->get();

        foreach ($users as $user) {
            $user->update(['password' => Hash::make(self::DEFAULT_PASSWORD)]);
        }

        if ($users->isNotEmpty()) {
            activity('user')
                ->causedBy(auth()->user())
                ->withProperties([
                    'jumlah' => $users->count(),
                    'nik_list' => $users->pluck('nik')->implode(', '),
                ])
                ->log("Reset password massal untuk {$users->count()} user");
        }

        return $users->count();
    }

    /**
     * Nonaktifkan massal user sesuai array ID user.
     * Super Admin dan user yang sedang login selalu dikecualikan.
     */
    public function bulkDeactivate(array $userIds): int
    {
        $users = User::whereIn('id', $userIds)
            ->where('role', '!=', UserRole::SuperAdmin->value)
            ->where('id', '!=', auth()->id())
            ->where('is_active', true) // skip yang sudah nonaktif
            ->get();

        foreach ($users as $user) {
            $user->update(['is_active' => false]);
        }

        if ($users->isNotEmpty()) {
            activity('user')
                ->causedBy(auth()->user())
                ->withProperties([
                    'jumlah'   => $users->count(),
                    'nik_list' => $users->pluck('nik')->implode(', '),
                ])
                ->log("Nonaktifkan massal {$users->count()} user");
        }

        return $users->count();
    }

    /**
     * Aktifkan massal user sesuai array ID user.
     * Super Admin dan user yang sedang login selalu dikecualikan.
     */
    public function bulkActivate(array $userIds): int
    {
        $users = User::whereIn('id', $userIds)
            ->where('role', '!=', UserRole::SuperAdmin->value)
            ->where('id', '!=', auth()->id())
            ->where('is_active', false) // skip yang sudah aktif
            ->get();

        foreach ($users as $user) {
            $user->update(['is_active' => true]);
        }

        if ($users->isNotEmpty()) {
            activity('user')
                ->causedBy(auth()->user())
                ->withProperties([
                    'jumlah'   => $users->count(),
                    'nik_list' => $users->pluck('nik')->implode(', '),
                ])
                ->log("Aktifkan massal {$users->count()} user");
        }

        return $users->count();
    }

    private function resolveIdCabang(UserRole $role, mixed $idCabang): ?int
    {
        return $role === UserRole::PicCabang ? (int) $idCabang : null;
    }
}
