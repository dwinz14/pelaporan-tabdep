<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\PeriodeLaporan;
use App\Services\PeriodeLaporanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KepalaPeriodeController extends Controller
{
    public function __construct(
        private readonly PeriodeLaporanService $service,
    ) {}

    public function index(Request $request): View
    {
        // Semua periode yang siap verifikasi final (semua cabang verified + pending)
        $periodeSiap = PeriodeLaporan::where('status_operasional', 'pending')
            ->withCount([
                'laporans as total_cabang' => fn($q) => $q->where('jenis', 'tabungan'),
                'laporans as total_verified' => fn($q) => $q->where('jenis', 'tabungan')
                    ->where('status_verifikasi', 'verified_accounting'),
            ])
            ->orderBy('tanggal_akhir', 'desc')
            ->paginate(10);

        // Riwayat periode yang sudah final
        $periodeSelesai = PeriodeLaporan::where('status_operasional', 'verified')
            ->orderBy('tanggal_akhir', 'desc')
            ->take(10)
            ->get();

        return view('kepala.periode.index', [
            'title'          => 'Dashboard Verifikasi Final',
            'subtitle'       => 'Periode yang menunggu persetujuan akhir',
            'periodeSiap'    => $periodeSiap,
            'periodeSelesai' => $periodeSelesai,
        ]);
    }

    public function show(PeriodeLaporan $periode): View
    {
        return view('kepala.periode.show', [
            'title'    => $periode->nama_periode,
            'subtitle' => 'Review & Verifikasi Final',
            'periode'  => $periode,
        ]);
    }

    public function finalize(PeriodeLaporan $periode): RedirectResponse
    {
        $this->service->finalVerify($periode);

        return redirect()->route('kepala.periode.index')
            ->with('success', "Periode \"{$periode->nama_periode}\" berhasil diverifikasi final. Data periode telah dikunci.");
    }
}
