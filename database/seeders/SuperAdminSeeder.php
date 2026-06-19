<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['nik' => 'AP000000001'],
            [
                'name'      => 'Super Admin',
                'nik'       => 'AP111111111',
                'password'  => Hash::make('admin123'),
                'role'      => UserRole::SuperAdmin,
                'id_cabang' => null,
                'is_active' => true,
            ]
        );

        $this->command->info('Super Admin berhasil dibuat.');
        $this->command->info('NIK: AP111111111');
        $this->command->info('Password: Admin@123');
    }
}
