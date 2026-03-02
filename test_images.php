<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class)->bootstrap();

// Check Testimonials
$testimonials = \Illuminate\Support\Facades\DB::table('testimonials')->select('id', 'name', 'image')->limit(5)->get();
echo "=== TESTIMONIALS ===\n";
foreach ($testimonials as $t) {
    echo "ID: {$t->id}, Name: {$t->name}, Image: {$t->image}\n";
}

// Check Articles
$articles = \Illuminate\Support\Facades\DB::table('articles')->select('id', 'title', 'thumbnail')->limit(5)->get();
echo "\n=== ARTICLES ===\n";
foreach ($articles as $a) {
    echo "ID: {$a->id}, Title: {$a->title}, Thumbnail: {$a->thumbnail}\n";
}

// Check Events
$events = \Illuminate\Support\Facades\DB::table('events')->select('id', 'title', 'thumbnail')->limit(5)->get();
echo "\n=== EVENTS ===\n";
foreach ($events as $e) {
    echo "ID: {$e->id}, Title: {$e->title}, Thumbnail: {$e->thumbnail}\n";
}

// Check Sketches
$sketches = \Illuminate\Support\Facades\DB::table('sketches')->select('id', 'title', 'thumbnail')->limit(5)->get();
echo "\n=== SKETCHES ===\n";
foreach ($sketches as $s) {
    echo "ID: {$s->id}, Title: {$s->title}, Thumbnail: {$s->thumbnail}\n";
}
