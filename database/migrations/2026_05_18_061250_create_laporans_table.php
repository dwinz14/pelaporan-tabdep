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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cabang')->constrained('cabangs')->cascadeOnDelete();
            $table->foreignId('id_periode')->constrained('periode_laporans')->cascadeOnDelete();
            $table->enum('jenis', ['tabungan', 'deposito']);
            $table->unsignedInteger('saldo_awal')->default(0);
            $table->unsignedInteger('tambahan_stok')->default(0);
            $table->unsignedInteger('jumlah_digunakan')->default(0);
            $table->unsignedInteger('jml_dibatalkan_rusak')->default(0);
            $table->unsignedInteger('jml_dibatalkan_hilang')->default(0);
            $table->unsignedInteger('saldo_akhir')->default(0);
            $table->enum('status_verifikasi', [
                'draft',
                'submitted',
                'verified_accounting',
                'revision_requested',
            ])->default('draft');
            $table->dateTime('tgl_submit')->nullable();
            $table->dateTime('tgl_verifikasi_akunting')->nullable();
            $table->foreignId('verified_by_akunting')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_revisi')->nullable();
            $table->timestamps();

            // Satu laporan per cabang per periode per jenis
            $table->unique(['id_cabang', 'id_periode', 'jenis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
