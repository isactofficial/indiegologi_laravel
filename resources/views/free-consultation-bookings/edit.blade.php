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
                        <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Edit Booking Konsultasi Gratis</h2>
                        <p class="text-muted mb-0">Update status booking peserta konsultasi gratis.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('admin.free-consultation-bookings.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.free-consultation-bookings.update', $freeConsultationBooking->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Participant Attendance --}}
                        @if($freeConsultationBooking->participants->count() > 0)
                        <hr class="my-4">
                        <h5 class="fw-bold mb-3">Status Kehadiran Peserta</h5>
                        
                        <div class="row">
                            @foreach($freeConsultationBooking->participants as $participant)
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
                            <a href="{{ route('admin.free-consultation-bookings.index') }}" class="btn btn-outline-secondary px-4">
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