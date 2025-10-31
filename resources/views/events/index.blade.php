@extends('layouts.admin')

@section('content')
{{-- Palet warna utama untuk konsistensi --}}
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
        box-shadow: 0 4px 8px rgba(12, 44, 90, 0.2);
    }
    .btn-sporty-primary:hover {
        background-color: #081f3f;
        border-color: #081f3f;
        transform: translateY(-2px);
        color: white;
    }
    .alert-custom-success {
        background-color: rgba(12, 44, 90, 0.1);
        color: var(--theme-primary);
        border: 1px solid rgba(12, 44, 90, 0.3);
    }

    /* --- Responsive Styles --- */
    @media (max-width: 768px) {
        /* Sembunyikan tabel di mobile */
        .table-responsive {
            display: none;
        }
        /* Tampilkan card di mobile */
        .mobile-event-cards {
            display: block;
        }
        /* Penyesuaian padding */
        .card-body { padding: 1rem !important; }
        .container-fluid {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        .event-card {
            background: white;
            border-radius: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid #f0f0f0;
            overflow: hidden;
        }
        .event-thumbnail {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .event-thumbnail-placeholder {
            width: 100%;
            height: 180px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #f0f0f0;
        }
        .event-card-content {
            padding: 1rem;
        }
        .event-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--theme-primary);
            margin-bottom: 0.75rem;
        }
        .event-meta {
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .event-price {
            font-weight: bold;
            color: var(--theme-primary);
            font-size: 1.1rem;
        }
        .event-actions {
            display: flex;
            gap: 0.5rem;
        }
        .event-actions .btn, .event-actions form {
            flex: 1;
        }
        .participants-badge {
            background: #e8f5e8;
            color: #2e7d32;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
    }

    @media (min-width: 769px) {
        .mobile-event-cards {
            display: none;
        }
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
                        <i class="fas fa-calendar-alt fs-2" style="color: var(--theme-primary);"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Manajemen Events</h2>
                        <p class="text-muted mb-0">Kelola semua event yang tersedia.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end">
            <a href="{{ route('admin.events.create') }}" class="btn btn-sporty-primary d-flex align-items-center px-4 py-2">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Tambah Event Baru</span>
            </a>
        </div>
    </div>

    {{-- Container --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-custom-success rounded-3 mb-4"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif

            {{-- Desktop Table View --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Thumbnail</th>
                            <th class="py-3">Nama Event</th>
                            <th class="py-3">Tanggal & Waktu</th>
                            <th class="py-3">Tempat</th>
                            <th class="py-3">Harga</th>
                            <th class="py-3">Peserta</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td class="py-3">
                                    @if($event->thumbnail)
                                        <img src="{{ asset('storage/' . $event->thumbnail) }}" alt="{{ $event->title }}" class="rounded-3 object-fit-cover shadow-sm" style="width: 120px; height: 80px;">
                                    @else
                                        <div class="bg-light rounded-3 d-flex justify-content-center align-items-center" style="width: 120px; height: 80px;">
                                            <span class="text-muted small">No Image</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 fw-semibold" style="color: var(--theme-primary);">{{ $event->title }}</td>
                                <td class="py-3">
                                    <small class="text-muted">{{ $event->event_date->format('d M Y') }}</small><br>
                                    <small>{{ $event->event_time }}</small>
                                </td>
                                <td class="py-3">
                                    <small>{{ $event->place }}</small><br>
                                    <span class="badge bg-secondary">{{ ucfirst($event->session_type) }}</span>
                                </td>
                                <td class="py-3 fw-bold" style="color: var(--theme-primary);">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3">
                                    <span class="participants-badge">
                                        {{ $event->current_participants }}/{{ $event->max_participants }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    @php
                                        $statusClass = 'bg-secondary';
                                        if ($event->status === 'published') $statusClass = 'bg-success';
                                        if ($event->status === 'cancelled') $statusClass = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($event->status) }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3" style="border-color: #00617a; color: #00617a;" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="border-color: #f4b704; color: #f4b704;" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="border-color: #cb2786; color: #cb2786;" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-calendar-times me-2"></i>Tidak ada event yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards View --}}
            <div class="mobile-event-cards">
                @forelse($events as $event)
                    <div class="event-card">
                        @if($event->thumbnail)
                            <img src="{{ asset('storage/' . $event->thumbnail) }}" alt="{{ $event->title }}" class="event-thumbnail">
                        @else
                            <div class="event-thumbnail-placeholder">
                                <span class="text-muted">No Image</span>
                            </div>
                        @endif
                        <div class="event-card-content">
                            <div class="event-title">{{ $event->title }}</div>
                            <div class="event-meta">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="event-price">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                                    @php
                                        $statusClass = 'bg-secondary';
                                        if ($event->status === 'published') $statusClass = 'bg-success';
                                        if ($event->status === 'cancelled') $statusClass = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($event->status) }}</span>
                                </div>
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-calendar me-1"></i>{{ $event->event_date->format('d M Y') }} • {{ $event->event_time }}
                                </div>
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $event->place }}
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="participants-badge">{{ $event->current_participants }}/{{ $event->max_participants }} peserta</span>
                                    <span class="badge bg-secondary">{{ ucfirst($event->session_type) }}</span>
                                </div>
                            </div>
                            <div class="event-actions">
                                <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-sm btn-outline-info" style="border-color: #00617a; color: #00617a;" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-outline-secondary" style="border-color: #f4b704; color: #f4b704;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger w-100" style="border-color: #cb2786; color: #cb2786;" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-calendar-times me-2"></i>Tidak ada event yang ditemukan.
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: "Yakin ingin menghapus event ini?",
            text: "Semua data event dan pendaftaran akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#cb2786",
            cancelButtonColor: "#808080",
            confirmButtonText: "Ya, Hapus Sekarang!",
            cancelButtonText: "Batalkan",
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
    }
</script>
@endsection