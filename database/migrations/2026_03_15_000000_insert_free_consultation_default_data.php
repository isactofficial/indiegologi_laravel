<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Insert default free consultation data that will persist on hosting
     * This data will be inserted when migration runs and won't require seeding
     */
    public function up()
    {
        // Delete existing data first to ensure fresh insert with proper future dates
        DB::table('free_consultation_schedules')->delete();
        DB::table('free_consultation_types')->delete();

        // Insert consultation types
        $typeIds = [];
        
        $typeIds[] = DB::table('free_consultation_types')->insertGetId([
            'name' => 'Pendampingan Meditasi',
            'description' => 'Sesi konsultasi gratis untuk belajar teknik meditasi dasar dan membangun rutinitas meditasi yang sehat untuk kehidupan sehari-hari.',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $typeIds[] = DB::table('free_consultation_types')->insertGetId([
            'name' => 'Law of Attraction',
            'description' => 'Konsultasi tentang prinsip-prinsip law of attraction dan cara menerapkannya dalam kehidupan sehari-hari untuk mencapai tujuan.',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $typeIds[] = DB::table('free_consultation_types')->insertGetId([
            'name' => 'Psikologi Dasar',
            'description' => 'Pemahaman dasar tentang psikologi manusia dan cara mengelola emosi serta pikiran dengan lebih baik.',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Generate schedules for each type with fixed future dates
        // Using year 2026-2027 to ensure data always appears as future dates
        
        // Type 1: Pendampingan Meditasi - Morning slots
        $meditasiTimes = ['07:00:00', '08:30:00', '10:00:00', '07:30:00', '09:00:00'];
        for ($i = 0; $i < 5; $i++) {
            $date = date('Y-m-d', strtotime("2026-12-01 +{$i} days"));
            DB::table('free_consultation_schedules')->insert([
                'type_id' => $typeIds[0],
                'scheduled_date' => $date,
                'scheduled_time' => $meditasiTimes[$i],
                'is_available' => true,
                'max_participants' => 1,
                'current_bookings' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Type 2: Law of Attraction - Afternoon slots
        $loaTimes = ['14:00:00', '15:30:00', '16:00:00', '14:30:00', '15:00:00'];
        for ($i = 0; $i < 5; $i++) {
            $date = date('Y-m-d', strtotime("2026-12-05 +{$i} days"));
            DB::table('free_consultation_schedules')->insert([
                'type_id' => $typeIds[1],
                'scheduled_date' => $date,
                'scheduled_time' => $loaTimes[$i],
                'is_available' => true,
                'max_participants' => 1,
                'current_bookings' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Type 3: Psikologi Dasar - Mix of morning and evening
        $psikologiTimes = ['09:00:00', '11:00:00', '14:00:00', '19:00:00', '10:00:00'];
        for ($i = 0; $i < 5; $i++) {
            $date = date('Y-m-d', strtotime("2026-12-03 +{$i} days"));
            DB::table('free_consultation_schedules')->insert([
                'type_id' => $typeIds[2],
                'scheduled_date' => $date,
                'scheduled_time' => $psikologiTimes[$i],
                'is_available' => true,
                'max_participants' => 1,
                'current_bookings' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Optional: Keep the data or uncomment to delete
        // DB::table('free_consultation_schedules')->delete();
        // DB::table('free_consultation_types')->delete();
    }
};

