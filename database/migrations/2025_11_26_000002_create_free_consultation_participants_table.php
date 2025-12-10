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
        Schema::create('free_consultation_participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('consultation_booking_id');
            $table->foreign('consultation_booking_id')->references('id')->on('free_consultation_bookings')->onDelete('cascade');
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('attendance_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('free_consultation_participants');
    }
};
