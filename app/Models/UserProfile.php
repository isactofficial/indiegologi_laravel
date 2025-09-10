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
        'zodiac',         // <-- [DITAMBAHKAN]
        'shio_element',   // <-- [DITAMBAHKAN]
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
     * Tabel data Tahun Baru Imlek untuk perhitungan Shio yang akurat.
     * @var array
     */
    private static $lunarNewYearDates = [
        1930 => '1930-01-30', 1931 => '1931-02-17', 1932 => '1932-02-06', 1933 => '1933-01-26',
        1934 => '1934-02-14', 1935 => '1935-02-04', 1936 => '1936-01-24', 1937 => '1937-02-11',
        1938 => '1938-01-31', 1939 => '1939-02-19', 1940 => '1940-02-08', 1941 => '1941-01-27',
        1942 => '1942-02-15', 1943 => '1943-02-05', 1944 => '1944-01-25', 1945 => '1945-02-13',
        1946 => '1946-02-02', 1947 => '1947-01-22', 1948 => '1948-02-10', 1949 => '1949-01-29',
        1950 => '1950-02-17', 1951 => '1951-02-06', 1952 => '1952-01-27', 1953 => '1953-02-14',
        1954 => '1954-02-03', 1955 => '1955-01-24', 1956 => '1956-02-12', 1957 => '1957-01-31',
        1958 => '1958-02-18', 1959 => '1959-02-08', 1960 => '1960-01-28', 1961 => '1961-02-15',
        1962 => '1962-02-05', 1963 => '1963-01-25', 1964 => '1964-02-13', 1965 => '1965-02-02',
        1966 => '1966-01-21', 1967 => '1967-02-09', 1968 => '1968-01-30', 1969 => '1969-02-17',
        1970 => '1970-02-06', 1971 => '1971-01-27', 1972 => '1972-02-15', 1973 => '1973-02-03',
        1974 => '1974-01-23', 1975 => '1975-02-11', 1976 => '1976-01-31', 1977 => '1977-02-18',
        1978 => '1978-02-07', 1979 => '1979-01-28', 1980 => '1980-02-16', 1981 => '1981-02-05',
        1982 => '1982-01-25', 1983 => '1983-02-13', 1984 => '1984-02-02', 1985 => '1985-02-20',
        1986 => '1986-02-09', 1987 => '1987-01-29', 1988 => '1988-02-17', 1989 => '1989-02-06',
        1990 => '1990-01-27', 1991 => '1991-02-15', 1992 => '1992-02-04', 1993 => '1993-01-23',
        1994 => '1994-02-10', 1995 => '1995-01-31', 1996 => '1996-02-19', 1997 => '1997-02-07',
        1998 => '1998-01-28', 1999 => '1999-02-16', 2000 => '2000-02-05', 2001 => '2001-01-24',
        2002 => '2002-02-12', 2003 => '2003-02-01', 2004 => '2004-01-22', 2005 => '2005-02-09',
        2006 => '2006-01-29', 2007 => '2007-02-18', 2008 => '2008-02-07', 2009 => '2009-01-26',
        2010 => '2010-02-14', 2011 => '2011-02-03', 2012 => '2012-01-23', 2013 => '2013-02-10',
        2014 => '2014-01-31', 2015 => '2015-02-19', 2016 => '2016-02-08', 2017 => '2017-01-28',
        2018 => '2018-02-16', 2019 => '2019-02-05', 2020 => '2020-01-25', 2021 => '2021-02-12',
        2022 => '2022-02-01', 2023 => '2023-01-22', 2024 => '2024-02-10', 2025 => '2025-01-29',
    ];

    /**
     * @param string $birthdate
     * @return string|null
     */
    private static function calculateShioAndElement($birthdate)
    {
        try {
            $date = Carbon::parse($birthdate);
            $year = $date->year;

            // Tentukan tahun shio yang efektif, perhitungkan tanggal Tahun Baru Imlek
            $shioYear = $year;
            $lunarNewYear = self::$lunarNewYearDates[$year] ?? null;

            if ($lunarNewYear && $date->isBefore(Carbon::parse($lunarNewYear))) {
                $shioYear = $year - 1;
            }

            // Urutan shio
            $shio = ['Tikus', 'Kerbau', 'Harimau', 'Kelinci', 'Naga', 'Ular', 'Kuda', 'Kambing', 'Monyet', 'Ayam', 'Anjing', 'Babi'];

            // Urutan elemen
            $elements = ['Logam', 'Logam', 'Air', 'Air', 'Kayu', 'Kayu', 'Api', 'Api', 'Tanah', 'Tanah'];

            $shioIndex = ((($shioYear - 1924) % 12) + 12) % 12;

            // Gunakan $shioYear untuk mendapatkan elemen yang benar
            $elementIndex = $shioYear % 10;

            $shioName = $shio[$shioIndex];
            $elementName = $elements[$elementIndex];

            return "{$shioName} {$elementName}";
        } catch (\Exception $e) {
            return null;
        }
    }
}