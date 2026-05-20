<?php

namespace App\Repositories;

use App\Contracts\Repositories\LaporanRepositoryInterface;
use App\Enums\JenisLaporan;
use App\Models\Laporan;
use Illuminate\Database\Eloquent\Collection;

class LaporanRepository implements LaporanRepositoryInterface
{
    public function findOrFail(int $id): Laporan
    {
        return Laporan::with('periode')->findOrFail($id);
    }

    public function findByCabangPeriodeJenis(int $cabangId, int $periodeId, JenisLaporan $jenis): ?Laporan
    {
        return Laporan::where('id_cabang', $cabangId)
            ->where('id_periode', $periodeId)
            ->where('jenis', $jenis)
            ->first();
    }

    public function getByPeriode(int $periodeId): Collection
    {
        return Laporan::with('cabang')
            ->where('id_periode', $periodeId)
            ->orderBy('id_cabang')
            ->get();
    }

    public function getByCabang(int $cabangId, int $limit = 10): Collection
    {
        return Laporan::with('periode')
            ->where('id_cabang', $cabangId)
            ->orderBy('id_periode', 'desc')
            ->take($limit)
            ->get();
    }

    public function create(array $data): Laporan
    {
        return Laporan::create($data);
    }

    public function update(Laporan $laporan, array $data): Laporan
    {
        $laporan->update($data);
        return $laporan->fresh();
    }
}
