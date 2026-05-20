<?php

namespace App\Contracts\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator;
    public function findById(int $id): User;
    public function create(array $data): User;
    public function update(User $user, array $data): User;
    public function toggleActive(User $user): User;
    public function resetPassword(User $user, string $password): User;
}
