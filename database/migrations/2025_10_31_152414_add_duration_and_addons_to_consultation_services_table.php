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
    public function up()
    {
        if (Schema::hasTable('consultation_services')) {
            Schema::table('consultation_services', function (Blueprint $table) {
                if (!Schema::hasColumn('consultation_services', 'base_duration')) {
                    $table->integer('base_duration')->nullable()->after('hourly_price');
                }
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
        if (Schema::hasTable('consultation_services')) {
            Schema::table('consultation_services', function (Blueprint $table) {
                if (Schema::hasColumn('consultation_services', 'base_duration')) {
                    $table->dropColumn('base_duration');
                }
            });
        }
    }
};
