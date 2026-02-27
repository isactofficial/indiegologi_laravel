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
        // consultation_services
        if (!Schema::hasTable('consultation_services')) {
            Schema::create('consultation_services', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->decimal('price', 15, 2);
                $table->decimal('hourly_price', 15, 2)->nullable();
                $table->integer('base_duration')->default(1);
                $table->enum('status', ['published', 'draft', 'special'])->default('draft');
                $table->text('short_description')->nullable();
                $table->text('product_description')->nullable();
                $table->string('thumbnail')->nullable();
                $table->json('add_ons')->nullable();
                $table->timestamps();
            });
        }

        // referral_codes
        if (!Schema::hasTable('referral_codes')) {
            Schema::create('referral_codes', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->enum('discount_type', ['fixed', 'percentage']);
                $table->decimal('discount_percentage', 5, 2)->nullable();
                $table->decimal('discount_amount', 15, 2)->nullable();
                $table->decimal('min_purchase_amount', 15, 2)->default(0);
                $table->dateTime('valid_from')->nullable();
                $table->dateTime('valid_until')->nullable();
                $table->integer('max_uses')->nullable();
                $table->integer('current_uses')->default(0);
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }

        // invoices
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('invoice_no')->unique();
                $table->string('invoice_type')->nullable();
                $table->string('source_type')->nullable();
                $table->string('source_id')->nullable();
                $table->foreignId('parent_invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
                $table->integer('revision_number')->default(0);
                $table->dateTime('invoice_date');
                $table->dateTime('due_date');
                $table->decimal('subtotal_amount', 15, 2);
                $table->decimal('total_amount', 15, 2);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('auto_discount_amount', 15, 2)->default(0);
                $table->decimal('manual_discount_amount', 15, 2)->default(0);
                $table->decimal('total_discount_amount', 15, 2)->default(0);
                $table->decimal('final_amount', 15, 2);
                $table->decimal('remaining_amount', 15, 2);
                $table->string('payment_type')->nullable();
                $table->string('payment_status')->default('unpaid');
                $table->string('session_type')->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // consultation_bookings
        if (!Schema::hasTable('consultation_bookings')) {
            Schema::create('consultation_bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('receiver_name')->nullable();
                $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
                $table->string('contact_preference')->nullable();
                $table->string('payment_type')->nullable();
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('final_price', 15, 2);
                $table->string('session_status')->default('pending');
                $table->timestamps();
            });
        }

        // booking_service (Pivot)
        if (!Schema::hasTable('booking_service')) {
            Schema::create('booking_service', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('consultation_bookings')->onDelete('cascade');
                $table->string('service_id')->nullable(); // Can be UUID or ID string
                $table->decimal('total_price_at_booking', 15, 2);
                $table->decimal('discount_amount_at_booking', 15, 2)->default(0);
                $table->decimal('final_price_at_booking', 15, 2);
                $table->foreignId('referral_code_id')->nullable()->constrained('referral_codes')->onDelete('set null');
                $table->integer('hours_booked')->default(1);
                $table->date('booked_date')->nullable();
                $table->time('booked_time')->nullable();
                $table->string('session_type')->nullable();
                $table->text('offline_address')->nullable();
                $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->string('contact_preference')->nullable();
                $table->unsignedBigInteger('free_consultation_type_id')->nullable();
                $table->unsignedBigInteger('free_consultation_schedule_id')->nullable();
                $table->unsignedBigInteger('event_id')->nullable();
                $table->timestamps();
            });
        }

        // events
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->date('event_date');
                $table->time('event_time');
                $table->string('place')->nullable();
                $table->integer('max_participants');
                $table->integer('current_participants')->default(0);
                $table->decimal('price', 15, 2)->default(0);
                $table->string('session_type')->default('offline');
                $table->string('status')->default('draft');
                $table->string('thumbnail')->nullable();
                $table->timestamps();
            });
        }

        // event_bookings
        if (!Schema::hasTable('event_bookings')) {
            Schema::create('event_bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('event_id')->constrained()->onDelete('cascade');
                $table->string('booker_name')->nullable();
                $table->string('booker_phone')->nullable();
                $table->string('booker_email')->nullable();
                $table->integer('participant_count')->default(1);
                $table->decimal('total_price', 15, 2);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('final_price', 15, 2);
                $table->string('payment_type')->nullable();
                $table->string('contact_preference')->nullable();
                $table->string('booking_status')->default('pending');
                $table->string('payment_status')->default('unpaid');
                $table->foreignId('referral_code_id')->nullable()->constrained('referral_codes')->onDelete('set null');
                $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
                $table->string('guest_name')->nullable();
                $table->string('guest_phone')->nullable();
                $table->string('guest_email')->nullable();
                $table->timestamps();
            });
        }

        // event_participants
        if (!Schema::hasTable('event_participants')) {
            Schema::create('event_participants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_booking_id')->constrained()->onDelete('cascade');
                $table->string('full_name');
                $table->string('phone_number')->nullable();
                $table->string('email')->nullable();
                $table->string('attendance_status')->default('pending');
                $table->timestamps();
            });
        }

        // testimonials
        if (!Schema::hasTable('testimonials')) {
            Schema::create('testimonials', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('age')->nullable();
                $table->string('occupation')->nullable();
                $table->string('location')->nullable();
                $table->text('quote');
                $table->string('image')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // free_consultation_types
        if (!Schema::hasTable('free_consultation_types')) {
            Schema::create('free_consultation_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        // free_consultation_schedules
        if (!Schema::hasTable('free_consultation_schedules')) {
            Schema::create('free_consultation_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('type_id')->constrained('free_consultation_types')->onDelete('cascade');
                $table->date('scheduled_date');
                $table->time('scheduled_time');
                $table->boolean('is_available')->default(true);
                $table->integer('max_participants')->default(1);
                $table->integer('current_bookings')->default(0);
                $table->timestamps();
            });
        }

        // user_profiles
        if (!Schema::hasTable('user_profiles')) {
            Schema::create('user_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('profile_photo')->nullable();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->date('birthdate')->nullable();
                $table->string('gender')->nullable();
                $table->string('phone_number')->nullable();
                $table->string('social_media')->nullable();
                $table->text('description')->nullable();
                $table->string('zodiac')->nullable();
                $table->string('shio_element')->nullable();
                $table->timestamps();
            });
        }

        // cart_items
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

        // cart_participants
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
    public function down()
    {
        Schema::dropIfExists('cart_participants');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('free_consultation_schedules');
        Schema::dropIfExists('free_consultation_types');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('event_participants');
        Schema::dropIfExists('event_bookings');
        Schema::dropIfExists('events');
        Schema::dropIfExists('booking_service');
        Schema::dropIfExists('consultation_bookings');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('referral_codes');
        Schema::dropIfExists('consultation_services');
    }
};
