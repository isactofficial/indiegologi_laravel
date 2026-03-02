<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class StorageDirectoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Ensure all required storage directories exist
        $requiredDirectories = [
            'testimonials',
            'articles',
            'sketches',
            'service-thumbnails',
            'event-thumbnails',
            'user-profiles',
            'thumbnails',
        ];

        foreach ($requiredDirectories as $directory) {
            if (!Storage::disk('public')->exists($directory)) {
                try {
                    Storage::disk('public')->makeDirectory($directory, 0755, true);
                } catch (\Exception $e) {
                    // Log error but don't fail application boot
                    \Log::warning("Could not create storage directory: $directory", [
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }
}
