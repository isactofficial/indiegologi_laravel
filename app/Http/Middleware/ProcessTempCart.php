<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\ConsultationService;
use Illuminate\Support\Facades\Log;

class ProcessTempCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Jalankan middleware ini hanya jika pengguna sudah login
        if (Auth::check()) {
            // Ambil data keranjang sementara dari cookie atau session
            // Asumsikan data disimpan di cookie dengan nama 'temp_cart'
            $tempCartData = $request->cookie('temp_cart');

            if ($tempCartData) {
                // Konversi data JSON menjadi array
                $tempCartItems = json_decode($tempCartData, true);

                if (!empty($tempCartItems)) {
                    $userId = Auth::id();

                    foreach ($tempCartItems as $serviceId => $item) {
                        try {
                            $service = ConsultationService::find($serviceId);
                            if ($service) {
                                // Pindahkan data dari keranjang sementara ke database
                                CartItem::updateOrCreate(
                                    [
                                        'user_id' => $userId,
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
                        } catch (\Exception $e) {
                            Log::error("Failed to move temp cart item for user {$userId}: " . $e->getMessage());
                            // Lanjutkan ke item berikutnya meskipun ada yang gagal
                        }
                    }

                    // Hapus data keranjang sementara dari cookie setelah berhasil
                    return $next($request)->withCookie(cookie()->forget('temp_cart'));
                }
            }
        }

        return $next($request);
    }
}
