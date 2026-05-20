<?php

namespace App\Exports;

use App\Exports\Sheets\DepositoSheet;
use App\Exports\Sheets\TabunganSheet;
use App\Models\Cabang;
use App\Models\Laporan;
use App\Models\PeriodeLaporan;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StokLaporanExport implements WithMultipleSheets
{
    private \Illuminate\Database\Eloquent\Collection $cabangs;
    private \Illuminate\Database\Eloquent\Collection $laporans;

    public function __construct(
        private readonly PeriodeLaporan $periode,
    ) {
        $this->cabangs  = Cabang::where('is_active', true)->orderBy('kode_cabang')->get();
        $this->laporans = Laporan::where('id_periode', $periode->id)
            ->get();
    }

    public function sheets(): array
    {
        return [
            new TabunganSheet($this->periode, $this->cabangs, $this->laporans),
            new DepositoSheet($this->periode, $this->cabangs, $this->laporans),
        ];
    }
}
