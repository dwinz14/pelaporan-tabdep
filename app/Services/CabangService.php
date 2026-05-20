<?php

namespace App\Services;

use App\Contracts\Repositories\CabangRepositoryInterface;
use App\Models\Cabang;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CabangService
{
    public function __construct(
        private readonly CabangRepositoryInterface $repo,
    ) {}

    public function list(int $perPage = 15, string $search = ''): LengthAwarePaginator
    {
        return $this->repo->paginate($perPage, $search);
    }

    public function getAllActive(): Collection
    {
        return $this->repo->getAllActive();
    }

    public function findById(int $id): Cabang
    {
        return $this->repo->findById($id);
    }

    public function create(array $data): Cabang
    {
        return $this->repo->create([
            'kode_cabang' => strtoupper(trim($data['kode_cabang'])),
            'nama_cabang' => trim($data['nama_cabang']),
            'alamat'      => isset($data['alamat']) ? trim($data['alamat']) : null,
            'is_active'   => true,
        ]);
    }

    public function update(Cabang $cabang, array $data): Cabang
    {
        return $this->repo->update($cabang, [
            'kode_cabang' => strtoupper(trim($data['kode_cabang'])),
            'nama_cabang' => trim($data['nama_cabang']),
            'alamat'      => isset($data['alamat']) ? trim($data['alamat']) : null,
        ]);
    }

    public function toggleActive(Cabang $cabang): Cabang
    {
        return $this->repo->toggleActive($cabang);
    }
}
