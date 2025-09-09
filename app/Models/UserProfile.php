<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'profile_photo',
        'name',
        'email',
        'birthdate',
        'gender',
        'phone_number',
        'social_media',
        'description',

    ];

    /**
     * Relasi ke model User.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * LOGIKA OTOMATIS UNTUK ZODIAK & SHIO
     */

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Event ini akan berjalan setiap kali model akan disimpan (baik saat membuat baru atau update)
        static::saving(function ($profile) {
            // Cek apakah kolom 'birthdate' diubah atau baru diisi
            if ($profile->isDirty('birthdate')) {
                if ($profile->birthdate) {
                    // Jika birthdate ada, hitung dan set nilai zodiac dan shio
                    $profile->zodiac = self::calculateZodiacSign($profile->birthdate);
                    $profile->shio_element = self::calculateShioAndElement($profile->birthdate);
                } else {
                    // Jika birthdate dikosongkan, kosongkan juga zodiac dan shio
                    $profile->zodiac = null;
                    $profile->shio_element = null;
                }
            }
        });
    }

    /**
     * Menghitung Zodiak berdasarkan tanggal lahir.
     *
     * @param string $birthdate
     * @return string|null
     */
    private static function calculateZodiacSign($birthdate)
    {
        try {
            $date = Carbon::parse($birthdate);
            $month = $date->month;
            $day = $date->day;

            if (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) return 'Aries';
            if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) return 'Taurus';
            if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 20)) return 'Gemini';
            if (($month == 6 && $day >= 21) || ($month == 7 && $day <= 22)) return 'Cancer';
            if (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) return 'Leo';
            if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) return 'Virgo';
            if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 22)) return 'Libra';
            if (($month == 10 && $day >= 23) || ($month == 11 && $day <= 21)) return 'Scorpio';
            if (($month == 11 && $day >= 22) || ($month == 12 && $day <= 21)) return 'Sagittarius';
            if (($month == 12 && $day >= 22) || ($month == 1 && $day <= 19)) return 'Capricorn';
            if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) return 'Aquarius';
            if (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) return 'Pisces';
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }

    /**
     * Menghitung Shio & Elemen berdasarkan tanggal lahir.
     *
     * @param string $birthdate
     * @return string|null
     */
    private static function calculateShioAndElement($birthdate)
    {
        try {
            $date = Carbon::parse($birthdate);
            $year = $date->year;

            $shio = ['Monyet', 'Ayam', 'Anjing', 'Babi', 'Tikus', 'Kerbau', 'Harimau', 'Kelinci', 'Naga', 'Ular', 'Kuda', 'Kambing'];
            $elements = ['Logam', 'Logam', 'Air', 'Air', 'Kayu', 'Kayu', 'Api', 'Api', 'Tanah', 'Tanah'];

            $shioName = $shio[($year - 1900) % 12];
            $elementName = $elements[$year % 10];

            return "{$shioName} {$elementName}";
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}
