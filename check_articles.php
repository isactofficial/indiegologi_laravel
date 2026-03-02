<?php

// Simple script to check article thumbnails in database

$host = '127.0.0.1';
$db   = 'indiegologi_laravel';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

echo "=== CHECKING ARTICLES THUMBNAILS ===\n\n";

$stmt = $pdo->query("SELECT id, title, thumbnail, status FROM articles LIMIT 10");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($articles as $article) {
    echo "ID: {$article['id']}\n";
    echo "Title: {$article['title']}\n";
    echo "Status: {$article['status']}\n";
    echo "Thumbnail: " . ($article['thumbnail'] ? $article['thumbnail'] : 'NULL') . "\n";
    
    if ($article['thumbnail']) {
        // Check if file exists
        $fullPath = 'c:/Users/TUF/Documents/GitHub/Indiegologi/indiegologi_laravel/storage/app/public/' . $article['thumbnail'];
        if (file_exists($fullPath)) {
            echo "File exists: YES\n";
        } else {
            echo "File exists: NO - Path: $fullPath\n";
        }
    }
    echo "\n" . str_repeat("-", 50) . "\n\n";
}
