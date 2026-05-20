<?php

namespace App\Http\Controllers\Akunting;

use App\Exports\StokLaporanExport;
use App\Http\Controllers\Controller;
use App\Models\PeriodeLaporan;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function exportPeriode(PeriodeLaporan $periode): BinaryFileResponse
    {
        $filename = 'Laporan_Stock_Periode_'
            . $periode->tanggal_akhir->format('Y-m-d')
            . '.xlsx';

        activity('export')
            ->causedBy(auth()->user())
            ->withProperties([
                'periode'  => $periode->nama_periode,
                'filename' => $filename,
            ])
            ->log("Export Excel periode {$periode->nama_periode} oleh {$this->userName()}");

        return Excel::download(new StokLaporanExport($periode), $filename);
    }

    private function userName(): string
    {
        return auth()->user()->name ?? 'unknown';
    }
}
