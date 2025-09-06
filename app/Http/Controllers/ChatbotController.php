<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use App\Models\Article;
use App\Models\Sketch;
use App\Models\ConsultationService;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Handle the incoming chatbot requests.
     */
    public function handle()
    {
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        $config = [
            'web' => [
                'matchingData' => [
                    'driver' => 'web',
                ],
            ],
        ];

        $botman = BotManFactory::create($config);

        // --- Logika Respons Chatbot Cerdas ---

        // Respon sapaan yang lebih ramah dan informatif
        $botman->hears('^(halo|hi|hai|selamat pagi|selamat siang|selamat sore|selamat malam)$', function (BotMan $bot) {
            $bot->reply('Halo! 👋 Saya Mindie, asisten virtual Indiegologi. Ada yang bisa saya bantu? Kamu bisa tanya tentang "layanan", "artikel terbaru", atau "sketsa".');
        });

        // Respon untuk 'siapa namamu'
        $botman->hears('siapa namamu', function (BotMan $bot) {
            $bot->reply('Saya Mindie, asisten virtual yang siap membantumu menjelajahi Indiegologi.');
        });
        
        // Respon untuk 'terima kasih'
        $botman->hears('^(terima kasih|makasih|thanks|thx)$', function (BotMan $bot) {
            $bot->reply('Sama-sama! Jika ada hal lain yang ingin kamu tanyakan, jangan ragu ya. 😊');
        });

        // Respon interaktif untuk permintaan "layanan"
        $botman->hears('^(tampilkan|info|apa saja)?\s?(layanan|service|jasa|konsultasi)', function (BotMan $bot) {
            $this->showServices($bot);
        });

        // Respon interaktif untuk permintaan "artikel"
        $botman->hears('^(tampilkan|info|lihat)?\s?(artikel|tulisan|blog)\s?(terbaru)?', function (BotMan $bot) {
            $this->showRecentArticles($bot);
        });

        // Respon interaktif untuk permintaan "sketsa"
        $botman->hears('^(tampilkan|info|lihat)?\s?(sketsa|sketch|desain|karya)\s?(terbaru)?', function (BotMan $bot) {
            $this->showRecentSketches($bot);
        });

$botman->fallback(function ($bot) {
            // 1. Dapatkan pesan mentah (payload)
            $message = $bot->getMessage();
            $payload = $message->getPayload();

            // 2. JIKA INI ADALAH KLIK TOMBOL, ABAIKAN DAN HENTIKAN
            // Ini mencegah error saat tombol benar-benar di-klik nanti.
            if (isset($payload['type']) && $payload['type'] === 'web_button_callback') {
                return;
            }

            // 3. Dapatkan teks pesan
            $keyword = $message->getText();

            // 4. JIKA PESAN KOSONG ATAU NULL (KARENA EFEK "GEMA"), ABAIKAN
            // Ini adalah perbaikan utama untuk masalah di screenshot-mu.
            if (empty($keyword) || strtolower($keyword) === 'null') {
                // Jangan balas apa-apa, cukup hentikan eksekusi.
                return;
            }

            // 5. HANYA JIKA ADA KATA KUNCI ASLI, LAKUKAN PENCARIAN
            $this->performSearch($bot, $keyword);
        });


        $botman->listen();
    }

    /**
     * Mengambil dan menampilkan layanan konsultasi secara dinamis.
     */
    private function showServices(BotMan $bot)
    {
        $services = ConsultationService::where('status', 'published')->orWhere('status', 'special')->get();

        if ($services->isEmpty()) {
            $bot->reply('Saat ini kami belum memiliki layanan yang tersedia. Silakan cek kembali nanti.');
            return;
        }

        $bot->reply('Tentu, ini adalah beberapa layanan unggulan yang kami tawarkan:');

        foreach ($services as $service) {
            // Membuat pesan dengan gambar (thumbnail)
            $attachment = new Image(url('storage/' . $service->thumbnail));
            $message = OutgoingMessage::create()->withAttachment($attachment);
            $bot->reply($message);
            
            // Membuat pertanyaan dengan tombol
            $question = Question::create("✨ *{$service->title}*\n\n{$service->short_description}")
                ->addButtons([
                    Button::create('Lihat Detail Layanan')->url(url('/layanan/' . $service->id)),
                ]);

            $bot->reply($question);
        }
    }

    /**
     * Mengambil dan menampilkan 3 artikel terbaru.
     */
    private function showRecentArticles(BotMan $bot)
    {
        $articles = Article::where('status', 'Published')->latest()->take(3)->get();

        if ($articles->isEmpty()) {
            $bot->reply('Maaf, belum ada artikel yang dipublikasikan saat ini.');
            return;
        }

        $bot->reply('Ini dia 3 artikel terbaru dari kami, semoga bisa memberimu inspirasi:');

        foreach ($articles as $article) {
            $attachment = new Image(url('storage/' . $article->thumbnail));
            $message = OutgoingMessage::create()->withAttachment($attachment);
            $bot->reply($message);
            
            $question = Question::create("📄 *{$article->title}*\n\n" . Str::limit($article->description, 100))
                ->addButtons([
                    Button::create('Baca Selengkapnya')->url(url('/articles/' . $article->slug)),
                ]);

            $bot->reply($question);
        }
    }

    /**
     * Mengambil dan menampilkan 3 sketsa terbaru.
     */
    private function showRecentSketches(BotMan $bot)
    {
        $sketches = Sketch::where('status', 'Published')->latest()->take(3)->get();

        if ($sketches->isEmpty()) {
            $bot->reply('Belum ada sketsa yang bisa ditampilkan saat ini.');
            return;
        }

        $bot->reply('Berikut adalah beberapa sketsa dan karya terbaru kami:');

        foreach ($sketches as $sketch) {
            $attachment = new Image(url('storage/' . $sketch->thumbnail));
            $message = OutgoingMessage::create()->withAttachment($attachment);
            $bot->reply($message);

            $question = Question::create("🎨 *{$sketch->title}*\n\nOleh: {$sketch->author}")
                ->addButtons([
                    Button::create('Lihat Sketsa')->url(url('/sketches/' . $sketch->slug)),
                ]);
            
            $bot->reply($question);
        }
    }

    /**
     * Melakukan pencarian berdasarkan kata kunci di semua sumber daya.
     */
    private function performSearch(BotMan $bot, $keyword)
    {
        $services = ConsultationService::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('short_description', 'LIKE', "%{$keyword}%")
            ->whereIn('status', ['published', 'special'])
            ->get();

        $articles = Article::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('description', 'LIKE', "%{$keyword}%")
            ->where('status', 'Published')
            ->get();

        $sketches = Sketch::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('content', 'LIKE', "%{$keyword}%")
            ->where('status', 'Published')
            ->get();

         if ($services->isEmpty() && $articles->isEmpty() && $sketches->isEmpty()) {
            $bot->reply("Maaf, saya tidak dapat menemukan apa pun yang cocok dengan kata kunci \"{$keyword}\". 🙁\n\nAnda bisa mencoba kata kunci lain atau tanyakan tentang \"layanan\", \"artikel\", atau \"sketsa\".");
            return;
        }

        $bot->reply('Saya menemukan beberapa hal yang mungkin relevan dengan pencarianmu:');

        // Menampilkan hasil pencarian layanan
        foreach ($services as $service) {
            $question = Question::create(" Layanan: *{$service->title}*")
                ->addButtons([
                    Button::create('Lihat Detail')->url(url('/layanan/' . $service->id)),
                ]);
            $bot->reply($question);
        }

        // Menampilkan hasil pencarian artikel
        foreach ($articles as $article) {
             $question = Question::create("Artikel: *{$article->title}*")
                ->addButtons([
                    Button::create('Baca Artikel')->url(url('/articles/' . $article->slug)),
                ]);
            $bot->reply($question);
        }

        // Menampilkan hasil pencarian sketsa
        foreach ($sketches as $sketch) {
             $question = Question::create("Sketsa: *{$sketch->title}*")
                ->addButtons([
                    Button::create('Lihat Sketsa')->url(url('/sketches/' . $sketch->slug)),
                ]);
            $bot->reply($question);
        }
    }
}