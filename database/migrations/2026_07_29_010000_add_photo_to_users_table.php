<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     * Menambahkan kolom "photo" (nama file foto profil) ke tabel users.
     * Nullable karena tidak semua user wajib punya foto (pakai avatar default).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('username');
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};
