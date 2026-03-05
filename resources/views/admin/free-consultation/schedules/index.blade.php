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

    .badge-available {
        background-color: rgba(25, 135, 84, 0.15);
        color: #198754;
    }

    .badge-full {
        background-color: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
    }

    .mobile-cards {
        display: none;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1rem !important;
        }

        .table-responsive {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .schedule-card {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #f0f0f0;
        }
        
        .schedule-card:last-child {
            margin-bottom: 0;
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
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Manajemen Jadwal Konsultasi Gratis</h2>
                        <p class="text-muted mb-0">Kelola jadwal konsultasi gratis di sini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Tabs --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.free-consultation.types.index') }}">
                        <i class="fas fa-list me-2"></i>Jenis Konsultasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.free-consultation.schedules.index') }}">
                        <i class="fas fa-calendar-alt me-2"></i>Jadwal Konsultasi
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Add Buttons --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-sporty-primary" data-bs-toggle="modal" data-bs-target="#bulkCreateModal">
                <i class="fas fa-plus-square me-2"></i>Buat Massal
            </button>
            <a href="{{ route('admin.free-consultation.schedules.create') }}" class="btn btn-sporty-primary">
                <i class="fas fa-plus me-2"></i>Tambah Satu
            </a>
        </div>
    </div>

    {{-- Table & Cards Container --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success rounded-3 alert-custom-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger rounded-3 mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            {{-- [DESKTOP] Tampilan Tabel --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="py-3">ID</th>
                            <th class="py-3">Jenis</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Waktu</th>
                            <th class="py-3">Partisipan</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="py-3">{{ $schedule->id }}</td>
                                <td class="py-3 fw-semibold text-break" style="color: var(--theme-primary);">{{ $schedule->type->name }}</td>
                                <td class="py-3">{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d M Y') }}</td>
                                <td class="py-3">{{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('H:i') }}</td>
                                <td class="py-3">
                                    @if($schedule->current_bookings >= $schedule->max_participants)
                                        <span class="badge badge-full">{{ $schedule->current_bookings }}/{{ $schedule->max_participants }}</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $schedule->current_bookings }}/{{ $schedule->max_participants }}</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if($schedule->is_available)
                                        @if(\Carbon\Carbon::parse($schedule->scheduled_date)->isPast())
                                            <span class="badge bg-secondary">Expired</span>
                                        @else
                                            <span class="badge badge-available">Tersedia</span>
                                        @endif
                                    @else
                                        <span class="badge badge-status-inactive">Penuh</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.free-consultation.schedules.show', $schedule) }}" class="btn btn-sm btn-outline-info rounded-pill px-3" style="border-color: var(--theme-primary); color: var(--theme-primary);" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.free-consultation.schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="border-color: var(--theme-accent); color: var(--theme-accent);" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.free-consultation.schedules.toggle-availability', $schedule) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $schedule->is_available ? 'danger' : 'success' }} rounded-pill px-3" style="border-color: var(--theme-{{ $schedule->is_available ? 'danger' : 'primary' }}); color: var(--theme-{{ $schedule->is_available ? 'danger' : 'primary' }});" title="{{ $schedule->is_available ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="fas fa-{{ $schedule->is_available ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.free-consultation.schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="border-color: var(--theme-danger); color: var(--theme-danger);" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted"><i class="fas fa-calendar-times me-2"></i>Tidak ada jadwal konsultasi gratis.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- [MOBILE] Tampilan Kartu --}}
            <div class="mobile-cards">
                @forelse($schedules as $schedule)
                    <div class="schedule-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold" style="color: var(--theme-primary);">{{ $schedule->type->name }}</h5>
                            @if($schedule->is_available)
                                @if(\Carbon\Carbon::parse($schedule->scheduled_date)->isPast())
                                    <span class="badge bg-secondary">Expired</span>
                                @else
                                    <span class="badge badge-available">Tersedia</span>
                                @endif
                            @else
                                <span class="badge badge-status-inactive">Penuh</span>
                            @endif
                        </div>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d M Y') }}
                            <i class="fas fa-clock ms-2 me-1"></i> {{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('H:i') }}
                        </p>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-users me-1"></i> Partisipan: {{ $schedule->current_bookings }}/{{ $schedule->max_participants }}
                        </p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.free-consultation.schedules.show', $schedule) }}" class="btn btn-sm btn-outline-info" style="border-color: var(--theme-primary); color: var(--theme-primary);"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.free-consultation.schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-secondary" style="border-color: var(--theme-accent); color: var(--theme-accent);"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.free-consultation.schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger" style="border-color: var(--theme-danger); color: var(--theme-danger);"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted"><i class="fas fa-calendar-times me-2"></i>Tidak ada jadwal konsultasi gratis.</div>
                @endforelse
            </div>

            @if($schedules->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $schedules->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Create Modal -->
<div class="modal fade" id="bulkCreateModal" tabindex="-1" aria-labelledby="bulkCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkCreateModalLabel">Buat Jadwal Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.free-consultation.schedules.bulk-create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="type_id" class="form-label">Jenis Konsultasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="type_id" name="type_id" required>
                            <option value="">Pilih Jenis</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="number_of_schedules" class="form-label">Jumlah Jadwal <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="number_of_schedules" name="number_of_schedules" min="1" max="30" value="5" required>
                    </div>
                    <div class="mb-3">
                        <label for="time" class="form-label">Waktu <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="time" name="time" required>
                    </div>
                    <div class="mb-3">
                        <label for="max_participants" class="form-label">Maksimal Partisipan <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="max_participants" name="max_participants" min="1" max="100" value="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: "Yakin ingin menghapus jadwal ini?",
            text: "Jadwal yang sudah dibooking tidak dapat dihapus!",
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

