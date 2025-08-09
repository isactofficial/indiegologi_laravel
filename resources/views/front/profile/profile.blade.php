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

    {{-- Profile Title --}}
    <div class="text-center mb-4">
        <div class="profile-title mb-3">Profile</div>
        <div class="mx-auto rounded-circle overflow-hidden shadow" style="width:120px; height:120px; background:#ccc;">
            @if($user->profile && $user->profile->profile_photo)
            <img src="{{ asset('storage/'.$user->profile->profile_photo) }}" alt="Profile Photo"
                class="w-100 h-100 object-fit-cover">
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
            <a href="{{ route('profile.edit') }}" class="change-btn">Change</a>
        </div>
        <div class="info-row">
            <div>
                <div class="info-label">Birthdate</div>
                <div class="info-value">{{ $user->profile->birthdate ?? '-' }}</div>
            </div>
            <a href="{{ route('profile.edit') }}" class="change-btn">Change</a>
        </div>
        <div class="info-row">
            <div>
                <div class="info-label">Gender</div>
                <div class="info-value">{{ $user->profile->gender ?? '-' }}</div>
            </div>
            <a href="{{ route('profile.edit') }}" class="change-btn">Change</a>
        </div>
        <div class="info-row">
            <div>
                <div class="info-label">Email</div>
                <div class="info-value">{{ $user->email }}</div>
            </div>
            <a href="{{ route('profile.edit') }}" class="change-btn">Change</a>
        </div>
        <div class="info-row">
            <div>
                <div class="info-label">Phone Number</div>
                <div class="info-value">{{ $user->profile->phone_number ?? '-' }}</div>
            </div>
            <a href="{{ route('profile.edit') }}" class="change-btn">Change</a>
        </div>
        <div class="info-row">
            <div>
                <div class="info-label">Social Media</div>
                <div class="info-value">{{ $user->profile->social_media ?? '-' }}</div>
            </div>
            <a href="{{ route('profile.edit') }}" class="change-btn">Change</a>
        </div>
        <div class="info-row" style="border-bottom:none;">
            <div>
                <div class="info-label">Short Description</div>
                <div class="info-value">{{ $user->profile->description ?? '-' }}</div>
            </div>
            <a href="{{ route('profile.edit') }}" class="change-btn">Change</a>
        </div>
    </div>

</div>
@endsection
