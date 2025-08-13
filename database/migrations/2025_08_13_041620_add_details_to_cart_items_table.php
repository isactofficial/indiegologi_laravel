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
    public function up()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Tambahkan kolom-kolom yang kurang setelah kolom 'price'
            $table->decimal('hourly_price', 10, 2)->default(0.00)->after('price');
            $table->integer('hours')->default(0)->after('quantity');
            $table->date('booked_date')->nullable()->after('hours');
            $table->time('booked_time')->nullable()->after('booked_date');
            $table->string('session_type')->default('Online')->after('booked_time');
            $table->text('offline_address')->nullable()->after('session_type');
            $table->string('contact_preference')->default('chat_only')->after('offline_address');
            $table->string('payment_type')->default('full_payment')->after('contact_preference');
            $table->string('referral_code')->nullable()->after('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Ini untuk 'rollback' jika diperlukan
            $table->dropColumn([
                'hourly_price', 'hours', 'booked_date', 'booked_time', 
                'session_type', 'offline_address', 'contact_preference', 
                'payment_type', 'referral_code'
            ]);
        });
    }
};