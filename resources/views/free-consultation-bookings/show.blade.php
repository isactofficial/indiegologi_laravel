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
                            <i class="fas fa-headset fs-2" style="color: #0C2C5A;"></i>
                        </div>
                        <div>
                            <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Detail Booking Konsultasi</h2>
                            <p class="text-muted mb-0">Informasi lengkap tentang booking konsultasi.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.consultation-bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                        <a href="#" class="btn btn-primary">
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
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-body p-4">
                    <div class="row">
                        {{-- Informasi Jadwal Konsultasi --}}
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">Informasi Jadwal</h5>
                            @if ($freeConsultationBooking->schedule)
                            <div class="mb-3">
                                <strong>Tipe Konsultasi:</strong> 
                                {{ $freeConsultationBooking->schedule->type->name ?? 'N/A' }}
                            </div>
                            <div class="mb-3">
                                <strong>Tanggal sesi:</strong> 
                                {{ $freeConsultationBooking->scheduled_at ? \Carbon\Carbon::parse($freeConsultationBooking->scheduled_at)->format('d M Y') : 'N/A' }} 
                            </div>
                            <div class="mb-3">
                                <strong>Waktu sesi:</strong> 
                                {{ $freeConsultationBooking->scheduled_at ? \Carbon\Carbon::parse($freeConsultationBooking->scheduled_at)->format('H:i') : 'N/A' }} WIB
                            </div>
                            <div class="mb-3">
                                <strong>Tipe Sesi:</strong> 
                                {{ ucfirst($freeConsultationBooking->session_type ?? 'N/A') }}
                            </div>
                            @if ($freeConsultationBooking->session_type === 'Offline' && $freeConsultationBooking->offline_address)
                            <div class="mb-3">
                                <strong>Alamat Offline:</strong> 
                                <p class="text-wrap small">{{ $freeConsultationBooking->offline_address }}</p>
                            </div>
                            @endif
                            {{-- Informasi slot dari jadwal yang dipesan --}}
                            {{-- <div class="mb-3">
                                <strong>Kapasitas Jadwal:</strong> 
                                {{ $freeConsultationBooking->schedule->current_bookings ?? 0 }} / {{ $freeConsultationBooking->schedule->max_participants ?? 'N/A' }}
                            </div> --}}
                            @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>Jadwal konsultasi tidak ditemukan.
                            </div>
                            @endif
                        </div>
                        
                        {{-- Informasi Pemesan (Menggunakan kolom booker_name/phone/email yang di-store) --}}
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">Informasi Pemesan</h5>
                            <div class="mb-3">
                                <strong>Nama:</strong> 
                                {{ $freeConsultationBooking->booker_name ?? ($freeConsultationBooking->user->name ?? 'N/A') }}
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong> 
                                {{ $freeConsultationBooking->booker_email ?? ($freeConsultationBooking->user->email ?? 'N/A') }}
                            </div>
                            <div class="mb-3">
                                <strong>Telepon:</strong> 
                                {{ $freeConsultationBooking->booker_phone ?? ($freeConsultationBooking->user->phone_number ?? 'N/A') }}
                            </div>
                            <div class="mb-3">
                                <strong>Preferensi Kontak:</strong>
                                @php
                                $contactText = [
                                'chat_and_call' => 'Telepon & WhatsApp',
                                'chat_only' => 'Hanya WhatsApp'
                                ];
                                @endphp
                                <span class="badge bg-info">
                                    {{ $contactText[$freeConsultationBooking->contact_preference] ?? $freeConsultationBooking->contact_preference }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Participants --}}
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Daftar Peserta ({{ $freeConsultationBooking->participants->count() }})</h5>
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
                                @forelse($freeConsultationBooking->participants as $participant)
                                <tr>
                                    <td>
                                        {{ $participant->full_name }} 
                                        @if($participant->is_booker)
                                        <span class="badge bg-success ms-1">Pemesan</span>
                                        @endif
                                    </td>
                                    <td>{{ $participant->phone_number }}</td>
                                    <td>{{ $participant->email ?? '-' }}</td>
                                    <td>
                                        @php
                                        $attendanceStatus = $participant->attendance_status ?? 'pending';
                                        $attendanceClass = [
                                        'hadir' => 'bg-success',
                                        'tidak_hadir' => 'bg-danger',
                                        'pending' => 'bg-warning'
                                        ][$attendanceStatus];
                                        @endphp
                                        <span class="badge {{ $attendanceClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $attendanceStatus)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada peserta terdaftar.</td>
                                </tr>
                                @endforelse
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
                        <strong>ID Booking:</strong> #{{ $freeConsultationBooking->id }}
                    </div>
                    <div class="mb-3">
                        <strong>Status Booking:</strong>
                        @php
                            $bookingStatus = strtolower($freeConsultationBooking->booking_status);
                            $statusClass = [
                            'confirmed' => 'bg-success'
                            ][(string)$bookingStatus];
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $bookingStatus)) }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Tanggal Dibuat:</strong> {{ $freeConsultationBooking->created_at->format('d M Y H:i') }}
                    </div>

                    <hr>

                    {{-- In the pricing section --}}
                    <div class="mb-2">
                        <strong>Harga Awal Layanan:</strong>
                        Rp {{ number_format($freeConsultationBooking->total_base_price ?? 0, 0, ',', '.') }}
                    </div>
                    
                    @if(isset($freeConsultationBooking->participant_count) && $freeConsultationBooking->participant_count >= 1)
                    <div class="mb-2">
                        <strong>Jumlah peserta:</strong> {{ $freeConsultationBooking->participant_count }}
                    </div>
                    @endif
                    
                    <div class="mb-2">
                        <strong>Subtotal:</strong> Rp {{ number_format($freeConsultationBooking->total_price ?? 0, 0, ',', '.') }}
                    </div>
                    @if(($freeConsultationBooking->discount_amount ?? 0) > 0)
                    <div class="mb-2 text-success">
                        <strong>Diskon:</strong> -Rp {{ number_format($freeConsultationBooking->discount_amount, 0, ',', '.') }}
                    </div>
                    @endif
                    <div class="mb-3 fw-bold fs-5 text-success">
                        <strong>Total Final:</strong> Rp {{ number_format($freeConsultationBooking->final_price ?? 0, 0, ',', '.') }}
                    </div>

                    {{-- Tombol Konfirmasi Pembayaran --}}
                    @if($bookingStatus === 'menunggu pembayaran' && ($paymentStatus) === 'paid')
                    <form action="{{ route('admin.consultation-bookings.confirm-payment', $freeConsultationBooking->id) }}" method="POST" class="mt-3">
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
                        <a href="{{ route('admin.free-consultation-bookings.edit', $freeConsultationBooking->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Edit Status
                        </a>
                        @if($bookingStatus !== 'dibatalkan')
                        <form action="{{ route('admin.free-consultation-bookings.destroy', $freeConsultationBooking->id) }}" method="POST">
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