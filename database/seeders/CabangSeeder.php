<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        $cabangs = [
            ['kode_cabang' => '101', 'nama_cabang' => 'Kantor Pare'],
            ['kode_cabang' => '102', 'nama_cabang' => 'Kantor Sambi'],
        ];

        foreach ($cabangs as $data) {
            Cabang::updateOrCreate(
                ['kode_cabang' => $data['kode_cabang']],
                array_merge($data, ['is_active' => true])
            );
        }

        $this->command->info('cabang berhasil di-seed.');
    }
}
