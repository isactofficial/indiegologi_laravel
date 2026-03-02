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
        if (Schema::hasTable('consultation_services')) {
            Schema::table('consultation_services', function (Blueprint $table) {
                if (!Schema::hasColumn('consultation_services', 'hourly_price')) {
                    $table->decimal('hourly_price', 10, 2)->nullable()->after('price');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('consultation_services')) {
            Schema::table('consultation_services', function (Blueprint $table) {
                $table->dropColumn('hourly_price');
            });
        }
    }
};
