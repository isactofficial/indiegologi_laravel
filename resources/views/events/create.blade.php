@extends('layouts.admin')

@section('content')
<style>
    @media (max-width: 768px) {
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
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #0C2C5A;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                        style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-calendar-plus fs-2" style="color: #0C2C5A;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Tambah Event Baru</h2>
                        <p class="text-muted mb-0">Isi detail event di bawah ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label text-secondary fw-medium">Nama Event</label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label text-secondary fw-medium">Harga</label>
                        <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', 0) }}" min="0" required>
                        @error('price')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="event_date" class="form-label text-secondary fw-medium">Tanggal Event</label>
                        <input type="date" id="event_date" name="event_date" class="form-control @error('event_date') is-invalid @enderror" value="{{ old('event_date') }}" required>
                        @error('event_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="event_time" class="form-label text-secondary fw-medium">Waktu Event</label>
                        <input type="time" id="event_time" name="event_time" class="form-control @error('event_time') is-invalid @enderror" value="{{ old('event_time') }}" required>
                        @error('event_time')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="place" class="form-label text-secondary fw-medium">Tempat</label>
                        <input type="text" id="place" name="place" class="form-control @error('place') is-invalid @enderror" value="{{ old('place') }}" required>
                        @error('place')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="max_participants" class="form-label text-secondary fw-medium">Maksimal Peserta</label>
                        <input type="number" id="max_participants" name="max_participants" class="form-control @error('max_participants') is-invalid @enderror" value="{{ old('max_participants') }}" min="1" required>
                        @error('max_participants')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- HAPUS FIELD BATAS PENDAFTARAN --}}
                    <div class="col-md-6 mb-3">
                        <label for="session_type" class="form-label text-secondary fw-medium">Tipe Sesi</label>
                        <select id="session_type" name="session_type" class="form-select @error('session_type') is-invalid @enderror" required>
                            <option value="online" {{ old('session_type') == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="offline" {{ old('session_type') == 'offline' ? 'selected' : '' }}>Offline</option>
                        </select>
                        @error('session_type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label text-secondary fw-medium">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="thumbnail" class="form-label text-secondary fw-medium">Gambar Thumbnail</label>
                        <div class="position-relative border rounded-3 d-flex align-items-center justify-content-center" style="height: 240px;">
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="position-absolute w-100 h-100 opacity-0" style="cursor: pointer; z-index: 3;">
                            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center border border-success bg-white rounded-3" style="pointer-events: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#36b37e" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                                </svg>
                            </div>
                        </div>
                        @error('thumbnail')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-4">
                        <label for="description" class="form-label text-secondary fw-medium">Deskripsi Event</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-start mt-4 form-actions">
                    <button type="submit" class="btn btn-success px-4 py-2">Simpan Event</button>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary ms-2 px-4 py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const thumbInput = document.getElementById('thumbnail');
        if (thumbInput) {
            thumbInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        const parent = thumbInput.parentElement;
                        const overlay = parent.querySelector('.border');
                        let preview = parent.querySelector('img');
                        if (!preview) {
                            preview = document.createElement('img');
                            preview.classList.add('position-absolute', 'w-100', 'h-100');
                            preview.style.objectFit = 'cover';
                            preview.style.borderRadius = '0.375rem';
                            parent.appendChild(preview);
                        }
                        preview.src = ev.target.result;
                        if (overlay) overlay.style.display = 'none';
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }

        // Set minimum dates - HAPUS REFERENSI KE registration_deadline
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('event_date').min = today;
    });
</script>
@endpush
@endsection