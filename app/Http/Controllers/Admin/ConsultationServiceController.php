<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rule; // <-- TAMBAHKAN INI

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
        // Ambil semua layanan yang ada untuk opsi add-on
        $existingServices = ConsultationService::orderBy('title')->get(['id', 'title', 'price']);
        return view('consultation-services.create', compact('existingServices'));
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
            'base_duration'       => 'required|integer|min:1', // <-- VALIDASI DURASI
            'status'              => 'required|string|in:draft,published,special',
            'short_description'   => 'nullable|string',
            'product_description' => 'required|string',
            'thumbnail'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Validasi Add-on
            'add_ons'             => 'nullable|array',
            'add_ons.*.type'      => 'required|string|in:custom,existing',
            'add_ons.*.title'     => 'nullable|required_if:add_ons.*.type,custom|string|max:255',
            'add_ons.*.price'     => 'nullable|required_if:add_ons.*.type,custom|numeric|min:0',
            'add_ons.*.service_id' => 'nullable|required_if:add_ons.*.type,existing|integer|exists:consultation_services,id',
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

        $data = $request->only([
            'title', 'price', 'hourly_price', 'base_duration', // <-- AMBIL DURASI
            'status', 'short_description', 'product_description'
        ]);
        $data['thumbnail'] = $thumbnailPath;

        // Persiapkan data add-ons untuk disimpan sebagai JSON
        $addOnsData = [];
        if ($request->has('add_ons')) {
            foreach ($request->add_ons as $addOn) {
                $addOnsData[] = [
                    'type'       => $addOn['type'],
                    'title'      => $addOn['type'] == 'custom' ? $addOn['title'] : null,
                    'price'      => $addOn['type'] == 'custom' ? $addOn['price'] : null,
                    'service_id' => $addOn['type'] == 'existing' ? $addOn['service_id'] : null,
                ];
            }
        }

        $data['add_ons'] = $addOnsData; // Model akan meng-cast ini ke JSON

        ConsultationService::create($data);

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
        // Ambil layanan lain, KECUALI layanan yang sedang diedit
        $existingServices = ConsultationService::where('id', '!=', $consultationService->id)
                                            ->orderBy('title')
                                            ->get(['id', 'title', 'price']);

        return view('consultation-services.edit', compact('consultationService', 'existingServices'));
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
            'base_duration'       => 'required|integer|min:1', // <-- VALIDASI DURASI
            'status'              => 'required|string|in:draft,published,special',
            'short_description'   => 'nullable|string',
            'product_description' => 'required|string',
            'thumbnail'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Validasi Add-on
            'add_ons'             => 'nullable|array',
            'add_ons.*.type'      => 'required|string|in:custom,existing',
            'add_ons.*.title'     => 'nullable|required_if:add_ons.*.type,custom|string|max:255',
            'add_ons.*.price'     => 'nullable|required_if:add_ons.*.type,custom|numeric|min:0',
            'add_ons.*.service_id' => [
                'nullable',
                'required_if:add_ons.*.type,existing',
                'integer',
                'exists:consultation_services,id',
                // Pastikan tidak merujuk ke diri sendiri
                Rule::notIn([$consultationService->id])
            ],
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

        $data = $request->only([
            'title', 'price', 'hourly_price', 'base_duration', // <-- AMBIL DURASI
            'status', 'short_description', 'product_description'
        ]);
        $data['thumbnail'] = $thumbnailPath;

        // Persiapkan data add-ons
        $addOnsData = [];
        if ($request->has('add_ons')) {
            foreach ($request->add_ons as $addOn) {
                $addOnsData[] = [
                    'type'       => $addOn['type'],
                    'title'      => $addOn['type'] == 'custom' ? $addOn['title'] : null,
                    'price'      => $addOn['type'] == 'custom' ? $addOn['price'] : null,
                    'service_id' => $addOn['type'] == 'existing' ? $addOn['service_id'] : null,
                ];
            }
        }
        $data['add_ons'] = $addOnsData; // Model akan meng-cast ini ke JSON

        $consultationService->update($data);

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
