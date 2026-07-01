<?php

namespace App\Exports;

use App\Exports\Sheets\PetunjukSheet;
use App\Exports\Sheets\ReferensiCabangSheet;
use App\Exports\Sheets\TemplateDataSheet;
use App\Models\Cabang;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $cabangs = Cabang::orderBy('kode_cabang')->get();

        return [
            new PetunjukSheet(),
            new TemplateDataSheet($cabangs),
            new ReferensiCabangSheet($cabangs),
        ];
    }
}
