<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FreeConsultationType;

class FreeConsultationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consultationType = FreeConsultationType::latest()->paginate(10);
        return view('free-consultation-services.index', compact('consultationType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('free-consultation-services.create');
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
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'base_price' => 'nullable|numeric|min:0',
            'description' => 'required|string',
        ]);

        FreeConsultationType::create($validated);

        return redirect()->route('admin.free-consultation.types.index')
            ->with('success', 'Consultation type created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FreeConsultationType $type)
    {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit(FreeConsultationType $type)
    {
        return view('free-consultation-services.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FreeConsultationType $type)
    {
          $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'base_price' => 'nullable|numeric|min:0',
            'description' => 'required|string',
        ]);

        $type->update($validated);

        return redirect()->route('admin.free-consultation.types.index')
            ->with('success', 'Consultation type update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FreeConsultationType $type)
    {
        $type->delete();

        return redirect()->route('admin.free-consultation.types.index')
            ->with('success', 'Consultation type deleted successfully.');
    }
}
