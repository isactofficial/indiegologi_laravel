@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4" style="min-height: 100vh;">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #f4b704;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(244, 183, 4, 0.1);">
                        <i class="fas fa-palette fs-2" style="color: #f4b704;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #f4b704;">Edit Sketsa: {{ $sketch->title }}</h2>
                        <p class="text-muted mb-0">Perbarui detail sketsa ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            {{-- Menggunakan slug untuk route update --}}
            <form action="{{ route('admin.sketches.update', $sketch->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="thumbnail" class="form-label text-secondary fw-medium">Gambar Thumbnail</label>
                        <div class="mb-3">
                            @if($sketch->thumbnail)
                                {{-- Menggunakan thumbnail dari model --}}
                                <img src="{{ asset('storage/' . $sketch->thumbnail) }}" alt="{{ $sketch->title }}" class="img-fluid rounded-3" style="max-height: 200px;">
                            @else
                                <div class="text-muted">Tidak ada gambar yang diunggah.</div>
                            @endif
                        </div>
                        {{-- Nama input disesuaikan menjadi 'thumbnail' --}}
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="form-control @error('thumbnail') is-invalid @enderror">
                        @error('thumbnail')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label text-secondary fw-medium">Judul Sketsa</label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $sketch->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label text-secondary fw-medium">Penulis</label>
                            <input type="text" id="author" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author', $sketch->author) }}" required>
                            @error('author')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Tambahan input Status --}}
                <div class="mb-3">
                    <label for="status" class="form-label text-secondary fw-medium">Status Publikasi</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="Draft" {{ old('status', $sketch->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Published" {{ old('status', $sketch->status) == 'Published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tambahan input Content --}}
                <div class="mb-4">
                    <label for="content" class="form-label text-secondary fw-medium">Konten Sketsa (Deskripsi)</label>
                    <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content', $sketch->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-start">
                    <button type="submit" class="btn btn-success px-4 py-2">Update Sketsa</button>
                    <a href="{{ route('admin.sketches.index') }}" class="btn btn-outline-secondary ms-2 px-4 py-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
