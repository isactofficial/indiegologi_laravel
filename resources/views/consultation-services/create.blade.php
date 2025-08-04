@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4" style="min-height: 100vh;">
    {{-- ... Header ... --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #00617a;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(0, 97, 122, 0.1);">
                        <i class="fas fa-handshake fs-2" style="color: #00617a;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #00617a;">Tambah Layanan Baru</h2>
                        <p class="text-muted mb-0">Isi detail layanan konsultasi di bawah ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Form --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.consultation-services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label text-secondary fw-medium">Judul Layanan</label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label text-secondary fw-medium">Harga Dasar (Rp)</label>
                        <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required min="0">
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="hourly_price" class="form-label text-secondary fw-medium">Harga Per Jam (Rp) - Opsional</label>
                        <input type="number" id="hourly_price" name="hourly_price" class="form-control @error('hourly_price') is-invalid @enderror" value="{{ old('hourly_price') }}" min="0">
                        @error('hourly_price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label text-secondary fw-medium">Status Layanan</label>
                        <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="special" {{ old('status') == 'special' ? 'selected' : '' }}>Special</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="short_description" class="form-label text-secondary fw-medium">Deskripsi Singkat</label>
                        <textarea id="short_description" name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="2">{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="thumbnail" class="form-label text-secondary fw-medium">Gambar Thumbnail</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="form-control @error('thumbnail') is-invalid @enderror">
                        @error('thumbnail')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-4">
                        <label for="product_description" class="form-label text-secondary fw-medium">Deskripsi Produk Lengkap</label>
                        <textarea id="product_description" name="product_description" class="form-control @error('product_description') is-invalid @enderror" rows="5" required>{{ old('product_description') }}</textarea>
                        @error('product_description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-start mt-4">
                    <button type="submit" class="btn btn-success px-4 py-2">Simpan Layanan</button>
                    <a href="{{ route('admin.consultation-services.index') }}" class="btn btn-outline-secondary ms-2 px-4 py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
