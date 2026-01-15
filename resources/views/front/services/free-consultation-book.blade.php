@extends('layouts.app') 

@section('title', 'Pendaftaran Konsultasi Gratis')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --indiegologi-primary: #0c2c5a;
        }

        .participant-form {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .participant-form:hover {
            border-color: var(--indiegologi-primary);
            background: #e3f2fd;
        }
        
        .participant-form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        /* Gaya original Ringkasan Pesanan */
        .price-summary {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 2rem;
        }

        .form-label {
            font-weight: 500;
        }

        .form-control, .form-select {
            border-radius: 6px;
        }

        .btn-primary-custom {
            background-color: var(--indiegologi-primary);
            border-color: var(--indiegologi-primary);
            transition: all 0.2s;
            color: white; 
        }
        .btn-primary-custom:hover {
            background-color: #081d3d;
            border-color: #081d3d;
            color: white;
        }
        
        .form-section h5 {
            font-weight: 600;
        }

        /* Detail Tambahan untuk Diskon */
        .price-discount-tag {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .original-price {
            font-family: 'Poppins', sans-serif !important;
            text-decoration: line-through;
            color: #adb5bd;
            font-size: 0.9rem;
        }
        .discounted-price {
            color: #28a745;
            font-weight: 700;
        }
        .percent-badge {
            font-family: 'Poppins', sans-serif !important;
            background: #ffc107;
            color: #000;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 700;
        }
    </style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        
        {{-- Form Data Pemesan --}}
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Pendaftaran Konsultasi Gratis</h1>
                <a href="{{ route('front.layanan') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Layanan
                </a>
            </div>

            {{-- Ringkasan Jadwal --}}
            <div class="form-section mb-4 participant-form" id="consultation-summary-box">
                <h4 id="consultation-title" style="color: var(--indiegologi-primary);">Memuat Jenis Konsultasi...</h4>
                <p class="text-muted mb-0" id="schedule-display">
                    <i class="bi bi-calendar-event"></i> Tanggal dan Waktu: Memuat... |
                    <i class="bi bi-check-circle-fill text-success"></i> Sesi: Online/Offline
                </p>
            </div>

            <form id="freeBookingForm" method="POST" action="{{ route('front.free.booking.confirm') }}">
                @csrf

                {{-- HIDDEN INPUTS untuk data jadwal --}}
                <input type="hidden" name="free_consultation_type_id" id="free_consultation_type_id">
                <input type="hidden" name="free_consultation_schedule_id" id="free_consultation_schedule_id">
                <input type="hidden" name="booked_date" id="booked_date">
                <input type="hidden" name="booked_time" id="booked_time">

                <div class="form-section mb-4 participant-form">
                    <h5>Data Pemesan (Peserta 1)</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap Pemesan *</label>
                                <input type="text" class="form-control" name="nama_lengkap" required
                                    placeholder="Masukkan nama lengkap pemesan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nomor Telepon Pemesan *</label>
                                <input type="tel" class="form-control" name="nomor_telepon" required
                                    placeholder="Contoh: 081234567890">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Email Pemesan *</label>
                                <input type="email" class="form-control" name="email_pemesan" required
                                    placeholder="email@contoh.com">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Jumlah Peserta Dropdown --}}
                <div class="form-section mb-4 participant-form">
                    <h5>Jumlah Peserta</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Berapa banyak peserta? *</label>
                            <select class="form-select" id="participantCount" name="participant_count" required>
                                <option value="1">1 peserta (Sesi Pribadi)</option>
                                @for($i = 2; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} peserta</option>
                                @endfor
                            </select>
                            <div class="form-text">
                                Total peserta termasuk data pemesan (maksimal 5 orang).
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Dynamic Participant Forms Container --}}
                <div id="participantForms" class="mb-4">
                </div>
                
                {{-- Pilihan Sesi --}}
                <div class="form-section mb-4 participant-form">
                    <h5>Detail Konsultasi</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Pilihan Sesi *</label>
                            <select class="form-select" id="session_type" name="session_type" required>
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                            </select>
                        </div>
                        <div class="col-12 mt-3" id="offline-address-container" style="display:none;">
                            <label class="form-label">Alamat untuk Sesi Offline *</label>
                            <textarea class="form-control" name="offline_address" id="offline_address" rows="3" 
                                placeholder="Masukkan alamat lengkap untuk sesi offline"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section mb-4 participant-form">
                    <h5>Preferensi Kontak</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="contact_preference" value="chat_and_call" id="pref_chat_call" checked>
                        <label class="form-check-label" for="pref_chat_call">Telepon & WhatsApp (Disarankan)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="contact_preference" value="chat_only" id="pref_chat_only">
                        <label class="form-check-label" for="pref_chat_only">Hanya WhatsApp</label>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-secondary me-md-2" onclick="window.location.href='{{ route('front.layanan') }}'">Batal</button>
                    <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                        <i class="bi bi-check-circle"></i> Konfirmasi Booking
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="price-summary">
                <h5 style="color: var(--indiegologi-primary); font-weight: 700;">Ringkasan Pesanan</h5>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Harga per sesi:</span>
                    <div class="price-discount-tag text-end">
                        <span class="original-price" id="summaryOriginalPrice">Rp 0</span>
                        <span class="discounted-price">FREE</span>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Status Promo:</span>
                    <span class="percent-badge text-white">DISKON 100%</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Jumlah peserta:</span>
                    <span id="summaryParticipantCount" class="fw-bold">1</span>
                </div>
                <div class="price-breakdown mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between mb-2">
                        <strong class="text-dark">Total Pembayaran:</strong>
                        <strong id="totalAmount" class="text-success fs-5">Rp 0</strong>
                    </div>
                </div>
                <div class="mt-4 p-2 rounded-2" style="background-color: #e8f5e9; border: 1px solid #c8e6c9;">
                    <p class="mb-0 small text-success fw-bold text-center">
                        <i class="bi bi-shield-check"></i> Pendaftaran gratis. Aman tanpa perlu memasukkan data perbankan atau pembayaran apa pun.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const freeBookingDataKey = 'freeBookingData';
        const form = document.getElementById('freeBookingForm');
        const submitBtn = document.getElementById('submitBtn');
        const sessionTypeSelect = document.getElementById('session_type');
        const offlineAddressContainer = document.getElementById('offline-address-container');
        const offlineAddressTextarea = document.getElementById('offline_address');
        const participantCount = document.getElementById('participantCount');
        const participantForms = document.getElementById('participantForms');
        
        let bookingData = null;

        // Fungsi pembantu format rupiah
        function formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value).replace('IDR', 'Rp');
        }
        
        // --- Load Data dari LocalStorage ---
        try {
            const dataString = localStorage.getItem(freeBookingDataKey);
            if (!dataString) throw new Error('No booking data found.');
            bookingData = JSON.parse(dataString);
            
            // Isi data tampilan teks
            const serviceName = bookingData.service_name ? bookingData.service_name.replace('Konsultasi Gratis: ', '') : 'Memuat Jenis Konsultasi...';
            document.getElementById('consultation-title').textContent = serviceName;
            document.getElementById('schedule-display').innerHTML = 
                `<i class="bi bi-calendar-event"></i> ${bookingData.schedule_display} | 
                 <i class="bi bi-check-circle-fill text-success"></i> Sesi: ${bookingData.session_type}`;
            
            // SINKRONISASI HARGA CORET (Base Price)
            if(bookingData.base_price) {
                document.getElementById('summaryOriginalPrice').textContent = formatCurrency(bookingData.base_price);
            }

            // Isi hidden inputs
            document.getElementById('free_consultation_type_id').value = bookingData.free_consultation_type_id;
            document.getElementById('free_consultation_schedule_id').value = bookingData.free_consultation_schedule_id;
            document.getElementById('booked_date').value = bookingData.booked_date;
            document.getElementById('booked_time').value = bookingData.booked_time;

            // Set default pilihan sesi
            sessionTypeSelect.value = bookingData.session_type || 'Online';
            handleSessionTypeToggle();

        } catch (error) {
            console.error("Error loading booking data:", error);
            window.location.href = '{{ route('front.layanan') }}';
            return;
        }
        
        // --- Logika Update Price Summary ---
        function updatePriceSummary() {
            const count = parseInt(participantCount.value) || 1;
            document.getElementById('summaryParticipantCount').textContent = count;
            document.getElementById('totalAmount').textContent = 'Rp 0';
        }
        
        // --- Logika Dynamic Participant Forms ---
        function generateParticipantForms() {
            const count = parseInt(participantCount.value) || 1;
            participantForms.innerHTML = '';
            
            if (count > 1) {
                for (let i = 1; i < count; i++) {
                    const formHtml = `
                        <div class="participant-form">
                            <div class="participant-form-header">
                                <h6 class="mb-0">Data Peserta ${i + 1}</h6>
                                <span class="badge bg-primary">Wajib diisi</span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap *</label>
                                        <input type="text" name="participants[${i}][full_name]" class="form-control" required placeholder="Masukkan nama lengkap">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Telepon *</label>
                                        <input type="tel" name="participants[${i}][phone_number]" class="form-control" required placeholder="Contoh: 081234567890">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="participants[${i}][email]" class="form-control" placeholder="email@contoh.com (opsional)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    participantForms.innerHTML += formHtml;
                }
            }
            updatePriceSummary();
        }

        participantCount.addEventListener('change', generateParticipantForms);
        
        function handleSessionTypeToggle() {
            if (sessionTypeSelect.value === 'Offline') {
                offlineAddressContainer.style.display = 'block';
                offlineAddressTextarea.setAttribute('required', true);
            } else {
                offlineAddressContainer.style.display = 'none';
                offlineAddressTextarea.removeAttribute('required');
                offlineAddressTextarea.value = '';
            }
            if(bookingData) {
                bookingData.session_type = sessionTypeSelect.value;
                document.getElementById('schedule-display').innerHTML = 
                    `<i class="bi bi-calendar-event"></i> ${bookingData.schedule_display} | 
                     <i class="bi bi-check-circle-fill text-success"></i> Sesi: ${sessionTypeSelect.value}`;
            }
        }
        sessionTypeSelect.addEventListener('change', handleSessionTypeToggle);

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#0c2c5a'
                    }).then(() => {
                        localStorage.removeItem(freeBookingDataKey);
                        window.location.href = '{{ route("front.layanan") }}';
                    });
                } else {
                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Terjadi kesalahan server.', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        generateParticipantForms();
    });
</script>
@endpush