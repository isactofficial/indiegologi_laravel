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
        Schema::table('booking_service', function (Blueprint $table) {
            // Make service_id nullable to support free consultations
            $table->unsignedBigInteger('service_id')->nullable()->change();
            
            // Add columns for free consultation support if they don't exist
            if (!Schema::hasColumn('booking_service', 'free_consultation_type_id')) {
                $table->unsignedBigInteger('free_consultation_type_id')->nullable();
                $table->foreign('free_consultation_type_id')
                      ->references('id')
                      ->on('free_consultation_types')
                      ->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('booking_service', 'free_consultation_schedule_id')) {
                $table->unsignedBigInteger('free_consultation_schedule_id')->nullable();
                $table->foreign('free_consultation_schedule_id')
                      ->references('id')
                      ->on('free_consultation_schedules')
                      ->onDelete('cascade');
            }
            
            // Add contact_preference if it doesn't exist
            if (!Schema::hasColumn('booking_service', 'contact_preference')) {
                $table->string('contact_preference')->default('chat_and_call');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_service', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['free_consultation_type_id']);
            $table->dropForeign(['free_consultation_schedule_id']);
            
            // Drop columns
            $table->dropColumn([
                'free_consultation_type_id',
                'free_consultation_schedule_id',
                'contact_preference'
            ]);
            
            // Make service_id not nullable again
            $table->unsignedBigInteger('service_id')->nullable(false)->change();
        });
    }
};