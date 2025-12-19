<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Models\FreeConsultationBooking; 
use App\Models\FreeConsultationParticipant;
use App\Models\FreeConsultationSchedule; 

class FreeConsultationBookController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan form pendaftaran.
     */
    public function showForm()
    {
        return view("front.services.free-consultation-book");
    }

    /**
     * Memproses data form dan menyimpan booking.
     */
    public function confirmBooking(Request $request)
    {
        // 1. Aturan Validasi
        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'email_pemesan' => 'required|email|max:255',
            'session_type' => 'required|in:Online,Offline',
            'offline_address' => 'nullable|required_if:session_type,Offline|string|max:500', 
            'contact_preference' => 'required|in:chat_and_call,chat_only',
            'free_consultation_type_id' => 'required|integer|exists:free_consultation_types,id',
            'free_consultation_schedule_id' => 'required|integer|exists:free_consultation_schedules,id',
            'booked_date' => 'required|date',
            'booked_time' => 'required',
            'participant_count' => 'required|integer|min:1|max:5',
            'participants' => 'nullable|array|max:4', // Maks 4 peserta tambahan (total 5)
            'participants.*.full_name' => 'required_if:participant_count,>1|string|max:255',
            'participants.*.phone_number' => 'required_if:participant_count,>1|string|max:20',
            'participants.*.email' => 'nullable|email|max:255',
        ];
        
        $messages = [
            'offline_address.required_if' => 'Alamat wajib diisi untuk sesi Offline.',
            'participants.*.full_name.required_if' => 'Nama lengkap peserta wajib diisi jika peserta lebih dari satu.',
            'participants.*.phone_number.required_if' => 'Nomor telepon peserta wajib diisi jika peserta lebih dari satu.',
            'participant_count.max' => 'Jumlah peserta maksimal adalah 5 orang.',
            'booked_date.date' => 'Format tanggal tidak valid.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            Log::warning('Free Consultation Booking validation failed', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Harap periksa kembali isian form Anda.',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();
            $userId = Auth::id(); 

            // Penguraian Tanggal dan Waktu
            $cleanedDateInput = preg_replace('/\s+/', ' ', trim($data['booked_date']));
            $cleanedTime = preg_replace('/\s+/', ' ', trim($data['booked_time']));

            $dateOnly = Carbon::parse($cleanedDateInput)->toDateString();
            $dateTimeString = $dateOnly . ' ' . $cleanedTime;
            $scheduledDateTime = Carbon::parse($dateTimeString);
            
            if (!$scheduledDateTime) {
                 throw new \Exception("Gagal mengurai string tanggal/waktu dari data form.");
            }

            $totalParticipants = $data['participant_count'];
            $scheduleId = $data['free_consultation_schedule_id']; 

            // 2. Cek ketersediaan slot dalam transaksi (MENGGUNAKAN LOCK)
            $schedule = FreeConsultationSchedule::where('id', $scheduleId)->lockForUpdate()->first();

            if (!$schedule) {
                // Seharusnya tidak terjadi karena sudah divalidasi dengan exists:
                throw new \Exception("Jadwal konsultasi tidak ditemukan.");
            }

            // Perhitungan slot yang tersisa
            $remainingSlots = $schedule->max_participants - $schedule->current_bookings;
            if ($remainingSlots < $totalParticipants) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Slot jadwal tidak mencukupi untuk jumlah peserta yang diminta. Sisa slot: ' . $remainingSlots,
                ], 422);
            }


            // 3. Simpan Booking Utama ke FreeConsultationBooking
            $booking = FreeConsultationBooking::create([
                'user_id' => $userId, 
                'service_type' => 'free_consultation',
                'service_id' => $data['free_consultation_type_id'],
                'schedule_id' => $scheduleId,
                'scheduled_at' => $scheduledDateTime,
                'booker_name' => $data['nama_lengkap'],
                'booker_phone' => $data['nomor_telepon'],
                'booker_email' => $data['email_pemesan'],
                'participant_count' => $totalParticipants,
                'session_type' => $data['session_type'],
                'offline_address' => $data['offline_address'] ?? null, 
                'contact_preference' => $data['contact_preference'],
                
                'total_price' => 0, 
                'booking_status' => 'Confirmed',
            ]);
            
            // 4. Simpan Peserta Pertama (Pemesan)
            FreeConsultationParticipant::create([
                'consultation_booking_id' => $booking->id,
                'full_name' => $data['nama_lengkap'],
                'phone_number' => $data['nomor_telepon'],
                'email' => $data['email_pemesan'],
                'is_booker' => true,
            ]);

            // 5. Simpan Peserta Tambahan (Jika ada)
            if ($totalParticipants > 1) {
                $participantsData = $data['participants'] ?? [];
                // Peserta tambahan dimulai dari index 1 hingga $totalParticipants - 1
                // Pastikan untuk mengabaikan index 0 karena itu data pemesan
                for ($i = 1; $i < $totalParticipants; $i++) {
                    // $participantsData harusnya array numerik, tapi karena form menggunakan participants[i]
                    // kita asumsikan index $i sudah ada atau menggunakan index dari request jika validasi lolos
                    $participantData = $participantsData[$i] ?? null;

                    if ($participantData) {
                        FreeConsultationParticipant::create([ 
                            'consultation_booking_id' => $booking->id,
                            'full_name' => $participantData['full_name'],
                            'phone_number' => $participantData['phone_number'],
                            'email' => $participantData['email'] ?? null,
                            'is_booker' => false,
                        ]);
                    }
                }
            }

            // 6. Update Slot Jadwal
            $schedule->current_bookings += $totalParticipants;
            $schedule->save();
            
            Log::info('Booking Konsultasi Gratis Sukses Disimpan', [
                'booking_id' => $booking->id,
                'user_id' => $userId, 
                'total_participants' => $totalParticipants,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking Konsultasi Gratis berhasil dikonfirmasi untuk ' . $totalParticipants . ' peserta! Kami akan menghubungi Anda segera.',
                'booking_id' => $booking->id
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal konfirmasi booking gratis dan roll back DB: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal konfirmasi booking. Silakan coba lagi. Detail: ' . $e->getMessage(), 
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }
}