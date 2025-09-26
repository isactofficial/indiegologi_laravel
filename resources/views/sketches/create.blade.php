@extends('layouts.admin')

@push('styles')
{{-- Menyesuaikan seluruh palet warna dengan tema utama --}}
<style>
    .form-control:focus, .form-select:focus {
        border-color: #0C2C5A;
        box-shadow: 0 0 0 0.25rem rgba(12, 44, 90, 0.25);
    }
    .btn-success {
        background-color: #0C2C5A;
        border-color: #0C2C5A;
    }
    .btn-success:hover {
        background-color: #081f3f;
        border-color: #081f3f;
    }

    /* Penyesuaian Tampilan Mobile */
    @media (max-width: 767px) {
        /* Mengecilkan header */
        .header-card {
            padding: 1rem !important;
        }
        .header-icon-container {
            width: 50px !important;
            height: 50px !important;
        }
        .header-icon-container .fs-2 {
            font-size: 1.5rem !important;
        }
        .header-title {
            font-size: 1.5rem !important;
        }

        /* Mengurangi padding pada form */
        .form-card-body {
            padding: 1.5rem !important;
        }

        /* Membuat tombol menjadi full-width dan tersusun vertikal */
        .form-buttons {
            flex-direction: column;
            gap: 0.5rem;
        }
        .form-buttons .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4" style="min-height: 100vh;">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            {{-- Header disesuaikan dengan tema utama --}}
            <div class="bg-white rounded-4 shadow-sm p-4 header-card" style="border-left: 8px solid #0C2C5A;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4 header-icon-container"
                         style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-plus fs-2" style="color: #0C2C5A;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1 header-title" style="color: #0C2C5A;">Tambah Sketsa Baru</h2>
                        <p class="text-muted mb-0">Isi detail untuk sketsa baru Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4 form-card-body">
            <form action="{{ route('admin.sketches.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="thumbnail" class="form-label text-secondary fw-medium">Gambar Thumbnail</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="form-control @error('thumbnail') is-invalid @enderror">
                        @error('thumbnail')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="mb-3">
                            <label for="title" class="form-label text-secondary fw-medium">Judul Sketsa</label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label text-secondary fw-medium">Penulis</label>
                            <input type="text" id="author" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}" required>
                            @error('author')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label text-secondary fw-medium">Status Publikasi</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Published" {{ old('status') == 'Published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-4">
                        <label for="content" class="form-label text-secondary fw-medium">Konten Sketsa (Deskripsi)</label>
                        <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-start form-buttons">
                    <button type="submit" class="btn btn-success px-4 py-2">Save Sketsa</button>
                    <a href="{{ route('admin.sketches.index') }}" class="btn btn-outline-secondary ms-0 ms-md-2 px-4 py-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
