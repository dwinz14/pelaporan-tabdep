<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public const DEFAULT_PASSWORD = 'password123';

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
            'nik'       => strtoupper(trim($data['nik'])),
            'name'      => trim($data['name']),
            'email'     => isset($data['email']) ? trim($data['email']) : null,
            'password'  => Hash::make($data['password']),
            'role'      => $role,
            'id_cabang' => $this->resolveIdCabang($role, $data['id_cabang'] ?? null),
            'is_active' => true,
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

    private function resolveIdCabang(UserRole $role, mixed $idCabang): ?int
    {
        return $role === UserRole::PicCabang ? (int) $idCabang : null;
    }
}
