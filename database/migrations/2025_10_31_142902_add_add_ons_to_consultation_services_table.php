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
                if (!Schema::hasColumn('consultation_services', 'add_ons')) {
                    $table->text('add_ons')->nullable()->after('thumbnail');
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
                if (Schema::hasColumn('consultation_services', 'add_ons')) {
                    $table->dropColumn('add_ons');
                }
            });
        }
    }
};
