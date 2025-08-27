<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
        // Validasi input login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba autentikasi pengguna
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Pindahkan data keranjang sementara dari form login ke database
            $tempCartData = $request->input('temp_cart_data');
            if ($tempCartData) {
                try {
                    $tempCartItems = json_decode($tempCartData, true);

                    if ($tempCartItems) {
                        foreach ($tempCartItems as $serviceId => $item) {
                            $service = ConsultationService::find($serviceId);
                            if ($service) {
                                // Simpan item keranjang ke database
                                CartItem::updateOrCreate(
                                    [
                                        'user_id' => $user->id,
                                        'service_id' => $service->id,
                                    ],
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

            // PENTING: Perbaikan di sini, ganti hasRole() dengan method yang ada
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isAuthor()) {
                return redirect()->intended(route('author.dashboard'));
            } else { // Asumsikan role default adalah 'reader'
                return redirect()->intended(route('reader.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // PENTING: Set role default untuk pengguna baru
        $user->role = 'reader'; // Atau role default lain yang Anda tetapkan
        $user->save();

        Auth::login($user);

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
            // PENTING: Perbaikan di sini, ganti hasRole() dengan method yang ada
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
