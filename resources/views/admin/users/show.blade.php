@extends('layouts.admin')

@section('content')
{{-- CSS untuk konsistensi tampilan --}}
<style>
    :root {
        --indiegologi-primary: #0C2C5A; --indiegologi-accent: #F4B704;
        --indiegologi-dark-text: #212529; --indiegologi-muted-text: #6c757d;
    }
    .profile-page-main-title { font-weight: 700; font-size: 2.2rem; color: var(--indiegologi-primary); margin-bottom: 2rem; text-align: center; }
    .profile-section-subtitle { font-weight: 600; font-size: 1.4rem; color: var(--indiegologi-primary); margin-bottom: 1.5rem; text-align: center; }
    .profile-section-wrapper { padding: 2.5rem; margin-bottom: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06); background: #ffffff; border: 1px solid #e9ecef; }
    .profile-photo-container { margin-bottom: 1.5rem; }
    .info-card-inner { padding: 0 1rem; }
    .info-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f2f5; }
    .info-row:last-child { border-bottom: none; }
    .info-label { font-size: 0.9rem; color: var(--indiegologi-muted-text); font-weight: 500; }
    .info-value { font-size: 1.05rem; color: var(--indiegologi-dark-text); font-weight: 600; }
</style>

<div class="container-fluid px-4">
    {{-- Tombol Kembali --}}
    <div class="d-flex justify-content-start mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Booking
        </a>
    </div>

    <div class="profile-page-main-title">Profil Pengguna</div>

    {{-- Kartu Informasi Profil --}}
    <div class="profile-section-wrapper mx-auto">
        <div class="text-center mb-4">
            <div class="mx-auto rounded-circle overflow-hidden shadow-sm profile-photo-container" style="width:120px; height:120px; background:#e0e0e0; border: 3px solid var(--indiegologi-primary);">
                @if(optional($user->profile)->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile->profile_photo) }}" alt="Profile Photo" class="w-100 h-100 object-fit-cover">
                @else
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted bg-light">
                        <i class="fas fa-user-circle" style="font-size: 3rem; color: #b0b0b0;"></i>
                    </div>
                @endif
            </div>
            <h2 class="h5 mt-3 mb-1 fw-bold text-dark">{{ $user->name }}</h2>
            <p class="text-muted">{{ $user->email }}</p>
        </div>

        <div class="profile-section-subtitle">Informasi Dasar</div>
        <div class="info-card-inner">
            <div class="info-row"><div><div class="info-label">Nama</div><div class="info-value">{{ $user->name }}</div></div></div>
            <div class="info-row"><div><div class="info-label">Tanggal Lahir</div><div class="info-value">{{ optional($user->profile)->birthdate ? \Carbon\Carbon::parse($user->profile->birthdate)->translatedFormat('d F Y') : '-' }}</div></div></div>
            <div class="info-row"><div><div class="info-label">Jenis Kelamin</div><div class="info-value">{{ optional($user->profile)->gender ?? '-' }}</div></div></div>
            <div class="info-row"><div><div class="info-label">Email</div><div class="info-value">{{ $user->email }}</div></div></div>
            <div class="info-row"><div><div class="info-label">Nomor Telepon</div><div class="info-value">{{ optional($user->profile)->phone_number ?? '-' }}</div></div></div>
            <div class="info-row"><div><div class="info-label">Media Sosial</div><div class="info-value">{{ optional($user->profile)->social_media ?? '-' }}</div></div></div>
        </div>
    </div>
</div>
@endsection
