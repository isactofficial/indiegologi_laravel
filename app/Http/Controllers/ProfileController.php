<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     */
    public function index()
    {
        $user = Auth::user();
        $user->load('profile');

        return view('front.profile.profile', compact('user'));
    }

    /**
     * Show the form for editing the user's basic profile information.
     */
    public function edit()
    {
        $user = Auth::user();
        $user->load('profile');

        return view('front.profile.edit', compact('user'));
    }

    /**
     * Update the user's basic profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'birthdate' => 'nullable|date|before_or_equal:today',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'phone_number' => 'nullable|string|max:255',
            'social_media' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'clear_profile_photo' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Update name di tabel users
            if ($user->name !== $request->name) {
                $user->name = $request->name;
                $user->save();
            }

            // Update data profil
            $profile = $user->profile ?? new Profile();
            $profile->user_id = $user->id;
            $profile->name = $request->name;
            $profile->birthdate = $request->birthdate;
            $profile->gender = $request->gender;
            $profile->phone_number = $request->phone_number;
            $profile->social_media = $request->social_media;

            // Upload foto profil
            if ($request->hasFile('profile_photo')) {
                if ($profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
                    Storage::disk('public')->delete($profile->profile_photo);
                }
                $profile->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
            } elseif ($request->boolean('clear_profile_photo')) {
                if ($profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
                    Storage::disk('public')->delete($profile->profile_photo);
                }
                $profile->profile_photo = null;
            }

            $user->profile()->save($profile);

            DB::commit();

            return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating profile: " . $e->getMessage(), [
                'user_id' => $user->id,
                'request_data' => $request->except('profile_photo')
            ]);
            return back()->withInput()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }
}
