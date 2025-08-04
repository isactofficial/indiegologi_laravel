@extends('layouts.admin')

@section('content')
<style>
    /* Custom Badge Styles */
    .badge-pending {
        background-color: #ffc107;
        color: #343a40;
    }
    .badge-paid {
        background-color: #28a745;
        color: #fff;
    }
    .badge-failed {
        background-color: #dc3545;
        color: #fff;
    }
    .badge-menunggu-pembayaran {
        background-color: #ffc107;
        color: #343a40;
    }
    .badge-terdaftar {
        background-color: #17a2b8;
        color: #fff;
    }
    .badge-ongoing {
        background-color: #007bff;
        color: #fff;
    }
    .badge-selesai {
        background-color: #28a745;
        color: #fff;
    }
    .badge-dibatalkan {
        background-color: #6c757d;
        color: #fff;
    }
    .badge-secondary {
        background-color: #6c757d;
        color: #fff;
    }
</style>
<div class="container-fluid px-4" style="min-height: 100vh;">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #00617a;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(0, 97, 122, 0.1);">
                        <i class="fas fa-calendar-check fs-2" style="color: #00617a;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #00617a;">Manajemen Booking Konsultasi</h2>
                        <p class="text-muted mb-0">Kelola semua jadwal booking konsultasi.</p>
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

    {{-- Table --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success rounded-3 alert-custom-success mb-4"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="py-3">No. Invoice</th>
                            <th class="py-3">Pemesan</th>
                            <th class="py-3">Layanan</th>
                            <th class="py-3">Harga Akhir</th>
                            <th class="py-3">Status Pembayaran</th>
                            <th class="py-3">Status Sesi</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="py-3 fw-semibold">{{ $booking->invoice->invoice_no ?? 'N/A' }}</td>
                                <td class="py-3">{{ $booking->user->name ?? 'User Dihapus' }}</td>
                                <td class="py-3">{{ $booking->services->first()->title ?? 'N/A' }}</td>
                                <td class="py-3">Rp {{ number_format($booking->final_price, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    @php
                                        // Mengambil status pembayaran dari invoice
                                        $paymentStatus = strtolower($booking->invoice->payment_status ?? 'N/A');
                                        $paymentStatusClass = 'badge-' . $paymentStatus;
                                    @endphp
                                    <span class="badge {{ $paymentStatusClass }}">{{ $booking->invoice->payment_status ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3">
                                    @php
                                        // Mengambil status sesi dari booking
                                        $sessionStatus = str_replace(' ', '-', strtolower($booking->session_status ?? 'N/A'));
                                        $sessionStatusClass = 'badge-' . $sessionStatus;
                                    @endphp
                                    <span class="badge {{ $sessionStatusClass }}">{{ ucfirst($booking->session_status ?? 'N/A') }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.consultation-bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3" style="border-color: #00617a; color: #00617a;" title="Lihat Invoice">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                        <a href="{{ route('admin.consultation-bookings.edit', $booking->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="border-color: #f4b704; color: #f4b704;" title="Edit Booking">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.consultation-bookings.destroy', $booking->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="border-color: #cb2786; color: #cb2786;" title="Hapus Booking">
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
