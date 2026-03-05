@extends('layouts.admin')

@section('content')
<style>
    :root {
        --theme-primary: #0C2C5A;
        --theme-accent: #f4b704;
        --theme-danger: #cb2786;
    }

    .btn-sporty-primary {
        background-color: var(--theme-primary);
        border-color: var(--theme-primary);
        color: white;
        border-radius: 0.75rem;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease-in-out;
    }

    .btn-sporty-primary:hover {
        background-color: #081f3f;
        border-color: #081f3f;
        color: white;
    }

    .badge-status-active {
        background-color: rgba(0, 97, 122, 0.15);
        color: #00617a;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
    }

    .badge-status-inactive {
        background-color: rgba(203, 39, 134, 0.15);
        color: #cb2786;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
    }
</style>

<div class="container-fluid px-4" style="min-height: 100vh;">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid var(--theme-primary);">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-eye fs-2" style="color: var(--theme-primary);"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Detail Jenis Konsultasi Gratis</h2>
                        <p class="text-muted mb-0">Lihat detail jenis konsultasi gratis.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Nama Jenis Konsultasi</label>
                    <h4 class="fw-bold" style="color: var(--theme-primary);">{{ $type->name }}</h4>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Status</label>
                    <div>
                        @php
                            $statusClass = $type->status === 'active' ? 'badge-status-active' : 'badge-status-inactive';
                            $statusText = $type->status === 'active' ? 'Aktif' : 'Nonaktif';
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label text-muted">Deskripsi</label>
                    <p class="mb-0">{!! nl2br(e($type->description)) !!}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label text-muted">Jumlah Jadwal</label>
                    <span class="badge bg-info fs-6">{{ $type->schedules->count() }} jadwal</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Schedules Table --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4" style="color: var(--theme-primary);">Jadwal Tersedia</h5>
            
            @if($type->schedules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Partisipan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($type->schedules as $schedule)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('H:i') }}</td>
                                    <td>{{ $schedule->current_bookings }}/{{ $schedule->max_participants }}</td>
                                    <td>
                                        @if($schedule->is_available)
                                            <span class="badge badge-status-active">Tersedia</span>
                                        @else
                                            <span class="badge badge-status-inactive">Penuh</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Belum ada jadwal untuk jenis konsultasi ini.</p>
            @endif

            <div class="mt-4">
                <a href="{{ route('admin.free-consultation.types.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

