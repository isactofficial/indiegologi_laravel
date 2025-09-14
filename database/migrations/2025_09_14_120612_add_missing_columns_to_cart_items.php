<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, let's add missing columns to cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'hourly_price')) {
                $table->decimal('hourly_price', 10, 2)->nullable()->default(0)->after('price');
            }
            if (!Schema::hasColumn('cart_items', 'hours')) {
                $table->integer('hours')->default(1)->after('quantity');
            }
            if (!Schema::hasColumn('cart_items', 'booked_date')) {
                $table->date('booked_date')->nullable()->after('hours');
            }
            if (!Schema::hasColumn('cart_items', 'booked_time')) {
                $table->time('booked_time')->nullable()->after('booked_date');
            }
            if (!Schema::hasColumn('cart_items', 'session_type')) {
                $table->string('session_type')->default('Online')->after('booked_time');
            }
            if (!Schema::hasColumn('cart_items', 'offline_address')) {
                $table->text('offline_address')->nullable()->after('session_type');
            }
            if (!Schema::hasColumn('cart_items', 'contact_preference')) {
                $table->string('contact_preference')->default('chat_and_call')->after('offline_address');
            }
            if (!Schema::hasColumn('cart_items', 'referral_code')) {
                $table->string('referral_code')->nullable()->after('contact_preference');
            }
            if (!Schema::hasColumn('cart_items', 'payment_type')) {
                $table->string('payment_type')->default('full_payment')->after('referral_code');
            }
        });

        // Now let's handle the service_id column change using raw SQL
        try {
            // First, drop foreign key if exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_NAME = 'cart_items' 
                AND COLUMN_NAME = 'service_id' 
                AND CONSTRAINT_NAME != 'PRIMARY'
                AND TABLE_SCHEMA = DATABASE()
            ");
            
            foreach ($foreignKeys as $fk) {
                DB::statement("ALTER TABLE cart_items DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
            }
        } catch (\Exception $e) {
            // Foreign key might not exist
        }

        // Change service_id to VARCHAR to support 'free-consultation'
        DB::statement('ALTER TABLE cart_items MODIFY service_id VARCHAR(255) NULL');

        // Add missing columns to booking_service table if it exists
        if (Schema::hasTable('booking_service')) {
            Schema::table('booking_service', function (Blueprint $table) {
                if (!Schema::hasColumn('booking_service', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('booking_service', 'service_id')) {
                    $table->string('service_id')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('booking_service', 'contact_preference')) {
                    $table->string('contact_preference')->default('chat_and_call');
                }
            });
        }
    }

    public function down(): void
    {
        // Remove added columns from cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            $columnsToRemove = [
                'hourly_price', 'hours', 'booked_date', 'booked_time', 
                'session_type', 'offline_address', 'contact_preference', 
                'referral_code', 'payment_type'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('cart_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // Remove added columns from booking_service
        if (Schema::hasTable('booking_service')) {
            Schema::table('booking_service', function (Blueprint $table) {
                if (Schema::hasColumn('booking_service', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
                if (Schema::hasColumn('booking_service', 'service_id')) {
                    $table->dropColumn('service_id');
                }
                if (Schema::hasColumn('booking_service', 'contact_preference')) {
                    $table->dropColumn('contact_preference');
                }
            });
        }
    }
};