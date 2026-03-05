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
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Tambah Jadwal Konsultasi Gratis</h2>
                        <p class="text-muted mb-0">Tambahkan jadwal konsultasi gratis baru.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.free-consultation.schedules.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="type_id" class="form-label fw-semibold">Jenis Konsultasi <span class="text-danger">*</span></label>
                    <select class="form-select @error('type_id') is-invalid @enderror" id="type_id" name="type_id" required>
                        <option value="">Pilih Jenis Konsultasi</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="scheduled_date" class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('scheduled_date') is-invalid @enderror" id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}" required>
                        @error('scheduled_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="scheduled_time" class="form-label fw-semibold">Waktu <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('scheduled_time') is-invalid @enderror" id="scheduled_time" name="scheduled_time" value="{{ old('scheduled_time') }}" required>
                        @error('scheduled_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="max_participants" class="form-label fw-semibold">Maksimal Partisipan <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('max_participants') is-invalid @enderror" id="max_participants" name="max_participants" value="{{ old('max_participants', 1) }}" min="1" max="100" required>
                        @error('max_participants')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="is_available" class="form-label fw-semibold">Status</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">
                                Tersedia untuk booking
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('admin.free-consultation.schedules.index') }}" class="btn btn-outline-secondary">
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

