@extends('layouts.admin')

@push('styles')
<style>
    .cursor-pointer {
        cursor: pointer;
    }
    .preview-container {
        position: relative;
        width: 100%;
        height: 240px;
        border: 2px dashed #ddd;
        border-radius: .375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        overflow: hidden;
    }
    .preview-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }
    .upload-text {
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    {{-- Back Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-light px-4 py-2 shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    {{-- Alert Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Main Card --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h4 class="mb-1 fw-bold" style="color: #0C2C5A;">
                <i class="fas fa-edit me-2"></i>Edit Testimoni
            </h4>
            <p class="text-muted mb-0">Update informasi testimoni pelanggan</p>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data" id="testimonialForm">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    {{-- Image Upload Section --}}
                    <div class="col-md-6 mb-4 mb-md-0">
                        <label class="form-label text-secondary fw-medium">Foto Testimoni</label>
                        <div class="position-relative border rounded-3 d-flex align-items-center justify-content-center" style="height: 240px; overflow: hidden;">
                            <input type="file" id="image" name="image" accept="image/*" class="position-absolute w-100 h-100 opacity-0 cursor-pointer" style="z-index: 3;">
                            
                            @if($testimonial->image)
                                <img src="{{ $testimonial->image_url }}" 
                                     alt="{{ $testimonial->name }}"
                                     class="position-absolute w-100 h-100 current-image" 
                                     style="object-fit: cover; border-radius: 0.375rem; pointer-events: none; z-index: 1;">
                            @else
                                <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center border border-secondary bg-white rounded-3" style="pointer-events: none; z-index: 1;">
                                    <div class="text-center text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#6c757d" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                                        </svg>
                                        <p class="mb-0">Klik untuk memilih gambar baru</p>
                                        <small>PNG, JPG, WEBP (Maks. 2MB)</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        @error('image')
                            <div class="text-danger mt-2 small">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        
                        @if($testimonial->image)
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>Kosongkan jika tidak ingin mengubah foto
                            </small>
                        @endif
                    </div>

                    {{-- Form Fields Section --}}
                    <div class="col-md-6">
                        {{-- Name Field --}}
                        <div class="mb-3">
                            <label for="name" class="form-label text-secondary fw-medium">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $testimonial->name) }}" 
                                   placeholder="Contoh: Budi Santoso"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Age and Occupation Row --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="age" class="form-label text-secondary fw-medium">
                                    Usia <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('age') is-invalid @enderror" 
                                       id="age" 
                                       name="age" 
                                       value="{{ old('age', $testimonial->age) }}" 
                                       placeholder="Contoh: 35" 
                                       min="1" 
                                       max="150"
                                       required>
                                @error('age')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="occupation" class="form-label text-secondary fw-medium">
                                    Pekerjaan <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('occupation') is-invalid @enderror" 
                                       id="occupation" 
                                       name="occupation" 
                                       value="{{ old('occupation', $testimonial->occupation) }}" 
                                       placeholder="Contoh: Pengusaha"
                                       required>
                                @error('occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Location Field --}}
                        <div class="mb-3">
                            <label for="location" class="form-label text-secondary fw-medium">
                                Kota / Negara
                            </label>
                            <input type="text" 
                                   class="form-control @error('location') is-invalid @enderror" 
                                   id="location" 
                                   name="location" 
                                   value="{{ old('location', $testimonial->location) }}" 
                                   placeholder="Contoh: Jakarta, Indonesia">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Active Status Toggle - HANYA 1x --}}
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   role="switch" 
                                   id="testimonialActiveSwitch" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="testimonialActiveSwitch">
                                Aktifkan Testimoni
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Testimonial Quote --}}
                <div class="mb-4">
                    <label for="quote" class="form-label text-secondary fw-medium">
                        Testimoni <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('quote') is-invalid @enderror" 
                              id="quote" 
                              name="quote" 
                              rows="5" 
                              placeholder="Tuliskan testimoni klien di sini..."
                              required>{{ old('quote', $testimonial->quote) }}</textarea>
                    @error('quote')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="quoteCounter" class="form-text mt-1"></div>
                </div>
                
                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-5" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Update Testimoni
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('testimonialForm');
        const submitBtn = document.getElementById('submitBtn');
        
        // Prevent double submission
        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            });
        }

        // Image preview logic
        const imageInput = document.querySelector('input[name="image"]');
        if (imageInput) {
            imageInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const file = e.target.files[0];
                    
                    // Validate file size (2MB = 2048KB)
                    if (file.size > 2048 * 1024) {
                        alert('Ukuran file terlalu besar! Maksimal 2MB');
                        e.target.value = '';
                        return;
                    }
                    
                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Format file tidak didukung! Gunakan JPG, PNG, atau WEBP');
                        e.target.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const parent = imageInput.parentElement;
                        let preview = parent.querySelector('img.current-image');
                        
                        if (!preview) {
                            preview = parent.querySelector('img.preview-image');
                            if (!preview) {
                                preview = document.createElement('img');
                                preview.classList.add('position-absolute', 'w-100', 'h-100', 'preview-image');
                                preview.style.objectFit = 'cover';
                                preview.style.borderRadius = '0.375rem';
                                preview.style.pointerEvents = 'none';
                                preview.style.zIndex = '2';
                                parent.appendChild(preview);
                                
                                // Hide default upload text
                                const defaultText = parent.querySelector('.border');
                                if(defaultText) defaultText.style.display = 'none';
                            }
                        }
                        
                        preview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // Character counter for quote
        const quoteTextarea = document.querySelector('textarea[name="quote"]');
        const counterDiv = document.getElementById('quoteCounter');
        
        if (quoteTextarea && counterDiv) {
            function updateCounter() {
                const length = quoteTextarea.value.length;
                
                if (length < 10) {
                    counterDiv.className = 'form-text mt-1 text-danger';
                    counterDiv.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>${length} karakter (minimal 10 karakter)`;
                } else if (length > 1000) {
                    counterDiv.className = 'form-text mt-1 text-danger';
                    counterDiv.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>${length} karakter (maksimal 1000 karakter)`;
                } else {
                    counterDiv.className = 'form-text mt-1 text-success';
                    counterDiv.innerHTML = `<i class="fas fa-check-circle me-1"></i>${length} karakter`;
                }
            }
            
            quoteTextarea.addEventListener('input', updateCounter);
            updateCounter();
        }
    });
</script>
@endpush
@endsection