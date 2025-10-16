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
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('admin.testimonials.index') }}" class="btn px-4 py-2"
           style="background-color: #F0F5FF; color: #5B93FF; border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-secondary fw-medium">Foto Testimoni</label>
                        <div class="position-relative border rounded-3 d-flex align-items-center justify-content-center" style="height: 240px;">
                            <input type="file" id="image" name="image" accept="image/*" class="position-absolute w-100 h-100 opacity-0 cursor-pointer" style="z-index: 3;">
                            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center border @error('image') border-danger @else border-success @enderror bg-white rounded-3" style="pointer-events: none;">
                                <div class="text-center text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#36b37e" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                                    </svg>
                                    <p class="mb-0">Klik untuk memilih gambar</p>
                                    <small>PNG, JPG (Maks. 2MB)</small>
                                </div>
                            </div>
                        </div>
                        @error('image')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label text-secondary fw-medium">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="age" class="form-label text-secondary fw-medium">Usia</label>
                                <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{ old('age') }}" placeholder="Contoh: 35">
                                @error('age')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="occupation" class="form-label text-secondary fw-medium">Pekerjaan</label>
                                <input type="text" class="form-control @error('occupation') is-invalid @enderror" id="occupation" name="occupation" value="{{ old('occupation') }}" placeholder="Contoh: Pengusaha">
                                @error('occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- INPUT KOTA / NEGARA DITAMBAHKAN DI SINI --}}
                        <div class="mb-3">
                            <label for="location" class="form-label text-secondary fw-medium">Kota / Negara <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" placeholder="Contoh: Jakarta, Indonesia" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="isActive" name="is_active" checked>
                            <label class="form-check-label" for="isActive">Aktifkan Testimoni</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="quote" class="form-label text-secondary fw-medium">Testimoni</label>
                    <textarea class="form-control @error('quote') is-invalid @enderror" id="quote" name="quote" rows="4" placeholder="Tuliskan testimoni klien di sini...">{{ old('quote') }}</textarea>
                    @error('quote')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary px-4 me-2">Batal</a>
                    <button type="submit" class="btn btn-primary px-5">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Image preview logic
        const imageInput = document.querySelector('input[name="image"]');
        imageInput.addEventListener('change', function (e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const parent = imageInput.parentElement;
                    const overlay = parent.querySelector('.border');
                    let preview = parent.querySelector('img');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.classList.add('position-absolute', 'w-100', 'h-100');
                        preview.style.objectFit = 'cover';
                        preview.style.borderRadius = '0.375rem';
                        parent.appendChild(preview);
                    }
                    preview.src = e.target.result;
                    if(overlay) overlay.style.display = 'none';
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Character counter for quote
        const quoteTextarea = document.querySelector('textarea[name="quote"]');
        if (quoteTextarea) {
            const counterDiv = document.createElement('div');
            counterDiv.className = 'form-text mt-1';
            quoteTextarea.parentNode.appendChild(counterDiv);

            function updateCounter() {
                const length = quoteTextarea.value.length;
                counterDiv.textContent = `${length} karakter`;
                counterDiv.className = length < 10 ? 'form-text mt-1 text-danger' : 'form-text mt-1 text-success';
            }
            quoteTextarea.addEventListener('input', updateCounter);
            updateCounter();
        }
    });
</script>
@endpush
@endsection