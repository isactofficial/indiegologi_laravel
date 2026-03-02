<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar - Indiegologi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon/favicon-light.png') }}" media="(prefers-color-scheme: light)" type="image/png">
    <link rel="icon" href="{{ asset('favicon/favicon-dark.png') }}" media="(prefers-color-scheme: dark)" type="image/png">
    <style>
        /* Indiegologi Brand Colors */
        :root {
            --indiegologi-primary: #0C2C5A;
            --indiegologi-accent: #F4B704;
            --indiegologi-light-bg: #F5F7FA;
            --indiegologi-dark-text: #212529;
            --indiegologi-light-text: #ffffff;
            --indiegologi-muted-text: #6c757d;
            --strength-weak: #dc3545;
            --strength-medium: #ffc107;
            --strength-strong: #28a745;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--indiegologi-light-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--indiegologi-dark-text);
            padding: 2rem 0;
        }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            background-color: #ffffff;
            overflow: hidden;
        }

        .card-body {
            padding: 3rem;
        }

        .logo-container {
            width: 80px;
            height: 80px;
            background-color: var(--indiegologi-primary);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .logo-container:hover {
            transform: translateY(-5px);
        }

        .logo-icon {
            font-size: 2.5rem;
            color: var(--indiegologi-accent);
        }

        h1.h3 {
            color: var(--indiegologi-primary);
            font-weight: 700 !important;
            font-size: 2rem;
        }

        .text-muted {
            color: var(--indiegologi-muted-text) !important;
        }

        .form-label {
            font-weight: 500;
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 0.85rem 1.25rem;
            border: 1px solid #e0e0e0;
            background-color: #fcfcfc;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--indiegologi-primary);
            box-shadow: 0 0 0 0.25rem rgba(12, 44, 90, 0.1);
            background-color: #ffffff;
        }

        .input-group-text {
            background-color: transparent;
            border: 1px solid #e0e0e0;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: var(--indiegologi-primary);
        }

        .form-control.border-start-0, .form-select.border-start-0 {
            border-left: none;
        }
        
        .btn-primary {
            background-color: var(--indiegologi-primary);
            border: none;
            border-radius: 12px;
            padding: 0.85rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(12, 44, 90, 0.2);
            transition: all 0.3s ease;
            letter-spacing: 0.03em;
        }

        .btn-primary:disabled {
            background-color: #e9ecef;
            color: #6c757d;
            box-shadow: none;
            cursor: not-allowed;
            transform: none;
        }

        .btn-primary:hover:not(:disabled) {
            background-color: #082142;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(12, 44, 90, 0.3);
        }

        .alert {
            border-radius: 12px;
            border: none;
            font-weight: 500;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .text-primary {
            color: var(--indiegologi-primary) !important;
        }

        .text-primary:hover {
            color: var(--indiegologi-accent) !important;
        }

        .social-login {
            background-color: #f7f9fc;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.3s ease;
            font-weight: 500;
            color: var(--indiegologi-dark-text);
        }

        .social-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background-color: #eef1f5;
        }

        .social-icon {
            width: 24px;
            height: 24px;
            margin-right: 0.75rem;
        }

        .btn-back {
            background-color: transparent;
            border: 1px solid var(--indiegologi-primary);
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: var(--indiegologi-primary);
            box-shadow: none;
        }

        .btn-back:hover {
            background-color: var(--indiegologi-primary);
            color: var(--indiegologi-light-text);
            transform: none;
            box-shadow: 0 4px 12px rgba(12, 44, 90, 0.2);
        }

        .btn-back i {
            font-size: 1.2rem;
            color: inherit;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-item {
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }
        .delay-7 { animation-delay: 0.7s; }
        .delay-8 { animation-delay: 0.8s; }
        .delay-9 { animation-delay: 0.9s; }
        .delay-10 { animation-delay: 1.0s; }
        .delay-11 { animation-delay: 1.1s; }
        .delay-12 { animation-delay: 1.2s; }
        .delay-13 { animation-delay: 1.3s; }
        .delay-14 { animation-delay: 1.4s; }

        .password-feedback {
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 5px;
            height: 1.2rem;
        }
        .password-feedback.weak { color: var(--strength-weak); }
        .password-feedback.medium { color: var(--strength-medium); }
        .password-feedback.strong { color: var(--strength-strong); }

        .password-clue {
            font-size: 0.8rem;
            color: var(--indiegologi-muted-text);
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem;
            }
            .logo-container {
                width: 70px;
                height: 70px;
            }
            .logo-icon {
                font-size: 2.25rem;
            }
            h1.h3 {
                font-size: 1.8rem;
            }
            .form-control, .form-select {
                padding: 0.65rem 1rem;
            }
            .btn-primary, .btn-back {
                padding: 0.65rem 1rem;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-4 py-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8 col-md-7 col-lg-5 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="logo-container animate-item delay-1">
                                <i class="fas fa-hand-holding-heart logo-icon"></i>
                            </div>
                            <h1 class="h3 fw-bold mb-1">Mari Berbagi Ide di Indiegologi!</h1>
                            <p class="text-muted mb-0">Daftar untuk mulai mewujudkan ide kreatif bersama kami.</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger d-flex align-items-center mb-3">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('verification.verify-and-register') }}" id="register-form">
                            @csrf
                            
                            <!-- Step 1: Email Input -->
                            <div id="step-email">
                                <div class="mb-3 animate-item delay-5">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" name="email" id="email" required
                                               class="form-control border-start-0"
                                               placeholder="nama@email.com">
                                    </div>
                                    <small class="text-muted">Kami akan mengirim kode verifikasi ke email ini.</small>
                                </div>

                                <button type="button" id="send-otp-btn" class="btn btn-primary w-100 mb-4 animate-item delay-6">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Kode OTP
                                </button>
                            </div>

                            <!-- Step 2: OTP Verification -->
                            <div id="step-otp" style="display: none;">
                                <div class="text-center mb-4">
                                    <div class="otp-icon mb-3">
                                        <i class="fas fa-envelope-open-text" style="font-size: 3rem; color: var(--indiegologi-primary);"></i>
                                    </div>
                                    <h5>Cek Email Anda</h5>
                                    <p class="text-muted small">Kami telah mengirim kode verifikasi ke<br><strong id="display-email"></strong></p>
                                </div>

                                <div class="mb-3 animate-item">
                                    <label for="otp" class="form-label">Masukkan Kode OTP</label>
                                    <div class="input-group justify-content-center">
                                        <input type="text" name="otp" id="otp" required
                                               class="form-control text-center"
                                               style="letter-spacing: 8px; font-size: 1.5rem; max-width: 200px;"
                                               placeholder="------" maxlength="6">
                                    </div>
                                    <div id="otp-timer" class="text-center mt-2 text-danger">
                                        <i class="fas fa-clock me-1"></i> <span id="countdown">60</span> detik
                                    </div>
                                </div>

                                <button type="button" id="verify-otp-btn" class="btn btn-primary w-100 mb-2" disabled>
                                    <i class="fas fa-check-circle me-2"></i> Verifikasi
                                </button>

                                <button type="button" id="resend-otp-btn" class="btn btn-outline-secondary w-100 mb-4" disabled>
                                    <i class="fas fa-redo me-2"></i> Kirim Ulang OTP
                                </button>
                            </div>

                            <!-- Step 3: Registration Form -->
                            <div id="step-register" style="display: none;">
                                <div class="text-center mb-4">
                                    <div class="success-icon mb-3">
                                        <i class="fas fa-check-circle" style="font-size: 3rem; color: #28a745;"></i>
                                    </div>
                                    <h5>Email Terverifikasi!</h5>
                                    <p class="text-muted small">Lanjutkan mengisi data registrasi</p>
                                </div>

                                <div class="mb-3 animate-item delay-4">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" name="name" id="reg-name" required
                                               class="form-control border-start-0"
                                               placeholder="Masukkan nama lengkap Anda" value="{{ old('name') }}">
                                    </div>
                                </div>

                                <input type="hidden" name="email" id="reg-email">

                                <div class="mb-3 animate-item delay-6">
                                    <label for="birthdate" class="form-label">Tanggal Lahir</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        <input type="date" name="birthdate" id="birthdate" required
                                               class="form-control border-start-0" value="{{ old('birthdate') }}">
                                    </div>
                                </div>

                                <div class="mb-3 animate-item delay-7">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-venus-mars"></i>
                                        </span>
                                        <select name="gender" id="gender" required class="form-select border-start-0">
                                            <option value="" disabled selected>Pilih jenis kelamin Anda</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 animate-item delay-8">
                                    <label for="phone_number" class="form-label">Nomor Telepon</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input type="tel" name="phone_number" id="phone_number" required
                                               class="form-control border-start-0"
                                               placeholder="Contoh: 081234567890" value="{{ old('phone_number') }}">
                                    </div>
                                </div>

                                <div class="mb-2 animate-item delay-9">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="text" name="password" id="reg-password" required
                                               class="form-control border-start-0"
                                               placeholder="Masukkan password Anda" autocomplete="new-password">
                                        <button class="btn btn-outline-secondary border-start-0" type="button" id="toggle-password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text password-clue">
                                        Minimal 8 karakter dengan kombinasi huruf, angka, simbol.
                                    </div>
                                    <div id="password-strength-status" class="password-feedback"></div>
                                </div>

                                <div class="mb-4 animate-item delay-10">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required
                                               class="form-control border-start-0"
                                               placeholder="Ulangi password Anda" autocomplete="new-password">
                                    </div>
                                </div>

                                <button type="submit" id="register-btn" class="btn btn-primary w-100 mb-4 animate-item delay-11">
                                    <i class="fas fa-user-plus me-2"></i> Daftar Akun
                                </button>
                            </div>
                        </form>

                            <div class="text-center text-muted mb-4 animate-item delay-12">
                                Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-medium text-primary">Masuk di sini</a>
                            </div>

                        </form>

                        <div class="text-center animate-item delay-13">
                            <p class="text-muted mb-3">Atau daftar dengan</p>
                            <div class="social-login">
                                <form action="{{ route('auth.google.redirect') }}" method="POST" id="google-register-form">
                                    @csrf
                                    <input type="hidden" name="temp_cart_data" class="temp-cart-input">
                                    <button type="submit" class="btn btn-link d-flex align-items-center justify-content-center text-decoration-none w-100 p-0 border-0" style="background: none;">
                                        <svg class="social-icon" viewBox="0 0 24 24">
                                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                        </svg>
                                        <span class="ms-2">Google</span>
                                    </a>
                                </div>
                            </div>
                            <div class="text-center mt-3 animate-item delay-14">
                                <a href="{{ route('front.index') }}" class="btn btn-back w-100">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const sendOtpBtn = document.getElementById('send-otp-btn');
            const stepEmail = document.getElementById('step-email');
            const stepOtp = document.getElementById('step-otp');
            const stepRegister = document.getElementById('step-register');
            const displayEmail = document.getElementById('display-email');
            const otpInput = document.getElementById('otp');
            const verifyOtpBtn = document.getElementById('verify-otp-btn');
            const resendOtpBtn = document.getElementById('resend-otp-btn');
            const countdownEl = document.getElementById('countdown');
            const registerForm = document.getElementById('register-form');
            const passwordInput = document.getElementById('reg-password');
            const strengthStatus = document.getElementById('password-strength-status');
            const registerBtn = document.getElementById('register-btn');
            const regEmail = document.getElementById('reg-email');
            
            let countdownInterval;
            let isOtpVerified = false;

            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message,
                    confirmButtonColor: '#0C2C5A'
                });
            }

            function showSuccess(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: message,
                    confirmButtonColor: '#28a745',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            // Toggle password visibility
            document.getElementById('toggle-password').addEventListener('click', function() {
                const passwordInput = document.getElementById('reg-password');
                const icon = this.querySelector('i');
                if (passwordInput.type === 'text') {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });

            // Send OTP
            sendOtpBtn.addEventListener('click', async function() {
                const email = emailInput.value.trim();
                
                if (!email || !email.includes('@')) {
                    showError('Silakan masukkan email yang valid.');
                    return;
                }

                sendOtpBtn.disabled = true;
                sendOtpBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...';

                try {
                    const response = await fetch('{{ route("verification.send-otp") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ email: email })
                    });

                    const data = await response.json();

                    if (data.success) {
                        showSuccess(data.message);
                        displayEmail.textContent = email;
                        stepEmail.style.display = 'none';
                        stepOtp.style.display = 'block';
                        startCountdown();
                        otpInput.focus();
                    } else {
                        showError(data.message);
                        sendOtpBtn.disabled = false;
                        sendOtpBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Kirim Kode OTP';
                    }
                } catch (error) {
                    showError('Terjadi kesalahan. Silakan coba lagi.');
                    sendOtpBtn.disabled = false;
                    sendOtpBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Kirim Kode OTP';
                }
            });

            // Start countdown timer
            function startCountdown() {
                let seconds = 60;
                countdownEl.textContent = seconds;
                resendOtpBtn.disabled = true;
                verifyOtpBtn.disabled = true;

                clearInterval(countdownInterval);
                countdownInterval = setInterval(() => {
                    seconds--;
                    countdownEl.textContent = seconds;

                    if (seconds <= 0) {
                        clearInterval(countdownInterval);
                        resendOtpBtn.disabled = false;
                        resendOtpBtn.innerHTML = '<i class="fas fa-redo me-2"></i> Kirim Ulang OTP';
                    }
                }, 1000);
            }

            // OTP input - enable verify button when 6 digits
            otpInput.addEventListener('input', function() {
                if (this.value.length === 6) {
                    verifyOtpBtn.disabled = false;
                } else {
                    verifyOtpBtn.disabled = true;
                }
            });

            // Verify OTP
            verifyOtpBtn.addEventListener('click', async function() {
                const email = emailInput.value.trim();
                const otp = otpInput.value.trim();

                verifyOtpBtn.disabled = true;
                verifyOtpBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memverifikasi...';

                try {
                    const response = await fetch('{{ route("verification.verify-and-register") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: email,
                            otp: otp,
                            name: '',
                            birthdate: '',
                            gender: '',
                            phone_number: '',
                            password: '',
                            password_confirmation: ''
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        isOtpVerified = true;
                        clearInterval(countdownInterval);
                        stepOtp.style.display = 'none';
                        stepRegister.style.display = 'block';
                        regEmail.value = email;
                        
                        showSuccess('Email berhasil diverifikasi!');
                    } else {
                        showError(data.message || 'Kode OTP tidak valid.');
                        verifyOtpBtn.disabled = false;
                        verifyOtpBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Verifikasi';
                    }
                } catch (error) {
                    showError('Terjadi kesalahan. Silakan coba lagi.');
                    verifyOtpBtn.disabled = false;
                    verifyOtpBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Verifikasi';
                }
            });

            // Resend OTP
            resendOtpBtn.addEventListener('click', async function() {
                const email = emailInput.value.trim();

                resendOtpBtn.disabled = true;
                resendOtpBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...';

                try {
                    const response = await fetch('{{ route("verification.resend-otp") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        showSuccess(data.message);
                        otpInput.value = '';
                        verifyOtpBtn.disabled = true;
                        startCountdown();
                        resendOtpBtn.innerHTML = '<i class="fas fa-redo me-2"></i> Kirim Ulang OTP';
                    } else {
                        showError(data.message);
                        resendOtpBtn.disabled = false;
                        resendOtpBtn.innerHTML = '<i class="fas fa-redo me-2"></i> Kirim Ulang OTP';
                    }
                } catch (error) {
                    showError('Terjadi kesalahan. Silakan coba lagi.');
                    resendOtpBtn.disabled = false;
                    resendOtpBtn.innerHTML = '<i class="fas fa-redo me-2"></i> Kirim Ulang OTP';
                }
            });

            // Password strength checker
            if (passwordInput) {
                passwordInput.addEventListener('keyup', function() {
                    const password = this.value;
                    let score = 0;
                    let feedbackText = '';

                    strengthStatus.classList.remove('weak', 'medium', 'strong');

                    if (password.length < 8) {
                        feedbackText = 'Minimal 8 karakter.';
                        strengthStatus.classList.add('weak');
                    } else if (/\s/.test(password)) {
                        feedbackText = 'Password tidak boleh mengandung spasi.';
                        strengthStatus.classList.add('weak');
                    } else {
                        if (/[a-z]/.test(password)) score++;
                        if (/[A-Z]/.test(password)) score++;
                        if (/[0-9]/.test(password)) score++;
                        if (/[^a-zA-Z0-9]/.test(password)) score++;

                        switch (score) {
                            case 1:
                                feedbackText = 'Password lemah';
                                strengthStatus.classList.add('weak');
                                break;
                            case 2:
                                feedbackText = 'Password cukup kuat';
                                strengthStatus.classList.add('medium');
                                break;
                            case 3:
                            case 4:
                                feedbackText = 'Password kuat';
                                strengthStatus.classList.add('strong');
                                break;
                            default:
                                feedbackText = '';
                                break;
                        }
                    }
                    strengthStatus.textContent = feedbackText;
                });
            }

            // Form submit
            if (registerForm) {
                registerForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    if (!isOtpVerified) {
                        showError('Silakan verifikasi email terlebih dahulu.');
                        return;
                    }

                    const formData = new FormData(this);
                    
                    try {
                        const response = await fetch('{{ route("verification.verify-and-register") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                email: formData.get('email'),
                                otp: 'verified',
                                name: formData.get('name'),
                                birthdate: formData.get('birthdate'),
                                gender: formData.get('gender'),
                                phone_number: formData.get('phone_number'),
                                password: formData.get('password'),
                                password_confirmation: formData.get('password_confirmation')
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            showSuccess(data.message);
                            setTimeout(() => {
                                window.location.href = data.redirect || '/';
                            }, 1500);
                        } else {
                            showError(data.message || 'Registrasi gagal.');
                        }
                    } catch (error) {
                        showError('Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            }
        });
    </script>
</body>
</html>
