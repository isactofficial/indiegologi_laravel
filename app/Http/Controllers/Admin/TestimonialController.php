<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $testimonials = Testimonial::ordered()->paginate(10);
            return view('testimonials.index', compact('testimonials'));
        } catch (Exception $e) {
            Log::error('Error fetching testimonials: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data testimoni.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Log request untuk debugging
        Log::info('Store Testimonial Request', [
            'data' => $request->except('image'),
            'has_image' => $request->hasFile('image')
        ]);

        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:150',
            'occupation' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'quote' => 'required|string|min:10|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'age.required' => 'Usia wajib diisi.',
            'age.min' => 'Usia minimal 1 tahun.',
            'age.max' => 'Usia maksimal 150 tahun.',
            'occupation.required' => 'Pekerjaan wajib diisi.',
            'location.required' => 'Kota / Negara wajib diisi.',
            'quote.required' => 'Testimoni wajib diisi.',
            'quote.min' => 'Testimoni minimal 10 karakter.',
            'quote.max' => 'Testimoni maksimal 1000 karakter.',
            'image.required' => 'Foto testimoni wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus: jpeg, png, jpg, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        DB::beginTransaction();
        
        try {
            // 2. Proses Upload Gambar
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = Str::slug($validated['name']) . '-' . time() . '.' . $image->extension();
                $imagePath = $image->storeAs('testimonials', $imageName, 'public');
                
                Log::info('Image uploaded successfully', ['path' => $imagePath]);
            }

            // 3. Menyiapkan Data untuk Disimpan
            $dataToStore = [
                'name' => $validated['name'],
                'age' => $validated['age'],
                'occupation' => $validated['occupation'],
                'location' => $validated['location'] ?? null,
                'quote' => $validated['quote'],
                'image' => $imagePath,
                'is_active' => $request->has('is_active') ? true : false,
            ];
            
            // 4. Simpan ke Database
            $testimonial = Testimonial::create($dataToStore);
            
            DB::commit();
            
            Log::info('Testimonial created successfully', ['id' => $testimonial->id]);

            // 5. Redirect dengan Pesan Sukses
            return redirect()
                ->route('admin.testimonials.index')
                ->with('success', 'Testimoni berhasil ditambahkan.');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            // Hapus gambar jika ada error
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            Log::error('Error creating testimonial: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan testimoni: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function show(Testimonial $testimonial)
    {
        try {
            return view('testimonials.show', compact('testimonial'));
        } catch (Exception $e) {
            Log::error('Error showing testimonial: ' . $e->getMessage());
            return redirect()
                ->route('admin.testimonials.index')
                ->with('error', 'Testimoni tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function edit(Testimonial $testimonial)
    {
        try {
            return view('testimonials.edit', compact('testimonial'));
        } catch (Exception $e) {
            Log::error('Error editing testimonial: ' . $e->getMessage());
            return redirect()
                ->route('admin.testimonials.index')
                ->with('error', 'Testimoni tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        // Log request untuk debugging
        Log::info('Update Testimonial Request', [
            'id' => $testimonial->id,
            'data' => $request->except('image'),
            'has_image' => $request->hasFile('image')
        ]);

        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:150',
            'occupation' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'quote' => 'required|string|min:10|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'age.required' => 'Usia wajib diisi.',
            'age.min' => 'Usia minimal 1 tahun.',
            'age.max' => 'Usia maksimal 150 tahun.',
            'occupation.required' => 'Pekerjaan wajib diisi.',
            'location.required' => 'Kota / Negara wajib diisi.',
            'quote.required' => 'Testimoni wajib diisi.',
            'quote.min' => 'Testimoni minimal 10 karakter.',
            'quote.max' => 'Testimoni maksimal 1000 karakter.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus: jpeg, png, jpg, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        DB::beginTransaction();
        
        try {
            // 2. Menyiapkan data yang akan di-update
            $dataToUpdate = [
                'name' => $validated['name'],
                'age' => $validated['age'],
                'occupation' => $validated['occupation'],
                'location' => $validated['location'] ?? null,
                'quote' => $validated['quote'],
                'is_active' => $request->has('is_active') ? true : false,
            ];

            // 3. Proses Update Gambar (jika ada gambar baru)
            $oldImagePath = $testimonial->image;
            
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = Str::slug($validated['name']) . '-' . time() . '.' . $image->extension();
                $newImagePath = $image->storeAs('testimonials', $imageName, 'public');
                
                if ($newImagePath) {
                    $dataToUpdate['image'] = $newImagePath;
                    Log::info('New image uploaded', ['path' => $newImagePath]);
                }
            }

            // 4. Update Data di Database
            $testimonial->update($dataToUpdate);
            
            // 5. Hapus gambar lama jika ada gambar baru yang berhasil diupload
            if (isset($newImagePath) && $oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
                Log::info('Old image deleted', ['path' => $oldImagePath]);
            }
            
            DB::commit();
            
            Log::info('Testimonial updated successfully', ['id' => $testimonial->id]);

            // 6. Redirect dengan Pesan Sukses
            return redirect()
                ->route('admin.testimonials.index')
                ->with('success', 'Testimoni berhasil diperbarui.');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            // Hapus gambar baru jika ada error
            if (isset($newImagePath) && Storage::disk('public')->exists($newImagePath)) {
                Storage::disk('public')->delete($newImagePath);
            }
            
            Log::error('Error updating testimonial: ' . $e->getMessage(), [
                'id' => $testimonial->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui testimoni: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status active/inactive testimonial
     * Method ini dipanggil dari tombol toggle di halaman index
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Testimonial $testimonial)
    {
        try {
            $newStatus = !$testimonial->is_active;
            
            $testimonial->update([
                'is_active' => $newStatus
            ]);
            
            $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
            
            Log::info('Testimonial status toggled', [
                'id' => $testimonial->id,
                'old_status' => !$newStatus,
                'new_status' => $newStatus
            ]);
            
            return redirect()
                ->route('admin.testimonials.index')
                ->with('success', "Testimoni berhasil {$statusText}.");
                
        } catch (Exception $e) {
            Log::error('Error toggling testimonial status: ' . $e->getMessage(), [
                'id' => $testimonial->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Gagal mengubah status testimoni.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimonial $testimonial)
    {
        DB::beginTransaction();
        
        try {
            $imagePath = $testimonial->image;
            $testimonialId = $testimonial->id;
            $testimonialName = $testimonial->name;
            
            // Hapus data dari database
            $testimonial->delete();
            
            // Hapus gambar dari storage setelah data terhapus
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
                Log::info('Testimonial image deleted', ['path' => $imagePath]);
            }
            
            DB::commit();
            
            Log::info('Testimonial deleted successfully', [
                'id' => $testimonialId,
                'name' => $testimonialName
            ]);

            return redirect()
                ->route('admin.testimonials.index')
                ->with('success', 'Testimoni berhasil dihapus.');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting testimonial: ' . $e->getMessage(), [
                'id' => $testimonial->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus testimoni: ' . $e->getMessage());
        }
    }
}