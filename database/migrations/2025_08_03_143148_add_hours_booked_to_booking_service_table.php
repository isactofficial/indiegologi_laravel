<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_service')) {
            Schema::table('booking_service', function (Blueprint $table) {
                if (!Schema::hasColumn('booking_service', 'hours_booked')) {
                    $table->integer('hours_booked')->default(1)->after('booked_time');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('booking_service')) {
            Schema::table('booking_service', function (Blueprint $table) {
                $table->dropColumn('hours_booked');
            });
        }
    }
};
