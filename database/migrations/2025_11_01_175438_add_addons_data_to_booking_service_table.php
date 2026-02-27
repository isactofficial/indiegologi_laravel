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
        if (Schema::hasTable('booking_service')) {
            Schema::table('booking_service', function (Blueprint $table) {
                $table->json('addons_data')->nullable()->after('offline_address');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('booking_service')) {
            Schema::table('booking_service', function (Blueprint $table) {
                $table->dropColumn('addons_data');
            });
        }
    }
};
