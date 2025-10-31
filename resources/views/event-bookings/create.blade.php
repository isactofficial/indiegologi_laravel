@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #28a745;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                        style="width: 70px; height: 70px; background-color: rgba(40, 167, 69, 0.1);">
                        <i class="fas fa-plus fs-2" style="color: #28a745;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #28a745;">Buat Booking Event Baru</h2>
                        <p class="text-muted mb-0">Buat booking event manual untuk user.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('admin.event-bookings.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.event-bookings.store') }}" method="POST" id="bookingForm">
                        @csrf

                        <div class="row">
                            {{-- Basic Information --}}
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3">Informasi Dasar</h5>

                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User</label>
                                    <select name="user_id" id="user_id" class="form-select" required>
                                        <option value="">Pilih User</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Pilih user yang terdaftar di sistem. Untuk guest booking, gunakan fitur booking tanpa login di frontend.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="event_id" class="form-label">Event</label>
                                    <select name="event_id" id="event_id" class="form-select" required>
                                        <option value="">Pilih Event</option>
                                        @foreach($events as $event)
                                        @php
                                        // Hitung available spots dengan safe check
                                        $availableSpots = max(0, $event->max_participants - $event->current_participants);

                                        // Pastikan price adalah numeric
                                        $price = is_numeric($event->price) ? (float)$event->price : 0;

                                        $eventInfo = $event->title . ' - ' . $event->event_date->format('d M Y') . ' (Sisa: ' . $availableSpots . ' slot)';
                                        $selected = old('event_id') == $event->id ? 'selected' : '';

                                        // Data untuk JavaScript - format yang sederhana
                                        $eventData = [
                                        'price' => $price,
                                        'title' => $event->title,
                                        'date' => $event->event_date->format('d M Y'),
                                        'available_spots' => $availableSpots
                                        ];

                                        // Encode JSON dengan escape
                                        $eventJson = htmlspecialchars(json_encode($eventData), ENT_QUOTES, 'UTF-8');
                                        @endphp
                                        <option value="{{ $event->id }}"
                                            {{ $selected }}
                                            data-event-info="{{ $eventJson }}">
                                            {{ $event->title }} - {{ $event->event_date->format('d M Y') }} - Rp {{ number_format($price, 0, ',', '.') }} (Sisa: {{ $availableSpots }} slot)
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- ADD THIS MISSING FIELD --}}
                                <div class="mb-3">
                                    <label for="participant_count" class="form-label">Jumlah Peserta</label>
                                    <input type="number" name="participant_count" id="participant_count"
                                        class="form-control" value="{{ old('participant_count', 1) }}"
                                        min="1" max="10" required>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Jumlah peserta yang akan mengikuti event.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3">Informasi Booking</h5>

                                {{-- REMOVE DUPLICATE - Keep only one contact preference section --}}
                                <div class="mb-3">
                                    <label class="form-label">Preferensi Kontak</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="contact_preference"
                                            value="chat_and_call" {{ old('contact_preference', 'chat_and_call') == 'chat_and_call' ? 'checked' : '' }}>
                                        <label class="form-check-label">Telepon & WhatsApp</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="contact_preference"
                                            value="chat_only" {{ old('contact_preference') == 'chat_only' ? 'checked' : '' }}>
                                        <label class="form-check-label">Hanya WhatsApp</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tipe Pembayaran</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_type"
                                            value="full_payment" {{ old('payment_type', 'full_payment') == 'full_payment' ? 'checked' : '' }}>
                                        <label class="form-check-label">Full Payment</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_type"
                                            value="dp" {{ old('payment_type') == 'dp' ? 'checked' : '' }}>
                                        <label class="form-check-label">DP (50%)</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="referral_code_id" class="form-label">Kode Referral (Opsional)</label>
                                    <select name="referral_code_id" id="referral_code_id" class="form-select">
                                        <option value="">Pilih Kode Referral</option>
                                        @foreach($referralCodes as $code)
                                        @php
                                        $selected = old('referral_code_id') == $code->id ? 'selected' : '';

                                        // Determine display text based on discount type
                                        if ($code->discount_type === 'percentage') {
                                        $displayText = $code->code . ' - ' . $code->discount_percentage . '% diskon';
                                        } else {
                                        $displayText = $code->code . ' - Rp ' . number_format($code->discount_amount, 0, ',', '.') . ' diskon';
                                        }

                                        // Add minimum purchase info if exists
                                        if ($code->min_purchase_amount) {
                                        $displayText .= ' (min. Rp ' . number_format($code->min_purchase_amount, 0, ',', '.') . ')';
                                        }
                                        @endphp
                                        <option value="{{ $code->id }}"
                                            {{ $selected }}
                                            data-discount-type="{{ $code->discount_type }}"
                                            data-discount-percentage="{{ $code->discount_percentage }}"
                                            data-discount-amount="{{ $code->discount_amount }}"
                                            class="{{ $code->discount_type === 'percentage' ? 'referral-option percentage' : 'referral-option fixed' }}">
                                            {{ $displayText }}
                                            @if($code->min_purchase_amount)
                                            <span class="referral-min-purchase">Min. Rp {{ number_format($code->min_purchase_amount, 0, ',', '.') }}</span>
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Pilih kode referral untuk mendapatkan diskon. Diskon bisa berupa persentase atau nominal tetap.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Dynamic Participant Forms --}}
                        <div id="participantForms">
                            <h5 class="fw-bold mb-3">Data Peserta</h5>
                            <div class="participant-form">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap *</label>
                                            <input type="text" name="participants[0][full_name]"
                                                class="form-control" required placeholder="Masukkan nama lengkap"
                                                value="{{ old('participants.0.full_name', '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nomor Telepon *</label>
                                            <input type="tel" name="participants[0][phone_number]"
                                                class="form-control" required placeholder="081234567890"
                                                value="{{ old('participants.0.phone_number', '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Email (Opsional)</label>
                                            <input type="email" name="participants[0][email]"
                                                class="form-control" placeholder="email@contoh.com"
                                                value="{{ old('participants.0.email', '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Price Summary --}}
                        <div class="row mt-4">
                            <div class="col-md-6 offset-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">Ringkasan Harga</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Harga per peserta:</span>
                                            <span id="price-per-participant">Rp 0</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Jumlah peserta:</span>
                                            <span id="summary-participant-count">0</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span id="subtotal-amount">Rp 0</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 text-success">
                                            <span>Diskon:</span>
                                            <span id="discount-amount">- Rp 0</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold fs-5">
                                            <span>Total:</span>
                                            <span id="total-amount">Rp 0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.event-bookings.index') }}" class="btn btn-outline-secondary px-4">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save me-2"></i>Buat Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const eventSelect = document.getElementById('event_id');
        const participantCount = document.getElementById('participant_count');
        const participantForms = document.getElementById('participantForms');
        const referralSelect = document.getElementById('referral_code_id');

        let currentEvent = null;
        window.currentReferral = null; // Make it global

        console.log('JavaScript loaded - Event Booking Create');

        // Event selection handler
        eventSelect.addEventListener('change', function() {
            console.log('Event changed:', this.value);
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.value && selectedOption.getAttribute('data-event-info')) {
                try {
                    const eventJson = selectedOption.getAttribute('data-event-info');
                    console.log('Raw JSON data:', eventJson);

                    currentEvent = JSON.parse(eventJson);
                    console.log('✅ Successfully parsed event data:', currentEvent);

                    updateParticipantForms();
                    updatePriceSummary();

                } catch (e) {
                    console.error('❌ Error parsing event data:', e);
                    console.error('Problematic JSON string:', selectedOption.getAttribute('data-event-info'));

                    // Fallback: try to extract data from option text
                    try {
                        const optionText = selectedOption.textContent;
                        const priceMatch = optionText.match(/Rp\s*([\d.,]+)/);

                        if (priceMatch) {
                            currentEvent = {
                                price: parseFloat(priceMatch[1].replace(/[.,]/g, '')),
                                title: selectedOption.textContent.split(' - ')[0] || 'Unknown Event',
                                available_spots: 10 // default value
                            };
                            console.log('✅ Fallback event data:', currentEvent);

                            updateParticipantForms();
                            updatePriceSummary();
                        } else {
                            throw new Error('Cannot extract price from option text');
                        }
                    } catch (fallbackError) {
                        console.error('❌ Fallback also failed:', fallbackError);
                        alert('Error loading event data. Please select another event.');
                    }
                }
            } else {
                currentEvent = null;
                document.getElementById('event-info-text').textContent = '';
                updatePriceSummary();
                console.log('No event selected or no event data');
            }
        });

        // Participant count change handler
        participantCount.addEventListener('change', function() {
            console.log('Participant count changed to:', this.value);
            updateParticipantForms();
            updatePriceSummary();
        });

        participantCount.addEventListener('input', function() {
            console.log('Participant count input:', this.value);
            updateParticipantForms();
            updatePriceSummary();
        });

        // Referral code change handler - FIXED: Auto update price summary
        referralSelect.addEventListener('change', function() {
            console.log('Referral code changed:', this.value);
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.value) {
                try {
                    // Get discount data from data attributes
                    const discountType = selectedOption.getAttribute('data-discount-type');
                    const discountPercentage = parseFloat(selectedOption.getAttribute('data-discount-percentage')) || 0;
                    const discountAmount = parseFloat(selectedOption.getAttribute('data-discount-amount')) || 0;

                    console.log('Referral code selected:', {
                        type: discountType,
                        percentage: discountPercentage,
                        amount: discountAmount
                    });

                    // Store referral data
                    window.currentReferral = {
                        type: discountType,
                        percentage: discountPercentage,
                        amount: discountAmount
                    };

                } catch (e) {
                    console.error('Error parsing referral data:', e);
                    window.currentReferral = null;
                }
            } else {
                console.log('No referral code selected');
                window.currentReferral = null;
            }

            // Immediately update price summary
            updatePriceSummary();
        });

        // Format currency helper function
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }

        // Update participant forms based on count
        function updateParticipantForms() {
            console.log('Updating participant forms...');

            const count = parseInt(participantCount.value) || 1;
            console.log('Target participant count:', count);

            // Get all existing forms
            const existingForms = participantForms.querySelectorAll('.participant-form');
            console.log('Existing forms:', existingForms.length);

            // Remove extra forms if we have too many
            if (existingForms.length > count) {
                for (let i = count; i < existingForms.length; i++) {
                    console.log('Removing form', i);
                    existingForms[i].remove();
                }
            }

            // Add new forms if needed
            for (let i = existingForms.length; i < count; i++) {
                console.log('Adding form', i);
                const formHtml = createParticipantForm(i);
                participantForms.insertAdjacentHTML('beforeend', formHtml);
            }

            // Update participant numbers in all forms
            updateParticipantNumbers();

            console.log('Final form count:', participantForms.querySelectorAll('.participant-form').length);
        }

        // Create HTML for a participant form
        function createParticipantForm(index) {
            return `
            <div class="participant-form mt-3 border-top pt-3" data-participant-index="${index}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Peserta ${index + 1}</h6>
                    <span class="badge bg-primary">Wajib diisi</span>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" name="participants[${index}][full_name]" 
                                   class="form-control participant-name" required 
                                   placeholder="Masukkan nama lengkap">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon *</label>
                            <input type="tel" name="participants[${index}][phone_number]" 
                                   class="form-control participant-phone" required 
                                   placeholder="081234567890">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Email (Opsional)</label>
                            <input type="email" name="participants[${index}][email]" 
                                   class="form-control participant-email" 
                                   placeholder="email@contoh.com">
                        </div>
                    </div>
                </div>
            </div>
            `;
        }

        // Update participant numbers in all forms
        function updateParticipantNumbers() {
            const forms = participantForms.querySelectorAll('.participant-form');
            forms.forEach((form, index) => {
                const title = form.querySelector('h6');
                if (title) {
                    title.textContent = `Peserta ${index + 1}`;
                }

                // Update the data attribute
                form.setAttribute('data-participant-index', index);

                // Update input names
                const nameInput = form.querySelector('.participant-name');
                const phoneInput = form.querySelector('.participant-phone');
                const emailInput = form.querySelector('.participant-email');

                if (nameInput) nameInput.name = `participants[${index}][full_name]`;
                if (phoneInput) phoneInput.name = `participants[${index}][phone_number]`;
                if (emailInput) emailInput.name = `participants[${index}][email]`;
            });
        }

        // Update price summary - FIXED: Fixed discount applied per participant
        function updatePriceSummary() {
            console.log('=== UPDATING PRICE SUMMARY ===');
            console.log('Current event:', currentEvent);
            console.log('Current referral:', window.currentReferral);
            console.log('Participant count:', participantCount.value);

            if (!currentEvent) {
                console.log('No current event, resetting price summary');
                resetPriceSummary();
                return;
            }

            const count = parseInt(participantCount.value) || 1;
            const pricePerParticipant = currentEvent.price || 0;
            const subtotal = count * pricePerParticipant;

            // Calculate discount based on type
            let discount = 0;
            let discountDescription = '';

            if (window.currentReferral) {
                if (window.currentReferral.type === 'percentage') {
                    // Percentage discount: applied to total subtotal
                    discount = subtotal * (window.currentReferral.percentage / 100);
                    discountDescription = `(${window.currentReferral.percentage}% dari total)`;
                } else if (window.currentReferral.type === 'fixed') {
                    // FIXED: Fixed discount applied PER PARTICIPANT
                    const discountPerParticipant = window.currentReferral.amount;
                    discount = discountPerParticipant * count;

                    // Ensure total discount doesn't exceed subtotal
                    discount = Math.min(discount, subtotal);
                    discountDescription = `(Rp ${formatCurrency(discountPerParticipant)} per peserta)`;
                }
                console.log(`Discount applied: ${discountDescription} = Rp ${formatCurrency(discount)}`);
            }

            const total = Math.max(0, subtotal - discount);

            console.log('Final calculation:', {
                count,
                pricePerParticipant,
                subtotal,
                discount,
                total,
                referralType: window.currentReferral ? window.currentReferral.type : 'none',
                discountPerParticipant: window.currentReferral && window.currentReferral.type === 'fixed' ?
                    window.currentReferral.amount :
                    'N/A'
            });

            // Update UI
            document.getElementById('price-per-participant').textContent = 'Rp ' + formatCurrency(pricePerParticipant);
            document.getElementById('summary-participant-count').textContent = count;
            document.getElementById('subtotal-amount').textContent = 'Rp ' + formatCurrency(subtotal);
            document.getElementById('discount-amount').textContent = '- Rp ' + formatCurrency(discount);
            document.getElementById('total-amount').textContent = 'Rp ' + formatCurrency(total);

            // Show discount description if applicable
            const discountElement = document.getElementById('discount-amount');
            if (window.currentReferral && discount > 0) {
                discountElement.title = `Diskon referral ${discountDescription}`;
                discountElement.style.fontWeight = 'bold';
                discountElement.classList.add('text-success');
            } else {
                discountElement.title = '';
                discountElement.style.fontWeight = 'normal';
                discountElement.classList.remove('text-success');
            }
        }

        function resetPriceSummary() {
            document.getElementById('price-per-participant').textContent = 'Rp 0';
            document.getElementById('summary-participant-count').textContent = '0';
            document.getElementById('subtotal-amount').textContent = 'Rp 0';
            document.getElementById('discount-amount').textContent = '- Rp 0';
            document.getElementById('total-amount').textContent = 'Rp 0';
        }

        // Form validation
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            console.log('Form submission attempted');

            if (!currentEvent) {
                e.preventDefault();
                alert('❌ Pilih event terlebih dahulu!');
                eventSelect.focus();
                return;
            }

            const count = parseInt(participantCount.value) || 0;
            if (count < 1) {
                e.preventDefault();
                alert('❌ Jumlah peserta minimal 1!');
                participantCount.focus();
                return;
            }

            // Validate all participant forms
            const participantNames = this.querySelectorAll('.participant-name');
            const participantPhones = this.querySelectorAll('.participant-phone');
            let isValid = true;

            participantNames.forEach((input) => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            participantPhones.forEach((input) => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('❌ Harap lengkapi semua data peserta yang wajib diisi!');
            }
        });

        // Initialize form when page loads
        console.log('Initializing form...');

        // Set initial values
        if (!participantCount.value || participantCount.value < 1) {
            participantCount.value = 1;
        }

        // Initialize currentReferral
        window.currentReferral = null;

        // Trigger initial updates
        updateParticipantForms();
        updatePriceSummary();

        // If event is already selected, trigger change event
        if (eventSelect.value) {
            console.log('Event already selected on page load:', eventSelect.value);
            setTimeout(() => {
                eventSelect.dispatchEvent(new Event('change'));
            }, 500);
        }

        console.log('Form initialization complete');
    });

    // Debug function
    function debugForm() {
        console.log('=== FORM DEBUG INFO ===');
        console.log('Event select value:', document.getElementById('event_id').value);
        console.log('Participant count value:', document.getElementById('participant_count').value);
        console.log('Referral select value:', document.getElementById('referral_code_id').value);
        console.log('Current event:', window.currentEvent);
        console.log('Current referral:', window.currentReferral);
        console.log('Participant forms count:', document.querySelectorAll('.participant-form').length);
        console.log('========================');
    }
</script>

<style>
    .participant-form {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .participant-form:first-of-type {
        background: transparent;
        padding: 0;
        margin-bottom: 2rem;
        border: none;
    }

    .participant-form:hover {
        border-color: #0C2C5A;
        background: #e3f2fd;
    }

    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }

    /* Style for discount amount when applied */
    .text-success {
        color: #28a745 !important;
    }

    /* Optional: Add animation for price updates */
    #discount-amount,
    #total-amount {
        transition: all 0.3s ease;
    }
</style>
@endpush