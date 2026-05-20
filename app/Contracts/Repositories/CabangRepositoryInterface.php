<?php

namespace App\Contracts\Repositories;

use App\Models\Cabang;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CabangRepositoryInterface
{
    public function paginate(int $perPage, string $search): LengthAwarePaginator;
    public function getAllActive(): Collection;
    public function findById(int $id): Cabang;
    public function create(array $data): Cabang;
    public function update(Cabang $cabang, array $data): Cabang;
    public function toggleActive(Cabang $cabang): Cabang;
}
