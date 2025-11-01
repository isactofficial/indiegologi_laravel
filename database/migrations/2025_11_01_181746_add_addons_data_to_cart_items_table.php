<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('cart_items', function (Blueprint $table) {
            // Tambahkan kolom ini
            $table->json('addons_data')->nullable()->after('referral_code');
        });
    }
    public function down(): void {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('addons_data');
        });
    }
};
