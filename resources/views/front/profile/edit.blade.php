@extends('layouts.app')

@section('content')
<div class="container py-5" style="margin-top: 55px;">
    <div class=" card shadow-sm">
        <div class="card-header text-white" style="background-color: #0C2C5A;">
            <h4 class="mb-0">Edit Profile</h4>
        </div>
        <div class="card-body">
            {{-- Notifikasi sukses --}}
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

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
                    <input type="date" name="birthdate"
                        value="{{ old('birthdate', optional($user->profile)->birthdate) }}" class="form-control">
                </div>

                <!-- Gender -->
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">-- Select Gender --</option>
                        <option value="male"
                            {{ old('gender', optional($user->profile)->gender) == 'male' ? 'selected' : '' }}>Male
                        </option>
                        <option value="female"
                            {{ old('gender', optional($user->profile)->gender) == 'female' ? 'selected' : '' }}>Female
                        </option>
                        <option value="other"
                            {{ old('gender', optional($user->profile)->gender) == 'other' ? 'selected' : '' }}>Other
                        </option>
                    </select>
                </div>

                <!-- Phone Number -->
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone_number"
                        value="{{ old('phone_number', optional($user->profile)->phone_number) }}" class="form-control">
                </div>

                <!-- Social Media -->
                <div class="mb-3">
                    <label class="form-label">Social Media</label>
                    <input type="text" name="social_media"
                        value="{{ old('social_media', optional($user->profile)->social_media) }}" class="form-control">
                </div>

                <!-- Short Description -->
                <div class="mb-3">
                    <label class="form-label">Short Description</label>
                    <textarea name="description" class="form-control"
                        rows="3">{{ old('description', optional($user->profile)->description) }}</textarea>
                </div>

                <!-- Profile Photo -->
                <div class="mb-3">
                    <label class="form-label">Profile Photo</label>
                    @if(optional($user->profile)->profile_photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $user->profile->profile_photo) }}" alt="Profile Photo"
                            class="rounded shadow-sm" style="max-width: 100px; max-height: 100px;">
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
                <div class="d-flex justify-content-between">
                    <a href="{{ route('profile.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
