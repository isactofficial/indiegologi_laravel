@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4" style="min-height: 100vh;">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #00617a;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(0, 97, 122, 0.1);">
                        <i class="fas fa-palette fs-2" style="color: #00617a;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #00617a;">Detail Sketsa: {{ $sketch->title }}</h2>
                        <p class="text-muted mb-0">Informasi lengkap tentang sketsa ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('admin.sketches.index') }}" class="btn px-4 py-2"
           style="background-color: #F0F5FF; color: #5B93FF; border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>

    {{-- Detail Card --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h5 class="text-secondary fw-semibold">Gambar Thumbnail</h5>
                    {{-- Menggunakan thumbnail dari model --}}
                    @if($sketch->thumbnail)
                        <img src="{{ asset('storage/' . $sketch->thumbnail) }}" alt="{{ $sketch->title }}" class="img-fluid rounded-3 shadow-sm">
                    @else
                        <div class="text-muted">Tidak ada gambar yang diunggah.</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <h5 class="text-secondary fw-semibold">Informasi Dasar</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Judul:</strong> {{ $sketch->title }}
                        </li>
                        <li class="list-group-item">
                            <strong>Penulis:</strong> {{ $sketch->author }}
                        </li>
                        {{-- Menambahkan status sketsa --}}
                        <li class="list-group-item">
                            <strong>Status:</strong>
                            <span class="badge {{ $sketch->status == 'Published' ? 'badge-status-published' : 'badge-status-draft' }}">
                                {{ $sketch->status }}
                            </span>
                        </li>
                        {{-- Menambahkan jumlah views --}}
                        <li class="list-group-item">
                            <strong>Jumlah Dilihat:</strong> {{ $sketch->views }}
                        </li>
                        {{-- Menambahkan informasi user yang mengunggah --}}
                        <li class="list-group-item">
                            <strong>Dibuat oleh:</strong> {{ $sketch->user->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Dibuat pada:</strong> {{ $sketch->created_at->format('d M Y') }}
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <h5 class="text-secondary fw-semibold">Konten Sketsa</h5>
            <p>{{ $sketch->content }}</p>
        </div>
    </div>
</div>
@endsection
