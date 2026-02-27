<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_service')) {
            Schema::table('booking_service', function (Blueprint $table) {
                $table->dropColumn('price_at_booking');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('booking_service')) {
            Schema::table('booking_service', function (Blueprint $table) {
                $table->decimal('price_at_booking', 10, 2)->after('service_id');
            });
        }
    }
};
