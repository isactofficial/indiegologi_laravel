@extends('layouts.app')

@push('styles')
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

    .price-summary {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 2rem;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Pendaftaran Event (Tanpa Login)</h1>
                <a href="{{ route('front.events.index', $event->slug) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="form-section mb-4">
                <h4>{{ $event->title }}</h4>
                <p class="text-muted mb-0">
                    <i class="bi bi-calendar-event"></i>
                    {{ $event->formatted_event_date_time }} |
                    <i class="bi bi-geo-alt"></i>
                    {{ $event->session_type === 'online' ? 'Online' : $event->place }}
                </p>
            </div>

            <form id="guestEventBookingForm">
                @csrf

                <!-- Guest Contact Information -->
                <div class="form-section mb-4">
                    <h5>Data Pemesan</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap Pemesan *</label>
                                <input type="text" class="form-control" name="guest_name" required
                                    placeholder="Masukkan nama lengkap pemesan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nomor Telepon Pemesan *</label>
                                <input type="tel" class="form-control" name="guest_phone" required
                                    placeholder="Contoh: 081234567890">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Email Pemesan *</label>
                                <input type="email" class="form-control" name="guest_email" required
                                    placeholder="email@contoh.com">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Participant Count Selection -->
                <div class="form-section mb-4">
                    <h5>Jumlah Peserta</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Berapa banyak peserta?</label>
                            <select class="form-select" id="participantCount" name="participant_count" required>
                                <option value="">Pilih jumlah peserta</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }} peserta</option>
                                    @endfor
                            </select>
                            <!-- <div class="form-text">
                                Sisa slot tersedia: <strong>{{ $event->spots_left }}</strong>
                            </div> -->
                        </div>
                    </div>
                </div>

                <!-- Dynamic Participant Forms -->
                <div id="participantForms" class="mb-4">
                    <!-- Forms will be generated here dynamically -->
                </div>

                <!-- Contact Preference -->
                <div class="form-section mb-4">
                    <h5>Preferensi Kontak</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="contact_preference" value="chat_and_call" checked>
                        <label class="form-check-label">Telepon & WhatsApp</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="contact_preference" value="chat_only">
                        <label class="form-check-label">Hanya WhatsApp</label>
                    </div>
                </div>

                <!-- Referral Code -->
                <div class="form-section mb-4">
                    <h5>Kode Referral</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="referral_code" placeholder="Masukkan kode referral (opsional)">
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-secondary me-md-2" onclick="history.back()">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="bi bi-check-circle"></i> Daftar Event
                    </button>
                </div>
            </form>
        </div>

        <!-- Price Summary -->
        <div class="col-lg-4">
            <div class="price-summary">
                <h5>Ringkasan Pesanan</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Harga per peserta:</span>
                    <span>Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Jumlah peserta:</span>
                    <span id="summaryParticipantCount">0</span>
                </div>
                <div class="price-breakdown mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Subtotal:</strong>
                        <strong id="subtotalAmount">Rp 0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Diskon referral:</span>
                        <span id="discountAmount">- Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total:</strong>
                        <strong id="totalAmount">Rp 0</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    console.log('JavaScript loaded - Guest Booking');
    
    // PERBAIKI: Gunakan ID yang benar - guestEventBookingForm
    const participantCount = document.getElementById('participantCount');
    const participantForms = document.getElementById('participantForms');
    const form = document.getElementById('guestEventBookingForm'); // INI YANG DIPERBAIKI
    
    console.log('Elements found:', { 
        participantCount, 
        participantForms, 
        form 
    });
    
    if (!participantCount || !participantForms || !form) {
        console.error('❌ Element not found. Check IDs:', {
            participantCount: !!participantCount,
            participantForms: !!participantForms,
            form: !!form
        });
        return;
    }

    // Global variable untuk discount
    window.referralDiscountInfo = null;

    // Function untuk update price summary
    function updatePriceSummary() {
        try {
            const count = parseInt(participantCount.value) || 0;
            const pricePerParticipant = {{ $event->price }};
            const subtotal = count * pricePerParticipant;
            
            let discount = 0;
            let discountText = '- Rp 0';
            
            // Calculate discount jika ada referral
            if (window.referralDiscountInfo) {
                if (window.referralDiscountInfo.type === 'fixed') {
                    // Fixed discount: amount per participant × participant count
                    discount = window.referralDiscountInfo.fixed_amount * count;
                } else {
                    // Percentage discount
                    discount = subtotal * (window.referralDiscountInfo.percentage / 100);
                }
                
                // Pastikan discount tidak melebihi subtotal
                discount = Math.min(discount, subtotal);
                discountText = '- Rp ' + discount.toLocaleString('id-ID');
            }
            
            const total = Math.max(0, subtotal - discount);

            // Update UI elements
            const summaryCount = document.getElementById('summaryParticipantCount');
            const subtotalElement = document.getElementById('subtotalAmount');
            const discountElement = document.getElementById('discountAmount');
            const totalElement = document.getElementById('totalAmount');

            if (summaryCount) summaryCount.textContent = count;
            if (subtotalElement) subtotalElement.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            if (discountElement) discountElement.textContent = discountText;
            if (totalElement) totalElement.textContent = 'Rp ' + total.toLocaleString('id-ID');
            
            console.log('Price calculation:', { count, subtotal, discount, total });
            
        } catch (error) {
            console.error('Error in updatePriceSummary:', error);
        }
    }

    // Participant form generation
    participantCount.addEventListener('change', function() {
        const count = parseInt(this.value) || 0;
        console.log('Participant count:', count);
        
        participantForms.innerHTML = '';

        if (count > 0) {
            for (let i = 0; i < count; i++) {
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
        console.log('Generated', count, 'participant forms');
    });

    // REFERRAL CODE VALIDATION
    const referralInput = document.querySelector('input[name="referral_code"]');
    if (referralInput) {
        referralInput.addEventListener('blur', function() {
            const code = this.value.trim();
            const count = parseInt(participantCount.value) || 1;
            const pricePerParticipant = {{ $event->price }};
            const totalAmount = count * pricePerParticipant;
            
            console.log('Checking referral code:', code, 'Total amount:', totalAmount);
            
            if (code && totalAmount > 0) {
                console.log('Validating referral code via API:', code);
                
                // Tampilkan loading
                this.disabled = true;
                
                // Panggil API referral validation
                fetch('{{ route("referral.validate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        code: code,
                        total_amount: totalAmount 
                    })
                })
                .then(response => {
                    console.log('API Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('API Response data:', data);
                    
                    if (data.success) {
                        // Simpan info discount
                        window.referralDiscountInfo = {
                            type: data.discount_type,
                            percentage: data.discount_percentage,
                            fixed_amount: data.discount_amount,
                            calculated_discount: data.calculated_discount
                        };
                        
                        updatePriceSummary();
                        
                        let discountText = '';
                        if (data.discount_type === 'fixed') {
                            discountText = `Diskon Rp ${data.discount_amount.toLocaleString('id-ID')} per peserta`;
                        } else {
                            discountText = `Diskon ${data.discount_percentage}%`;
                        }
                        
                        // Gunakan SweetAlert untuk notifikasi yang lebih baik
                        Swal.fire({
                            icon: 'success',
                            title: 'Kode Referral Valid',
                            text: data.message + ' - ' + discountText,
                            timer: 3000,
                            showConfirmButton: true
                        });
                    } else {
                        window.referralDiscountInfo = null;
                        updatePriceSummary();
                        Swal.fire({
                            icon: 'error',
                            title: 'Kode Tidak Valid',
                            text: data.message,
                            timer: 3000,
                            showConfirmButton: true
                        });
                        this.value = '';
                    }
                })
                .catch(error => {
                    console.error('API Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat validasi kode referral',
                        timer: 3000,
                        showConfirmButton: true
                    });
                    window.referralDiscountInfo = null;
                    updatePriceSummary();
                })
                .finally(() => {
                    this.disabled = false;
                });
                
            } else if (code && totalAmount <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Pilih jumlah peserta terlebih dahulu',
                    timer: 3000,
                    showConfirmButton: true
                });
            } else {
                window.referralDiscountInfo = null;
                updatePriceSummary();
            }
        });
    }

    // Form submission untuk guest
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        console.log('Guest form submission started');
        
        const count = parseInt(participantCount.value);
        if (!count || count < 1) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Pilih jumlah peserta terlebih dahulu!',
                timer: 3000,
                showConfirmButton: true
            });
            return;
        }

        // Validasi data pemesan (guest)
        const guestName = document.querySelector('input[name="guest_name"]').value.trim();
        const guestPhone = document.querySelector('input[name="guest_phone"]').value.trim();
        const guestEmail = document.querySelector('input[name="guest_email"]').value.trim();

        if (!guestName || !guestPhone || !guestEmail) {
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Lengkap',
                text: 'Harap lengkapi data pemesan terlebih dahulu!',
                timer: 3000,
                showConfirmButton: true
            });
            return;
        }

        // Validasi data peserta
        const participantInputs = participantForms.querySelectorAll('input[required]');
        let isValid = true;
        let emptyFields = [];
        
        participantInputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
                const fieldName = input.name.includes('full_name') ? 'Nama Lengkap' : 'Nomor Telepon';
                if (!emptyFields.includes(fieldName)) {
                    emptyFields.push(fieldName);
                }
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Data Peserta Tidak Lengkap',
                text: 'Harap lengkapi: ' + emptyFields.join(' dan ') + ' untuk semua peserta',
                timer: 3000,
                showConfirmButton: true
            });
            return;
        }

        // Tampilkan loading
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn ? submitBtn.innerHTML : '';
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';
        }

        // Kirim data ke server
        fetch('{{ route("guest.events.process-booking", $event->id) }}', {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Server response:', data);
            
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Kembali ke Events'
                }).then(() => {
                    window.location.href = '{{ route("front.events.index") }}';
                });
            } else {
                let errorMessage = data.message || 'Terjadi kesalahan tidak terduga';
                
                if (data.errors) {
                    errorMessage = 'Terjadi kesalahan validasi:\n';
                    for (const field in data.errors) {
                        errorMessage += `• ${data.errors[field].join(', ')}\n`;
                    }
                }
                
                Swal.fire({
                    title: 'Gagal!',
                    text: errorMessage,
                    icon: 'error',
                    timer: 5000,
                    showConfirmButton: true
                });
                
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memproses pendaftaran',
                icon: 'error',
                timer: 5000,
                showConfirmButton: true
            });
            
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    });

    // Initialize price summary
    updatePriceSummary();
    console.log('✅ Guest JavaScript initialization complete');
});
</script>
@endpush