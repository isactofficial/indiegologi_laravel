<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreeConsultationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class FreeConsultationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $types = FreeConsultationType::with('schedules')->paginate(10);
            return view('admin.free-consultation.types.index', compact('types'));
        } catch (Exception $e) {
            Log::error('Error fetching free consultation types: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data jenis konsultasi gratis.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.free-consultation.types.create');
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
            'description' => 'required|string|min:10',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Nama jenis konsultasi wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        DB::beginTransaction();
        
        try {
            FreeConsultationType::create($validated);
            
            DB::commit();
            
            Log::info('Free consultation type created successfully', ['name' => $validated['name']]);

            return redirect()
                ->route('admin.free-consultation.types.index')
                ->with('success', 'Jenis konsultasi gratis berhasil ditambahkan.');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error creating free consultation type: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan jenis konsultasi gratis: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FreeConsultationType  $type
     * @return \Illuminate\Http\Response
     */
    public function show(FreeConsultationType $type)
    {
        try {
            $type->load('schedules');
            return view('admin.free-consultation.types.show', compact('type'));
        } catch (Exception $e) {
            Log::error('Error showing free consultation type: ' . $e->getMessage());
            return redirect()
                ->route('admin.free-consultation.types.index')
                ->with('error', 'Jenis konsultasi gratis tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FreeConsultationType  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(FreeConsultationType $type)
    {
        try {
            return view('admin.free-consultation.types.edit', compact('type'));
        } catch (Exception $e) {
            Log::error('Error editing free consultation type: ' . $e->getMessage());
            return redirect()
                ->route('admin.free-consultation.types.index')
                ->with('error', 'Jenis konsultasi gratis tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FreeConsultationType  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FreeConsultationType $type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Nama jenis konsultasi wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        DB::beginTransaction();
        
        try {
            $type->update($validated);
            
            DB::commit();
            
            Log::info('Free consultation type updated successfully', ['id' => $type->id]);

            return redirect()
                ->route('admin.free-consultation.types.index')
                ->with('success', 'Jenis konsultasi gratis berhasil diperbarui.');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating free consultation type: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui jenis konsultasi gratis: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FreeConsultationType  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(FreeConsultationType $type)
    {
        DB::beginTransaction();
        
        try {
            $typeId = $type->id;
            $typeName = $type->name;
            
            // Delete related schedules first
            $type->schedules()->delete();
            
            // Delete the type
            $type->delete();
            
            DB::commit();
            
            Log::info('Free consultation type deleted successfully', ['id' => $typeId, 'name' => $typeName]);

            return redirect()
                ->route('admin.free-consultation.types.index')
                ->with('success', 'Jenis konsultasi gratis berhasil dihapus.');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting free consultation type: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus jenis konsultasi gratis: ' . $e->getMessage());
        }
    }
}

