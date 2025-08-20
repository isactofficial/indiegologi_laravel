<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_service', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_service', 'invoice_id')) {
                $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_service', function (Blueprint $table) {
            if (Schema::hasColumn('booking_service', 'invoice_id')) {
                $table->dropConstrainedForeignId('invoice_id');
                $table->dropColumn('invoice_id');
            }
        });
    }
};
