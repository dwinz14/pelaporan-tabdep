<?php

namespace App\Http\Controllers\Akunting;

use App\Http\Controllers\Controller;
use App\Models\PeriodeLaporan;
use App\Services\PeriodeLaporanService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AkuntingPeriodeController extends Controller
{
    public function __construct(
        private readonly PeriodeLaporanService $service,
    ) {}

    public function index(Request $request): View
    {
        $periodes = $this->service->list(15, [
            'tahun'  => $request->integer('tahun') ?: null,
            'status' => $request->string('status')->toString() ?: null,
        ]);

        $tahunList = range(now()->year, 2021);

        return view('akunting.periode.index', [
            'title'     => 'Daftar Periode Laporan',
            'subtitle'  => 'Verifikasi laporan stok dari seluruh cabang',
            'periodes'  => $periodes,
            'tahunList' => $tahunList,
            'tahun'     => $request->integer('tahun') ?: null,
            'status'    => $request->string('status')->toString(),
        ]);
    }

    public function show(PeriodeLaporan $periode): View
    {
        return view('akunting.periode.show', [
            'title'    => $periode->nama_periode,
            'subtitle' => 'Verifikasi laporan per cabang',
            'periode'  => $periode,
        ]);
    }
}
