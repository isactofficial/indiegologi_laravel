@extends('layouts.app')

@section('content')
{{-- CSS khusus untuk halaman profil --}}
<style>
    /* Indiegologi Brand Colors */
    :root {
        --indiegologi-primary: #0C2C5A; /* Biru Tua - Classy, Pointed */
        --indiegologi-accent: #F4B704; /* Emas - Memorable */
        --indiegologi-light-bg: #F5F7FA; /* Background yang bersih */
        --indiegologi-dark-text: #212529; /* Warna teks gelap utama */
        --indiegologi-light-text: #ffffff; /* Warna teks terang */
        --indiegologi-muted-text: #6c757d; /* Warna teks abu-abu */
        --indiegologi-success: #28a745; /* Warna untuk status sukses */
        --indiegologi-danger: #dc3545; /* Warna untuk status bahaya/error */
        --indiegologi-info: #17a2b8; /* Warna untuk status info */
        --indiegologi-primary-rgb: 12, 44, 90;
        --indiegologi-accent-rgb: 244, 183, 4; /* Added for rgba usage */
    }

    .container-profile {
        width: 100%;
        max-width: 960px;
        margin: 0 auto;
        padding: 1rem 0; /* Memberi padding di dalam container */
        /* Pastikan container-profile cukup tinggi agar tidak ada overflow tersembunyi */
        min-height: calc(100vh - var(--navbar-height, 0px) - var(--footer-height, 0px)); /* Sesuaikan tinggi */
    }

    /* Main title for the entire profile page */
    .profile-page-main-title {
        font-weight: 700;
        font-size: 2.2rem; /* Make it larger for main title */
        color: var(--indiegologi-primary);
        margin-bottom: 2rem;
        text-align: center;
    }

    /* Section subtitle for sections within cards (e.g., Informasi Dasar) */
    .profile-section-subtitle {
        font-weight: 600; /* Slightly less bold than main title */
        font-size: 1.4rem; /* Appropriate size for a section header */
        color: var(--indiegologi-primary);
        margin-bottom: 1.5rem; /* Consistent spacing */
        text-align: center;
    }

    /* Outer wrapper for profile basic info */
    .profile-section-wrapper {
        position: relative; /* Untuk positioning tombol edit */
        padding: 2.5rem; /* Padding sesuai info-card default */
        margin-bottom: 2rem; /* Jarak antar card */
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        background: #ffffff;
        border: 1px solid #e9ecef;
    }

    /* Outer wrapper for appointment section */
    .appointment-section-wrapper {
        padding: 2.5rem; /* Padding sesuai info-card default */
        margin-bottom: 2rem; /* Jarak antar card */
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        background: #ffffff;
        border: 1px solid #e9ecef;
    }

    /* Style for the edit profile icon */
    .edit-profile-icon {
        position: absolute;
        top: 1.5rem; /* Jarak dari atas */
        right: 1.5rem; /* Jarak dari kanan */
        font-size: 1.3rem; /* Ukuran ikon */
        color: var(--indiegologi-primary); /* Use primary color directly for visibility */
        background-color: rgba(var(--indiegologi-accent-rgb), 0.2); /* Add a light accent background */
        border: 1px solid rgba(var(--indiegologi-primary-rgb), 0.1); /* Add a subtle border */
        transition: all 0.3s ease;
        padding: 0.5rem; /* Area klik yang lebih besar */
        border-radius: 50%; /* Membuat area klik bulat */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        z-index: 10; /* Pastikan ikon di atas elemen lain */
        box-sizing: border-box; /* Ensure padding/border don't push it out */
    }

    .edit-profile-icon:hover {
        color: var(--indiegologi-light-text); /* Text color on hover */
        background-color: var(--indiegologi-primary); /* Solid primary background on hover */
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(var(--indiegologi-primary-rgb), 0.2); /* Add shadow on hover */
    }

    /* Adjust profile photo container to align well */
    .profile-photo-container {
        margin-bottom: 1.5rem; /* Space below photo */
    }

    /* Inner wrapper for basic info rows to manage padding if parent has larger padding */
    .info-card-inner {
        padding: 0 1rem; /* Horizontal padding for the inner content, keeping it aligned with other elements */
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f5;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 0.9rem;
        color: var(--indiegologi-muted-text);
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .info-value {
        font-size: 1.05rem;
        color: var(--indiegologi-dark-text);
        font-weight: 600;
    }

    .btn-primary {
        background-color: var(--indiegologi-primary);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(var(--indiegologi-primary-rgb), 0.2);
        transition: all 0.3s ease;
        color: var(--indiegologi-light-text);
    }

    .btn-primary:hover {
        background-color: #082142;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(var(--indiegologi-primary-rgb), 0.3);
    }

    .btn-secondary-outline {
        background-color: transparent;
        border: 1px solid var(--indiegologi-primary);
        color: var(--indiegologi-primary);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-secondary-outline:hover {
        background-color: var(--indiegologi-primary);
        color: var(--indiegologi-light-text);
        text-decoration: none;
    }

    /* Appointment Card Specific Styles */
    .appointment-card {
        padding: 1.5rem 2rem; /* Lebih ringkas */
        margin-bottom: 1rem; /* Jarak antar list item */
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); /* Lebih ringan untuk item individual */
        background: #ffffff;
        border: 1px solid #f5f5f5;
    }

    .appointment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem; /* Lebih ringkas */
        padding-bottom: 0.5rem; /* Lebih ringkas */
        border-bottom: 1px dashed #e9ecef;
    }

    .appointment-title {
        font-size: 1.2rem; /* Sedikit lebih kecil */
        font-weight: 700;
        color: var(--indiegologi-primary);
    }

    .appointment-status {
        font-size: 0.8rem; /* Lebih kecil */
        font-weight: 600;
        padding: 0.2rem 0.7rem; /* Lebih ringkas */
        border-radius: 16px; /* Lebih bulat */
        text-transform: uppercase;
    }

    .status-paid {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--indiegologi-success);
    }

    .status-unpaid {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--indiegologi-danger);
    }

    .status-pending {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .status-dibatalkan {
        background-color: rgba(108, 117, 125, 0.1); /* Grayish for cancelled */
        color: var(--indiegologi-muted-text);
    }

    .appointment-details {
        display: grid; /* Menggunakan grid untuk layout detail */
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Responsif 2 kolom */
        gap: 0.75rem 1.5rem; /* Jarak antar item */
        margin-bottom: 1.25rem;
    }

    .detail-item {
        margin-bottom: 0; /* Hilangkan margin bawah default */
    }

    .detail-label {
        font-size: 0.8rem; /* Lebih kecil */
        color: var(--indiegologi-muted-text);
        margin-bottom: 0; /* Hilangkan margin bawah */
    }

    .detail-value {
        font-size: 0.9rem; /* Lebih kecil */
        color: var(--indiegologi-dark-text);
        font-weight: 500;
    }

    .appointment-actions {
        display: flex;
        gap: 0.5rem; /* Jarak antar tombol lebih kecil */
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .appointment-actions .btn {
        padding: 0.5rem 1rem; /* Tombol lebih ringkas */
        font-size: 0.85rem;
        min-width: unset; /* Hilangkan lebar minimum */
    }

    .alert {
        border-radius: 12px;
        font-weight: 500;
    }

    .alert-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--indiegologi-success);
    }

    /* Pagination styles */
    .pagination-controls {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 2rem;
        gap: 1rem;
        padding: 0 2.5rem; /* Match card padding for alignment */
    }

    .pagination-button {
        background-color: var(--indiegologi-primary);
        color: var(--indiegologi-light-text);
        border: none;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(var(--indiegologi-primary-rgb), 0.1);
    }

    .pagination-button:hover:not(:disabled) {
        background-color: #082142;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(var(--indiegologi-primary-rgb), 0.2);
    }

    .pagination-button:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
        opacity: 0.7;
        box-shadow: none;
    }

    .page-info {
        font-size: 1rem;
        font-weight: 500;
        color: var(--indiegologi-dark-text);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .profile-section-wrapper, .appointment-section-wrapper {
            padding: 1.5rem;
        }
        .profile-page-main-title {
            font-size: 1.8rem;
        }
        .profile-section-subtitle {
            font-size: 1.2rem;
        }
        .info-value, .appointment-title {
            font-size: 1rem;
        }
        .appointment-details {
            grid-template-columns: 1fr; /* Satu kolom di tablet dan mobile */
        }
        .appointment-actions {
            justify-content: center;
        }
        .edit-profile-icon {
            top: 15px; /* Sesuaikan posisi ikon di mobile */
            right: 15px;
            font-size: 1.3rem;
        }
        .info-card-inner {
            padding: 0; /* No horizontal padding on mobile */
        }
        .pagination-controls {
            padding: 0 1.5rem; /* Adjust padding on mobile */
        }
    }

    @media (max-width: 576px) {
        .profile-section-wrapper, .appointment-section-wrapper {
            padding: 1rem;
        }
        .profile-page-main-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .profile-section-subtitle {
            font-size: 1rem;
        }
        .info-label, .detail-label {
            font-size: 0.75rem;
        }
        .info-value, .detail-value, .appointment-title {
            font-size: 0.85rem;
        }
        .btn-primary, .btn-secondary-outline, .appointment-actions .btn, .pagination-button {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
        .page-info {
            font-size: 0.9rem;
        }
    }
    .no-appointment-message { /* Styling for the empty state message */
        padding: 2rem;
        text-align: center;
    }
</style>

{{-- Konten utama halaman profil --}}
<div class="container-profile">
    <!-- Notifikasi berhasil update data -->
    @if(session('success'))
    <div class="alert alert-success text-center mb-4">
        {{ session('success') }}
    </div>
    @endif

    {{-- Profile Main Title --}}
    <div class="profile-page-main-title">Profile Pengguna</div>

    {{-- Profile Information Section (Wrapped in a card) --}}
    <div class="profile-section-wrapper mx-auto">
        {{-- Edit Profile Icon --}}
        <a href="{{ route('profile.edit') }}" class="edit-profile-icon" title="Edit Profile">
            <i class="fas fa-edit"></i>
        </a>
        <div class="text-center mb-4">
            <div class="mx-auto rounded-circle overflow-hidden shadow-sm profile-photo-container" style="width:120px; height:120px; background:#e0e0e0; border: 3px solid var(--indiegologi-primary);">
                @if(optional($user->profile)->profile_photo)
                <img src="{{ asset('storage/' . $user->profile->profile_photo) }}" alt="Profile Photo"
                    class="w-100 h-100 object-fit-cover">
                @else
                <div class="d-flex align-items-center justify-content-center h-100 text-muted bg-light">
                    <i class="fas fa-user-circle" style="font-size: 3rem; color: #b0b0b0;"></i>
                </div>
                @endif
            </div>
            <h2 class="h5 mt-3 mb-1 fw-bold text-dark">{{ $user->name }}</h2>
            <p class="text-muted">{{ $user->email }}</p>
        </div>

        {{-- Informasi Dasar --}}
        <div class="profile-section-subtitle">Informasi Dasar</div>
        <div class="info-card-inner">
            <div class="info-row">
                <div>
                    <div class="info-label">Nama</div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">Tanggal Lahir</div>
                    <div class="info-value">{{ optional($user->profile)->birthdate ? \Carbon\Carbon::parse($user->profile->birthdate)->translatedFormat('d F Y') : '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">Jenis Kelamin</div>
                    <div class="info-value">{{ optional($user->profile)->gender ?? '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">Nomor Telepon</div>
                    <div class="info-value">{{ optional($user->profile)->phone_number ?? '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">Media Sosial</div>
                    <div class="info-value">{{ optional($user->profile)->social_media ?? '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <p class="info-label mb-1">Deskripsi Singkat</p>
                    <p class="info-value">{{ optional($user->profile)->description ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Appointment Section (Wrapped in a new card) --}}
    <div class="text-center profile-page-main-title mt-5">Jadwal Appointment</div> {{-- Adjusted main title for section --}}
    <div class="appointment-section-wrapper mx-auto">
        <div id="appointments-list">
            @forelse($user->invoices as $index => $invoice)
            <div class="appointment-card" data-index="{{ $index }}">
                <div class="appointment-header">
                    <h3 class="appointment-title">{{ $invoice->session_type ?? 'Paket Konsultasi' }}</h3>
                    @php
                        $displayPaymentStatus = $invoice->payment_status;
                        switch (Str::lower($invoice->payment_status)) {
                            case 'paid':
                                $displayPaymentStatus = 'Dibayar';
                                break;
                            case 'unpaid':
                                $displayPaymentStatus = 'Belum Dibayar';
                                break;
                            case 'pending':
                                $displayPaymentStatus = 'Sedang Diproses';
                                break;
                            case 'dibatalkan':
                                $displayPaymentStatus = 'Dibatalkan';
                                break;
                        }
                    @endphp
                    <span class="appointment-status status-{{ Str::slug($invoice->payment_status) }}">
                        {{ $displayPaymentStatus }}
                    </span>
                </div>
                <div class="appointment-details">
                    <div class="detail-item">
                        <div class="detail-label">Nomor Invoice</div>
                        <div class="detail-value">{{ $invoice->invoice_no ?? '-' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Invoice</div>
                        <div class="detail-value">{{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat('d F Y') : '-' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Jatuh Tempo</div>
                        <div class="detail-value">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d F Y') : '-' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tipe Pembayaran</div>
                        @php
                            $displayPaymentType = $invoice->payment_type;
                            if ($invoice->payment_type === 'dp') {
                                $displayPaymentType = 'Pembayaran Muka (DP)';
                            } elseif ($invoice->payment_type === 'full_payment') {
                                $displayPaymentType = 'Pembayaran Penuh';
                            }
                        @endphp
                        <div class="detail-value">{{ $displayPaymentType ?? '-' }}</div>
                    </div>
                </div>
                <div class="appointment-actions">
                    <a href="#" class="btn btn-secondary-outline">Print</a>
                    @if(Str::lower($invoice->payment_status) !== 'paid')
                        <a href="#" class="btn btn-primary">Bayar Sekarang</a>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center text-muted no-appointment-message">
                <p>Anda belum memiliki jadwal appointment.</p>
                <a href="#" class="btn btn-primary">Pesan Appointment Pertama Anda</a>
            </div>
            @endforelse
        </div>

        @if(count($user->invoices) > 0)
        <div class="pagination-controls">
            <button id="prevPage" class="pagination-button">&laquo; Sebelumnya</button>
            <span id="pageInfo" class="page-info"></span>
            <button id="nextPage" class="pagination-button">Selanjutnya &raquo;</button>
        </div>
        @endif
    </div>

</div>

{{-- Skrip JavaScript untuk paginasi --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const appointmentsList = document.getElementById('appointments-list');
        if (appointmentsList) {
            const appointmentCards = appointmentsList.querySelectorAll('.appointment-card');
            const prevButton = document.getElementById('prevPage');
            const nextPageButton = document.getElementById('nextPage');
            const pageInfoSpan = document.getElementById('pageInfo');

            const itemsPerPage = 5;
            let currentPage = 1;
            const totalPages = Math.ceil(appointmentCards.length / itemsPerPage);

            function showPage(page) {
                currentPage = page;
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;

                appointmentCards.forEach((card, index) => {
                    if (index >= startIndex && index < endIndex) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (pageInfoSpan) {
                    pageInfoSpan.textContent = `Halaman ${currentPage} dari ${totalPages}`;
                }
                if (prevButton) {
                    prevButton.disabled = currentPage === 1;
                }
                if (nextPageButton) {
                    nextPageButton.disabled = currentPage === totalPages;
                }
            }

            if (prevButton) {
                prevButton.addEventListener('click', () => {
                    if (currentPage > 1) {
                        showPage(currentPage - 1);
                    }
                });
            }

            if (nextPageButton) {
                nextPageButton.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        showPage(currentPage + 1);
                    }
                });
            }

            // Initial load
            if (appointmentCards.length > 0) {
                showPage(1);
            } else {
                if (prevButton && nextPageButton && pageInfoSpan) {
                    prevButton.style.display = 'none';
                    nextPageButton.style.display = 'none';
                    pageInfoSpan.style.display = 'none';
                }
            }
        }
    });
</script>
@endsection
