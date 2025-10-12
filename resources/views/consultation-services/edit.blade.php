@extends('layouts.admin')

@section('content')
<style>
    @media (max-width: 768px) {
        /* Aksi tombol di mobile */
        .form-actions {
            flex-direction: column;
            gap: 0.5rem;
            align-items: stretch;
        }
        .form-actions .btn {
            width: 100%;
            margin-left: 0 !important;
        }
    }
</style>
<div class="container-fluid px-4" style="min-height: 100vh;">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            {{-- [DIKEMBALIKAN] Warna header dikembalikan seperti semula --}}
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #f4b704;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(244, 183, 4, 0.1);">
                        <i class="fas fa-handshake fs-2" style="color: #f4b704;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #f4b704;">Edit Layanan</h2>
                        <p class="text-muted mb-0">Perbarui detail layanan konsultasi ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.consultation-services.update', $consultationService->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label text-secondary fw-medium">Judul Layanan</label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $consultationService->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="slug" class="form-label text-secondary fw-medium">Slug</label>
                        <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $consultationService->slug) }}" required>
                        @error('slug')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label text-secondary fw-medium">Harga Dasar</label>
                        <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $consultationService->price) }}" required>
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="hourly_price" class="form-label text-secondary fw-medium">Harga Per Jam (Tambahan)</label>
                        <input type="number" id="hourly_price" name="hourly_price" class="form-control @error('hourly_price') is-invalid @enderror" value="{{ old('hourly_price', $consultationService->hourly_price) }}" required>
                        @error('hourly_price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label text-secondary fw-medium">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="published" {{ old('status', $consultationService->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="special" {{ old('status', $consultationService->status) == 'special' ? 'selected' : '' }}>Special</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="short_description" class="form-label text-secondary fw-medium">Deskripsi Singkat</label>
                        <input type="text" id="short_description" name="short_description" class="form-control @error('short_description') is-invalid @enderror" value="{{ old('short_description', $consultationService->short_description) }}">
                        @error('short_description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="thumbnail" class="form-label text-secondary fw-medium">Gambar Thumbnail (Opsional)</label>
                        <div class="mb-2">
                            @if ($consultationService->thumbnail)
                                <img src="{{ asset('storage/' . $consultationService->thumbnail) }}" alt="Current Thumbnail" class="rounded-3" style="max-width: 200px; height: auto;">
                            @else
                                <div class="text-muted">Tidak ada gambar yang diunggah.</div>
                            @endif
                        </div>
                        <div class="position-relative border rounded-3 d-flex align-items-center justify-content-center" style="height: 240px;">
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="position-absolute w-100 h-100 opacity-0" style="cursor: pointer; z-index: 3;">
                            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center border border-secondary bg-white rounded-3" style="pointer-events: none; z-index: 1;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#6c757d" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                                </svg>
                            </div>
                        </div>
                        @error('thumbnail')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-4">
                        <label for="product_description" class="form-label text-secondary fw-medium">Deskripsi Produk Lengkap</label>
                        <textarea id="product_description" name="product_description" class="form-control @error('product_description') is-invalid @enderror" rows="5" required>{{ old('product_description', $consultationService->product_description) }}</textarea>
                        @error('product_description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-start mt-4 form-actions">
                    <button type="submit" class="btn btn-success px-4 py-2">Update Layanan</button>
                    <a href="{{ route('admin.consultation-services.index') }}" class="btn btn-outline-secondary ms-2 px-4 py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const thumbInput = document.getElementById('thumbnail');
    if (thumbInput) {
        thumbInput.addEventListener('change', function (e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    const parent = thumbInput.parentElement;
                    // if there is an existing current image, remove it
                    const current = parent.querySelector('img.current-image');
                    if (current) current.remove();
                    let preview = parent.querySelector('img.preview-image');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.classList.add('position-absolute', 'w-100', 'h-100', 'preview-image');
                        preview.style.objectFit = 'cover';
                        preview.style.borderRadius = '0.375rem';
                        preview.style.pointerEvents = 'none';
                        preview.style.zIndex = '2';
                        parent.appendChild(preview);
                    }
                    preview.src = ev.target.result;
                    const defaultOverlay = parent.querySelector('.border');
                    if (defaultOverlay) defaultOverlay.style.display = 'none';
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
});
</script>
@endpush
