@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4" style="min-height: 100vh;">
    {{-- ... Header, Back Button ... --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #00617a;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(0, 97, 122, 0.1);">
                        <i class="fas fa-handshake fs-2" style="color: #00617a;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #00617a;">Detail Layanan: {{ $consultationService->title }}</h2>
                        <p class="text-muted mb-0">Informasi lengkap tentang layanan ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('admin.consultation-services.index') }}" class="btn px-4 py-2"
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
                    @if($consultationService->thumbnail)
                        <img src="{{ asset('storage/' . $consultationService->thumbnail) }}" alt="{{ $consultationService->title }}" class="img-fluid rounded-3 shadow-sm">
                    @else
                        <div class="text-muted">Tidak ada gambar yang diunggah.</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <h5 class="text-secondary fw-semibold">Informasi Dasar</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Judul Layanan:</strong> {{ $consultationService->title }}
                        </li>
                        <li class="list-group-item">
                            <strong>Harga Dasar:</strong> Rp {{ number_format($consultationService->price, 0, ',', '.') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Harga Per Jam:</strong> Rp {{ number_format($consultationService->hourly_price, 0, ',', '.') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Status:</strong>
                            @php
                                $statusClass = 'bg-secondary';
                                if ($consultationService->status === 'published') $statusClass = 'bg-success';
                                if ($consultationService->status === 'special') $statusClass = 'bg-primary';
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst($consultationService->status) }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Dibuat pada:</strong> {{ $consultationService->created_at->format('d M Y') }}
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <h5 class="text-secondary fw-semibold">Deskripsi Singkat</h5>
            <p>{{ $consultationService->short_description ?? '-' }}</p>
            <h5 class="text-secondary fw-semibold mt-4">Deskripsi Produk Lengkap</h5>
            <p>{{ $consultationService->product_description }}</p>
        </div>
    </div>
</div>
@endsection
