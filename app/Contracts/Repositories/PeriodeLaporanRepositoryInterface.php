<?php

namespace App\Contracts\Repositories;

use App\Models\PeriodeLaporan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PeriodeLaporanRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator;
    public function findById(int $id): PeriodeLaporan;
    public function getCurrent(): ?PeriodeLaporan;
    public function create(array $data): PeriodeLaporan;
    public function update(PeriodeLaporan $periode, array $data): PeriodeLaporan;
    public function resetAllCurrent(): void;
    public function existsByTanggal(string $tanggal): bool;
}
