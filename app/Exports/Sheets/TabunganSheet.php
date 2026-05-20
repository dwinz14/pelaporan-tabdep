<?php

namespace App\Exports\Sheets;

use App\Models\Cabang;
use App\Models\Laporan;
use App\Models\PeriodeLaporan;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TabunganSheet implements FromArray, WithTitle, ShouldAutoSize, WithEvents, WithColumnFormatting
{
    public function __construct(
        protected readonly PeriodeLaporan $periode,
        protected readonly \Illuminate\Database\Eloquent\Collection $cabangs,
        protected readonly \Illuminate\Database\Eloquent\Collection $laporans,
    ) {}

    public function title(): string
    {
        return 'Tabungan';
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function array(): array
    {
        $rows   = [];
        $jenis  = 'tabungan';

        // ─── Judul ───────────────────────────────────────────
        $rows[] = $this->buildTitleRow('LAPORAN STOK BUKU TABUNGAN');
        $rows[] = $this->buildSubtitleRow();
        $rows[] = []; // blank

        // ─── Header Kolom ─────────────────────────────────────
        $rows[] = $this->buildHeaderRow();

        // ─── Data Baris ───────────────────────────────────────
        $uraians = [
            'saldo_awal'             => 'Saldo Awal',
            'tambahan_stok'          => 'Tambahan Stok',
            'jumlah_digunakan'       => 'Jumlah Digunakan',
            'jml_dibatalkan_rusak'   => 'Dibatalkan (rusak/salah cetak)',
            'jml_dibatalkan_hilang'  => 'Dibatalkan (hilang)',
            'saldo_akhir'            => 'Saldo Akhir',
        ];

        foreach ($uraians as $field => $label) {
            $row = [$label];
            $total = 0;

            foreach ($this->cabangs as $cabang) {
                $laporan = $this->laporans
                    ->where('id_cabang', $cabang->id)
                    ->where('jenis', $jenis)
                    ->first();

                $val = $laporan ? (int) $laporan->$field : 0;
                $row[] = $val;
                $total += $val;
            }

            $row[] = $total; // kolom Total
            $rows[] = $row;
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet->getDelegate();
                $cabangCount = $this->cabangs->count();
                $lastCol    = $this->columnLetter($cabangCount + 2); // +1 Uraian, +1 Total
                $dataRows   = 6; // jumlah baris uraian

                // ── Merge & Style Judul ─────────────────────
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A5F']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(28);

                // ── Merge & Style Subtitle ──────────────────
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FF1E3A5F']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE8EDF5']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Style Header Row (baris 4) ──────────────
                $headerRange = "A4:{$lastCol}4";
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 9],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2E5090']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFAAAAAA']]],
                ]);
                $sheet->getRowDimension(4)->setRowHeight(30);

                // ── Style Data Rows (baris 5-10) ─────────────
                for ($i = 5; $i <= 4 + $dataRows; $i++) {
                    $isSaldoAkhir = ($i === 4 + $dataRows);
                    $isEven       = ($i % 2 === 0);

                    $sheet->getStyle("A{$i}")->applyFromArray([
                        'font'      => ['bold' => $isSaldoAkhir, 'size' => 9],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                        'fill'      => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => $isSaldoAkhir ? 'FFD6E4F7' : ($isEven ? 'FFFAFAFA' : 'FFFFFFFF')]
                        ],
                    ]);

                    $dataRange = "B{$i}:{$lastCol}{$i}";
                    $sheet->getStyle($dataRange)->applyFromArray([
                        'font'        => ['bold' => $isSaldoAkhir, 'size' => 9],
                        'alignment'   => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                        'numberFormat' => ['formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1],
                        'fill'        => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => $isSaldoAkhir ? 'FFD6E4F7' : ($isEven ? 'FFFAFAFA' : 'FFFFFFFF')]
                        ],
                    ]);

                    // Kolom Total — warna berbeda
                    $sheet->getStyle("{$lastCol}{$i}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 9],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFECF0F7']],
                    ]);
                }

                // ── Border semua data ────────────────────────
                $allRange = "A4:{$lastCol}" . (4 + $dataRows);
                $sheet->getStyle($allRange)->applyFromArray([
                    'borders' => [
                        'allBorders'  => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCCCCCC']],
                        'outline'     => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF2E5090']],
                    ],
                ]);

                // ── Lebar kolom Uraian ───────────────────────
                $sheet->getColumnDimension('A')->setWidth(35);

                // ── Freeze pane ─────────────────────────────
                $sheet->freezePane('B5');
            },
        ];
    }

    // ─── Helpers ──────────────────────────────────────────────

    protected function buildTitleRow(string $title): array
    {
        $row = [$title];
        for ($i = 0; $i < $this->cabangs->count() + 1; $i++) {
            $row[] = null;
        }
        return $row;
    }

    protected function buildSubtitleRow(): array
    {
        $row = [$this->periode->nama_periode . ' | Tanggal: ' . $this->periode->tanggal_akhir->format('d/m/Y')];
        for ($i = 0; $i < $this->cabangs->count() + 1; $i++) {
            $row[] = null;
        }
        return $row;
    }

    protected function buildHeaderRow(): array
    {
        $row = ['Uraian'];
        foreach ($this->cabangs as $cabang) {
            $row[] = $cabang->kode_cabang;
        }
        $row[] = 'TOTAL';
        return $row;
    }

    protected function columnLetter(int $colNumber): string
    {
        $letter = '';
        while ($colNumber > 0) {
            $colNumber--;
            $letter = chr(65 + ($colNumber % 26)) . $letter;
            $colNumber = intdiv($colNumber, 26);
        }
        return $letter;
    }
}
