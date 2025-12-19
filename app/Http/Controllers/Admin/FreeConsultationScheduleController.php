<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FreeConsultationType;
use App\Models\FreeConsultationSchedule;

class FreeConsultationScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = FreeConsultationSchedule::with('type')
            ->latest()
            ->paginate(10);

        return view('free-consultation-schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = FreeConsultationType::orderBy('name')->get();

        return view('free-consultation-schedules.create', compact('types'));
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
            'type_id' => ['required', 'exists:free_consultation_types,id'],
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'scheduled_time' => ['required', 'date_format:H:i'],
            'max_participants' => ['required', 'integer', 'min:1'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        FreeConsultationSchedule::create(array_merge($validated, [
            'current_bookings' => 0,
            'is_available' => $request->boolean('is_available', true),
        ]));

        return redirect()->route('admin.free-consultation.schedules.index')
            ->with('success', 'Consultation schedule created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FreeConsultationSchedule $schedule) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FreeConsultationSchedule $schedule)
    {
        $types = FreeConsultationType::orderBy('name')->get();

        return view('free-consultation-schedules.edit', compact('schedule', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FreeConsultationSchedule $schedule) 
    {
        $validated = $request->validate([
            'type_id' => ['required', 'exists:free_consultation_types,id'],
            'scheduled_date' => ['required', 'date'],
            'scheduled_time' => ['required', 'date_format:H:i'],
            'max_participants' => ['required', 'integer', 'min:1'],
            'current_bookings' => ['required', 'integer', 'max:'.$request->max_participants],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $is_available = $request->boolean('is_available', true) && ($validated['current_bookings'] < $validated['max_participants']);
        
        $schedule->update(array_merge($validated, [
            'is_available' => $is_available,
        ]));

        return redirect()->route('admin.free-consultation.schedules.index')
            ->with('success', 'Consultation schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FreeConsultationSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.free-consultation.schedules.index')
            ->with('success', 'Consultation schedule deleted successfully.');
    }
}
