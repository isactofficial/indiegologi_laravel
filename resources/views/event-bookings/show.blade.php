@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #0C2C5A;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                            style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                            <i class="fas fa-calendar-check fs-2" style="color: #0C2C5A;"></i>
                        </div>
                        <div>
                            <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Detail Booking Event</h2>
                            <p class="text-muted mb-0">Informasi lengkap tentang booking event.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.event-bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                        <a href="{{ route('admin.event-bookings.edit', $eventBooking->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i> Edit Status
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Main Info --}}
        <div class="col-lg-8">
            {{-- Event & User Info --}}
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-body p-4">
                    <div class="row">
                        {{-- In the Event Information section --}}
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">Informasi Event</h5>
                            @if($eventBooking->event)
                            <div class="mb-3">
                                <strong>Event:</strong> {{ $eventBooking->event->title }}
                            </div>
                            <div class="mb-3">
                                <strong>Tanggal:</strong> {{ $eventBooking->event->event_date->format('d M Y') }}
                            </div>
                            <div class="mb-3">
                                <strong>Waktu:</strong> {{ $eventBooking->event->event_time }}
                            </div>
                            <div class="mb-3">
                                <strong>Lokasi:</strong> {{ $eventBooking->event->session_type === 'online' ? 'Online' : $eventBooking->event->place }}
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>Event tidak ditemukan atau telah dihapus.
                            </div>
                            @endif
                        </div>
                        {{-- In the User Information section --}}
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">Informasi Pemesan</h5>
                            {{-- FIXED: Handle guest users --}}
                            @if($eventBooking->user)
                            <div class="mb-3">
                                <strong>Nama:</strong> {{ $eventBooking->user->name }}
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong> {{ $eventBooking->user->email }}
                            </div>
                            <div class="mb-3">
                                <strong>Telepon:</strong> {{ $eventBooking->booker_phone }}
                            </div>
                            @else
                            <div class="mb-3">
                                <strong>Nama:</strong> {{ $eventBooking->guest_name ?? 'Guest User' }}
                                <span class="badge bg-secondary ms-2">Guest</span>
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong> {{ $eventBooking->guest_email ?? 'No email' }}
                            </div>
                            <div class="mb-3">
                                <strong>Telepon:</strong> {{ $eventBooking->guest_phone ?? 'No phone' }}
                            </div>
                            @endif
                            <div class="mb-3">
                                <strong>Preferensi Kontak:</strong>
                                @php
                                $contactText = [
                                'chat_and_call' => 'Telepon & WhatsApp',
                                'chat_only' => 'Hanya WhatsApp'
                                ];
                                @endphp
                                <span class="badge bg-info">
                                    {{ $contactText[$eventBooking->contact_preference] ?? $eventBooking->contact_preference }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Participants --}}
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Daftar Peserta ({{ $eventBooking->participant_count }})</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($eventBooking->participants as $participant)
                                <tr>
                                    <td>{{ $participant->full_name }}</td>
                                    <td>{{ $participant->phone_number }}</td>
                                    <td>{{ $participant->email ?? '-' }}</td>
                                    <td>
                                        @php
                                        $attendanceClass = [
                                        'hadir' => 'bg-success',
                                        'tidak_hadir' => 'bg-danger',
                                        'pending' => 'bg-warning'
                                        ][$participant->attendance_status];
                                        @endphp
                                        <span class="badge {{ $attendanceClass }}">
                                            {{ ucfirst($participant->attendance_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Booking Summary --}}
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Ringkasan Booking</h5>

                    <div class="mb-3">
                        <strong>ID Booking:</strong> #{{ $eventBooking->id }}
                    </div>
                    <div class="mb-3">
                        <strong>Status Booking:</strong>
                        @php
                        $statusClass = [
                        'menunggu pembayaran' => 'bg-warning',
                        'terdaftar' => 'bg-success',
                        'hadir' => 'bg-info',
                        'tidak_hadir' => 'bg-secondary',
                        'dibatalkan' => 'bg-danger'
                        ][$eventBooking->booking_status];
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($eventBooking->booking_status) }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Status Pembayaran:</strong>
                        @php
                        $paymentClass = [
                        'paid' => 'bg-success',
                        'pending' => 'bg-warning',
                        'unpaid' => 'bg-danger',
                        'draft' => 'bg-secondary',
                        'cancelled' => 'bg-danger'
                        ][$eventBooking->invoice->payment_status ?? 'unpaid'];
                        @endphp
                        <span class="badge {{ $paymentClass }}">
                            {{ ucfirst($eventBooking->invoice->payment_status ?? 'unpaid') }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Tanggal Booking:</strong> {{ $eventBooking->created_at->format('d M Y H:i') }}
                    </div>

                    <hr>

                    {{-- In the pricing section --}}
                    <div class="mb-2">
                        <strong>Harga per peserta:</strong>
                        @if($eventBooking->event)
                        Rp {{ number_format($eventBooking->event->price, 0, ',', '.') }}
                        @else
                        Rp {{ number_format(0, 0, ',', '.') }}
                        @endif
                    </div>
                    <div class="mb-2">
                        <strong>Jumlah peserta:</strong> {{ $eventBooking->participant_count }}
                    </div>
                    <div class="mb-2">
                        <strong>Subtotal:</strong> Rp {{ number_format($eventBooking->total_price, 0, ',', '.') }}
                    </div>
                    @if($eventBooking->discount_amount > 0)
                    <div class="mb-2 text-success">
                        <strong>Diskon:</strong> -Rp {{ number_format($eventBooking->discount_amount, 0, ',', '.') }}
                    </div>
                    @endif
                    <div class="mb-3 fw-bold fs-5 text-success">
                        <strong>Total:</strong> Rp {{ number_format($eventBooking->final_price, 0, ',', '.') }}
                    </div>

                    @if($eventBooking->booking_status === 'menunggu pembayaran' && ($eventBooking->invoice->payment_status ?? 'unpaid') === 'paid')
                    <form action="{{ route('admin.event-bookings.confirm-payment', $eventBooking->id) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-2"></i>Konfirmasi Pembayaran
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Aksi Cepat</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.event-bookings.edit', $eventBooking->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Edit Status
                        </a>
                        @if($eventBooking->booking_status !== 'dibatalkan')
                        <form action="{{ route('admin.event-bookings.cancel', $eventBooking->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger w-100"
                                onclick="return confirm('Yakin ingin membatalkan booking ini?')">
                                <i class="fas fa-times me-2"></i>Batalkan Booking
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection