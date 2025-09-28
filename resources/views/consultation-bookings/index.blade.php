@extends('layouts.admin')

@section('content')
<style>
    :root {
        --theme-primary: #0C2C5A;
        --theme-accent: #f4b704;
        --theme-danger: #cb2786;
    }

    /* Tombol Utama */
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

    /* Custom Badge Styles */
    .badge-status {
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
        font-size: 0.8rem;
    }
    .badge-pending, .badge-menunggu-pembayaran { background-color: rgba(255, 193, 7, 0.2); color: #856404; }
    .badge-paid, .badge-selesai { background-color: rgba(40, 167, 69, 0.2); color: #155724; }
    .badge-unpaid, .badge-failed { background-color: rgba(220, 53, 69, 0.2); color: #721c24; }
    .badge-terdaftar { background-color: rgba(23, 162, 184, 0.2); color: #0c5460; }
    .badge-ongoing { background-color: rgba(0, 123, 255, 0.2); color: #004085; }
    .badge-dibatalkan { background-color: rgba(108, 117, 125, 0.2); color: #383d41; }
    .badge-secondary { background-color: #e2e3e5; color: #383d41; }

    /* --- Responsive Styles --- */
    @media (max-width: 992px) {
        /* Sembunyikan tabel di mobile */
        .table-responsive {
            display: none;
        }

        /* Tampilkan card di mobile */
        .mobile-booking-cards {
            display: block;
        }

        /* Penyesuaian padding agar card lebih lebar */
        .card-body {
            padding: 1rem !important;
        }
        .container-fluid {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        .booking-card {
            background: white;
            border-radius: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
            padding: 1.25rem;
        }

        .booking-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .booking-user-info .user-name {
            font-weight: 600;
            color: var(--theme-primary);
            font-size: 1.1rem;
        }
        .booking-user-info .invoice-no {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .booking-card-body .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }
        .booking-card-body .detail-item .label {
            color: #6c757d;
            font-weight: 500;
        }
        .booking-card-body .detail-item .value {
            font-weight: 600;
            text-align: right;
        }

        .booking-card-body .session-status-container {
            margin-bottom: 0.75rem;
        }

        .booking-card-footer {
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
            display: flex;
            gap: 0.5rem;
        }
        .booking-card-footer .btn, .booking-card-footer form {
            flex: 1;
        }
    }

    @media (min-width: 993px) {
        /* Sembunyikan card di desktop */
        .mobile-booking-cards {
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
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Manajemen Booking Konsultasi</h2>
                        <p class="text-muted mb-0">Kelola semua booking konsultasi dari klien Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end">
            <a href="{{ route('admin.consultation-bookings.create') }}" class="btn btn-sporty-primary d-flex align-items-center px-4 py-2">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Buat Booking Baru</span>
            </a>
        </div>
    </div>

    {{-- Table Container --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success rounded-3 mb-4"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif

            {{-- Desktop Table View --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">User</th>
                            <th class="py-3">Invoice No.</th>
                            <th class="py-3">Total Harga</th>
                            <th class="py-3">Status Sesi</th>
                            <th class="py-3">Status Pembayaran</th>
                            <th class="py-3">Tanggal Booking</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td class="py-3 fw-semibold" style="color: var(--theme-primary);">{{ $booking->user->name }}</td>
                                <td class="py-3">{{ optional($booking->invoice)->invoice_no ?? 'N/A' }}</td>
                                <td class="py-3">Rp {{ number_format($booking->final_price, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    <span class="badge badge-status badge-{{ str_replace(' ', '-', strtolower($booking->session_status)) }}">
                                        {{ ucwords($booking->session_status) }}
                                    </span>
                                </td>
                                <td class="py-3">
                                     <span class="badge badge-status badge-{{ strtolower(optional($booking->invoice)->payment_status ?? 'secondary') }}">
                                        {{ optional($booking->invoice)->payment_status ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-3">{{ $booking->created_at->format('d M Y') }}</td>
                                {{-- PERBAIKAN: Mengembalikan 4 tombol aksi untuk Desktop --}}
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        @if ($booking->user)
                                            <a href="{{ route('admin.users.show', $booking->user->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                                title="Lihat Profil User">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.consultation-bookings.show', $booking->id) }}"
                                            class="btn btn-sm btn-outline-info rounded-pill px-3"
                                            title="Lihat Invoice">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                        <a href="{{ route('admin.consultation-bookings.edit', $booking->id) }}"
                                            class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                            title="Edit Booking">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.consultation-bookings.destroy', $booking->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)"
                                                class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                title="Hapus Booking">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-box-open me-2"></i>Tidak ada booking konsultasi yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards View --}}
            <div class="mobile-booking-cards">
                @forelse($bookings as $booking)
                    <div class="booking-card">
                        <div class="booking-card-header">
                            <div class="booking-user-info">
                                <div class="user-name">{{ $booking->user->name }}</div>
                                <div class="invoice-no">{{ optional($booking->invoice)->invoice_no ?? 'N/A' }}</div>
                            </div>
                            <div class="booking-price">
                                <div class="fw-bold fs-5" style="color: var(--theme-primary);">Rp {{ number_format($booking->final_price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="booking-card-body">
                            <div class="session-status-container">
                                <span class="badge badge-status badge-{{ str_replace(' ', '-', strtolower($booking->session_status)) }}">
                                    {{ ucwords($booking->session_status) }}
                                </span>
                            </div>

                            <div class="detail-item">
                                <span class="label">Status Pembayaran</span>
                                <span class="value">
                                    <span class="badge badge-status badge-{{ strtolower(optional($booking->invoice)->payment_status ?? 'secondary') }}">
                                        {{ optional($booking->invoice)->payment_status ?? 'N/A' }}
                                    </span>
                                </span>
                            </div>
                             <div class="detail-item">
                                <span class="label">Tanggal Booking</span>
                                <span class="value">{{ $booking->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                         {{-- PERBAIKAN: Mengembalikan 4 tombol aksi untuk Mobile --}}
                         <div class="booking-card-footer">
                            @if ($booking->user)
                                <a href="{{ route('admin.users.show', $booking->user->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Profil User"><i class="fas fa-eye"></i></a>
                            @endif
                            <a href="{{ route('admin.consultation-bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Invoice"><i class="fas fa-file-invoice"></i></a>
                            <a href="{{ route('admin.consultation-bookings.edit', $booking->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Booking"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.consultation-bookings.destroy', $booking->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger w-100" title="Hapus Booking"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-box-open me-2"></i>Tidak ada booking konsultasi yang ditemukan.
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: "Yakin ingin menghapus booking ini?",
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
    }
</script>
@endsection
