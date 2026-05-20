<?php

namespace App\Exports\Sheets;

use App\Models\PeriodeLaporan;

class DepositoSheet extends TabunganSheet
{
    public function title(): string
    {
        return 'Deposito';
    }

    public function array(): array
    {
        $rows  = [];
        $jenis = 'deposito';

        $rows[] = $this->buildTitleRow('LAPORAN STOK BUKU DEPOSITO');
        $rows[] = $this->buildSubtitleRow();
        $rows[] = [];
        $rows[] = $this->buildHeaderRow();

        $uraians = [
            'saldo_awal'            => 'Saldo Awal',
            'tambahan_stok'         => 'Tambahan Stok',
            'jumlah_digunakan'      => 'Jumlah Digunakan',
            'jml_dibatalkan_rusak'  => 'Dibatalkan (rusak/salah cetak)',
            'jml_dibatalkan_hilang' => 'Dibatalkan (hilang)',
            'saldo_akhir'           => 'Saldo Akhir',
        ];

        foreach ($uraians as $field => $label) {
            $row   = [$label];
            $total = 0;

            foreach ($this->cabangs as $cabang) {
                $laporan = $this->laporans
                    ->where('id_cabang', $cabang->id)
                    ->where('jenis', $jenis)
                    ->first();

                $val   = $laporan ? (int) $laporan->$field : 0;
                $row[] = $val;
                $total += $val;
            }

            $row[]  = $total;
            $rows[] = $row;
        }

        return $rows;
    }
}
