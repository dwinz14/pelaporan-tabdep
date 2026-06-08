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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('registration_status', ['pending', 'approved', 'rejected'])
                ->default('approved')
                ->after('is_active');
            $table->text('catatan_penolakan')->nullable()->after('registration_status');
            $table->timestamp('registered_at')->nullable()->after('catatan_penolakan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['registration_status', 'catatan_penolakan', 'registered_at']);
        });
    }
};
