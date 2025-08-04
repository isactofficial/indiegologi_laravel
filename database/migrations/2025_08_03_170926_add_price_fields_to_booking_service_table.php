<?php
// database/migrations/..._add_price_fields_to_booking_service_table.php

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
            $table->decimal('total_price_at_booking', 10, 2)->after('service_id')->nullable();
            $table->decimal('discount_amount_at_booking', 10, 2)->nullable()->after('total_price_at_booking');
            $table->decimal('final_price_at_booking', 10, 2)->nullable()->after('discount_amount_at_booking');
            $table->foreignId('referral_code_id')->nullable()->constrained('referral_codes')->onDelete('set null')->after('final_price_at_booking');
            // Jika Anda belum memiliki hours_booked, tambahkan di sini juga
            // $table->integer('hours_booked')->default(1)->after('booked_time');
            // Jika Anda belum memiliki session_type, tambahkan di sini juga
            // $table->enum('session_type', ['Online', 'Offline'])->default('Online')->after('booked_time');
            // Jika Anda belum memiliki offline_address, tambahkan di sini juga
            // $table->string('offline_address')->nullable()->after('session_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_service', function (Blueprint $table) {
            $table->dropForeign(['referral_code_id']); // Hapus foreign key terlebih dahulu
            $table->dropColumn(['total_price_at_booking', 'discount_amount_at_booking', 'final_price_at_booking', 'referral_code_id']);
        });
    }
};
