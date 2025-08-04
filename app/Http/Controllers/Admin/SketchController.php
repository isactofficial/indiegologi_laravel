<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sketch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

class SketchController extends Controller
{
    /**
     * Display a listing of the sketches.
     */
    public function index()
    {
        $sketches = Sketch::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('sketches.index', compact('sketches'));
    }

    /**
     * Show the form for creating a new sketch.
     */
    public function create()
    {
        return view('sketches.create');
    }

    /**
     * Store a newly created sketch in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'author'    => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'    => 'required|in:Draft,Published',
            'content'   => 'required|string',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $resizedImage = Image::make($image)
                ->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode($image->getClientOriginalExtension(), 75);

            Storage::disk('public')->put('sketches/' . $imageName, $resizedImage);
            $thumbnailPath = 'sketches/' . $imageName;
        }

        Sketch::create([
            'user_id'   => Auth::id(),
            'title'     => $request->title,
            'author'    => $request->author,
            'thumbnail' => $thumbnailPath,
            'status'    => $request->status,
            'content'   => $request->content,
        ]);

        return redirect()->route('admin.sketches.index')->with('success', 'Sketsa berhasil ditambahkan!');
    }

    /**
     * Display the specified sketch.
     */
    public function show(Sketch $sketch)
    {
        return view('sketches.show', compact('sketch'));
    }

    /**
     * Show the form for editing the specified sketch.
     */
    public function edit(Sketch $sketch)
    {
        return view('sketches.edit', compact('sketch'));
    }

    /**
     * Update the specified sketch in storage.
     */
    public function update(Request $request, Sketch $sketch)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'author'    => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'    => 'required|in:Draft,Published',
            'content'   => 'required|string',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($sketch->thumbnail && Storage::disk('public')->exists($sketch->thumbnail)) {
                Storage::disk('public')->delete($sketch->thumbnail);
            }

            $image = $request->file('thumbnail');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $resizedImage = Image::make($image)
                ->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode($image->getClientOriginalExtension(), 75);

            Storage::disk('public')->put('sketches/' . $imageName, $resizedImage);
            $sketch->thumbnail = 'sketches/' . $imageName;
        }

        $sketch->update([
            'title'   => $request->title,
            'author'  => $request->author,
            'status'  => $request->status,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.sketches.index')->with('success', 'Sketsa berhasil diperbarui!');
    }

    /**
     * Remove the specified sketch from storage.
     */
    public function destroy(Sketch $sketch)
    {
        if ($sketch->thumbnail && Storage::disk('public')->exists($sketch->thumbnail)) {
            Storage::disk('public')->delete($sketch->thumbnail);
        }

        $sketch->delete();

        return redirect()->route('admin.sketches.index')->with('success', 'Sketsa berhasil dihapus!');
    }
}
