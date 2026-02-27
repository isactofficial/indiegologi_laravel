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
        if (Schema::hasTable('tournaments')) {
            Schema::table('tournaments', function (Blueprint $table) {
                $table->dateTime('event_start')->nullable()->after('status');
                $table->dateTime('event_end')->nullable()->after('event_start');
                $table->enum('visibility_status', ['Draft', 'Published'])->default('Draft')->after('event_end');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            // Untuk rollback: hapus kolom-kolom ini
            $table->dropColumn(['event_start', 'event_end', 'visibility_status']);
        });
    }
};
