@extends('layouts.app')

@push('styles')
<style>
    .success-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
    }

    .success-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: #28a745;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .success-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .booking-details {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #dee2e6;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .participant-list {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .participant-item {
        padding: 0.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .participant-item:last-child {
        border-bottom: none;
    }

    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .instructions {
        background: #e7f3ff;
        border-left: 4px solid #0d6efd;
        padding: 1rem;
        border-radius: 4px;
        margin: 1.5rem 0;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            
            <h2 class="text-center mb-4">Pendaftaran Event Berhasil!</h2>
            
            <div class="instructions">
                <h6><i class="bi bi-info-circle"></i> Informasi Penting:</h6>
                <ul class="mb-0">
                    <li>Pendaftaran Anda telah berhasil disimpan</li>
                    <li>Admin kami akan menghubungi Anda melalui nomor telepon/WhatsApp yang terdaftar</li>
                    <li>Harap pastikan nomor telepon Anda aktif untuk menerima informasi lebih lanjut</li>
                    <li>Simpan ID Booking Anda untuk referensi</li>
                </ul>
            </div>

            <div class="booking-details">
                <h5 class="mb-3">Detail Pendaftaran</h5>
                
                <div class="detail-row">
                    <span class="text-muted">ID Booking:</span>
                    <strong>#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</strong>
                </div>
                
                <div class="detail-row">
                    <span class="text-muted">Event:</span>
                    <strong>{{ $booking->event->title }}</strong>
                </div>
                
                <div class="detail-row">
                    <span class="text-muted">Nama Pemesan:</span>
                    <strong>{{ $booking->booker_name }}</strong>
                </div>
                
                <div class="detail-row">
                    <span class="text-muted">No. Telepon:</span>
                    <strong>{{ $booking->booker_phone }}</strong>
                </div>
                
                @if($booking->booker_email)
                <div class="detail-row">
                    <span class="text-muted">Email:</span>
                    <strong>{{ $booking->booker_email }}</strong>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="text-muted">Tanggal Event:</span>
                    <strong>{{ \Carbon\Carbon::parse($booking->event->event_date)->format('d M Y') }}</strong>
                </div>
                
                <div class="detail-row">
                    <span class="text-muted">Waktu:</span>
                    <strong>{{ \Carbon\Carbon::parse($booking->event->event_time)->format('H:i') }} WIB</strong>
                </div>
                
                <div class="detail-row">
                    <span class="text-muted">Lokasi:</span>
                    <strong>{{ $booking->event->session_type === 'online' ? 'Online Event' : $booking->event->place }}</strong>
                </div>
                
                <div class="detail-row">
                    <span class="text-muted">Jumlah Peserta:</span>
                    <strong>{{ $booking->participant_count }} orang</strong>
                </div>
                
                @if($booking->discount_amount > 0)
                <div class="detail-row">
                    <span class="text-muted">Subtotal:</span>
                    <strong>{{ $booking->formatted_total_price }}</strong>
                </div>
                
                <div class="detail-row text-success">
                    <span>Diskon:</span>
                    <strong>- {{ $booking->formatted_discount_amount }}</strong>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="text-muted">Total Harga:</span>
                    <strong class="text-primary">{{ $booking->formatted_final_price }}</strong>
                </div>
                
                <div class="detail-row">
                    <span class="text-muted">Status:</span>
                    <span class="status-badge bg-{{ $booking->status_badge['class'] }}">
                        {{ $booking->status_badge['text'] }}
                    </span>
                </div>
            </div>

            <div class="participant-list">
                <h6 class="mb-3"><i class="bi bi-people"></i> Daftar Peserta:</h6>
                @foreach($booking->participants as $index => $participant)
                <div class="participant-item">
                    <strong>{{ $index + 1 }}. {{ $participant->full_name }}</strong>
                    <div class="text-muted small">
                        <i class="bi bi-telephone"></i> {{ $participant->phone_number }}
                        @if($participant->email)
                        | <i class="bi bi-envelope"></i> {{ $participant->email }}
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('front.events.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Event
                </a>
            </div>

            <div class="alert alert-warning mt-4">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Harap Diperhatikan!</strong><br>
                Simpan ID Booking <strong>#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</strong> Anda sebagai bukti pendaftaran. 
                Admin akan menghubungi Anda untuk informasi pembayaran dan konfirmasi kehadiran.
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-scroll to top on page load
    window.scrollTo(0, 0);
</script>
@endpush