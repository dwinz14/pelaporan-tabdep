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
        Schema::create('periode_laporans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_akhir')->unique()->comment('Selalu hari Jumat');
            $table->string('nama_periode', 100)->comment('Otomatis: Periode DD MMMM YYYY');
            $table->enum('status_operasional', ['pending', 'verified'])->default('pending');
            $table->dateTime('tgl_verifikasi_operasional')->nullable();
            $table->foreignId('verified_by_operasional')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_current')->default(false)->comment('Hanya satu true');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_laporans');
    }
};
