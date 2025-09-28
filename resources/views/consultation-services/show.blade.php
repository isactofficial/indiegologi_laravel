@extends('layouts.admin')

@section('content')
<style>
    @media (max-width: 768px) {
        /* Susun gambar dan detail secara vertikal di mobile */
        .detail-container .row > div {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .thumbnail-container {
            margin-bottom: 1.5rem;
            text-align: center; /* Pusatkan gambar */
        }
    }
</style>
<div class="container-fluid px-4" style="min-height: 100vh;">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            {{-- [DIKEMBALIKAN] Warna header dikembalikan seperti semula --}}
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #0C2C5A;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-handshake fs-2" style="color: #0C2C5A;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Detail Layanan: {{ $consultationService->title }}</h2>
                        <p class="text-muted mb-0">Informasi lengkap tentang layanan ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('admin.consultation-services.index') }}" class="btn px-4 py-2"
           style="background-color: #e6eef7; color: #0C2C5A; border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Layanan
        </a>
    </div>

    {{-- Detail Content --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4 detail-container">
        <div class="card-body p-4">
            <div class="row">
                {{-- Thumbnail --}}
                <div class="col-md-4 thumbnail-container">
                    @if($consultationService->thumbnail)
                        <img src="{{ asset('storage/' . $consultationService->thumbnail) }}" alt="{{ $consultationService->title }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 300px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height: 200px; border: 2px dashed #ccc;">
                            <span class="text-muted">Tidak Ada Gambar</span>
                        </div>
                    @endif
                </div>

                {{-- Service Details --}}
                <div class="col-md-8">
                    <h3 class="fw-bold" style="color: #0C2C5A;">{{ $consultationService->title }}</h3>
                    <p class="text-muted">Slug: {{ $consultationService->slug }}</p>
                    <ul class="list-group list-group-flush mt-3">
                        <li class="list-group-item">
                            <strong>Harga Dasar:</strong> Rp {{ number_format($consultationService->price, 0, ',', '.') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Harga Per Jam:</strong> Rp {{ number_format($consultationService->hourly_price, 0, ',', '.') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Status:</strong>
                            {{-- [DIKEMBALIKAN] Logika warna status dikembalikan seperti semula --}}
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
            <h5 class="fw-semibold" style="color: #0C2C5A;">Deskripsi Singkat</h5>
            <p>{{ $consultationService->short_description ?? '-' }}</p>
            <h5 class="fw-semibold mt-4" style="color: #0C2C5A;">Deskripsi Produk Lengkap</h5>
            <p>{{ $consultationService->product_description }}</p>
        </div>
    </div>
</div>
@endsection
