<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_service', function (Blueprint $table) {
            // Hapus kolom yang tidak lagi digunakan
            $table->dropColumn('price_at_booking');
        });
    }

    public function down(): void
    {
        Schema::table('booking_service', function (Blueprint $table) {
            // Tambahkan kembali kolom jika diperlukan untuk rollback
            $table->decimal('price_at_booking', 10, 2)->after('service_id');
        });
    }
};
