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
        // Index pada tabel laporans untuk query yang sering dipakai
        Schema::table('laporans', function (Blueprint $table) {
            $table->index(['id_periode', 'jenis'], 'idx_laporan_periode_jenis');
            $table->index(['id_cabang', 'id_periode'], 'idx_laporan_cabang_periode');
            $table->index('status_verifikasi', 'idx_laporan_status');
        });

        // Index pada tabel periode_laporans
        Schema::table('periode_laporans', function (Blueprint $table) {
            $table->index('status_operasional', 'idx_periode_status');
            $table->index('is_current', 'idx_periode_current');
            $table->index('tanggal_akhir', 'idx_periode_tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropIndex('idx_laporan_periode_jenis');
            $table->dropIndex('idx_laporan_cabang_periode');
            $table->dropIndex('idx_laporan_status');
        });

        Schema::table('periode_laporans', function (Blueprint $table) {
            $table->dropIndex('idx_periode_status');
            $table->dropIndex('idx_periode_current');
            $table->dropIndex('idx_periode_tanggal');
        });
    }
};
