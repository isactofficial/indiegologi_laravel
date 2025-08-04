<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_service', function (Blueprint $table) {
            // Hapus kolom yang tidak lagi diperlukan dari model ConsultationBooking
            // $table->dropColumn(['price_at_booking']); // Jika sudah ada, ganti

            // Tambahkan kolom baru untuk harga per layanan
            $table->decimal('total_price_at_booking', 10, 2)->after('service_id');
            $table->decimal('discount_amount_at_booking', 10, 2)->nullable()->after('total_price_at_booking');
            $table->decimal('final_price_at_booking', 10, 2)->after('discount_amount_at_booking');
            $table->foreignId('referral_code_id')->nullable()->constrained('referral_codes')->onDelete('set null')->after('final_price_at_booking');

            // Kolom untuk melacak penggunaan referral
            // $table->foreignId('referral_code_id')->nullable()->after('referral_code'); // Contoh jika Anda ingin memindahkan
        });
    }

    public function down(): void
    {
        Schema::table('booking_service', function (Blueprint $table) {
            $table->dropColumn(['total_price_at_booking', 'discount_amount_at_booking', 'final_price_at_booking', 'referral_code_id']);
        });
    }
};
