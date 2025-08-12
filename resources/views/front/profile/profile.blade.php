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
    margin-top: 20px;
}

.info-card {
    border-radius: 8px;
    box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.2);
    padding: 20px;
    background: white;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #ddd;
}

.info-label {
    font-size: 0.85rem;
    color: #666;
}

.info-value {
    font-size: 1rem;
    color: #0C2C5A;
    font-weight: 500;
}

.change-btn {
    font-size: 0.8rem;
    color: #0C2C5A;
    text-decoration: none;
    font-weight: bold;
}

.change-btn:hover {
    text-decoration: underline;
}
</style>

<div class="container-profile py-5">
    <!-- Notifikasi berhasil update data -->
    @if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
    @endif
    {{-- Profile Title --}}
    <div class="text-center mb-4">
        <div class="profile-title mb-3">Profile</div>
        <div class="mx-auto rounded-circle overflow-hidden shadow" style="width:120px; height:120px; background:#ccc;">
            @if(optional($user->profile)->profile_photo)
            <img src="{{ asset('storage/' . $user->profile->profile_photo) }}" alt="Profile Photo"
                class="w-100 h-100 object-fit-cover">
            @else
            <span class="d-flex align-items-center justify-content-center h-100 text-muted">
                No Photo
            </span>
            @endif
        </div>
    </div>

    {{-- Informasi Dasar --}}
    <div class="text-center profile-title mb-3">Informasi Dasar</div>

    <div class="info-card mx-auto" style="max-width: 700px;">
        <div class="info-row">
            <div>
                <div class="info-label">Name</div>
                <div class="info-value">{{ $user->name }}</div>
            </div>
        </div>
        <div class="info-row">
            <div>
                <div class="info-label">Birthdate</div>
                <div class="info-value">{{ optional($user->profile)->birthdate ?? '-' }}</div>
            </div>
        </div>
        <div class="info-row">
            <div>
                <div class="info-label">Gender</div>
                <div class="info-value">{{ optional($user->profile)->gender ?? '-' }}</div>
            </div>
        </div>
        <div class="info-row">
            <div>
                <div class="info-label">Email</div>
                <div class="info-value">{{ $user->email }}</div>
            </div>
        </div>
        <div class="info-row">
            <div>
                <div class="info-label">Phone Number</div>
                <div class="info-value">{{ optional($user->profile)->phone_number ?? '-' }}</div>
            </div>
        </div>
        <div class="info-row">
            <div>
                <div class="info-label">Social Media</div>
                <div class="info-value">{{ optional($user->profile)->social_media ?? '-' }}</div>
            </div>
        </div>
        <div class="mb-3">
            <p class="info-label mb-1">Short Description</p>
            <p class="info-value">{{ optional($user->profile)->description ?? '-' }}</p>
        </div>

        {{-- Tombol Edit --}}
        <div class="text-left mt-4">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-3">Edit Profile</a>
        </div>
    </div>

</div>
@endsection
