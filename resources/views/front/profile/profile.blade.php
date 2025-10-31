@extends('layouts.app')

@section('content')
{{-- CSS khusus untuk halaman profil --}}
<style>
    /* Indiegologi Brand Colors */
    :root {
        --indiegologi-primary: #0C2C5A;
        --indiegologi-accent: #F4B704;
        --indiegologi-light-bg: #F5F7FA;
        --indiegologi-dark-text: #212529;
        --indiegologi-light-text: #ffffff;
        --indiegologi-muted-text: #6c757d;
        --indiegologi-success: #28a745;
        --indiegologi-danger: #dc3545;
        --indiegologi-info: #17a2b8;
        --indiegologi-primary-rgb: 12, 44, 90;
        --indiegologi-accent-rgb: 244, 183, 4;
    }

    .container-profile {
        width: 100%;
        max-width: 960px;
        margin: 0 auto;
        padding: 1rem 0;
        min-height: calc(100vh - var(--navbar-height, 0px) - var(--footer-height, 0px));
    }

    .profile-page-main-title {
        font-weight: 700;
        font-size: 2.2rem;
        color: var(--indiegologi-primary);
        margin-bottom: 2rem;
        text-align: center;
    }

    .profile-section-subtitle {
        font-weight: 600;
        font-size: 1.4rem;
        color: var(--indiegologi-primary);
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .profile-section-wrapper {
        position: relative;
        padding: 2.5rem;
        margin-bottom: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        background: #ffffff;
        border: 1px solid #e9ecef;
    }

    .appointment-section-wrapper {
        padding: 2.5rem;
        margin-bottom: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        background: #ffffff;
        border: 1px solid #e9ecef;
    }

    .edit-profile-icon {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        font-size: 1.3rem;
        color: var(--indiegologi-primary);
        background-color: rgba(var(--indiegologi-accent-rgb), 0.2);
        border: 1px solid rgba(var(--indiegologi-primary-rgb), 0.1);
        transition: all 0.3s ease;
        padding: 0.5rem;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        box-sizing: border-box;
    }

    .edit-profile-icon:hover {
        color: var(--indiegologi-light-text);
        background-color: var(--indiegologi-primary);
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(var(--indiegologi-primary-rgb), 0.2);
    }

    .profile-photo-container {
        margin-bottom: 1.5rem;
    }

    .info-card-inner {
        padding: 0 1rem;
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

    .appointment-card {
        padding: 1.5rem 2rem;
        margin-bottom: 1rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        background: #ffffff;
        border: 1px solid #f5f5f5;
    }

    .appointment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px dashed #e9ecef;
    }

    .appointment-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--indiegologi-primary);
    }

    .appointment-status {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.2rem 0.7rem;
        border-radius: 16px;
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
        background-color: rgba(108, 117, 125, 0.1);
        color: var(--indiegologi-muted-text);
    }

    .appointment-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.75rem 1.5rem;
        margin-bottom: 1.25rem;
    }

    .detail-item {
        margin-bottom: 0;
    }

    .detail-label {
        font-size: 0.8rem;
        color: var(--indiegologi-muted-text);
        margin-bottom: 0;
    }

    .detail-value {
        font-size: 0.9rem;
        color: var(--indiegologi-dark-text);
        font-weight: 500;
    }

    .appointment-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .appointment-actions .btn {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        min-width: unset;
    }

    .alert {
        border-radius: 12px;
        font-weight: 500;
    }

    .alert-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--indiegologi-success);
    }

    .pagination-controls {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 2rem;
        gap: 1rem;
        padding: 0 2.5rem;
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

    .no-appointment-message {
        padding: 2rem;
        text-align: center;
    }

    @media (max-width: 768px) {

        .profile-section-wrapper,
        .appointment-section-wrapper {
            padding: 1.5rem;
        }

        .profile-page-main-title {
            font-size: 1.8rem;
        }

        .profile-section-subtitle {
            font-size: 1.2rem;
        }

        .info-value,
        .appointment-title {
            font-size: 1rem;
        }

        .appointment-details {
            grid-template-columns: 1fr;
        }

        .appointment-actions {
            justify-content: center;
        }

        .edit-profile-icon {
            top: 15px;
            right: 15px;
            font-size: 1.3rem;
        }

        .info-card-inner {
            padding: 0;
        }

        .pagination-controls {
            padding: 0 1.5rem;
        }
    }

    @media (max-width: 576px) {

        .profile-section-wrapper,
        .appointment-section-wrapper {
            padding: 1rem;
        }

        .profile-page-main-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .profile-section-subtitle {
            font-size: 1rem;
        }

        .info-label,
        .detail-label {
            font-size: 0.75rem;
        }

        .info-value,
        .detail-value,
        .appointment-title {
            font-size: 0.85rem;
        }

        .btn-primary,
        .btn-secondary-outline,
        .appointment-actions .btn,
        .pagination-button {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }

        .page-info {
            font-size: 0.9rem;
        }
    }
</style>

{{-- KONTEN UTAMA HALAMAN PROFIL --}}
<div class="container-profile">
    @if(session('success'))
    <div class="alert alert-success text-center mb-4">
        {{ session('success') }}
    </div>
    @endif

    {{-- Profile Main Title --}}
    <div class="profile-page-main-title">Profile Pengguna</div>

    {{-- Profile Information Section --}}
    <div class="profile-section-wrapper mx-auto">
        {{-- Edit Profile Icon --}}
        <a href="{{ route('profile.edit') }}" class="edit-profile-icon" title="Edit Profile">
            <i class="fas fa-edit"></i>
        </a>
        <div class="text-center mb-4">
            <div class="mx-auto rounded-circle overflow-hidden shadow-sm profile-photo-container" style="width:120px; height:120px; background:#e0e0e0; border: 3px solid var(--indiegologi-primary);">
                @if(optional($user->profile)->profile_photo)
                <img src="{{ asset('storage/' . $user->profile->profile_photo) }}" alt="Profile Photo" class="w-100 h-100 object-fit-cover">
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

            <div class="info-row">
                <div>
                    <div class="info-label">Zodiak</div>
                    <div class="info-value">{{ optional($user->profile)->zodiac ?? '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">Shio & Elemen</div>
                    <div class="info-value">{{ optional($user->profile)->shio_element ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Appointment Section --}}
    <div class="text-center profile-page-main-title mt-5">Jadwal Appointment</div>
    <div class="appointment-section-wrapper mx-auto">

        {{-- Appointment Summary Card --}}
        {{-- Appointment Summary Card --}}
        @php
        $appointmentStats = $stats;
        @endphp

        {{-- Detailed Appointments List - GROUPED BY INVOICE --}}
        <div id="appointments-list">
            @forelse($appointmentData as $index => $invoiceGroup)
            @php
            $invoice = $invoiceGroup['invoice'];
            $items = $invoiceGroup['items'];
            $totalItems = count($items);
            $status = $invoice ? $invoice->payment_status : 'unknown';
            @endphp

            <div class="appointment-card" data-index="{{ $index }}" style="padding: 2rem; margin-bottom: 2rem;">
                <div class="appointment-header">
                    <h3 class="appointment-title">
                        📋 Invoice: {{ $invoice->invoice_no ?? 'No Invoice' }}
                    </h3>

                    {{-- Status payment --}}
                    @php
                    $displayPaymentStatus = $status;
                    switch (Str::lower($status)) {
                    case 'paid': $displayPaymentStatus = 'Dibayar'; break;
                    case 'unpaid': $displayPaymentStatus = 'Belum Dibayar'; break;
                    case 'pending': $displayPaymentStatus = 'Sedang Diproses'; break;
                    case 'dibatalkan': $displayPaymentStatus = 'Dibatalkan'; break;
                    case 'terdaftar': $displayPaymentStatus = 'Terdaftar'; break;
                    case 'menunggu pembayaran': $displayPaymentStatus = 'Menunggu Pembayaran'; break;
                    }
                    @endphp
                    <span class="appointment-status status-{{ Str::slug($status) }}">
                        {{ $displayPaymentStatus }}
                    </span>
                </div>

                {{-- Invoice Summary --}}
                <div class="appointment-details" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="detail-item">
                        <div class="detail-label">Total Items</div>
                        <div class="detail-value" style="font-size: 1.1rem; font-weight: bold;">
                            {{ $totalItems }} Item{{ $totalItems > 1 ? 's' : '' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Total Pembayaran</div>
                        <div class="detail-value" style="font-size: 1.1rem; font-weight: bold; color: var(--indiegologi-primary);">
                            @if($invoice && $invoice->final_amount)
                            Rp {{ number_format($invoice->final_amount, 0, ',', '.') }}
                            @else
                            Rp 0
                            @endif
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Tanggal Invoice</div>
                        <div class="detail-value">
                            @if($invoice && $invoice->invoice_date)
                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat('d F Y') }}
                            @else
                            -
                            @endif
                        </div>
                    </div>

                    @if($invoice && $invoice->due_date)
                    <div class="detail-item">
                        <div class="detail-label">Batas Pembayaran</div>
                        <div class="detail-value">
                            {{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d F Y') }}
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Items List --}}
                <div style="border-top: 2px solid #f0f2f5; padding-top: 1.5rem;">
                    <h4 style="font-weight: 600; color: var(--indiegologi-primary); margin-bottom: 1rem;">
                        📦 Detail Items ({{ $totalItems }})
                    </h4>

                    <div style="display: grid; gap: 1rem;">
                        @foreach($items as $itemIndex => $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid var(--indiegologi-primary);">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    @if($item['type'] === 'event')
                                    <span style="background: #0C2C5A; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">EVENT</span>
                                    @elseif($item['type'] === 'service')
                                    <span style="background: #F4B704; color: #0C2C5A; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">SERVICE</span>
                                    @else
                                    <span style="background: #28a745; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">KONSULTASI</span>
                                    @endif
                                    <strong style="color: var(--indiegologi-dark-text);">{{ $item['title'] }}</strong>
                                </div>

                                @if($item['type'] === 'event' && $item['participant_count'] > 1)
                                <div style="font-size: 0.9rem; color: var(--indiegologi-muted-text);">
                                    👥 {{ $item['participant_count'] }} peserta
                                </div>
                                @endif
                            </div>

                            <div style="text-align: right;">
                                @if($item['type'] === 'event')
                                <div style="font-size: 1.5rem;">🎯</div>
                                @elseif($item['type'] === 'service')
                                <div style="font-size: 1.5rem;">💼</div>
                                @else
                                <div style="font-size: 1.5rem;">🆓</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="appointment-actions" style="margin-top: 1.5rem; border-top: 1px dashed #e9ecef; padding-top: 1.5rem;">
                    @if($invoice)
                    <a href="{{ route('invoice.download', $invoice->id) }}" class="btn btn-secondary-outline">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>
                    @endif

                    @if($invoice && Str::lower($status) !== 'paid' && Str::lower($status) !== 'dibatalkan')
                    <a href="{{ route('invoice.show', $invoice->id) }}" class="btn btn-primary">
                        <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-calendar-times fa-3x text-muted"></i>
                </div>
                <h5 class="text-muted mb-3">Belum Ada Appointment</h5>
                <p class="text-muted mb-4">Anda belum memiliki riwayat appointment.</p>
                <a href="{{ route('front.layanan') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-plus me-2"></i>Jelajahi Layanan
                </a>
                <a href="{{ route('front.events.index') }}" class="btn btn-secondary-outline ms-2">
                    <i class="fas fa-calendar-alt me-2"></i>Lihat Events
                </a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(count($appointmentData) > 0)
        <div class="pagination-controls">
            <button id="prevPage" class="pagination-button">&laquo; Sebelumnya</button>
            <span id="pageInfo" class="page-info"></span>
            <button id="nextPage" class="pagination-button">Selanjutnya &raquo;</button>
        </div>
        @endif
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