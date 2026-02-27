<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGalleryTableForTournamentDocumentation extends Migration
{
    public function up()
    {
        if (Schema::hasTable('galleries')) {
            Schema::table('galleries', function (Blueprint $table) {
                if (Schema::hasColumn('galleries', 'location')) {
                    $table->dropColumn('location');
                }
                if (Schema::hasColumn('galleries', 'function')) {
                    $table->dropColumn('function');
                }
                if (Schema::hasColumn('galleries', 'land_area')) {
                    $table->dropColumn('land_area');
                }
                if (Schema::hasColumn('galleries', 'building_area')) {
                    $table->dropColumn('building_area');
                }

                $table->string('tournament_name')->nullable()->after('title');
                $table->string('video_link')->nullable()->after('thumbnail');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('galleries')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->string('location')->nullable()->after('author');
                $table->string('function')->nullable()->after('location');
                $table->string('land_area')->nullable()->after('function');
                $table->string('building_area')->nullable()->after('land_area');

                $table->dropColumn('tournament_name');
                $table->dropColumn('video_link');
            });
        }
    }
}
