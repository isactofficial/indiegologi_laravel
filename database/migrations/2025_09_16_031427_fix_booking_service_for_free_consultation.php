<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('booking_service')) {
            return;
        }

        // Make service_id nullable using raw SQL (no Doctrine DBAL required)
        DB::statement('ALTER TABLE booking_service MODIFY service_id BIGINT UNSIGNED NULL');

        Schema::table('booking_service', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_service', 'free_consultation_type_id')) {
                $table->unsignedBigInteger('free_consultation_type_id')->nullable();
                $table->foreign('free_consultation_type_id')
                    ->references('id')
                    ->on('free_consultation_types')
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('booking_service', 'free_consultation_schedule_id')) {
                $table->unsignedBigInteger('free_consultation_schedule_id')->nullable();
                $table->foreign('free_consultation_schedule_id')
                    ->references('id')
                    ->on('free_consultation_schedules')
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('booking_service', 'contact_preference')) {
                $table->string('contact_preference')->default('chat_and_call');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('booking_service')) {
            return;
        }

        Schema::table('booking_service', function (Blueprint $table) {
            if (Schema::hasColumn('booking_service', 'free_consultation_type_id')) {
                $table->dropForeign(['free_consultation_type_id']);
                $table->dropColumn('free_consultation_type_id');
            }

            if (Schema::hasColumn('booking_service', 'free_consultation_schedule_id')) {
                $table->dropForeign(['free_consultation_schedule_id']);
                $table->dropColumn('free_consultation_schedule_id');
            }

            if (Schema::hasColumn('booking_service', 'contact_preference')) {
                $table->dropColumn('contact_preference');
            }
        });

        // Make service_id not nullable again
        DB::statement('ALTER TABLE booking_service MODIFY service_id BIGINT UNSIGNED NOT NULL');
    }
};