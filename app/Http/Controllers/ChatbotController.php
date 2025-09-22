<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Web\WebDriver;
use Illuminate\Support\Facades\Log;
use App\Models\Article;

class ChatbotController extends Controller
{
    /**
     * Handle the BotMan conversation.
     *
     * @param Request $request
     * @return void
     */
    public function handle(Request $request)
    {
        try {
            DriverManager::loadDriver(WebDriver::class);

            $config = [
                'web' => [
                    'matchingData' => [
                        'driver' => 'web',
                    ],
                ],
            ];

            $botman = BotManFactory::create($config, null, $request);

            // Perintah: 'help', 'bantuan', 'start', 'hi', 'hello'
            $botman->hears('.*(help|bantuan|start|hi|hello).*', function (BotMan $bot) {
                $response = "🎨 Selamat datang di *Indiegologi Assistant*!\n\n";
                $response .= "Saya bisa membantu Anda menavigasi website. Berikut perintah yang tersedia:\n";
                $response .= '• <a href="#" class="chatbot-command">artikel</a> - Menampilkan artikel terbaru' . "\n";
                $response .= '• <a href="#" class="chatbot-command">layanan</a> - Membuka halaman layanan kami' . "\n";
                $response .= '• <a href="#" class="chatbot-command">sketsa</a> - Melihat galeri sketsa' . "\n";
                $response .= '• <a href="#" class="chatbot-command">kontak</a> - Membuka halaman kontak' . "\n\n";
                $response .= "Silakan klik salah satu perintah di atas untuk memulai.";
                $bot->reply($response);
            });

            // Perintah: 'artikel'
            $botman->hears('.*(artikel|article|blog).*', function (BotMan $bot) {
                $articles = Article::where('status', 'published')
                                   ->latest()
                                   ->take(3)
                                   ->get();

                if ($articles->isEmpty()) {
                    $bot->reply("Saat ini belum ada artikel yang dipublikasikan. Silakan cek lagi nanti!");
                    return;
                }

                $response = "Berikut adalah 3 artikel terbaru kami:\n\n";
                foreach ($articles as $article) {
                    $articleUrl = route('front.articles.show', $article);
                    $response .= "📄 *" . $article->title . "*\n";
                    $response .= '<a href="' . $articleUrl . '" target="_blank" class="chatbot-link">Lihat Artikel</a>' . "\n\n";
                }
                $response .= "Anda juga bisa melihat semua artikel dengan menekan tombol di bawah ini:\n";
                $response .= '<a href="' . route('front.articles') . '" target="_blank" class="chatbot-button">Lihat Semua Artikel</a>';
                $bot->reply($response);
            });

            // Perintah: 'layanan'
            $botman->hears('.*(layanan|services|service).*', function (BotMan $bot) {
                $url = route('front.layanan');
                $response = "Kami menyediakan berbagai layanan desain dan konsultasi.\n\n";
                $response .= "Silakan klik tombol di bawah ini untuk melihat daftar lengkap layanan kami:\n";
                // Mengirimkan HTML untuk tombol
                $response .= '<a href="' . $url . '" target="_blank" class="chatbot-button">Buka Halaman Layanan</a>';
                $bot->reply($response);
            });

            // Perintah: 'sketsa'
            $botman->hears('.*(sketsa|sketch).*', function (BotMan $bot) {
                $url = route('front.sketch');
                $response = "Temukan inspirasi dari galeri sketsa hasil karya tim kami.\n\n";
                $response .= "Silakan klik tombol di bawah ini untuk melihat galeri sketsa kami:\n";
                $response .= '<a href="' . $url . '" target="_blank" class="chatbot-button">Lihat Galeri Sketsa</a>';
                $bot->reply($response);
            });

            // Perintah: 'kontak'
            $botman->hears('.*(kontak|contact|hubungi).*', function (BotMan $bot) {
                $url = route('front.contact');
                $response = "Punya pertanyaan atau ingin memulai proyek?\n\n";
                $response .= "Anda dapat menghubungi kami melalui halaman kontak dengan menekan tombol berikut:\n";
                // Mengirimkan HTML untuk tombol
                $response .= '<a href="' . $url . '" target="_blank" class="chatbot-button">Hubungi Kami</a>';
                $bot->reply($response);
            });

            // Fallback untuk pesan yang tidak dikenali
            $botman->fallback(function (BotMan $bot) {
                $bot->reply("Maaf, saya tidak mengerti. Ketik `help` untuk melihat daftar perintah yang tersedia.");
            });

            $botman->listen();

        } catch (\Exception $e) {
            Log::error('BotMan Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'messages' => [['text' => 'Maaf, terjadi kesalahan pada server chatbot.']]
            ]);
        }
    }
}
