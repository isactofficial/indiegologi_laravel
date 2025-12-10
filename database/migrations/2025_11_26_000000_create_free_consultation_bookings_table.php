<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('free_consultation_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');

            // User yang memesan
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            // Jenis layanan gratis
            $table->string('service_type')->nullable(); // selalu 'free_consultation'
            $table->unsignedBigInteger('service_id')->nullable(); // type ID

            $table->dateTime('scheduled_at')->nullable();

            // Data Pemesan (Booker)
            $table->string('booker_name',255)->nullable();;
            $table->string('booker_phone', 30)->nullable();
            $table->string('booker_email')->nullable();

            // Preferensi Kontak (chat_only / chat_and_call)
            $table->string('contact_preference')->nullable();

            // Peserta
            $table->integer('participant_count')->default(1);

            // Jenis sesi: Online / Offline
            $table->string('session_type')->nullable();
            $table->string('offline_address')->nullable();

            // Harga (gratis → 0)
            $table->integer('total_price')->default(0);

            // Status Booking
            $table->string('booking_status')->default('pending');
            $table->string('payment_status')->default('unpaid');

            // Invoice ID jika ada
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('free_consultation_bookings');
    }
};
