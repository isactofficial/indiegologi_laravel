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
        Schema::table('user_profiles', function (Blueprint $table) {
            // Menambahkan kolom 'description' sebagai tipe TEXT, nullable, setelah kolom 'social_media'
            $table->text('description')->nullable()->after('social_media');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            // Menghapus kolom 'description' jika migrasi di-rollback
            $table->dropColumn('description');
        });
    }
};

