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

class AuthController extends Controller
{
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
            $this->transferTempCartData($request, $user);

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isAuthor()) {
                return redirect()->intended(route('author.dashboard'));
            } else {
                return redirect()->intended(route('reader.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Enhanced temp cart data transfer with support for new free consultation system
     */
    private function transferTempCartData(Request $request, User $user)
    {
        $tempCartData = $request->input('temp_cart_data');
        
        if (!$tempCartData) {
            return;
        }

        try {
            $tempCartItems = json_decode($tempCartData, true);
            
            if (!is_array($tempCartItems) || empty($tempCartItems)) {
                return;
            }

            foreach ($tempCartItems as $serviceId => $item) {
                try {
                    // Handle new free consultation system
                    if (isset($item['consultation_type']) && $item['consultation_type'] === 'free-consultation-new') {
                        $typeId = $item['free_consultation_type_id'];
                        $scheduleId = $item['free_consultation_schedule_id'];

                        // Check if user already has this type of consultation in cart
                        $existingCartItem = CartItem::where('user_id', $user->id)
                            ->where('free_consultation_type_id', $typeId)
                            ->first();

                        if (!$existingCartItem) {
                            // Verify schedule still exists and is available
                            $schedule = FreeConsultationSchedule::where('id', $scheduleId)
                                ->where('type_id', $typeId)
                                ->first();

                            if ($schedule && $schedule->isAvailable()) {
                                CartItem::create([
                                    'user_id' => $user->id,
                                    'service_id' => null,
                                    'free_consultation_type_id' => $typeId,
                                    'free_consultation_schedule_id' => $scheduleId,
                                    'price' => 0,
                                    'hourly_price' => 0,
                                    'quantity' => 1,
                                    'hours' => 1,
                                    'booked_date' => $schedule->scheduled_date,
                                    'booked_time' => $schedule->scheduled_time,
                                    'session_type' => $item['session_type'] ?? 'Online',
                                    'offline_address' => $item['offline_address'] ?? null,
                                    'contact_preference' => $item['contact_preference'] ?? 'chat_and_call',
                                    'referral_code' => null,
                                    'payment_type' => 'full_payment'
                                ]);

                                // Reserve the schedule slot
                                $schedule->incrementBooking();

                                Log::info('New free consultation transferred to cart for user: ' . $user->id, [
                                    'type_id' => $typeId,
                                    'schedule_id' => $scheduleId
                                ]);
                            } else {
                                Log::warning('Free consultation schedule no longer available during transfer', [
                                    'user_id' => $user->id,
                                    'schedule_id' => $scheduleId,
                                    'type_id' => $typeId
                                ]);
                            }
                        }
                    }
                    // Handle legacy free consultation
                    elseif ($serviceId === 'free-consultation') {
                        // Check if user already has free consultation
                        $existing = CartItem::where('user_id', $user->id)
                            ->where('service_id', 'free-consultation')
                            ->first();
                        
                        if (!$existing) {
                            CartItem::create([
                                'user_id' => $user->id,
                                'service_id' => 'free-consultation',
                                'price' => 0,
                                'hourly_price' => 0,
                                'quantity' => 1,
                                'hours' => 1,
                                'booked_date' => $item['booked_date'] ?? null,
                                'booked_time' => $item['booked_time'] ?? null,
                                'session_type' => $item['session_type'] ?? 'Online',
                                'offline_address' => $item['offline_address'] ?? null,
                                'contact_preference' => $item['contact_preference'] ?? 'chat_and_call',
                                'referral_code' => null,
                                'payment_type' => 'full_payment'
                            ]);

                            Log::info('Legacy free consultation transferred to cart for user: ' . $user->id);
                        }
                    } 
                    // Handle regular services
                    else {
                        $service = ConsultationService::find($serviceId);
                        
                        if ($service) {
                            CartItem::updateOrCreate(
                                [
                                    'user_id' => $user->id, 
                                    'service_id' => $service->id
                                ],
                                [
                                    'price' => $service->price,
                                    'hourly_price' => $service->hourly_price ?? 0,
                                    'quantity' => 1,
                                    'hours' => (int)($item['hours'] ?? 1),
                                    'booked_date' => $item['booked_date'] ?? null,
                                    'booked_time' => $item['booked_time'] ?? null,
                                    'session_type' => $item['session_type'] ?? 'Online',
                                    'offline_address' => $item['offline_address'] ?? null,
                                    'contact_preference' => $item['contact_preference'] ?? 'chat_and_call',
                                    'referral_code' => $item['referral_code'] ?? null,
                                    'payment_type' => 'full_payment'
                                ]
                            );

                            Log::info('Regular service transferred to cart: ' . $service->title . ' for user: ' . $user->id);
                        }
                    }
                } catch (\Exception $itemException) {
                    Log::error('Failed to transfer individual cart item: ' . $itemException->getMessage(), [
                        'service_id' => $serviceId,
                        'user_id' => $user->id,
                        'item_data' => $item
                    ]);
                }
            }

            Log::info('Temp cart transfer completed for user: ' . $user->id);

        } catch (\Exception $e) {
            Log::error('Failed to move temp cart data to database after login: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'temp_cart_data' => $tempCartData
            ]);
        }
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

        // Transfer temp cart for new users too
        $this->transferTempCartData($request, $user);

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