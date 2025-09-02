<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Pastikan model User di-import

class OnboardingController extends Controller
{
    /**
     * Menampilkan halaman kuisioner onboarding.
     */
    public function show()
    {
        if (Auth::check() && Auth::user()->onboarding_completed_at) {
            return redirect()->route('redirect.dashboard');
        }
        return view('auth.onboarding');
    }

    /**
     * Menyimpan jawaban kuisioner dan mengupdate status user.
     */
    public function store(Request $request)
    {
        $answers = $request->except('_token');
        $user = Auth::user();

        try {
            DB::beginTransaction();

            foreach ($answers as $key => $value) {
                DB::table('user_questionnaire_answers')->insert([
                    'user_id' => $user->id,
                    'question_key' => $key,
                    'answer_value' => is_array($value) ? json_encode($value) : $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Update user di database
            $user->onboarding_completed_at = now();
            $user->save();

            DB::commit();

            // ---- [PERBAIKAN FINAL & PALING AMPUH] ----
            // 1. Logout pengguna saat ini untuk menghancurkan sesi lama yang 'basi'.
            Auth::logout();

            // 2. Hapus data sesi yang lama dan buat token baru untuk keamanan.
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // 3. Login kembali pengguna menggunakan ID-nya. Ini akan membuat sesi baru
            //    dengan data yang 100% fresh dari database.
            //    Parameter kedua (true) adalah untuk fitur "remember me".
            if (Auth::loginUsingId($user->id, true)) {
                // `intended()` akan mengarahkan user ke halaman yang ingin mereka tuju
                // sebelum dialihkan oleh middleware (yaitu, dashboard).
                return redirect()->intended(route('redirect.dashboard'))
                                 ->with('success', 'Selamat datang di Indiegologi!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            // Jika ada error, catat di log untuk debugging
            \Illuminate\Support\Facades\Log::error('Onboarding store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data Anda.');
        }
        
        // Fallback jika karena suatu alasan login gagal
        return redirect()->route('login')->with('error', 'Terjadi sedikit masalah, silakan login kembali.');
    }
}