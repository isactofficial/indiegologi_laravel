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
        if (Schema::hasTable('tournaments')) {
            Schema::table('tournaments', function (Blueprint $table) {
                if (!Schema::hasColumn('tournaments', 'max_participants')) {
                    $table->integer('max_participants')->after('prize_total')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('max_participants');
        });
    }
};
