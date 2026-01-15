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
                            <i class="fas fa-hand-holding-heart fs-2" style="color: #0C2C5A;"></i>
                        </div>
                        <div>
                            <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Tambah Layanan Konsultasi Gratis Baru</h2>
                            <p class="text-muted mb-0">Isi detail layanan di bawah ini.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="card shadow-sm border-0 mb-4 rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.free-consultation.types.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label text-secondary fw-medium">Nama Layanan</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label text-secondary fw-medium">Status</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror"
                                required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="base_price" class="form-label text-secondary fw-medium">Harga Original</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" id="base_price" name="base_price"
                                    class="form-control @error('base_price') is-invalid @enderror" 
                                    value="{{ old('base_price') }}"
                                    step="0.01">
                            </div>
                            @error('base_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <label for="description" class="form-label text-secondary fw-medium">Deskripsi Layanan</label>
                        <textarea id="description" name="description"
                            class="form-control @error('description') is-invalid @enderror" rows="5"
                            required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-start mt-4 form-actions">
                        <button type="submit" class="btn btn-success px-4 py-2">Simpan Layanan</button>
                        <a href="{{ route('admin.free-consultation.types.index') }}"
                            class="btn btn-outline-secondary ms-2 px-4 py-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection