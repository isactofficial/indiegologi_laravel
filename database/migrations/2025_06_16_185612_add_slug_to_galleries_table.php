<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('galleries')) {
            Schema::table('galleries', function (Blueprint $table) {
                if (!Schema::hasColumn('galleries', 'slug')) {
                    $table->string('slug')->unique()->after('title');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('galleries')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
