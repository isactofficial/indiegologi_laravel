<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('consultation_bookings')) {
            Schema::table('consultation_bookings', function (Blueprint $table) {
                $table->dropColumn('contact_preference');
                $table->dropColumn('payment_type');
            });

            Schema::table('consultation_bookings', function (Blueprint $table) {
                $table->enum('contact_preference', ['chat_only', 'chat_and_call'])->default('chat_only')->after('receiver_name');
                $table->enum('payment_type', ['dp', 'full_payment'])->default('full_payment')->after('final_price');
            });
        }

        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->decimal('paid_amount', 10, 2)->after('total_amount')->default(0);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('consultation_bookings')) {
            Schema::table('consultation_bookings', function (Blueprint $table) {
                $table->dropColumn(['contact_preference', 'payment_type']);
            });
        }

        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn(['paid_amount']);
            });
        }
    }
};
