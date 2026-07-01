<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PetunjukSheet implements FromArray, WithTitle, ShouldAutoSize, WithEvents
{
    public function title(): string
    {
        return 'Petunjuk';
    }

    public function array(): array
    {
        return [
            ['PETUNJUK PENGISIAN TEMPLATE IMPORT DATA HISTORIS'],
            [''],
            ['1. Buka sheet "Template Data" — isi data Anda mulai dari baris ke-2 (di bawah header).'],
            ['2. Jangan mengubah nama sheet, urutan kolom, atau header pada baris pertama.'],
            ['3. Format Tanggal Periode: DD/MM/YYYY, contoh: 24/04/2026'],
            ['4. Kode Cabang harus sesuai dengan daftar pada sheet "Referensi Cabang".'],
            ['5. Jenis hanya boleh diisi: Tabungan atau Deposito (lihat dropdown saat klik sel).'],
            ['6. Tambahan Stok, Jumlah Digunakan, Dibatalkan (Rusak), Dibatalkan (Hilang):'],
            ['   - Boleh dikosongkan (akan dianggap 0).'],
            ['   - Tidak boleh diisi angka negatif.'],
            ['7. Saldo Awal bersifat OPSIONAL:'],
            ['   - Kosongkan jika ingin dihitung otomatis sistem secara berurutan.'],
            ['   - Sistem akan mewariskan Saldo Akhir dari periode sebelumnya (cabang & jenis yang sama)'],
            ['     secara berurutan berdasarkan Tanggal Periode di dalam file ini.'],
            ['   - Isi manual hanya jika Anda tahu pasti saldo awal yang benar (misalnya baris paling awal).'],
            ['8. Saldo Akhir TIDAK PERLU diisi — dihitung otomatis oleh sistem dan tidak akan dibaca.'],
            ['9. Jika data untuk kombinasi Tanggal+Cabang+Jenis SUDAH ADA di sistem, baris tersebut akan'],
            ['   dilewati secara otomatis (tidak menimpa data yang sudah ada).'],
            ['10. Setelah file diunggah, sistem akan menampilkan PRATINJAU hasil validasi sebelum data'],
            ['    benar-benar disimpan. Anda dapat membatalkan jika ada kesalahan.'],
            [''],
            ['CONTOH PENGISIAN (lihat sheet "Template Data" untuk format kolom):'],
            ['No', 'Tanggal Periode', 'Kode Cabang', 'Jenis', 'Tambahan Stok', 'Jumlah Digunakan', 'Dibatalkan (Rusak)', 'Dibatalkan (Hilang)', 'Saldo Awal (Opsional)'],
            [1, '24/04/2026', '101', 'Tabungan', 100, 20, 1, 0, 0],
            [2, '24/04/2026', '101', 'Deposito', 50, 5, 0, 0, ''],
            [3, '08/05/2026', '101', 'Tabungan', 80, 15, 0, 1, ''],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A1:I1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A5F']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                $sheet->getStyle('A21')->getFont()->setBold(true)->setSize(11);
                $sheet->getStyle('A22:I22')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2E5090']],
                ]);
                $sheet->getStyle('A23:I25')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF0F4FA']],
                ]);

                $sheet->getColumnDimension('A')->setWidth(50);
            },
        ];
    }
}
