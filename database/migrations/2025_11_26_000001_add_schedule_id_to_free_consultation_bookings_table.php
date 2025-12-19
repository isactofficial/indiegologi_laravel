<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('free_consultation_bookings', function (Blueprint $table) {
            $table->foreignId('schedule_id')
                  ->nullable()
                  ->constrained('free_consultation_schedules') 
                  ->onDelete('set null')
                  ->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('free_consultation_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('schedule_id');
        });
    }
};