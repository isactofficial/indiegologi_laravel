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
        if (Schema::hasTable('user_profiles')) {
            Schema::table('user_profiles', function (Blueprint $table) {
                $table->text('description')->nullable()->after('social_media');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('user_profiles')) {
            Schema::table('user_profiles', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};

