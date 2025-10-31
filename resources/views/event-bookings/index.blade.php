@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #0C2C5A;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                        style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-calendar-check fs-2" style="color: #0C2C5A;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Manajemen Booking Event</h2>
                        <p class="text-muted mb-0">Kelola semua pendaftaran event.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end">
            <a href="{{ route('admin.event-bookings.create') }}" class="btn btn-success d-flex align-items-center px-4 py-2">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Buat Booking Baru</span>
            </a>
        </div>
    </div>

    {{-- Bookings Table --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            @if(session('success'))
            <div class="alert alert-success rounded-3 mb-4">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger rounded-3 mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID Booking</th>
                            <th>Event</th>
                            <th>User</th>
                            <th>Jumlah Peserta</th>
                            <th>Total Harga</th>
                            <th>Preferensi Kontak</th>
                            <th>Status Booking</th>
                            <th>Status Pembayaran</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td class="fw-semibold">#{{ $booking->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <!-- @if($booking->event && $booking->event->thumbnail)
                                    <img src="{{ asset('storage/' . $booking->event->thumbnail) }}"
                                        alt="{{ $booking->event->title }}"
                                        class="rounded-3 me-2"
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                    @endif -->
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $booking->event ? Str::limit($booking->event->title, 30) : 'Event Tidak Ditemukan' }}
                                        </div>
                                        @if($booking->event)
                                        <small class="text-muted">{{ $booking->event->event_date->format('d M Y') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{-- FIXED: Handle guest users --}}
                                @if($booking->user)
                                <div class="fw-semibold">{{ $booking->user->name }}</div>
                                <small class="text-muted">{{ $booking->user->email }}</small>
                                @else
                                <div class="fw-semibold">{{ $booking->guest_name ?? 'Guest User' }}</div>
                                <small class="text-muted">{{ $booking->guest_email ?? 'No email' }}</small>
                                <br>
                                <small class="text-muted">{{ $booking->guest_phone ?? 'No phone' }}</small>
                                <span class="badge bg-secondary ms-1">Guest</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $booking->participant_count }} peserta</span>
                            </td>
                            <td class="fw-bold text-success">
                                Rp {{ number_format($booking->final_price, 0, ',', '.') }}
                            </td>
                            <td>
                                @php
                                $contactText = [
                                'chat_and_call' => 'Telepon & WA',
                                'chat_only' => 'Hanya WA'
                                ];
                                @endphp
                                <span class="badge bg-info">
                                    {{ $contactText[$booking->contact_preference] ?? $booking->contact_preference }}
                                </span>
                            </td>
                            <td>
                                @php
                                $statusClass = [
                                'menunggu pembayaran' => 'bg-warning',
                                'terdaftar' => 'bg-success',
                                'hadir' => 'bg-info',
                                'tidak_hadir' => 'bg-secondary',
                                'dibatalkan' => 'bg-danger'
                                ][$booking->booking_status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($booking->booking_status) }}</span>
                            </td>
                            <td>
                                @php
                                // PERBAIKI: Gunakan effective_payment_status untuk menampilkan status yang benar
                                $paymentStatus = $booking->effective_payment_status;
                                $paymentClass = [
                                'paid' => 'bg-success',
                                'pending' => 'bg-warning',
                                'unpaid' => 'bg-danger',
                                'draft' => 'bg-secondary',
                                'cancelled' => 'bg-danger',
                                'dibatalkan' => 'bg-danger'
                                ][$paymentStatus] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $paymentClass }}">
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                                @if($booking->is_guest)
                                <small class="text-muted d-block">Guest Booking</small>
                                @endif
                            </td>
                            <td>
                                <small>{{ $booking->created_at->format('d M Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.event-bookings.show', $booking->id) }}"
                                        class="btn btn-sm btn-outline-info rounded-pill px-3"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.event-bookings.edit', $booking->id) }}"
                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                        title="Edit Status">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($booking->booking_status === 'menunggu pembayaran' && ($booking->invoice->payment_status ?? 'unpaid') === 'paid')
                                    <form action="{{ route('admin.event-bookings.confirm-payment', $booking->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3"
                                            title="Konfirmasi Pembayaran">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif

                                    {{-- DELETE BUTTON --}}
                                    <form action="{{ route('admin.event-bookings.destroy', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3 delete-booking"
                                            title="Hapus Booking"
                                            data-booking-id="{{ $booking->id }}"
                                            data-booking-user="{{ $booking->user ? $booking->user->name : ($booking->guest_name ?? 'Guest') }}"
                                            data-booking-event="{{ $booking->event ? $booking->event->title : 'Event Tidak Ditemukan' }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-calendar-times me-2"></i>Belum ada booking event.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SweetAlert for delete confirmation
        const deleteButtons = document.querySelectorAll('.delete-booking');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const bookingId = this.getAttribute('data-booking-id');
                const userName = this.getAttribute('data-booking-user');
                const eventName = this.getAttribute('data-booking-event');
                const form = this.closest('form');

                Swal.fire({
                    title: 'Hapus Booking Event?',
                    html: `<div class="text-start">
                         <p class="mb-2">Anda akan menghapus booking event:</p>
                         <ul class="text-start">
                           <li><strong>User:</strong> ${userName}</li>
                           <li><strong>Event:</strong> ${eventName}</li>
                           <li><strong>ID Booking:</strong> #${bookingId}</li>
                         </ul>
                         <p class="text-danger mt-2"><i class="fas fa-exclamation-triangle me-2"></i>Tindakan ini tidak dapat dibatalkan!</p>
                       </div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-4',
                        confirmButton: 'rounded-pill px-4',
                        cancelButton: 'rounded-pill px-4'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

<style>
    .delete-booking {
        transition: all 0.3s ease;
    }

    .delete-booking:hover {
        transform: scale(1.05);
        background-color: #dc3545;
        color: white;
    }
</style>
@endpush