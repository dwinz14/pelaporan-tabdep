<?php

namespace App\Repositories;

use App\Contracts\Repositories\PeriodeLaporanRepositoryInterface;
use App\Models\PeriodeLaporan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PeriodeLaporanRepository implements PeriodeLaporanRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return PeriodeLaporan::query()
            ->when(
                $filters['tahun'] ?? null,
                fn($q, $tahun) =>
                $q->whereYear('tanggal_akhir', $tahun)
            )
            ->when(
                $filters['status'] ?? null,
                fn($q, $status) =>
                $q->where('status_operasional', $status)
            )
            ->withCount([
                'laporans as total_cabang' => fn($q) => $q->where('jenis', 'tabungan'),
                'laporans as total_submitted' => fn($q) => $q->where('jenis', 'tabungan')
                    ->whereIn('status_verifikasi', ['submitted', 'verified_accounting']),
                'laporans as total_verified' => fn($q) => $q->where('jenis', 'tabungan')
                    ->where('status_verifikasi', 'verified_accounting'),
            ])
            ->orderBy('tanggal_akhir', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $id): PeriodeLaporan
    {
        return PeriodeLaporan::findOrFail($id);
    }

    public function getCurrent(): ?PeriodeLaporan
    {
        return PeriodeLaporan::where('is_current', true)->first();
    }

    public function create(array $data): PeriodeLaporan
    {
        return PeriodeLaporan::create($data);
    }

    public function update(PeriodeLaporan $periode, array $data): PeriodeLaporan
    {
        $periode->update($data);
        return $periode->fresh();
    }

    public function resetAllCurrent(): void
    {
        PeriodeLaporan::where('is_current', true)->update(['is_current' => false]);
    }

    public function existsByTanggal(string $tanggal): bool
    {
        return PeriodeLaporan::where('tanggal_akhir', $tanggal)->exists();
    }
}
