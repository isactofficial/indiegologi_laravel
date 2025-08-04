<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultation_bookings', function (Blueprint $table) {
            // Hapus kolom lama dan ganti dengan ENUM
            $table->dropColumn('contact_preference');
            $table->dropColumn('payment_type');
        });

        Schema::table('consultation_bookings', function (Blueprint $table) {
            $table->enum('contact_preference', ['chat_only', 'chat_and_call'])->default('chat_only')->after('receiver_name');
            $table->enum('payment_type', ['dp', 'full_payment'])->default('full_payment')->after('final_price');
        });

        Schema::table('invoices', function (Blueprint $table) {
            // Kita sudah memiliki payment_type di invoice. Mari kita pastikan tipe kolomnya string.
            // Jika Anda ingin mengubahnya menjadi ENUM, Anda bisa menggunakan logika yang sama.
            // Contoh di sini akan mengasumsikan tipe string.
            $table->decimal('paid_amount', 10, 2)->after('total_amount')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('consultation_bookings', function (Blueprint $table) {
            $table->dropColumn(['contact_preference', 'payment_type']);
            // Tambahkan kembali kolom lama jika diperlukan
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['paid_amount']);
        });
    }
};
