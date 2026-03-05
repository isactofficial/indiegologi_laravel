@extends('layouts.admin')

@section('content')
<style>
    :root {
        --theme-primary: #0C2C5A;
        --theme-accent: #f4b704;
        --theme-danger: #cb2786;
    }

    .btn-sporty-primary {
        background-color: var(--theme-primary);
        border-color: var(--theme-primary);
        color: white;
        border-radius: 0.75rem;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 8px rgba(12, 44, 90, 0.2);
    }

    .btn-sporty-primary:hover {
        background-color: #081f3f;
        border-color: #081f3f;
        transform: translateY(-2px);
        color: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--theme-primary);
        box-shadow: 0 0 0 0.25rem rgba(12, 44, 90, 0.25);
    }
</style>

<div class="container-fluid px-4" style="min-height: 100vh;">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid var(--theme-primary);">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-plus-circle fs-2" style="color: var(--theme-primary);"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Tambah Jenis Konsultasi Gratis</h2>
                        <p class="text-muted mb-0">Tambahkan jenis konsultasi gratis baru.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.free-consultation.types.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nama Jenis Konsultasi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Contoh: Pendampingan Meditasi, Law of Attraction, dll.</small>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Jelaskan tentang jenis konsultasi gratis ini.</small>
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="">Pilih Status</option>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('admin.free-consultation.types.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-sporty-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

