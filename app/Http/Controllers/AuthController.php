<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\CartItem;
use App\Models\ConsultationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // ... (Fungsi login Anda tidak perlu diubah)
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            $tempCartData = $request->input('temp_cart_data');
            if ($tempCartData) {
                try {
                    $tempCartItems = json_decode($tempCartData, true);
                    if ($tempCartItems) {
                        foreach ($tempCartItems as $serviceId => $item) {
                            $service = ConsultationService::find($serviceId);
                            if ($service) {
                                CartItem::updateOrCreate(
                                    ['user_id' => $user->id, 'service_id' => $service->id],
                                    [
                                        'price' => $service->price,
                                        'hourly_price' => $service->hourly_price,
                                        'quantity' => 1,
                                        'hours' => (int)($item['hours'] ?? 0),
                                        'booked_date' => $item['booked_date'] ?? null,
                                        'booked_time' => $item['booked_time'] ?? null,
                                        'session_type' => $item['session_type'] ?? 'Online',
                                        'offline_address' => $item['offline_address'] ?? null,
                                        'contact_preference' => $item['contact_preference'] ?? 'chat_and_call',
                                        'referral_code' => $item['referral_code'] ?? null,
                                    ]
                                );
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to move temp cart data to database after login: ' . $e->getMessage());
                }
            }

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isAuthor()) {
                return redirect()->intended(route('author.dashboard'));
            } else {
                return redirect()->intended(route('reader.dashboard'));
            }
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.',])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'birthdate' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'phone_number' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'reader',
        ]);

        if ($user) {
            // [DIUBAH] Menggunakan updateOrCreate untuk mencegah error duplikasi
            UserProfile::updateOrCreate(
                ['user_id' => $user->id], // Cari profil dengan user_id ini...
                [                          // ...lalu update dengan data ini (atau buat baru jika tidak ditemukan)
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'birthdate' => $validatedData['birthdate'],
                    'gender' => $validatedData['gender'],
                    'phone_number' => $validatedData['phone_number'],
                ]
            );
        }

        Auth::login($user);

        if ($user->onboarding_completed_at === null) {
            return redirect()->route('onboarding.start');
        }

        return $this->redirectDashboard();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function redirectDashboard()
    {
        $user = Auth::user();
        if ($user) {
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isAuthor()) {
                return redirect()->route('author.dashboard');
            } elseif ($user->isReader()) {
                return redirect()->route('reader.dashboard');
            }
        }
        return redirect('/');
    }
}