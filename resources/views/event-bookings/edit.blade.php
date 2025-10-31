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
                        <i class="fas fa-edit fs-2" style="color: #0C2C5A;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Edit Booking Event</h2>
                        <p class="text-muted mb-0">Update status booking dan pembayaran.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('admin.event-bookings.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.event-bookings.update', $eventBooking->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Booking Information --}}
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3">Informasi Booking</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">ID Booking</label>
                                    <input type="text" class="form-control" value="#{{ $eventBooking->id }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Event</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $eventBooking->event->title ?? 'Event Tidak Ditemukan' }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">User</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $eventBooking->booker_display_name }}" readonly>
                                    <small class="text-muted">{{ $eventBooking->booker_display_email }}</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jumlah Peserta</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $eventBooking->participant_count }} peserta" readonly>
                                </div>
                            </div>

                            {{-- Status Settings --}}
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3">Pengaturan Status</h5>

                                <div class="mb-3">
                                    <label for="booking_status" class="form-label">Status Booking *</label>
                                    <select name="booking_status" id="booking_status" class="form-select" required>
                                        <option value="menunggu pembayaran" {{ $eventBooking->booking_status == 'menunggu pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                        <option value="terdaftar" {{ $eventBooking->booking_status == 'terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                                        <option value="dibatalkan" {{ $eventBooking->booking_status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="payment_status" class="form-label">Status Pembayaran *</label>
                                    <select name="payment_status" id="payment_status" class="form-select" required>
                                        <option value="unpaid" {{ ($eventBooking->effective_payment_status ?? 'unpaid') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="paid" {{ ($eventBooking->effective_payment_status ?? 'unpaid') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="partial" {{ ($eventBooking->effective_payment_status ?? 'unpaid') == 'partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="cancelled" {{ ($eventBooking->effective_payment_status ?? 'unpaid') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @if($eventBooking->is_guest)
                                        <div class="form-text text-info">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Guest booking - status pembayaran disimpan langsung di booking.
                                        </div>
                                    @else
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Registered user - status pembayaran diupdate di invoice.
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="contact_preference" class="form-label">Preferensi Kontak</label>
                                    <select name="contact_preference" id="contact_preference" class="form-select" required>
                                        <option value="chat_and_call" {{ $eventBooking->contact_preference == 'chat_and_call' ? 'selected' : '' }}>Telepon & WhatsApp</option>
                                        <option value="chat_only" {{ $eventBooking->contact_preference == 'chat_only' ? 'selected' : '' }}>Hanya WhatsApp</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Participant Attendance --}}
                        @if($eventBooking->participants->count() > 0)
                        <hr class="my-4">
                        <h5 class="fw-bold mb-3">Status Kehadiran Peserta</h5>
                        
                        <div class="row">
                            @foreach($eventBooking->participants as $participant)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $participant->full_name }}</h6>
                                        <p class="card-text mb-2">
                                            <small>Telepon: {{ $participant->phone_number }}</small><br>
                                            <small>Email: {{ $participant->email ?? '-' }}</small>
                                        </p>
                                        <div class="form-group">
                                            <label class="form-label">Status Kehadiran</label>
                                            <select name="participants[{{ $participant->id }}][attendance_status]" 
                                                    class="form-select form-select-sm">
                                                <option value="pending" {{ $participant->attendance_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="hadir" {{ $participant->attendance_status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                <option value="tidak_hadir" {{ $participant->attendance_status == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.event-bookings.index') }}" class="btn btn-outline-secondary px-4">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Update Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection