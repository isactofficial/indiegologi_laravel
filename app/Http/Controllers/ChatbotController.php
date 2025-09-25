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
                $response .= '• <a href="#" class="chatbot-command">Artikel</a> - Menampilkan artikel terbaru' . "\n";
                $response .= '• <a href="#" class="chatbot-command">Layanan</a> - Membuka halaman layanan kami' . "\n";
                $response .= '• <a href="#" class="chatbot-command">Sketsa</a> - Melihat galeri sketsa' . "\n";
                $response .= '• <a href="#" class="chatbot-command">Hubungi Kami</a> - Menampilkan opsi kontak' . "\n\n";
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
                $response = "Tentu, kami menyediakan berbagai layanan dan konsultasi.\n\n";
                $response .= "**Berikut adalah *cara memesan layanan* kami:**\n";
                $response .= "1. Pilih layanan dari daftar yang tersedia.\n";
                $response .= "2. Isi formulir jadwal dan masukkan **Kode Referral** jika ada.\n";
                $response .= "3. Klik tombol `Pilih Layanan` untuk menambahkannya ke keranjang.\n";
                $response .= "4. Cek kembali pesanan Anda di halaman **Keranjang**.\n";
                $response .= "5. Pilih **Tipe Pembayaran** yang diinginkan.\n";
                $response .= "6. Klik `Checkout` untuk melakukan pembayaran (Pastikan Anda sudah *Login* terlebih dahulu).\n\n";
                $response .= "Untuk memulai, silakan klik tombol di bawah ini untuk melihat semua layanan kami:\n";
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

            // Perintah: 'kontak' - MODIFIED
            $botman->hears('.*(kontak|contact|hubungi).*', function (BotMan $bot) {
                // URL untuk WhatsApp dengan nomor yang diminta
                $waUrl = 'https://wa.me/6282220955595';

                // URL untuk halaman kontak
                $contactUrl = route('front.contact');

                // Merangkai pesan respons
                $response = "Punya pertanyaan atau ingin memulai proyek?\n\n";
                $response .= "Anda dapat langsung menghubungi kami melalui WhatsApp atau mengunjungi halaman kontak kami dengan menekan tombol di bawah ini:\n\n";

                // Tombol untuk WhatsApp
                $response .= '<a href="' . $waUrl . '" target="_blank" class="chatbot-button"> **+62 822-2095-5595** </a>' . "\n\n";

                // Tombol untuk Halaman Kontak
                $response .= '<a href="' . $contactUrl . '" target="_blank" class="chatbot-button">Buka Halaman Kontak Kami</a>';

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
