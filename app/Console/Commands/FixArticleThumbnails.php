<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FixArticleThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:fix-thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix article thumbnail paths that are not displaying correctly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting thumbnail fix process...');
        
        $articles = Article::all();
        $fixed = 0;
        $alreadyCorrect = 0;
        $notFound = 0;
        
        $this->newLine();
        $this->info("Found {$articles->count()} articles to check.");
        $this->newLine();
        
        foreach ($articles as $article) {
            $currentThumbnail = $article->thumbnail;
            
            if (!$currentThumbnail) {
                $this->line("Article ID {$article->id}: No thumbnail set, skipping.");
                continue;
            }
            
            // Check various possible locations
            $possiblePaths = [
                'thumbnails/' . basename($currentThumbnail),
                'articles/' . basename($currentThumbnail),
                $currentThumbnail,
            ];
            
            $foundPath = null;
            foreach ($possiblePaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    $foundPath = $path;
                    break;
                }
            }
            
            // Also check if the thumbnail is just a filename (without directory)
            if (!$foundPath && !str_contains($currentThumbnail, '/')) {
                // Try to find in thumbnails folder
                if (Storage::disk('public')->exists('thumbnails/' . $currentThumbnail)) {
                    $foundPath = 'thumbnails/' . $currentThumbnail;
                }
            }
            
            if ($foundPath) {
                // Check if current path is correct
                if ($currentThumbnail === $foundPath) {
                    $this->line("Article ID {$article->id} ('{$article->title}'): Path is already correct.");
                    $alreadyCorrect++;
                } else {
                    // Update the path
                    $article->thumbnail = $foundPath;
                    $article->save();
                    $this->info("Article ID {$article->id} ('{$article->title}'): Fixed! Changed from '{$currentThumbnail}' to '{$foundPath}'");
                    $fixed++;
                }
            } else {
                // File doesn't exist, check if it's a full URL or external link
                if (filter_var($currentThumbnail, FILTER_VALIDATE_URL)) {
                    $this->line("Article ID {$article->id}: Using external URL - keeping as is.");
                } else {
                    $this->warn("Article ID {$article->id} ('{$article->title}'): Thumbnail file not found! Path: '{$currentThumbnail}'");
                    $notFound++;
                }
            }
        }
        
        $this->newLine();
        $this->info("=== Summary ===");
        $this->info("Already correct: {$alreadyCorrect}");
        $this->info("Fixed: {$fixed}");
        $this->warn("Not found: {$notFound}");
        
        return Command::SUCCESS;
    }
}
