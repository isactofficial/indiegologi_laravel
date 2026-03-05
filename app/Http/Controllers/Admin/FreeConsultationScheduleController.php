<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreeConsultationType;
use App\Models\FreeConsultationSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class FreeConsultationScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $schedules = FreeConsultationSchedule::with('type')
                ->orderBy('scheduled_date', 'asc')
                ->orderBy('scheduled_time', 'asc')
                ->paginate(20);
            
            $types = FreeConsultationType::where('status', 'active')->get();
            
            return view('admin.free-consultation.schedules.index', compact('schedules', 'types'));
        } catch (Exception $e) {
            Log::error('Error fetching free consultation schedules: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data jadwal konsultasi gratis.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $types = FreeConsultationType::where('status', 'active')->get();
            return view('admin.free-consultation.schedules.create', compact('types'));
        } catch (Exception $e) {
            Log::error('Error showing create schedule form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat form tambah jadwal.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_id' => 'required|exists:free_consultation_types,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required',
            'max_participants' => 'required|integer|min:1|max:100',
            'is_available' => 'boolean',
        ], [
            'type_id.required' => 'Jenis konsultasi wajib dipilih.',
            'type_id.exists' => 'Jenis konsultasi tidak valid.',
            'scheduled_date.required' => 'Tanggal wajib diisi.',
            'scheduled_date.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini.',
            'scheduled_time.required' => 'Waktu wajib diisi.',
            'max_participants.required' => 'Maksimal partisipan wajib diisi.',
            'max_participants.min' => 'Maksimal partisipan minimal 1.',
            'max_participants.max' => 'Maksimal partisipan maksimal 100.',
        ]);

        DB::beginTransaction();
        
        try {
            FreeConsultationSchedule::create([
                'type_id' => $validated['type_id'],
                'scheduled_date' => $validated['scheduled_date'],
                'scheduled_time' => $validated['scheduled_time'],
                'max_participants' => $validated['max_participants'],
                'is_available' => $request->has('is_available') ? true : false,
                'current_bookings' => 0,
            ]);
            
            DB::commit();
            
            Log::info('Free consultation schedule created successfully', [
                'type_id' => $validated['type_id'],
                'date' => $validated['scheduled_date']
            ]);

            return redirect()
                ->route('admin.free-consultation.schedules.index')
                ->with('success', 'Jadwal konsultasi gratis berhasil ditambahkan.');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error creating free consultation schedule: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan jadwal konsultasi gratis: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FreeConsultationSchedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(FreeConsultationSchedule $schedule)
    {
        try {
            $schedule->load('type', 'cartItems', 'bookingServices');
            return view('admin.free-consultation.schedules.show', compact('schedule'));
        } catch (Exception $e) {
            Log::error('Error showing free consultation schedule: ' . $e->getMessage());
            return redirect()
                ->route('admin.free-consultation.schedules.index')
                ->with('error', 'Jadwal konsultasi gratis tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FreeConsultationSchedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(FreeConsultationSchedule $schedule)
    {
        try {
            $types = FreeConsultationType::where('status', 'active')->get();
            return view('admin.free-consultation.schedules.edit', compact('schedule', 'types'));
        } catch (Exception $e) {
            Log::error('Error editing free consultation schedule: ' . $e->getMessage());
            return redirect()
                ->route('admin.free-consultation.schedules.index')
                ->with('error', 'Jadwal konsultasi gratis tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FreeConsultationSchedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FreeConsultationSchedule $schedule)
    {
        $validated = $request->validate([
            'type_id' => 'required|exists:free_consultation_types,id',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'max_participants' => 'required|integer|min:1|max:100',
            'is_available' => 'boolean',
        ], [
            'type_id.required' => 'Jenis konsultasi wajib dipilih.',
            'type_id.exists' => 'Jenis konsultasi tidak valid.',
            'scheduled_date.required' => 'Tanggal wajib diisi.',
            'scheduled_time.required' => 'Waktu wajib diisi.',
            'max_participants.required' => 'Maksimal partisipan wajib diisi.',
            'max_participants.min' => 'Maksimal partisipan minimal 1.',
            'max_participants.max' => 'Maksimal partisipan maksimal 100.',
        ]);

        DB::beginTransaction();
        
        try {
            $schedule->update([
                'type_id' => $validated['type_id'],
                'scheduled_date' => $validated['scheduled_date'],
                'scheduled_time' => $validated['scheduled_time'],
                'max_participants' => $validated['max_participants'],
                'is_available' => $request->has('is_available') ? true : false,
            ]);
            
            DB::commit();
            
            Log::info('Free consultation schedule updated successfully', ['id' => $schedule->id]);

            return redirect()
                ->route('admin.free-consultation.schedules.index')
                ->with('success', 'Jadwal konsultasi gratis berhasil diperbarui.');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating free consultation schedule: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui jadwal konsultasi gratis: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FreeConsultationSchedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(FreeConsultationSchedule $schedule)
    {
        DB::beginTransaction();
        
        try {
            $scheduleId = $schedule->id;
            
            // Check if schedule has bookings
            if ($schedule->current_bookings > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Jadwal tidak dapat dihapus karena sudah memiliki bookings.');
            }
            
            $schedule->delete();
            
            DB::commit();
            
            Log::info('Free consultation schedule deleted successfully', ['id' => $scheduleId]);

            return redirect()
                ->route('admin.free-consultation.schedules.index')
                ->with('success', 'Jadwal konsultasi gratis berhasil dihapus.');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting free consultation schedule: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus jadwal konsultasi gratis: ' . $e->getMessage());
        }
    }

    /**
     * Bulk create schedules for a type
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkCreate(Request $request)
    {
        $validated = $request->validate([
            'type_id' => 'required|exists:free_consultation_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'number_of_schedules' => 'required|integer|min:1|max:30',
            'time' => 'required',
            'max_participants' => 'required|integer|min:1|max:100',
        ], [
            'type_id.required' => 'Jenis konsultasi wajib dipilih.',
            'type_id.exists' => 'Jenis konsultasi tidak valid.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini.',
            'number_of_schedules.required' => 'Jumlah jadwal wajib diisi.',
            'number_of_schedules.min' => 'Minimal 1 jadwal.',
            'number_of_schedules.max' => 'Maksimal 30 jadwal.',
            'time.required' => 'Waktu wajib diisi.',
            'max_participants.required' => 'Maksimal partisipan wajib diisi.',
        ]);

        DB::beginTransaction();
        
        try {
            $startDate = Carbon::parse($validated['start_date']);
            $count = 0;
            
            for ($i = 0; $i < $validated['number_of_schedules']; $i++) {
                $scheduleDate = $startDate->copy()->addDays($i);
                
                FreeConsultationSchedule::create([
                    'type_id' => $validated['type_id'],
                    'scheduled_date' => $scheduleDate->format('Y-m-d'),
                    'scheduled_time' => $validated['time'],
                    'max_participants' => $validated['max_participants'],
                    'is_available' => true,
                    'current_bookings' => 0,
                ]);
                
                $count++;
            }
            
            DB::commit();
            
            Log::info('Bulk free consultation schedules created', [
                'type_id' => $validated['type_id'],
                'count' => $count
            ]);

            return redirect()
                ->route('admin.free-consultation.schedules.index')
                ->with('success', "{$count} jadwal konsultasi gratis berhasil dibuat.");
                
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error bulk creating free consultation schedules: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Gagal membuat jadwal massal: ' . $e->getMessage());
        }
    }

    /**
     * Toggle schedule availability
     *
     * @param  \App\Models\FreeConsultationSchedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function toggleAvailability(FreeConsultationSchedule $schedule)
    {
        try {
            $newStatus = !$schedule->is_available;
            
            // Check if trying to make available but fully booked
            if ($newStatus && $schedule->current_bookings >= $schedule->max_participants) {
                return redirect()
                    ->back()
                    ->with('error', 'Jadwal tidak dapat diaktifkan karena sudah penuh.');
            }
            
            $schedule->update(['is_available' => $newStatus]);
            
            $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
            
            Log::info('Free consultation schedule availability toggled', [
                'id' => $schedule->id,
                'new_status' => $newStatus
            ]);

            return redirect()
                ->back()
                ->with('success', "Jadwal berhasil {$statusText}.");
                
        } catch (Exception $e) {
            Log::error('Error toggling schedule availability: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Gagal mengubah status ketersediaan jadwal.');
        }
    }
}

