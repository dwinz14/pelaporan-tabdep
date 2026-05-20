<?php

namespace App\Repositories;

use App\Contracts\Repositories\CabangRepositoryInterface;
use App\Models\Cabang;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CabangRepository implements CabangRepositoryInterface
{
    public function paginate(int $perPage = 15, string $search = ''): LengthAwarePaginator
    {
        return Cabang::query()
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('kode_cabang', 'like', "%{$search}%")
                    ->orWhere('nama_cabang', 'like', "%{$search}%");
            }))
            ->orderBy('kode_cabang')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getAllActive(): Collection
    {
        return Cabang::where('is_active', true)
            ->orderBy('kode_cabang')
            ->get();
    }

    public function findById(int $id): Cabang
    {
        return Cabang::findOrFail($id);
    }

    public function create(array $data): Cabang
    {
        return Cabang::create($data);
    }

    public function update(Cabang $cabang, array $data): Cabang
    {
        $cabang->update($data);
        return $cabang->fresh();
    }

    public function toggleActive(Cabang $cabang): Cabang
    {
        $cabang->update(['is_active' => ! $cabang->is_active]);
        return $cabang->fresh();
    }
}
