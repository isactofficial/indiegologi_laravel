<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

use App\Traits\HandlesCartTransfer;

class GoogleController extends Controller
{
    use HandlesCartTransfer;

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function prepareAndRedirect(Request $request)
    {
        if ($request->has('temp_cart_data')) {
            session(['temp_cart_data' => $request->temp_cart_data]);
        }
        
        return $this->redirectToGoogle();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            \Log::info('Google OAuth callback received', [
                'google_email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'google_name' => $googleUser->name
            ]);
            
            // 1. Try to find user by google_id
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // 2. If not found by google_id, try to find by email
                $user = User::where('email', $googleUser->email)->first();
                
                if ($user) {
                    \Log::info('Google Login - Updating existing user with google_id', [
                        'user_id' => $user->id,
                        'email' => $user->email
                    ]);
                    
                    // Update existing user with google_id
                    $user->update([
                        'google_id' => $googleUser->id,
                    ]);
                } else {
                    // 3. Create new user if not found by either
                    \Log::info('Google Login - Creating new user account', [
                        'google_email' => $googleUser->email,
                        'google_id' => $googleUser->id
                    ]);
                    
                    $user = User::create([
                        'name' => $googleUser->name ?? 'Google User',
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => null, // OAuth users don't need password
                        'role' => 'reader'
                    ]);
                    
                    \Log::info('Google Login - New user created successfully', [
                        'user_id' => $user->id,
                        'email' => $user->email
                    ]);
                    
                    // Ensure UserProfile is created
                    $user->profile()->updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'name' => $googleUser->name ?? 'Google User',
                            'email' => $googleUser->email,
                        ]
                    );
                }
            } else {
                \Log::info('Google Login - User found by google_id', [
                    'user_id' => $user->id
                ]);
            }

            Auth::login($user);

            // Transfer temp cart data if stored in session before redirect
            if (session()->has('temp_cart_data')) {
                $this->transferTempCartData(session('temp_cart_data'), $user);
                session()->forget('temp_cart_data');
            }

            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage(), [
                'exception' => $e,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')->with('error', 'Gagal masuk dengan Google. ' . ($e->getMessage() ?? 'Pastikan konfigurasi sudah benar.'));
        }
    }
}
