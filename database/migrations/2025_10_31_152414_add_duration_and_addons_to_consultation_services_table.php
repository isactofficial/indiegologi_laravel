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
        Schema::table('consultation_services', function (Blueprint $table) {
            $table->integer('base_duration')->nullable()->after('hourly_price'); // <-- UNTUK DURASI DASAR
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultation_services', function (Blueprint $table) {
            //
        });
    }
};
