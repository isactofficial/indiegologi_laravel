@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-3">
            {{-- Sidebar Menu --}}
            @include('front.profile.partials.sidebar')
        </div>
        
        <div class="col-lg-9">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 py-3 rounded-4">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                        Riwayat Booking Event
                    </h4>
                </div>
                <div class="card-body p-4">
                    
                    {{-- Upcoming Events --}}
                    @if($upcomingBookings->count() > 0)
                    <div class="mb-5">
                        <h5 class="fw-bold mb-3 text-success">Event Mendatang</h5>
                        @foreach($upcomingBookings as $booking)
                        <div class="card border-success mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="fw-bold mb-1">{{ $booking->event->title }}</h6>
                                        <div class="d-flex flex-wrap gap-3 text-muted small mb-2">
                                            <span>
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $booking->event->event_date->format('d M Y') }}
                                            </span>
                                            <span>
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $booking->event->event_time }}
                                            </span>
                                            <span>
                                                <i class="fas fa-users me-1"></i>
                                                {{ $booking->participant_count }} peserta
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-success me-2">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>
                                            <span class="badge bg-primary">
                                                {{ $booking->event->session_type === 'online' ? 'Online' : 'Offline' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="fw-bold text-success mb-1">
                                            Rp {{ number_format($booking->final_price, 0, ',', '.') }}
                                        </div>
                                        <a href="{{ route('front.events.show', $booking->event->slug) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            Lihat Event
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Past Events --}}
                    @if($pastBookings->count() > 0)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3 text-secondary">Event Terdahulu</h5>
                        @foreach($pastBookings as $booking)
                        <div class="card border-light mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="fw-bold mb-1">{{ $booking->event->title }}</h6>
                                        <div class="d-flex flex-wrap gap-3 text-muted small mb-2">
                                            <span>
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $booking->event->event_date->format('d M Y') }}
                                            </span>
                                            <span>
                                                <i class="fas fa-users me-1"></i>
                                                {{ $booking->participant_count }} peserta
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-secondary me-2">
                                                Selesai
                                            </span>
                                            @if($booking->participants->where('attendance_status', 'hadir')->count() > 0)
                                                <span class="badge bg-success">
                                                    {{ $booking->participants->where('attendance_status', 'hadir')->count() }} hadir
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="fw-bold text-muted mb-1">
                                            Rp {{ number_format($booking->final_price, 0, ',', '.') }}
                                        </div>
                                        <small class="text-muted">
                                            Booking: {{ $booking->created_at->format('d M Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Empty State --}}
                    @if($upcomingBookings->count() == 0 && $pastBookings->count() == 0)
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-calendar-times fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-3">Belum Ada Booking Event</h5>
                        <p class="text-muted mb-4">Anda belum memiliki riwayat booking event.</p>
                        <a href="{{ route('front.events.index') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Jelajahi Event
                        </a>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection