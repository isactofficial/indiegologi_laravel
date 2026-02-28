<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\EmailVerification;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class EmailVerificationController extends Controller
{
    /**
     * Send OTP to email for registration verification
     * For testing: OTP is returned in response
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if email is already registered
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terdaftar. Silakan login atau gunakan email lain.',
            ], 422);
        }

        // Generate OTP
        $otp = EmailVerification::generateOtp($request->email);

        // Try to send email, but don't fail if email service is not configured
        try {
            Mail::to($request->email)->send(new OtpVerificationMail($otp));
        } catch (\Exception $e) {
            // Log error for debugging
            \Illuminate\Support\Facades\Log::error('Failed to send OTP email: ' . $e->getMessage());
        }

        // Store email in session for verification step
        session(['verification_email' => $request->email]);

        // Return success - include OTP for testing purposes
        return response()->json([
            'success' => true,
            'message' => 'Kode OTP telah dikirim ke email Anda. Berlaku 60 detik.',
            'expires_in' => 60,
            'otp' => $otp, // For testing only - remove in production!
        ]);
    }

    /**
     * Verify OTP and create user account
     */
    public function verifyAndRegister(Request $request)
    {
        // If this is just OTP verification (step 2), only validate OTP
        if ($request->filled('otp') && $request->otp !== 'verified') {
            $email = session('verification_email');

            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi verifikasi telah berakhir. Silakan mulai ulang proses registrasi.',
                ], 422);
            }

            // Verify OTP
            if (!EmailVerification::verify($email, $request->otp)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP tidak valid atau sudah expired. Silakan minta kode baru.',
                ], 422);
            }

            // Mark as verified in session
            session(['otp_verified' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil diverifikasi!',
            ]);
        }

        // Full registration (step 3)
        $email = session('verification_email');
        $otpVerified = session('otp_verified');

        if (!$email || !$otpVerified) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan verifikasi email terlebih dahulu.',
            ], 422);
        }

        // Validate registration data
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'phone_number' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'role' => 'reader',
        ]);

        // Create user profile
        UserProfile::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $email,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
        ]);

        // Clear session
        session()->forget('verification_email');
        session()->forget('otp_verified');

        // Login the user
        auth()->login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Mengalihkan...',
            'redirect' => route('onboarding.start'),
        ]);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $email = session('verification_email');

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi berakhir. Silakan mulai ulang proses registrasi.',
            ], 422);
        }

        // Check if email is still available
        if (User::where('email', $email)->exists()) {
            session()->forget('verification_email');
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terdaftar. Silakan login atau gunakan email lain.',
            ], 422);
        }

        // Generate new OTP
        $otp = EmailVerification::generateOtp($email);

        try {
            Mail::to($email)->send(new OtpVerificationMail($otp));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to resend OTP: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP baru telah dikirim. Berlaku 60 detik.',
            'expires_in' => 60,
        ]);
    }
}
