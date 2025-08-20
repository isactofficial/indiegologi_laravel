@extends('layouts.app')

@section('content')
{{-- CSS khusus untuk halaman edit profil --}}
<style>
    /* Indiegologi Brand Colors (Pastikan ini juga ada di layouts.app atau file CSS global Anda) */
    :root {
        --indiegologi-primary: #0C2C5A; /* Biru Tua - Classy, Pointed */
        --indiegologi-accent: #F4B704; /* Emas - Memorable */
        --indiegologi-light-bg: #F5F7FA; /* Background yang bersih */
        --indiegologi-dark-text: #212529; /* Warna teks gelap utama */
        --indiegologi-light-text: #ffffff; /* Warna teks terang */
        --indiegologi-muted-text: #6c757d; /* Warna teks abu-abu */
        --indiegologi-success: #28a745; /* Warna untuk status sukses */
        --indiegologi-danger: #dc3545; /* Warna untuk status bahaya/error */
        --indiegologi-primary-rgb: 12, 44, 90;
    }

    .container-profile {
        width: 100%;
        max-width: 800px; /* Lebar maksimum yang sedikit lebih besar */
        margin: 0 auto;
        padding: 2rem 1rem; /* Padding atas-bawah dan samping */
    }

    .profile-title {
        font-weight: 700;
        font-size: 2.2rem;
        color: var(--indiegologi-primary);
        margin-bottom: 2rem;
        text-align: center;
    }

    .info-card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        padding: 3rem; /* Padding lebih luas */
        background: #ffffff;
        border: 1px solid #e9ecef;
    }

    .form-label {
        font-weight: 600; /* Lebih tebal */
        color: var(--indiegologi-dark-text); /* Warna teks gelap */
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 8px; /* Sudut lebih lembut */
        padding: 0.75rem 1rem; /* Padding yang nyaman */
        border: 1px solid #dee2e6; /* Border standar */
        transition: all 0.3s ease;
        background-color: #fcfcfc;
    }

    .form-control:focus {
        border-color: var(--indiegologi-primary);
        box-shadow: 0 0 0 0.25rem rgba(var(--indiegologi-primary-rgb), 0.15);
        background-color: #ffffff;
    }

    .form-select {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        background-color: #fcfcfc;
    }

    .form-select:focus {
        border-color: var(--indiegologi-primary);
        box-shadow: 0 0 0 0.25rem rgba(var(--indiegologi-primary-rgb), 0.15);
        background-color: #ffffff;
    }

    .form-check-input:checked {
        background-color: var(--indiegologi-primary);
        border-color: var(--indiegologi-primary);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(var(--indiegologi-primary-rgb), 0.15);
    }

    .btn-primary {
        background-color: var(--indiegologi-primary);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(var(--indiegologi-primary-rgb), 0.2);
        transition: all 0.3s ease;
        color: var(--indiegologi-light-text);
    }

    .btn-primary:hover {
        background-color: #082142;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(var(--indiegologi-primary-rgb), 0.3);
    }

    .btn-secondary {
        background-color: #6c757d; /* Warna abu-abu yang lebih netral */
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(108, 117, 125, 0.1);
        transition: all 0.3s ease;
        color: var(--indiegologi-light-text);
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(108, 117, 125, 0.2);
    }

    .alert {
        border-radius: 12px;
        font-weight: 500;
    }

    .alert-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--indiegologi-success);
    }

    .alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--indiegologi-danger);
    }

    /* Profile Photo Preview */
    .profile-photo-preview-container {
        width: 120px;
        height: 120px;
        border-radius: 50%; /* Lingkaran */
        overflow: hidden;
        margin: 0 auto 1.5rem auto; /* Tengah dan beri jarak bawah */
        border: 3px solid var(--indiegologi-primary); /* Border sesuai brand */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        background-color: #e0e0e0; /* Placeholder background */
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer; /* Menunjukkan bahwa elemen ini dapat diklik */
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .profile-photo-preview-container:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .profile-photo-preview {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Pastikan gambar mengisi container */
    }

    .profile-photo-placeholder-icon {
        font-size: 3rem;
        color: #b0b0b0;
    }

    /* Hide the actual file input */
    #profilePhotoInput {
        display: none;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .info-card {
            padding: 2rem;
        }
        .profile-title {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 576px) {
        .info-card {
            padding: 1.5rem;
        }
        .profile-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
    }
</style>

<div class="container-profile">
    <div class="text-center mb-5">
        <div class="profile-title">Edit Profile</div>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
    <div class="alert alert-success text-center mb-4">
        {{ session('success') }}
    </div>
    @endif

    {{-- Alert error validasi --}}
    @if ($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="info-card mx-auto">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Profile Photo Preview Section --}}
            <div class="text-center mb-4">
                <label class="form-label d-block mb-3">Foto Profil</label>
                <div class="profile-photo-preview-container" id="profilePhotoPreviewContainer">
                    @if($user->profile && $user->profile->profile_photo)
                        <img src="{{ asset('storage/'.$user->profile->profile_photo) }}" alt="Profile Photo" class="profile-photo-preview" id="currentProfilePhoto">
                    @else
                        <i class="fas fa-user-circle profile-photo-placeholder-icon" id="profilePhotoPlaceholder"></i>
                    @endif
                </div>
                {{-- Hidden file input --}}
                <input type="file" name="profile_photo" class="form-control" id="profilePhotoInput" accept="image/*">
                @if($user->profile && $user->profile->profile_photo)
                <div class="form-check mt-2">
                    <input type="checkbox" name="clear_profile_photo" value="1" class="form-check-input" id="clearPhoto">
                    <label for="clearPhoto" class="form-check-label text-muted">Hapus foto profil saat ini</label>
                </div>
                @endif
            </div>

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control" required>
            </div>

            <!-- Birthdate -->
            <div class="mb-3">
                <label for="birthdate" class="form-label">Tanggal Lahir</label>
                <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate', optional($user->profile)->birthdate ?? '') }}"
                    class="form-control">
            </div>

            <!-- Gender -->
            <div class="mb-3">
                <label for="gender" class="form-label">Jenis Kelamin</label>
                <select name="gender" id="gender" class="form-select">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="male" {{ old('gender', optional($user->profile)->gender ?? '') == 'male' ? 'selected' : '' }}>
                        Laki-laki</option>
                    <option value="female"
                        {{ old('gender', optional($user->profile)->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option>
                    <option value="other"
                        {{ old('gender', optional($user->profile)->gender ?? '') == 'other' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <!-- Phone Number -->
            <div class="mb-3">
                <label for="phone_number" class="form-label">Nomor Telepon</label>
                <input type="text" name="phone_number" id="phone_number"
                    value="{{ old('phone_number', optional($user->profile)->phone_number ?? '') }}" class="form-control">
            </div>

            <!-- Social Media -->
            <div class="mb-3">
                <label for="social_media" class="form-label">Media Sosial</label>
                <input type="text" name="social_media" id="social_media"
                    value="{{ old('social_media', optional($user->profile)->social_media ?? '') }}" class="form-control">
            </div>

            <!-- Short Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi Singkat</label>
                <textarea name="description" id="description" class="form-control"
                    rows="3">{{ old('description', optional($user->profile)->description ?? '') }}</textarea>
            </div>

            <!-- Submit / Cancel Buttons -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('profile.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- Skrip JavaScript untuk pratinjau gambar --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profilePhotoInput = document.getElementById('profilePhotoInput');
        const profilePhotoPreviewContainer = document.getElementById('profilePhotoPreviewContainer');
        let currentProfilePhoto = document.getElementById('currentProfilePhoto'); // Use let as it might be recreated
        const profilePhotoPlaceholder = document.getElementById('profilePhotoPlaceholder');
        const clearPhotoCheckbox = document.getElementById('clearPhoto');

        // Function to update image preview
        function updatePhotoPreview(file) {
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (currentProfilePhoto) {
                        currentProfilePhoto.src = e.target.result;
                        currentProfilePhoto.style.display = 'block';
                    } else {
                        // Create img element if it doesn't exist
                        const newImg = document.createElement('img');
                        newImg.id = 'currentProfilePhoto';
                        newImg.className = 'profile-photo-preview';
                        newImg.src = e.target.result;
                        profilePhotoPreviewContainer.innerHTML = ''; // Clear placeholder
                        profilePhotoPreviewContainer.appendChild(newImg);
                        currentProfilePhoto = newImg; // Update reference
                    }
                    if (profilePhotoPlaceholder) {
                        profilePhotoPlaceholder.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            } else {
                // If no file selected (e.g., input cleared), revert to original or placeholder
                if ("{{ optional($user->profile)->profile_photo }}") { // Check if there was an original photo
                    if (currentProfilePhoto) {
                        currentProfilePhoto.src = "{{ asset('storage/'.optional($user->profile)->profile_photo) }}";
                        currentProfilePhoto.style.display = 'block';
                    }
                    if (profilePhotoPlaceholder) {
                        profilePhotoPlaceholder.style.display = 'none';
                    }
                } else { // No original photo, show placeholder
                    if (currentProfilePhoto) {
                        currentProfilePhoto.style.display = 'none';
                    }
                    if (profilePhotoPlaceholder) {
                        profilePhotoPlaceholder.style.display = 'block';
                    }
                }
            }
        }

        // Event listener for clicking the preview container
        if (profilePhotoPreviewContainer) {
            profilePhotoPreviewContainer.addEventListener('click', function() {
                profilePhotoInput.click(); // Trigger click on the hidden file input
            });
        }

        // Event listener for file input change
        if (profilePhotoInput) {
            profilePhotoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    updatePhotoPreview(this.files[0]);
                    // Uncheck "Remove photo" if a new photo is selected
                    if (clearPhotoCheckbox) {
                        clearPhotoCheckbox.checked = false;
                    }
                } else {
                    // If file input is cleared, revert to original or placeholder
                    updatePhotoPreview(null);
                }
            });
        }

        // Event listener for "Remove photo" checkbox
        if (clearPhotoCheckbox) {
            clearPhotoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Hide current photo and show placeholder
                    if (currentProfilePhoto) {
                        currentProfilePhoto.style.display = 'none';
                    }
                    if (profilePhotoPlaceholder) {
                        profilePhotoPlaceholder.style.display = 'block';
                    }
                    // Clear file input if checked
                    if (profilePhotoInput) {
                        profilePhotoInput.value = ''; // Clear the selected file
                    }
                } else {
                    // If unchecked, show original photo or placeholder based on current state
                    if ("{{ optional($user->profile)->profile_photo }}") { // If there was an original photo
                        if (currentProfilePhoto) {
                            currentProfilePhoto.src = "{{ asset('storage/'.optional($user->profile)->profile_photo) }}";
                            currentProfilePhoto.style.display = 'block';
                        }
                        if (profilePhotoPlaceholder) {
                            profilePhotoPlaceholder.style.display = 'none';
                        }
                    } else { // No original photo, show placeholder
                        if (currentProfilePhoto) {
                            currentProfilePhoto.style.display = 'none';
                        }
                        if (profilePhotoPlaceholder) {
                            profilePhotoPlaceholder.style.display = 'block';
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
