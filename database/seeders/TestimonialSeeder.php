<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Wira',
                'age' => 27,
                'occupation' => 'Public university staff',
                'quote' => "As a person who's currently entering my 20's, I have to know myself more and discover my best potential. With \"indiegologi\", I can evolve to a better version of myself. With their sketch, I'm stongly reminded of who I was at that moment and what traits of me that can be developed more to reach my best potential. This helps me to achieve more in life as I grow to be the best version of me. Thank you, indiegologi!",
                'image' => 'assets/testimoni/Wira.jpeg',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Vika Matin',
                'age' => 32,
                'occupation' => 'Entrepreneur',
                'quote' => 'Bingung banget punya anak 2 deketan usia dengan segala dramanya tapi sulit nemu solusi tiap masalahnya. Takut salah treatment ke anak, karena setiap anak itu berbeda. Enak sama indiegologi ga perlu cerita. Tinggal pasang muka dia dah beberin semua hal yg kita bahkan ga sadar. thanks mas. You become a solution.',
                'image' => 'assets/testimoni/Vika.jpg',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Oghie',
                'age' => 35,
                'occupation' => 'Construction Business Owner',
                'quote' => 'Sebagai owner ada aja hal teknis yang sebenernya gampang tapi sulit ditentukan. Jadi kami punya peluang untuk mengisi jabatan strategis malah bimbang untuk milih siapa yang pas pada posisi tersebut. Alhamdulillah ketemu juga solusinya tidak puyeng karena pasti ada efeknya jika menempatkan orang yang mungkin pas tapi hasilnya zonk. Makasih loh udah di filter karyawan ku. Jadi gak bimbang naro orang lagi ini.',
                'image' => 'assets/testimoni/Ophie.jpeg',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Dean',
                'age' => 36,
                'occupation' => 'Architect',
                'quote' => 'I consult with indiego about many things, ranging from projects, family, friendships, even finances. The counseling is very helpful in making decisions, even though the sharing method is unique to me. We tell a little story and they immediately respond with real time character sketches, what kind of face are we "presenting" which is our condition at that time that will be examined. For those who are curious, please try it, you will be surprised with a "how come he knows" respond and they will help to find a solution. Good luck for "indiegologi" through "cerita indiegologi".',
                'image' => 'assets/testimoni/Dean.jpg',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Dienar',
                'age' => 32,
                'occupation' => 'Entrepreneur',
                'quote' => 'Konseling sama indiegologi itu enak banget, konseling tapi rasanya kaya curhat ke temen. Pendengar yg sangat baik, solving problem nya tidak menggurui, saran yang diberikan praktikal semua , bukan cuma sekedar teori yg bikin kita bingung harus mulai dari mana . Rasanya nyaman, karena tidak ada judgement, pendekatannya sesuai dengan karakter kita. Dan gak terburu-buru. Selalu menguatkan, bahwa ini semua adalah proses. Thanks a lot Mas, banyak hal yang udah di sharingkan ke aku, dan semuanya ngena banget.',
                'image' => 'assets/testimoni/Dienar 1.jpeg',
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Dhiana',
                'age' => 42,
                'occupation' => 'HR & GA manager',
                'quote' => 'I am amazed, they can define our personal strengths and weaknesses from facial sketches. So its easier to develop and focus on the abilities that we have, the gifts from God. Great talent!',
                'image' => 'assets/testimoni/Dhiana 1.jpeg',
                'is_active' => true,
                'sort_order' => 6
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}