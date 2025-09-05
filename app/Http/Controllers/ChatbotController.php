<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

class ChatbotController extends Controller
{
    public function handle()
    {
        // Load the BotMan Web Driver
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        // Configure BotMan with the necessary settings
        $config = [
            'web' => [
                'matchingData' => [
                    'driver' => 'web',
                ],
            ],
        ];

        // Create the BotMan instance
        $botman = BotManFactory::create($config);

        // --- Logika Respons Chatbot ---

        // Response for 'Halo' or similar greetings
        $botman->hears('^(halo|hi|hai|selamat pagi|selamat siang|selamat sore|selamat malam)$', function(BotMan $bot) {
            $bot->reply('Halo juga! Ada yang bisa saya bantu?');
        });

        // Response for 'siapa namamu'
        $botman->hears('siapa namamu', function(BotMan $bot) {
            $bot->reply('Nama saya adalah bot, saya dibuat untuk membantu Anda.');
        });

        // Response for 'terima kasih' or 'makasih'
        $botman->hears('^(terima kasih|makasih)$', function(BotMan $bot) {
            $bot->reply('Sama-sama! Senang bisa membantu.');
        });

        // Response for 'layanan' or 'layanan apa saja'
        $botman->hears('^(layanan|layanan apa saja|apa layanan Anda)$', function(BotMan $bot) {
            $this->showServices($bot);
        });

        // Fallback response for unrecognized messages
        $botman->fallback(function($bot) {
            $bot->reply('Maaf, saya tidak mengerti. Silakan ketik "Halo" atau "Layanan" untuk memulai.');
        });

        // Start listening for messages
        $botman->listen();
    }

    /**
     * Helper method to show available services.
     */
    private function showServices(BotMan $bot)
    {
        $bot->reply('Kami menyediakan beberapa layanan utama:');
        $bot->reply('1. Konsultasi Bisnis');
        $bot->reply('2. Konsultasi Keuangan');
        $bot->reply('3. Desain Produk');
        $bot->reply('Untuk detail lebih lanjut, silakan kunjungi halaman Layanan kami.');
    }
}
