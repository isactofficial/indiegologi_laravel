@extends('layouts.admin')

@section('content')
<style>
    @media (max-width: 768px) {
        .detail-container .row > div {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .thumbnail-container {
            margin-bottom: 1.5rem;
            text-align: center;
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
                        <i class="fas fa-calendar-alt fs-2" style="color: #0C2C5A;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Detail Event: {{ $event->title }}</h2>
                        <p class="text-muted mb-0">Informasi lengkap tentang event ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('admin.events.index') }}" class="btn px-4 py-2"
           style="background-color: #e6eef7; color: #0C2C5A; border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Events
        </a>
    </div>

    {{-- Detail Content --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4 detail-container">
        <div class="card-body p-4">
            <div class="row">
                {{-- Thumbnail --}}
                <div class="col-md-4 thumbnail-container">
                    @if($event->thumbnail)
                        <img src="{{ asset('storage/' . $event->thumbnail) }}" alt="{{ $event->title }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 300px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height: 200px; border: 2px dashed #ccc;">
                            <span class="text-muted">Tidak Ada Gambar</span>
                        </div>
                    @endif
                </div>

                {{-- Event Details --}}
                <div class="col-md-8">
                    <h3 class="fw-bold" style="color: #0C2C5A;">{{ $event->title }}</h3>
                    <p class="text-muted">Slug: {{ $event->slug }}</p>
                    <ul class="list-group list-group-flush mt-3">
                        <li class="list-group-item">
                            <strong>Harga:</strong> Rp {{ number_format($event->price, 0, ',', '.') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Tanggal & Waktu:</strong> 
                            {{ $event->event_date->format('d M Y') }} • {{ $event->event_time }}
                        </li>
                        <li class="list-group-item">
                            <strong>Tempat:</strong> {{ $event->place }}
                        </li>
                        <li class="list-group-item">
                            <strong>Batas Pendaftaran:</strong> {{ $event->registration_deadline->format('d M Y') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Peserta:</strong> 
                            <span class="badge bg-success">{{ $event->current_participants }}/{{ $event->max_participants }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Tipe Sesi:</strong>
                            <span class="badge bg-secondary">{{ ucfirst($event->session_type) }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Status:</strong>
                            @php
                                $statusClass = 'bg-secondary';
                                if ($event->status === 'published') $statusClass = 'bg-success';
                                if ($event->status === 'cancelled') $statusClass = 'bg-danger';
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst($event->status) }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Dibuat pada:</strong> {{ $event->created_at->format('d M Y H:i') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Diupdate pada:</strong> {{ $event->updated_at->format('d M Y H:i') }}
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <h5 class="fw-semibold" style="color: #0C2C5A;">Deskripsi Event</h5>
            <p>{{ $event->description }}</p>
        </div>
    </div>

    {{-- Event Bookings Section --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h5 class="fw-semibold mb-4" style="color: #0C2C5A;">
                <i class="fas fa-users me-2"></i>Daftar Pendaftar Event
            </h5>
            
            @if($event->bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama User</th>
                                <th>Email</th>
                                <th>Tanggal Daftar</th>
                                <th>Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($event->bookings as $booking)
                                <tr>
                                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->user->email ?? 'N/A' }}</td>
                                    <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        @php
                                            $paymentStatus = $booking->invoice->payment_status ?? 'unknown';
                                            $statusClass = 'bg-secondary';
                                            if ($paymentStatus === 'paid') $statusClass = 'bg-success';
                                            if ($paymentStatus === 'pending') $statusClass = 'bg-warning';
                                            if ($paymentStatus === 'unpaid') $statusClass = 'bg-danger';
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ ucfirst($paymentStatus) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-users-slash me-2"></i>Belum ada pendaftar untuk event ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection