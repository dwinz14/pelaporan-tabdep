<?php

namespace App\Services;

use App\Enums\JenisLaporan;
use App\Enums\StatusVerifikasi;
use App\Enums\TipeTransaksi;
use App\Models\Cabang;
use App\Models\Laporan;
use App\Models\PencatatanLaporan;
use App\Models\PeriodeLaporan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MonitoringService
{
    // ─────────────────────────────────────────────────────────
    // OVERVIEW: stok tabungan & deposito semua cabang
    // ─────────────────────────────────────────────────────────

    /**
     * Data overview untuk semua cabang aktif.
     * Dipakai di halaman index monitoring.
     */
    public function getAllCabangOverview(): Collection
    {
        $cabangs       = Cabang::where('is_active', true)->orderBy('kode_cabang')->get();
        $periodeAktif  = PeriodeLaporan::where('is_current', true)->first();

        // Batch query: saldo terkini semua cabang
        $saldoMap = $this->buildSaldoMap($cabangs->pluck('id'));

        // Batch query: pencatatan hari ini semua cabang
        $today           = now()->format('Y-m-d');
        $pencatatanHariIni = PencatatanLaporan::whereIn('id_cabang', $cabangs->pluck('id'))
            ->whereDate('tanggal_catat', $today)
            ->get()
            ->groupBy('id_cabang');

        // Batch query: laporan periode aktif semua cabang
        $laporanPeriodeAktif = $periodeAktif
            ? Laporan::where('id_periode', $periodeAktif->id)
                ->whereIn('id_cabang', $cabangs->pluck('id'))
                ->get()
                ->groupBy('id_cabang')
            : collect();

        return $cabangs->map(function ($cabang) use (
            $saldoMap, $pencatatanHariIni,
            $laporanPeriodeAktif, $periodeAktif
        ) {
            $saldo           = $saldoMap[$cabang->id] ?? $this->emptySaldo();
            $pencatatan      = $pencatatanHariIni->get($cabang->id, collect());
            $laporanAktif    = $laporanPeriodeAktif->get($cabang->id, collect());

            $laporanTab = $laporanAktif->first(fn ($l) => $l->jenis === JenisLaporan::Tabungan);
            $laporanDep = $laporanAktif->first(fn ($l) => $l->jenis === JenisLaporan::Deposito);

            // Pencatatan hari ini per jenis
            $hariIniTab = $pencatatan->filter(fn ($p) => $p->jenis === JenisLaporan::Tabungan);
            $hariIniDep = $pencatatan->filter(fn ($p) => $p->jenis === JenisLaporan::Deposito);

            return [
                'cabang'              => $cabang,

                // Saldo terkini
                'saldo_tab'           => $saldo['tabungan']['terkini'],
                'saldo_dep'           => $saldo['deposito']['terkini'],
                'saldo_base_tab'      => $saldo['tabungan']['base'],
                'saldo_base_dep'      => $saldo['deposito']['base'],
                'delta_tab'           => $saldo['tabungan']['terkini'] - $saldo['tabungan']['base'],
                'delta_dep'           => $saldo['deposito']['terkini'] - $saldo['deposito']['base'],

                // Pencatatan hari ini
                'pencatatan_tab_hari_ini' => $hariIniTab->count(),
                'pencatatan_dep_hari_ini' => $hariIniDep->count(),
                'masuk_tab_hari_ini'      => $hariIniTab
                    ->where('tipe_transaksi', TipeTransaksi::TambahanStok)->sum('jumlah'),
                'keluar_tab_hari_ini'     => $hariIniTab
                    ->where('tipe_transaksi', '!=', TipeTransaksi::TambahanStok)->sum('jumlah'),
                'masuk_dep_hari_ini'      => $hariIniDep
                    ->where('tipe_transaksi', TipeTransaksi::TambahanStok)->sum('jumlah'),
                'keluar_dep_hari_ini'     => $hariIniDep
                    ->where('tipe_transaksi', '!=', TipeTransaksi::TambahanStok)->sum('jumlah'),

                // Status laporan periode aktif
                'status_laporan_tab'  => $laporanTab?->status_verifikasi,
                'status_laporan_dep'  => $laporanDep?->status_verifikasi,
                'periode_aktif'       => $periodeAktif,
            ];
        });
    }

    /**
     * Total agregat semua cabang aktif.
     */
    public function getTotalAgregat(): array
    {
        $cabangs  = Cabang::where('is_active', true)->pluck('id');
        $saldoMap = $this->buildSaldoMap($cabangs);

        $totalTab = 0;
        $totalDep = 0;
        $masukHariIniTab = 0;
        $keluarHariIniTab = 0;
        $masukHariIniDep = 0;
        $keluarHariIniDep = 0;

        foreach ($saldoMap as $data) {
            $totalTab += $data['tabungan']['terkini'];
            $totalDep += $data['deposito']['terkini'];
        }

        $today = now()->format('Y-m-d');
        $pencatatanHariIni = PencatatanLaporan::whereIn('id_cabang', $cabangs)
            ->whereDate('tanggal_catat', $today)
            ->get();

        $masukHariIniTab  = $pencatatanHariIni->filter(fn ($p) =>
            $p->jenis === JenisLaporan::Tabungan && $p->tipe_transaksi === TipeTransaksi::TambahanStok
        )->sum('jumlah');

        $keluarHariIniTab = $pencatatanHariIni->filter(fn ($p) =>
            $p->jenis === JenisLaporan::Tabungan && $p->tipe_transaksi !== TipeTransaksi::TambahanStok
        )->sum('jumlah');

        $masukHariIniDep  = $pencatatanHariIni->filter(fn ($p) =>
            $p->jenis === JenisLaporan::Deposito && $p->tipe_transaksi === TipeTransaksi::TambahanStok
        )->sum('jumlah');

        $keluarHariIniDep = $pencatatanHariIni->filter(fn ($p) =>
            $p->jenis === JenisLaporan::Deposito && $p->tipe_transaksi !== TipeTransaksi::TambahanStok
        )->sum('jumlah');

        return [
            'total_tab'           => $totalTab,
            'total_dep'           => $totalDep,
            'masuk_tab_hari_ini'  => $masukHariIniTab,
            'keluar_tab_hari_ini' => $keluarHariIniTab,
            'masuk_dep_hari_ini'  => $masukHariIniDep,
            'keluar_dep_hari_ini' => $keluarHariIniDep,
            'total_cabang_aktif'  => $cabangs->count(),
        ];
    }

    // ─────────────────────────────────────────────────────────
    // DETAIL: satu cabang
    // ─────────────────────────────────────────────────────────

    public function getCabangDetail(Cabang $cabang): array
    {
        $saldo = $this->buildSaldoMap(collect([$cabang->id]))[$cabang->id]
            ?? $this->emptySaldo();

        // Riwayat pencatatan 30 hari
        $pencatatan30Hari = PencatatanLaporan::where('id_cabang', $cabang->id)
            ->where('tanggal_catat', '>=', now()->subDays(30)->format('Y-m-d'))
            ->orderBy('tanggal_catat', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Pergerakan harian 14 hari terakhir (untuk grafik)
        $pergerakanHarian = $this->getPergerakanHarian($cabang->id, 14);

        // Riwayat 5 periode terakhir
        $riwayatPeriode = Laporan::where('id_cabang', $cabang->id)
            ->with('periode')
            ->join('periode_laporans', 'laporans.id_periode', '=', 'periode_laporans.id')
            ->orderBy('periode_laporans.tanggal_akhir', 'desc')
            ->select('laporans.*', 'periode_laporans.tanggal_akhir as tgl_periode')
            ->take(10)
            ->get()
            ->groupBy('id_periode')
            ->take(5)
            ->map(function ($group) {
                return [
                    'periode'   => $group->first()->periode,
                    'tabungan'  => $group->first(fn ($l) => $l->jenis === JenisLaporan::Tabungan),
                    'deposito'  => $group->first(fn ($l) => $l->jenis === JenisLaporan::Deposito),
                ];
            })->values();

        // Breakdown pencatatan 30 hari per tipe
        $breakdownTab = $this->getBreakdown($pencatatan30Hari, JenisLaporan::Tabungan);
        $breakdownDep = $this->getBreakdown($pencatatan30Hari, JenisLaporan::Deposito);

        // Laporan periode aktif
        $periodeAktif   = PeriodeLaporan::where('is_current', true)->first();
        $laporanAktif   = $periodeAktif
            ? Laporan::where('id_periode', $periodeAktif->id)
                ->where('id_cabang', $cabang->id)
                ->get()
                ->keyBy(fn ($l) => $l->jenis->value)
            : collect();

        return [
            'cabang'              => $cabang,
            'saldo'               => $saldo,
            'pencatatan_30_hari'  => $pencatatan30Hari,
            'pergerakan_harian'   => $pergerakanHarian,
            'riwayat_periode'     => $riwayatPeriode,
            'breakdown_tab'       => $breakdownTab,
            'breakdown_dep'       => $breakdownDep,
            'periode_aktif'       => $periodeAktif,
            'laporan_aktif'       => $laporanAktif,
        ];
    }

    // ─────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────

    /**
     * Hitung saldo terkini untuk setiap cabang dalam batch.
     * Saldo terkini = saldo_akhir laporan terakhir + pencatatan setelahnya s/d hari ini.
     */
    private function buildSaldoMap(Collection $cabangIds): array
    {
        $map = [];

        foreach ($cabangIds as $cabangId) {
            $map[$cabangId] = [
                'tabungan' => $this->hitungSaldoTerkini($cabangId, JenisLaporan::Tabungan),
                'deposito' => $this->hitungSaldoTerkini($cabangId, JenisLaporan::Deposito),
            ];
        }

        return $map;
    }

    private function hitungSaldoTerkini(int $cabangId, JenisLaporan $jenis): array
    {
        // Laporan terakhir yang submitted/verified
        $lastLaporan = Laporan::where('id_cabang', $cabangId)
            ->where('jenis', $jenis)
            ->whereIn('status_verifikasi', [
                StatusVerifikasi::Submitted->value,
                StatusVerifikasi::VerifiedAccounting->value,
            ])
            ->join('periode_laporans', 'laporans.id_periode', '=', 'periode_laporans.id')
            ->orderBy('periode_laporans.tanggal_akhir', 'desc')
            ->select('laporans.*', 'periode_laporans.tanggal_akhir as tgl_periode_akhir')
            ->first();

        $saldoBase  = $lastLaporan?->saldo_akhir ?? 0;
        $lockDate   = $lastLaporan?->tgl_periode_akhir;

        // Pencatatan setelah lock date
        $query = PencatatanLaporan::where('id_cabang', $cabangId)->where('jenis', $jenis);
        if ($lockDate) {
            $query->where('tanggal_catat', '>', $lockDate);
        }

        $pencatatans = $query->get();
        $masuk  = $pencatatans->where('tipe_transaksi', TipeTransaksi::TambahanStok)->sum('jumlah');
        $keluar = $pencatatans->where('tipe_transaksi', '!=', TipeTransaksi::TambahanStok)->sum('jumlah');

        return [
            'base'    => $saldoBase,
            'terkini' => $saldoBase + $masuk - $keluar,
            'masuk'   => $masuk,
            'keluar'  => $keluar,
            'lock_at' => $lockDate,
            'dari'    => $lockDate ? Carbon::parse($lockDate)->addDay()->format('d/m/Y') : 'Awal',
        ];
    }

    private function getPergerakanHarian(int $cabangId, int $days): array
    {
        $result = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');

            $pencatatan = PencatatanLaporan::where('id_cabang', $cabangId)
                ->whereDate('tanggal_catat', $date)
                ->get();

            $result[] = [
                'tanggal'    => $date,
                'label'      => Carbon::parse($date)->format('d/m'),
                'masuk_tab'  => $pencatatan->filter(fn ($p) =>
                    $p->jenis === JenisLaporan::Tabungan && $p->tipe_transaksi === TipeTransaksi::TambahanStok
                )->sum('jumlah'),
                'keluar_tab' => $pencatatan->filter(fn ($p) =>
                    $p->jenis === JenisLaporan::Tabungan && $p->tipe_transaksi !== TipeTransaksi::TambahanStok
                )->sum('jumlah'),
                'masuk_dep'  => $pencatatan->filter(fn ($p) =>
                    $p->jenis === JenisLaporan::Deposito && $p->tipe_transaksi === TipeTransaksi::TambahanStok
                )->sum('jumlah'),
                'keluar_dep' => $pencatatan->filter(fn ($p) =>
                    $p->jenis === JenisLaporan::Deposito && $p->tipe_transaksi !== TipeTransaksi::TambahanStok
                )->sum('jumlah'),
            ];
        }

        return $result;
    }

    private function getBreakdown(Collection $pencatatan, JenisLaporan $jenis): array
    {
        $filtered = $pencatatan->filter(fn ($p) => $p->jenis === $jenis);

        return [
            'tambahan'  => $filtered->where('tipe_transaksi', TipeTransaksi::TambahanStok)->sum('jumlah'),
            'digunakan' => $filtered->where('tipe_transaksi', TipeTransaksi::Digunakan)->sum('jumlah'),
            'rusak'     => $filtered->where('tipe_transaksi', TipeTransaksi::DibatalkanRusak)->sum('jumlah'),
            'hilang'    => $filtered->where('tipe_transaksi', TipeTransaksi::DibatalkanHilang)->sum('jumlah'),
            'total'     => $filtered->count(),
        ];
    }

    private function emptySaldo(): array
    {
        return [
            'tabungan' => ['base' => 0, 'terkini' => 0, 'masuk' => 0, 'keluar' => 0, 'lock_at' => null, 'dari' => 'Awal'],
            'deposito' => ['base' => 0, 'terkini' => 0, 'masuk' => 0, 'keluar' => 0, 'lock_at' => null, 'dari' => 'Awal'],
        ];
    }
}