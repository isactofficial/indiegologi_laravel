<?php

// File: database/seeders/FreeConsultationSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FreeConsultationType;
use App\Models\FreeConsultationSchedule;
use Carbon\Carbon;

class FreeConsultationSeeder extends Seeder
{
    public function run()
    {
        // Create consultation types
        $types = [
            [
                'name' => 'Pendampingan Meditasi',
                'description' => 'Sesi konsultasi gratis untuk belajar teknik meditasi dasar dan membangun rutinitas meditasi yang sehat untuk kehidupan sehari-hari.',
                'status' => 'active'
            ],
            [
                'name' => 'Law of Attraction',
                'description' => 'Konsultasi tentang prinsip-prinsip law of attraction dan cara menerapkannya dalam kehidupan sehari-hari untuk mencapai tujuan.',
                'status' => 'active'
            ],
            [
                'name' => 'Psikologi Dasar',
                'description' => 'Pemahaman dasar tentang psikologi manusia dan cara mengelola emosi serta pikiran dengan lebih baik.',
                'status' => 'active'
            ]
        ];

        foreach ($types as $typeData) {
            $type = FreeConsultationType::create($typeData);

            // Create 5 different schedules for each type
            $schedules = $this->generateSchedulesForType($type->id);
            
            foreach ($schedules as $scheduleData) {
                FreeConsultationSchedule::create($scheduleData);
            }
        }
    }

    private function generateSchedulesForType($typeId)
    {
        $schedules = [];
        $baseDate = Carbon::now()->addDays(2); // Start from 2 days from now

        // Generate 5 different schedules with different dates and times
        $timeSlots = [
            ['09:00:00', '10:00:00'],
            ['11:00:00', '13:00:00'],
            ['14:00:00', '15:30:00'],
            ['16:00:00', '10:30:00'],
            ['09:30:00', '14:30:00']
        ];

        for ($i = 0; $i < 5; $i++) {
            $scheduleDate = $baseDate->copy()->addDays($i * 2); // Every 2 days
            $timeSlot = $timeSlots[$i];
            
            $schedules[] = [
                'type_id' => $typeId,
                'scheduled_date' => $scheduleDate->format('Y-m-d'),
                'scheduled_time' => $timeSlot[0],
                'is_available' => true,
                'max_participants' => 1,
                'current_bookings' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Add some variation based on type
        switch ($typeId) {
            case 1: // Pendampingan Meditasi
                // Add morning slots preference
                $schedules[0]['scheduled_time'] = '07:00:00';
                $schedules[1]['scheduled_time'] = '08:30:00';
                break;
                
            case 2: // Law of Attraction
                // Add afternoon slots preference
                $schedules[2]['scheduled_time'] = '15:00:00';
                $schedules[3]['scheduled_time'] = '16:30:00';
                break;
                
            case 3: // Psikologi Dasar
                // Mix of morning and evening slots
                $schedules[0]['scheduled_time'] = '09:00:00';
                $schedules[4]['scheduled_time'] = '19:00:00';
                break;
        }

        return $schedules;
    }
}

// Run this seeder with: php artisan db:seed --class=FreeConsultationSeeder