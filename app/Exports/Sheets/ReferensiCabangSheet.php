<?php

namespace App\Exports\Sheets;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReferensiCabangSheet implements FromArray, WithTitle, ShouldAutoSize, WithEvents
{
    public function __construct(
        private readonly Collection $cabangs,
    ) {}

    public function title(): string
    {
        return 'Referensi Cabang';
    }

    public function array(): array
    {
        $rows = [['Kode Cabang', 'Nama Cabang', 'Status']];

        foreach ($this->cabangs as $cabang) {
            $rows[] = [
                $cabang->kode_cabang,
                $cabang->nama_cabang,
                $cabang->is_active ? 'Aktif' : 'Nonaktif',
            ];
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getStyle('A1:C1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2E5090']],
                ]);
            },
        ];
    }
}
