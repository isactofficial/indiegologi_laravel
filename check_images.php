<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

echo "=== EVENTS ===\n";
$events = \App\Models\Event::all(['id', 'title', 'thumbnail']);
foreach ($events as $event) {
    echo "Event {$event->id}: {$event->title} -> Thumbnail: " . ($event->thumbnail ?: 'NULL') . "\n";
}

echo "\n=== CONSULTATION SERVICES ===\n";
$services = \App\Models\ConsultationService::all(['id', 'title', 'thumbnail']);
foreach ($services as $service) {
    echo "Service {$service->id}: {$service->title} -> Thumbnail: " . ($service->thumbnail ?: 'NULL') . "\n";
}

echo "\n=== TESTIMONIALS ===\n";
$testimonials = \App\Models\Testimonial::all(['id', 'name', 'image']);
foreach ($testimonials as $testimonial) {
    echo "Testimonial {$testimonial->id}: {$testimonial->name} -> Image: " . ($testimonial->image ?: 'NULL') . "\n";
}

echo "\n=== USER PROFILES ===\n";
$profiles = \App\Models\UserProfile::all(['id', 'user_id', 'profile_photo']);
foreach ($profiles as $profile) {
    echo "Profile {$profile->id} (User {$profile->user_id}): Photo: " . ($profile->profile_photo ?: 'NULL') . "\n";
}
