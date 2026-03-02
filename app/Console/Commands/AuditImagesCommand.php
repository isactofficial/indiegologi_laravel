<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuditImagesCommand extends Command
{
    protected $signature = 'images:audit';
    protected $description = 'Audit semua referensi gambar di database dan tentukan mana yang missing';

    public function handle()
    {
        $this->line('=== IMAGE AUDIT ===');
        $this->newLine();

        // Check Events
        $this->line('🔍 Checking EVENTS table...');
        $events = DB::table('events')->whereNotNull('thumbnail')->get(['id', 'title', 'thumbnail']);
        $this->checkImages($events, 'title');

        // Check Consultation Services
        $this->line('🔍 Checking CONSULTATION_SERVICES table...');
        $services = DB::table('consultation_services')->whereNotNull('thumbnail')->get(['id', 'title', 'thumbnail']);
        $this->checkImages($services, 'title');

        // Check Testimonials
        $this->line('🔍 Checking TESTIMONIALS table...');
        $testimonials = DB::table('testimonials')->whereNotNull('image')->get(['id', 'name', 'image']);
        $this->checkImages($testimonials, 'name', 'image');

        // Check User Profiles
        $this->line('🔍 Checking USER_PROFILES table...');
        $profiles = DB::table('user_profiles')->whereNotNull('profile_photo')->get(['id', 'user_id', 'profile_photo']);
        $this->checkImages($profiles, 'user_id', 'profile_photo');

        // Check Sketches
        $this->line('🔍 Checking SKETCHES table...');
        $sketches = DB::table('sketches')->whereNotNull('thumbnail')->get(['id', 'title', 'thumbnail']);
        $this->checkImages($sketches, 'title');

        $this->newLine();
        $this->info('✅ Audit selesai!');
    }

    private function checkImages($records, $displayField = 'title', $imageField = 'thumbnail')
    {
        if ($records->isEmpty()) {
            $this->comment("  ℹ️  Tidak ada data dengan gambar");
            return;
        }

        $missing = 0;
        $found = 0;

        foreach ($records as $record) {
            $path = $record->$imageField;
            if (!$path) continue;

            if (Storage::disk('public')->exists($path)) {
                $found++;
                // $this->info("  ✓ {$record->id}: {$path}");
            } else {
                $missing++;
                $this->warn("  ✗ ID {$record->id} ({$record->$displayField}): {$path} [FILE NOT FOUND]");
            }
        }

        $this->line("  📊 Total: " . ($found + $missing) . " | Found: $found | Missing: $missing");
    }
}
