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

        .participant-form {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .participant-form:hover {
            border-color: #4c51bf;
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
            background-color: #4c51bf;
            border-color: #4c51bf;
            transition: all 0.2s;
            color: white; 
        }
        .btn-primary-custom:hover {
            background-color: #3f448c;
            border-color: #3f448c;
        }
        
        .form-section h5 {
            font-weight: 600;
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
                <h4 id="consultation-title">Memuat Jenis Konsultasi...</h4>
                <p class="text-muted mb-0" id="schedule-display">
                    <i class="bi bi-calendar-event"></i> Tanggal dan Waktu: Memuat... |
                    <i class="bi bi-check-circle-fill text-success"></i> Sesi: Online/Offline
                </p>
                <p class="text-muted mb-0 mt-2 text-primary fw-bold">
                    <i class="bi bi-tag-fill"></i> Biaya: GRATIS
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
                            <div class="invalid-feedback" id="offline-address-error">Alamat wajib diisi untuk sesi Offline.</div>
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
                    <button type="submit" class="btn btn-primary btn-primary-custom" id="submitBtn">
                        <i class="bi bi-check-circle"></i> Konfirmasi Booking Gratis
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="price-summary">
                <h5>Ringkasan Pesanan</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Harga per sesi:</span>
                    <span class="text-success fw-bold">GRATIS</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Jumlah peserta:</span>
                    <span id="summaryParticipantCount">1</span>
                </div>
                <div class="price-breakdown mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total Pembayaran:</strong>
                        <strong id="totalAmount" class="text-success">Rp 0</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        const offlineAddressError = document.getElementById('offline-address-error');
        
        let bookingData = null;
        
        // --- Load Data dari LocalStorage ---
        try {
            const dataString = localStorage.getItem(freeBookingDataKey);
            if (!dataString) throw new Error('No booking data found.');
            bookingData = JSON.parse(dataString);
            
            // Isi data tampilan
            const serviceName = bookingData.service_name ? bookingData.service_name.replace('Pilih Jadwal: ', '') : 'Memuat Jenis Konsultasi...';
            document.getElementById('consultation-title').textContent = serviceName;
            document.getElementById('schedule-display').innerHTML = 
                `<i class="bi bi-calendar-event"></i> ${bookingData.schedule_display} | 
                 <i class="bi bi-check-circle-fill text-success"></i> Sesi: ${bookingData.session_type}`;
            
            // Isi hidden inputs
            document.getElementById('free_consultation_type_id').value = bookingData.free_consultation_type_id;
            document.getElementById('free_consultation_schedule_id').value = bookingData.free_consultation_schedule_id;
            document.getElementById('booked_date').value = bookingData.booked_date;
            document.getElementById('booked_time').value = bookingData.booked_time;

            // Set default pilihan sesi dan alamat jika ada
            sessionTypeSelect.value = bookingData.session_type;
            // Panggil listener untuk inisialisasi tampilan alamat
            handleSessionTypeToggle();

            if (bookingData.offline_address) {
                offlineAddressTextarea.value = bookingData.offline_address;
            }

        } catch (error) {
            console.error("Error loading booking data:", error);
            Swal.fire({
                title: 'Error',
                text: 'Jadwal konsultasi belum dipilih. Kembali ke halaman layanan.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                // Redirect jika data tidak ada
                window.location.href = '{{ route('front.layanan') }}';
            });
            return;
        }
        
        // --- Logika Update Price Summary ---
        function updatePriceSummary() {
            const count = parseInt(participantCount.value) || 1;
            // Update UI elements
            document.getElementById('summaryParticipantCount').textContent = count;
            document.getElementById('totalAmount').textContent = 'Rp 0';
        }
        
        // --- Logika Dynamic Participant Forms ---
        function generateParticipantForms() {
            const count = parseInt(participantCount.value) || 1;
            participantForms.innerHTML = '';
            
            // Hanya generate form jika count > 1 (karena peserta 1 adalah data pemesan)
            if (count > 1) {
                for (let i = 1; i < count; i++) {
                    // Menggunakan index i untuk participants[i] agar berurut dari 1-4 di array backend
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
                                        <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Telepon *</label>
                                        <input type="tel" name="participants[${i}][phone_number]" class="form-control" required placeholder="Contoh: 081234567890">
                                        <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
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

        // Event listener untuk perubahan jumlah peserta
        participantCount.addEventListener('change', generateParticipantForms);
        
        // --- Logika Session Type Toggle ---
        function handleSessionTypeToggle() {
            if (sessionTypeSelect.value === 'Offline') {
                offlineAddressContainer.style.display = 'block';
                // HTML5 required akan ditambahkan/dihapus di client side validation sebelum submit
                offlineAddressTextarea.setAttribute('required', true); 
                offlineAddressTextarea.classList.remove('is-invalid');
            } else {
                offlineAddressContainer.style.display = 'none';
                offlineAddressTextarea.removeAttribute('required');
                offlineAddressTextarea.classList.remove('is-invalid');
                offlineAddressTextarea.value = '';
            }
            // Update data di LocalStorage agar konsisten
            if(bookingData) {
                bookingData.session_type = sessionTypeSelect.value;
                localStorage.setItem(freeBookingDataKey, JSON.stringify(bookingData));
            }
        }
        sessionTypeSelect.addEventListener('change', handleSessionTypeToggle);

        // --- Fungsi Reset Validasi ---
        function resetValidationClasses() {
            form.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
        }

        // --- Form Submission (Menggunakan AJAX POST) ---
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            resetValidationClasses(); // Bersihkan class error

            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';

            // 1. Validasi Sesi Offline
            let isValid = true;
            if (sessionTypeSelect.value === 'Offline' && !offlineAddressTextarea.value.trim()) {
                offlineAddressTextarea.classList.add('is-invalid');
                isValid = false;
            }
            
            // 2. Validasi Peserta Dinamis (Nama & Nomor Telepon)
            const participantInputs = participantForms.querySelectorAll('input[required]');
            participantInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                }
            });

            if (!isValid) {
                 submitBtn.disabled = false;
                 submitBtn.innerHTML = originalText;
                 return Swal.fire('Data Belum Lengkap', 'Harap lengkapi semua isian yang wajib diisi (bertanda *).', 'error');
            }


            // 3. Kirim data ke Controller
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                // Tangani response non-JSON (misalnya error 500)
                if (!response.ok) {
                    return response.json().catch(() => {
                        throw new Error(`Server responded with status ${response.status}.`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Selesai'
                    }).then(() => {
                        localStorage.removeItem(freeBookingDataKey);
                        window.location.href = '{{ route("front.layanan") }}';
                    });
                } else {
                    let errorMessage = data.message || 'Terjadi kesalahan saat mengkonfirmasi booking.';
                    
                    if (data.errors) {
                        // Fokus pada error validasi dari server
                        errorMessage = 'Terjadi kesalahan validasi. Harap periksa kembali form Anda.';
                        for (const field in data.errors) {
                            const inputElement = document.querySelector(`[name="${field}"]`);
                            if (inputElement) {
                                inputElement.classList.add('is-invalid');
                            }
                            // Khusus untuk nested array validation
                            if (field.startsWith('participants')) {
                                const parts = field.split('.'); // participants.1.full_name
                                if (parts.length > 2) {
                                    const participantIndex = parts[1];
                                    const fieldName = parts[2];
                                    const nestedInput = document.querySelector(`input[name="participants[${participantIndex}][${fieldName}]"]`);
                                    if (nestedInput) {
                                        nestedInput.classList.add('is-invalid');
                                    }
                                }
                            }
                        }
                    }
                    Swal.fire('Gagal!', errorMessage, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan jaringan atau server saat booking. Detail: ' + error.message, 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // Initialize form generation and summary
        generateParticipantForms();
        updatePriceSummary();
    });
</script>
@endpush