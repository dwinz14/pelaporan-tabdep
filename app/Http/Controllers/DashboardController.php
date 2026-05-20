<?php

namespace App\Http\Controllers;

use App\Enums\JenisLaporan;
use App\Enums\StatusVerifikasi;
use App\Models\Cabang;
use App\Models\Laporan;
use App\Models\PeriodeLaporan;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function pic(): View
    {
        $user    = auth()->user();
        $periode = PeriodeLaporan::where('is_current', true)->first();

        // Data laporan periode aktif (untuk overview card)
        $laporanAktif = collect();
        if ($periode && $user->id_cabang) {
            $laporanAktif = \App\Models\Laporan::where('id_periode', $periode->id)
                ->where('id_cabang', $user->id_cabang)
                ->get()
                ->keyBy(fn($l) => $l->jenis->value);
        }

        // 5 periode terakhir untuk ringkasan riwayat
        $riwayatSingkat = collect();
        if ($user->id_cabang) {
            $riwayatSingkat = PeriodeLaporan::whereHas(
                'laporans',
                fn($q) =>
                $q->where('id_cabang', $user->id_cabang)
            )
                ->where('is_current', false)
                ->orderBy('tanggal_akhir', 'desc')
                ->with([
                    'laporans' => fn($q) =>
                    $q->where('id_cabang', $user->id_cabang)
                ])
                ->take(5)
                ->get();
        }

        return view('dashboard.pic', [
            'title'          => 'Dashboard',
            'subtitle'       => 'Selamat datang, ' . $user->name,
            'periode'        => $periode,
            'laporanAktif'   => $laporanAktif,
            'riwayatSingkat' => $riwayatSingkat,
        ]);
    }

    public function akunting(): View
    {
        $totalCabang  = Cabang::where('is_active', true)->count();
        $periodeAktif = PeriodeLaporan::where('is_current', true)->first();

        $totalSubmitted = 0;
        $totalVerified  = 0;
        $totalRevisi    = 0;

        if ($periodeAktif) {
            $laporanTab = Laporan::where('id_periode', $periodeAktif->id)
                ->where('jenis', JenisLaporan::Tabungan)
                ->get();

            $totalSubmitted = $laporanTab->where('status_verifikasi', StatusVerifikasi::Submitted)->count();
            $totalVerified  = $laporanTab->where('status_verifikasi', StatusVerifikasi::VerifiedAccounting)->count();
            $totalRevisi    = $laporanTab->where('status_verifikasi', StatusVerifikasi::RevisionRequested)->count();
        }

        // 5 periode terbaru untuk quick access
        $periodeRecent = PeriodeLaporan::orderBy('tanggal_akhir', 'desc')
            ->withCount([
                'laporans as total_cabang'   => fn($q) => $q->where('jenis', 'tabungan'),
                'laporans as total_verified' => fn($q) => $q->where('jenis', 'tabungan')
                    ->where('status_verifikasi', 'verified_accounting'),
            ])
            ->take(5)
            ->get();

        return view('dashboard.akunting', [
            'title'          => 'Dashboard Akunting',
            'subtitle'       => 'Monitor & verifikasi laporan seluruh cabang',
            'totalCabang'    => $totalCabang,
            'periodeAktif'   => $periodeAktif,
            'totalSubmitted' => $totalSubmitted,
            'totalVerified'  => $totalVerified,
            'totalRevisi'    => $totalRevisi,
            'periodeRecent'  => $periodeRecent,
        ]);
    }

    public function kepala(): View
    {
        $periodePending = PeriodeLaporan::where('status_operasional', 'pending')
            ->withCount([
                'laporans as total_cabang'   => fn($q) => $q->where('jenis', 'tabungan'),
                'laporans as total_verified' => fn($q) => $q->where('jenis', 'tabungan')
                    ->where('status_verifikasi', 'verified_accounting'),
            ])
            ->get();

        $siapVerifikasi = $periodePending
            ->filter(fn($p) => ($p->total_cabang ?? 0) > 0
                && $p->total_cabang === $p->total_verified)
            ->count();

        $belumLengkap = $periodePending->count() - $siapVerifikasi;
        $totalFinal   = PeriodeLaporan::where('status_operasional', 'verified')->count();

        return view('dashboard.kepala', [
            'title'          => 'Dashboard Kepala Operasional',
            'subtitle'       => 'Verifikasi final periode laporan',
            'siapVerifikasi' => $siapVerifikasi,
            'belumLengkap'   => $belumLengkap,
            'totalFinal'     => $totalFinal,
        ]);
    }

    public function admin(): View
    {
        $totalCabang  = Cabang::where('is_active', true)->count();
        $totalUser    = User::where('is_active', true)->count();
        $totalPeriode = PeriodeLaporan::count();
        $periodeAktif = PeriodeLaporan::where('is_current', true)->first();

        // Saldo terkini per cabang (dari periode dengan tanggal terbaru yang verified)
        $saldoTerkini = $this->getSaldoTerkini();

        return view('dashboard.admin', [
            'title'        => 'Dashboard Super Admin',
            'subtitle'     => 'Kelola sistem informasi stok buku',
            'totalCabang'  => $totalCabang,
            'totalUser'    => $totalUser,
            'totalPeriode' => $totalPeriode,
            'periodeAktif' => $periodeAktif,
            'saldoTerkini' => $saldoTerkini,
        ]);
    }

    // ─── Private Helper ───────────────────────────────────────

    private function getSaldoTerkini(): Collection
    {
        // Ambil periode terakhir yang ada laporannya
        $lastPeriode = PeriodeLaporan::orderBy('tanggal_akhir', 'desc')->first();

        if (! $lastPeriode) {
            return collect();
        }

        return Cabang::where('is_active', true)
            ->orderBy('kode_cabang')
            ->with([
                'laporans' => fn($q) =>
                $q->where('id_periode', $lastPeriode->id)
            ])
            ->get()
            ->map(function ($cabang) {
                $tab = $cabang->laporans->firstWhere('jenis', JenisLaporan::Tabungan);
                $dep = $cabang->laporans->firstWhere('jenis', JenisLaporan::Deposito);

                return [
                    'cabang'       => $cabang,
                    'saldo_tab'    => $tab?->saldo_akhir ?? 0,
                    'saldo_dep'    => $dep?->saldo_akhir ?? 0,
                    'status_tab'   => $tab?->status_verifikasi,
                    'status_dep'   => $dep?->status_verifikasi,
                ];
            });
    }
}
