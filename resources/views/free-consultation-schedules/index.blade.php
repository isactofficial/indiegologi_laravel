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

        /* --- Responsive Styles --- */
        @media (max-width: 768px) {

            /* Sembunyikan tabel di mobile */
            .table-responsive {
                display: none;
            }

            /* Tampilkan card di mobile */
            .mobile-schedule-cards {
                display: block;
            }

            /* Penyesuaian padding */
            .card-body {
                padding: 1rem !important;
            }

            .container-fluid {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            .schedule-card {
                background: white;
                border-radius: 1rem;
                margin-bottom: 1rem;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                border: 1px solid #f0f0f0;
                overflow: hidden;
            }

            .schedule-card-content {
                padding: 1rem;
            }

            .schedule-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--theme-primary);
                margin-bottom: 0.75rem;
            }

            .schedule-meta {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
                font-size: 0.9rem;
            }

            .schedule-detail {
                font-weight: 500;
                color: #333;
            }

            .schedule-actions {
                display: flex;
                gap: 0.5rem;
                margin-top: 1rem;
            }

            .schedule-actions .btn,
            .schedule-actions form {
                flex: 1;
            }
        }

        @media (min-width: 769px) {
            .mobile-schedule-cards {
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
                            <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Manajemen Jadwal Konsultasi
                                Gratis</h2>
                            <p class="text-muted mb-0">Kelola semua jadwal layanan konsultasi gratis yang tersedia.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Button --}}
        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-end">
                <a href="{{ route('admin.free-consultation.schedules.create') }}"
                    class="btn btn-sporty-primary d-flex align-items-center px-4 py-2">
                    <i class="fas fa-plus me-2"></i>
                    <span class="fw-semibold">Tambah Jadwal Baru</span>
                </a>
            </div>
        </div>

        {{-- Container --}}
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-custom-success rounded-3 mb-4"><i
                            class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">Nama Layanan</th>
                                <th class="py-3 text-center">Klien</th>
                                <th class="py-3 text-center">Ketersediaan</th>
                                <th class="py-3">Jadwal</th>
                                <th class="py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                                <tr>
                                    <td class="py-3 fw-semibold" style="color: var(--theme-primary);">
                                        {{ $schedule->type->name ?? 'N/A' }}
                                    </td>
                                    <td class="py-3 text-center">
                                        {{ $schedule->current_bookings }} / {{ $schedule->max_participants }}
                                    </td>
                                    <td class="py-3 text-center">
                                        @php
                                            $isAvailable = $schedule->is_available && ($schedule->current_bookings < $schedule->max_participants);
                                            $availabilityClass = $isAvailable ? 'bg-success' : 'bg-danger';
                                            $availabilityText = $isAvailable ? 'available' : 'unavailable';
                                            @endphp
                                        <span class="badge {{ $availabilityClass }}">{{ $availabilityText }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="">
                                            {{ $schedule->formatted_date }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $schedule->formatted_time }}
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex gap-2">
                                            {{-- <a href="{{ route('admin.free-consultation.schedules.show', $schedule->id) }}"
                                                class="btn btn-sm btn-outline-info rounded-pill px-3"
                                                style="border-color: #00617a; color: #00617a;" title="Lihat Detail"><i
                                                    class="fas fa-eye"></i></a> --}}
                                            <a href="{{ route('admin.free-consultation.schedules.edit', $schedule->id) }}"
                                                class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                                style="border-color: #f4b704; color: #f4b704;" title="Edit"><i
                                                    class="fas fa-edit"></i></a>
                                            <form
                                                action="{{ route('admin.free-consultation.schedules.destroy', $schedule->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete(event, this.parentElement)"
                                                    class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                    style="border-color: #cb2786; color: #cb2786;" title="Hapus"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-box-open me-2"></i>Tidak ada jadwal konsultasi gratis yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards View --}}
                <div class="mobile-schedule-cards">
                    @forelse($schedules as $schedule)
                        <div class="schedule-card">
                            <div class="schedule-card-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="schedule-title">
                                        <div class="fw-semibold fw-normal my-1">Layanan:
                                            {{ $schedule->type->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $schedule->formatted_date }} | {{ $schedule->formatted_time }}
                                        </div>
                                    </div>
                                    @php
                                        $isAvailable = $schedule->is_available && ($schedule->current_bookings < $schedule->max_participants);
                                        $availabilityClass = $isAvailable ? 'bg-success' : 'bg-danger';
                                        $availabilityText = $isAvailable ? 'Tersedia' : 'Penuh';
                                    @endphp
                                    <span class="badge {{ $availabilityClass }}">{{ $availabilityText }}</span>
                                </div>

                                <hr class="my-2">

                                <div class="d-flex justify-content-between my-2">
                                    <span class="text-muted">Kapasitas Maks:</span>
                                    <span class="schedule-detail">{{ $schedule->max_participants }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Telah Dipesan:</span>
                                    <span class="schedule-detail">{{ $schedule->current_bookings }}</span>
                                </div>

                                <div class="schedule-actions">
                                    <a href="{{ route('admin.free-consultation.schedules.show', $schedule->id) }}"
                                        class="btn btn-sm btn-outline-info" style="border-color: #00617a; color: #00617a;"
                                        title="Lihat Detail"><i class="fas fa-eye"></i></a>

                                    <a href="{{ route('admin.free-consultation.schedules.edit', $schedule->id) }}"
                                        class="btn btn-sm btn-outline-secondary" style="border-color: #f4b704; color: #f4b704;"
                                        title="Edit"><i class="fas fa-edit"></i></a>

                                    <form action="{{ route('admin.free-consultation.schedules.destroy', $schedule->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(event, this.parentElement)"
                                            class="btn btn-sm btn-outline-danger w-100"
                                            style="border-color: #cb2786; color: #cb2786;" title="Hapus"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-box-open me-2"></i>Tidak ada jadwal konsultasi gratis yang ditemukan.
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $schedules->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(event, form) {
            event.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: "Yakin ingin menghapus jadwal ini?",
                    text: "Anda tidak akan bisa mengembalikannya!",
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
            } else {
                if (window.confirm("Yakin ingin menghapus jadwal ini? Anda tidak akan bisa mengembalikannya!")) {
                    form.submit();
                }
            }
        }
    </script>
@endsection