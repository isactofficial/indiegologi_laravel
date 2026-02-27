<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('service_id')->nullable();
                $table->unsignedBigInteger('free_consultation_type_id')->nullable();
                $table->unsignedBigInteger('free_consultation_schedule_id')->nullable();
                $table->unsignedBigInteger('event_id')->nullable();
                $table->integer('quantity')->default(1);
                $table->integer('participant_count')->default(1);
                $table->integer('hours')->default(1);
                $table->date('booked_date')->nullable();
                $table->time('booked_time')->nullable();
                $table->string('session_type')->nullable();
                $table->text('offline_address')->nullable();
                $table->string('contact_preference')->nullable();
                $table->string('payment_type')->nullable();
                $table->string('referral_code')->nullable();
                $table->decimal('price', 15, 2)->default(0);
                $table->decimal('original_price', 15, 2)->default(0);
                $table->decimal('hourly_price', 15, 2)->default(0);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->string('item_type')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cart_participants')) {
            Schema::create('cart_participants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cart_item_id')->constrained()->onDelete('cascade');
                $table->string('full_name');
                $table->string('phone_number')->nullable();
                $table->string('email')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_participants');
        Schema::dropIfExists('cart_items');
    }
};
