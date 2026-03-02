<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Sketch;
use App\Models\Event;
use App\Models\ConsultationService;
use App\Models\Testimonial;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FixImportedThumbnails extends Command
{
    protected $signature = 'fix:imported-thumbnails {--model= : Specific model to fix (article, sketch, event, service, testimonial, all)}';
    protected $description = 'Fix thumbnail paths for imported data that are not displaying correctly';

    public function handle()
    {
        $model = $this->option('model') ?? 'all';
        
        $this->info("Starting thumbnail fix for imported data...");
        $this->newLine();

        switch ($model) {
            case 'article':
                $this->fixArticles();
                break;
            case 'sketch':
                $this->fixSketches();
                break;
            case 'event':
                $this->fixEvents();
                break;
            case 'service':
                $this->fixServices();
                break;
            case 'testimonial':
                $this->fixTestimonials();
                break;
            case 'all':
            default:
                $this->fixArticles();
                $this->fixSketches();
                $this->fixEvents();
                $this->fixServices();
                $this->fixTestimonials();
                break;
        }

        $this->newLine();
        $this->info("Fix process completed!");
        
        return Command::SUCCESS;
    }

    private function fixArticles()
    {
        $this->info("=== Fixing ARTICLES ===");
        
        // Get all articles with thumbnails
        $articles = DB::table('articles')
            ->whereNotNull('thumbnail')
            ->where('thumbnail', '!=', '')
            ->get();

        $this->info("Found {$articles->count()} articles with thumbnails");
        
        $fixed = 0;
        
        foreach ($articles as $article) {
            $oldPath = $article->thumbnail;
            $newPath = $this->normalizePath($oldPath, 'thumbnails');
            
            if ($newPath && $newPath !== $oldPath) {
                DB::table('articles')
                    ->where('id', $article->id)
                    ->update(['thumbnail' => $newPath]);
                
                $this->info("  Article ID {$article->id}: '{$oldPath}' -> '{$newPath}'");
                $fixed++;
            }
        }
        
        $this->info("Fixed {$fixed} article thumbnails");
        $this->newLine();
    }

    private function fixSketches()
    {
        $this->info("=== Fixing SKETCHES ===");
        
        $sketches = DB::table('sketches')
            ->whereNotNull('thumbnail')
            ->where('thumbnail', '!=', '')
            ->get();

        $this->info("Found {$sketches->count()} sketches with thumbnails");
        
        $fixed = 0;
        
        foreach ($sketches as $sketch) {
            $oldPath = $sketch->thumbnail;
            $newPath = $this->normalizePath($oldPath, 'sketches');
            
            if ($newPath && $newPath !== $oldPath) {
                DB::table('sketches')
                    ->where('id', $sketch->id)
                    ->update(['thumbnail' => $newPath]);
                
                $this->info("  Sketch ID {$sketch->id}: '{$oldPath}' -> '{$newPath}'");
                $fixed++;
            }
        }
        
        $this->info("Fixed {$fixed} sketch thumbnails");
        $this->newLine();
    }

    private function fixEvents()
    {
        $this->info("=== Fixing EVENTS ===");
        
        $events = DB::table('events')
            ->whereNotNull('thumbnail')
            ->where('thumbnail', '!=', '')
            ->get();

        $this->info("Found {$events->count()} events with thumbnails");
        
        $fixed = 0;
        
        foreach ($events as $event) {
            $oldPath = $event->thumbnail;
            $newPath = $this->normalizePath($oldPath, 'event-thumbnails');
            
            if ($newPath && $newPath !== $oldPath) {
                DB::table('events')
                    ->where('id', $event->id)
                    ->update(['thumbnail' => $newPath]);
                
                $this->info("  Event ID {$event->id}: '{$oldPath}' -> '{$newPath}'");
                $fixed++;
            }
        }
        
        $this->info("Fixed {$fixed} event thumbnails");
        $this->newLine();
    }

    private function fixServices()
    {
        $this->info("=== Fixing CONSULTATION SERVICES ===");
        
        $services = DB::table('consultation_services')
            ->whereNotNull('thumbnail')
            ->where('thumbnail', '!=', '')
            ->get();

        $this->info("Found {$services->count()} services with thumbnails");
        
        $fixed = 0;
        
        foreach ($services as $service) {
            $oldPath = $service->thumbnail;
            $newPath = $this->normalizePath($oldPath, 'service-thumbnails');
            
            if ($newPath && $newPath !== $oldPath) {
                DB::table('consultation_services')
                    ->where('id', $service->id)
                    ->update(['thumbnail' => $newPath]);
                
                $this->info("  Service ID {$service->id}: '{$oldPath}' -> '{$newPath}'");
                $fixed++;
            }
        }
        
        $this->info("Fixed {$fixed} service thumbnails");
        $this->newLine();
    }

    private function fixTestimonials()
    {
        $this->info("=== Fixing TESTIMONIALS ===");
        
        $testimonials = DB::table('testimonials')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->get();

        $this->info("Found {$testimonials->count()} testimonials with images");
        
        $fixed = 0;
        
        foreach ($testimonials as $testimonial) {
            $oldPath = $testimonial->image;
            $newPath = $this->normalizePath($oldPath, 'testimoni');
            
            if ($newPath && $newPath !== $oldPath) {
                DB::table('testimonials')
                    ->where('id', $testimonial->id)
                    ->update(['image' => $newPath]);
                
                $this->info("  Testimonial ID {$testimonial->id}: '{$oldPath}' -> '{$newPath}'");
                $fixed++;
            }
        }
        
        $this->info("Fixed {$fixed} testimonial images");
        $this->newLine();
    }

    /**
     * Normalize the thumbnail path to the correct format
     */
    private function normalizePath($path, $defaultFolder)
    {
        // If path is empty or null, return null
        if (empty($path)) {
            return null;
        }

        // If it's already a valid URL, keep it as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // If it already has the correct format (e.g., "thumbnails/filename.jpg"), check if file exists
        if (preg_match('/^[a-zA-Z0-9_\-\/]+\.[a-zA-Z]+$/', $path)) {
            // Check if file exists in storage
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
            
            // Try to find in the default folder
            $filename = basename($path);
            if (Storage::disk('public')->exists($defaultFolder . '/' . $filename)) {
                return $defaultFolder . '/' . $filename;
            }
        }

        // Remove Windows drive letters (C:\, D:\, etc.)
        $path = preg_replace('/^[A-Za-z]:\\\\/', '', $path);
        
        // Remove leading slashes
        $path = ltrim($path, '/');
        
        // Remove backslashes and replace with forward slashes
        $path = str_replace('\\', '/', $path);

        // If path contains storage/app/public, remove that part
        $path = str_replace('storage/app/public/', '', $path);
        
        // If path starts with 'public/', remove it
        $path = str_replace('public/', '', $path);

        // Extract just the filename
        $filename = basename($path);
        
        // Check if the file exists in the default folder
        if (Storage::disk('public')->exists($defaultFolder . '/' . $filename)) {
            return $defaultFolder . '/' . $filename;
        }
        
        // Also check in root of public folder
        if (Storage::disk('public')->exists($filename)) {
            return $filename;
        }

        // If we can't find the file, return the original path
        // but add the folder prefix if it looks like just a filename
        if (!str_contains($path, '/')) {
            return $defaultFolder . '/' . $path;
        }

        return $path;
    }
}
