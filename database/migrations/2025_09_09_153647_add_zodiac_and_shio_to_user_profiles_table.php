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
        if (Schema::hasTable('user_profiles')) {
            Schema::table('user_profiles', function (Blueprint $table) {
                $table->string('zodiac', 20)->nullable()->after('birthdate');
                $table->string('shio_element', 50)->nullable()->after('zodiac');
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
        if (Schema::hasTable('user_profiles')) {
            Schema::table('user_profiles', function (Blueprint $table) {
                $table->dropColumn('zodiac');
                $table->dropColumn('shio_element');
            });
        }
    }
};
