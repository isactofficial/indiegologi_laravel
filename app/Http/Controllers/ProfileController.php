<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\UserProfile; 
use App\Models\Invoice;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     */
    public function index()
    {
        $user = Auth::user();
        // Load relasi profile (yang sebenarnya UserProfile) dan invoices
        $user->load(['profile', 'invoices']); // Relasi 'profile' di model User harus mengacu ke UserProfile

        return view('front.profile.profile', compact('user'));
    }

    /**
     * Show the form for editing the user's basic profile information.
     */
    public function edit()
    {
        $user = Auth::user();
        $user->load('profile'); // Relasi 'profile' di model User harus mengacu ke UserProfile

        return view('front.profile.edit', compact('user'));
    }

    /**
     * Update the user's basic profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'phone_number' => 'nullable|string|max:20',
            'social_media' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update user basic info
        $user->name = $request->name;
        $user->save();

        // Update profile info
        // <--- PERUBAHAN DI SINI: Menggunakan UserProfile saat membuat instance baru
        $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);
        $profile->birthdate = $request->birthdate;
        $profile->gender = $request->gender;
        $profile->phone_number = $request->phone_number;
        $profile->social_media = $request->social_media;
        $profile->description = $request->description;

        // Handle photo upload
        if ($request->has('clear_profile_photo') && $request->clear_profile_photo) {
            if ($profile->profile_photo && Storage::exists('public/'.$profile->profile_photo)) {
                Storage::delete('public/'.$profile->profile_photo);
            }
            $profile->profile_photo = null;
        }

        if ($request->hasFile('profile_photo')) {
            if ($profile->profile_photo && Storage::exists('public/'.$profile->profile_photo)) {
                Storage::delete('public/'.$profile->profile_photo);
            }
            $profile->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $profile->save();

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profile berhasil diperbarui!');
    }
}
