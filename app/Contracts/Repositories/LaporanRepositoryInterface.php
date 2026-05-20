<?php

namespace App\Contracts\Repositories;

use App\Enums\JenisLaporan;
use App\Models\Laporan;
use Illuminate\Database\Eloquent\Collection;

interface LaporanRepositoryInterface
{
    public function findOrFail(int $id): Laporan;
    public function findByCabangPeriodeJenis(int $cabangId, int $periodeId, JenisLaporan $jenis): ?Laporan;
    public function getByPeriode(int $periodeId): Collection;
    public function getByCabang(int $cabangId, int $limit): Collection;
    public function create(array $data): Laporan;
    public function update(Laporan $laporan, array $data): Laporan;
}
