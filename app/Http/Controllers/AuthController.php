<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\CartItem;
use App\Models\ConsultationService;
use App\Models\FreeConsultationType;
use App\Models\FreeConsultationSchedule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use App\Traits\HandlesCartTransfer;

class AuthController extends Controller
{
    use HandlesCartTransfer;
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Transfer temp cart data after successful login
            $this->transferTempCartDataFromRequest($request, $user);

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isAuthor()) {
                return redirect()->intended(route('author.dashboard'));
            } else {
                // return redirect()->intended(route('reader.dashboard'));
                return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Enhanced temp cart data transfer with support for new free consultation system
     */
    private function transferTempCartDataFromRequest(Request $request, User $user)
    {
        $this->transferTempCartData($request->input('temp_cart_data'), $user);
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
            'phone_number' => 'required|numeric|digits_between:8,15',
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

        // Transfer temp cart for new users too
        $this->transferTempCartDataFromRequest($request, $user);

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
                // return redirect()->route('reader.dashboard');
                return redirect('/');
            }
        }
        return redirect('/');
    }
}