@extends('layouts.app')

@section('content')
<style>
.container-profile {
    margin-top: 50px;
}

.profile-title {
    font-weight: bold;
    font-size: 1.5rem;
    color: #0C2C5A;
    margin-bottom: 20px;
    margin-top: 20px;
}

.info-card {
    border-radius: 8px;
    box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.2);
    padding: 20px;
    background: white;
}

.form-label {
    font-weight: 500;
    color: #0C2C5A;
}
</style>

<div class="container-profile py-5">
    <div class="text-center mb-4">
        <div class="profile-title">Edit Profile</div>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
    @endif

    <div class="info-card mx-auto" style="max-width: 700px;">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
            </div>

            <!-- Birthdate -->
            <div class="mb-3">
                <label class="form-label">Birthdate</label>
                <input type="date" name="birthdate" value="{{ old('birthdate', $user->profile->birthdate ?? '') }}"
                    class="form-control">
            </div>

            <!-- Gender -->
            <div class="mb-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-control">
                    <option value="">-- Select Gender --</option>
                    <option value="male" {{ old('gender', $user->profile->gender ?? '') == 'male' ? 'selected' : '' }}>
                        Male</option>
                    <option value="female"
                        {{ old('gender', $user->profile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other"
                        {{ old('gender', $user->profile->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Phone Number -->
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone_number"
                    value="{{ old('phone_number', $user->profile->phone_number ?? '') }}" class="form-control">
            </div>

            <!-- Social Media -->
            <div class="mb-3">
                <label class="form-label">Social Media</label>
                <input type="text" name="social_media"
                    value="{{ old('social_media', $user->profile->social_media ?? '') }}" class="form-control">
            </div>

            <!-- Short Description -->
            <div class="mb-3">
                <label class="form-label">Short Description</label>
                <textarea name="description" class="form-control"
                    rows="3">{{ old('description', $user->profile->description ?? '') }}</textarea>
            </div>

            <!-- Profile Photo -->
            <div class="mb-3">
                <label class="form-label">Profile Photo</label>
                @if($user->profile && $user->profile->profile_photo)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$user->profile->profile_photo) }}" alt="Profile Photo"
                        style="max-width: 100px; max-height: 100px;">
                </div>
                <div class="form-check mb-2">
                    <input type="checkbox" name="clear_profile_photo" value="1" class="form-check-input"
                        id="clearPhoto">
                    <label for="clearPhoto" class="form-check-label">Remove current photo</label>
                </div>
                @endif
                <input type="file" name="profile_photo" class="form-control">
            </div>

            <!-- Submit -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('profile.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
