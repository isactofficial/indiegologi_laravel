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
        if (Schema::hasTable('booking_service')) {
            Schema::table('booking_service', function (Blueprint $table) {
                $table->decimal('total_price_at_booking', 10, 2)->after('service_id')->nullable();
                $table->decimal('discount_amount_at_booking', 10, 2)->nullable()->after('total_price_at_booking');
                $table->decimal('final_price_at_booking', 10, 2)->nullable()->after('discount_amount_at_booking');
                $table->foreignId('referral_code_id')->nullable()->constrained('referral_codes')->onDelete('set null')->after('final_price_at_booking');
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
                $table->dropForeign(['referral_code_id']);
                $table->dropColumn(['total_price_at_booking', 'discount_amount_at_booking', 'final_price_at_booking', 'referral_code_id']);
            });
        }
    }
};
