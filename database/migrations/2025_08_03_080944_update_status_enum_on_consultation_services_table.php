<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('consultation_services')) {
            return;
        }

        // Ubah kolom 'status' menjadi ENUM
        Schema::table('consultation_services', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('consultation_services', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published', 'special'])->default('draft')->after('short_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('consultation_services')) {
            return;
        }

        // Balikkan kolom 'status' ke tipe string (opsional, tergantung kebutuhan)
        Schema::table('consultation_services', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('consultation_services', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('short_description');
        });
    }
};
