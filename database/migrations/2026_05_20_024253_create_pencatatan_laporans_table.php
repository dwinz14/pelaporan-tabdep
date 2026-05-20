<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pencatatan_laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cabang')->constrained('cabangs')->cascadeOnDelete();
            $table->foreignId('id_periode')->constrained('periode_laporans')->cascadeOnDelete();
            $table->enum('jenis', ['tabungan', 'deposito']);
            $table->enum('tipe_transaksi', [
                'tambahan_stok',
                'digunakan',
                'dibatalkan_rusak',
                'dibatalkan_hilang',
            ]);
            $table->unsignedInteger('jumlah')->default(1);
            $table->string('keterangan', 255)->nullable()->comment('Catatan opsional, misal nama nasabah');
            $table->date('tanggal_catat');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // Index untuk query yang sering dipakai
            $table->index(['id_cabang', 'id_periode', 'jenis'], 'idx_pencatatan_cabang_periode_jenis');
            $table->index('tanggal_catat', 'idx_pencatatan_tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencatatan_laporans');
    }
};
