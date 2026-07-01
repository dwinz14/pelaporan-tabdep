<?php

namespace App\Exports\Sheets;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplateDataSheet implements FromArray, WithTitle, ShouldAutoSize, WithEvents
{
    public function __construct(
        private readonly Collection $cabangs,
    ) {}

    public function title(): string
    {
        return 'Template Data';
    }

    public function array(): array
    {
        return [[
            'No',
            'Tanggal Periode',
            'Kode Cabang',
            'Jenis',
            'Tambahan Stok',
            'Jumlah Digunakan',
            'Dibatalkan (Rusak)',
            'Dibatalkan (Hilang)',
            'Saldo Awal (Opsional)',
        ]];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2E5090']],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(28);
                $sheet->freezePane('A2');

                // Dropdown Jenis (kolom D), baris 2 s/d 1000
                for ($row = 2; $row <= 1000; $row++) {
                    $validation = $sheet->getCell("D{$row}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowDropDown(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Jenis tidak valid');
                    $validation->setError('Pilih "Tabungan" atau "Deposito" dari daftar.');
                    $validation->setFormula1('"Tabungan,Deposito"');
                }

                // Dropdown Kode Cabang (kolom C), referensi ke sheet Referensi Cabang
                $cabangCount = max($this->cabangs->count(), 1);
                for ($row = 2; $row <= 1000; $row++) {
                    $validation = $sheet->getCell("C{$row}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_WARNING);
                    $validation->setAllowBlank(false);
                    $validation->setShowDropDown(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Kode cabang tidak dikenali');
                    $validation->setError('Periksa kembali Kode Cabang pada sheet Referensi Cabang.');
                    $validation->setFormula1("'Referensi Cabang'!\$A\$2:\$A\${$cabangCount}1");
                }

                $sheet->getColumnDimension('B')->setWidth(18);
                $sheet->getColumnDimension('I')->setWidth(22);
            },
        ];
    }
}
