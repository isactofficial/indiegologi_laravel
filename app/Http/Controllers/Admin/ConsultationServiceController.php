<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ConsultationServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index()
    {
        $services = ConsultationService::orderBy('title', 'asc')->paginate(10);
        return view('consultation-services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        return view('consultation-services.create');
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'               => 'required|string|max:255',
            'price'               => 'required|numeric|min:0',
            'hourly_price'        => 'nullable|numeric|min:0',
            'status'              => 'required|string|in:draft,published,special', // Tambahkan validasi ini
            'short_description'   => 'nullable|string',
            'product_description' => 'required|string',
            'thumbnail'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $resizedImage = Image::make($image)
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode($image->getClientOriginalExtension(), 75);

            Storage::disk('public')->put('service-thumbnails/' . $imageName, $resizedImage);
            $thumbnailPath = 'service-thumbnails/' . $imageName;
        }

        ConsultationService::create([
            'title'               => $request->title,
            'price'               => $request->price,
            'hourly_price'        => $request->hourly_price,
            'status'              => $request->status, // Simpan status
            'short_description'   => $request->short_description,
            'product_description' => $request->product_description,
            'thumbnail'           => $thumbnailPath,
        ]);

        return redirect()->route('admin.consultation-services.index')->with('success', 'Layanan konsultasi berhasil ditambahkan!');
    }

    /**
     * Display the specified service.
     */
    public function show(ConsultationService $consultationService)
    {
        return view('consultation-services.show', compact('consultationService'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(ConsultationService $consultationService)
    {
        return view('consultation-services.edit', compact('consultationService'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, ConsultationService $consultationService)
    {
        $request->validate([
            'title'               => 'required|string|max:255',
            'price'               => 'required|numeric|min:0',
            'hourly_price'        => 'nullable|numeric|min:0',
            'status'              => 'required|string|in:draft,published,special', // Tambahkan validasi ini
            'short_description'   => 'nullable|string',
            'product_description' => 'required|string',
            'thumbnail'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $thumbnailPath = $consultationService->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            $image = $request->file('thumbnail');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $resizedImage = Image::make($image)
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode($image->getClientOriginalExtension(), 75);

            Storage::disk('public')->put('service-thumbnails/' . $imageName, $resizedImage);
            $thumbnailPath = 'service-thumbnails/' . $imageName;
        }

        $consultationService->update([
            'title'               => $request->title,
            'price'               => $request->price,
            'hourly_price'        => $request->hourly_price,
            'status'              => $request->status, // Update status
            'short_description'   => $request->short_description,
            'product_description' => $request->product_description,
            'thumbnail'           => $thumbnailPath,
        ]);

        return redirect()->route('admin.consultation-services.index')->with('success', 'Layanan konsultasi berhasil diperbarui!');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(ConsultationService $consultationService)
    {
        if ($consultationService->thumbnail && Storage::disk('public')->exists($consultationService->thumbnail)) {
            Storage::disk('public')->delete($consultationService->thumbnail);
        }

        $consultationService->delete();
        return redirect()->route('admin.consultation-services.index')->with('success', 'Layanan konsultasi berhasil dihapus!');
    }
}
