<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar - Indiegologi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Indiegologi Brand Colors */
        :root {
            --indiegologi-primary: #0C2C5A; /* Biru Tua - Classy, Pointed */
            --indiegologi-accent: #F4B704; /* Emas - Memorable */
            --indiegologi-light-bg: #F5F7FA;
            --indiegologi-dark-text: #212529;
            --indiegologi-light-text: #ffffff;
            --indiegologi-muted-text: #6c757d;
            --strength-weak: #dc3545;     /* Merah untuk password lemah */
            --strength-medium: #ffc107;   /* Kuning untuk cukup kuat */
            --strength-strong: #28a745;   /* Hijau untuk kuat */
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

        /* Gaya untuk tombol saat dinonaktifkan */
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

        /* [ANIMASI] CSS untuk animasi staggered fade-in */
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
            opacity: 0; /* Sembunyikan elemen secara default */
            animation: fadeInUp 0.6s ease-out forwards;
        }
        /* Memberi jeda (delay) yang berbeda pada setiap elemen */
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


        /* Styling untuk feedback password */
        .password-feedback {
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 5px;
            height: 1.2rem; /* Reserve space to prevent layout shift */
        }
        .password-feedback.weak { color: var(--strength-weak); }
        .password-feedback.medium { color: var(--strength-medium); }
        .password-feedback.strong { color: var(--strength-strong); }

        /* Styling untuk petunjuk password */
        .password-clue {
            font-size: 0.8rem;
            color: var(--indiegologi-muted-text);
        }

        /* Responsive adjustments */
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
                            <p class="text-muted mb-0">Daftar untuk mulai **mewujudkan ide kreatif** bersama kami.</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger d-flex align-items-center mb-3">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3 animate-item delay-4">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" name="name" id="name" required
                                           class="form-control border-start-0"
                                           placeholder="Masukkan nama lengkap Anda" value="{{ old('name') }}">
                                </div>
                            </div>

                            <div class="mb-3 animate-item delay-5">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" id="email" required
                                           class="form-control border-start-0"
                                           placeholder="nama@indiegologi.com" value="{{ old('email') }}">
                                </div>
                            </div>

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
                                    <input type="password" name="password" id="password" required
                                           class="form-control border-start-0"
                                           placeholder="Masukkan password Anda">
                                </div>
                                <div class="form-text password-clue">
                                    Minimal 2 jenis karakter (huruf, angka, simbol).
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
                                           placeholder="Ulangi password Anda">
                                </div>
                            </div>

                            <button type="submit" id="register-btn" class="btn btn-primary w-100 mb-4 animate-item delay-11" disabled>
                                <i class="fas fa-user-plus me-2"></i> Daftar Akun
                            </button>

                            <div class="text-center text-muted mb-4 animate-item delay-12">
                                Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-medium text-primary">Masuk di sini</a>
                            </div>

                            <div class="text-center animate-item delay-13">
                                <p class="text-muted mb-3">Atau daftar dengan</p>
                                <div class="social-login">
                                    <a href="{{ route('auth.google') }}" class="d-flex align-items-center justify-content-center text-decoration-none">
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const strengthStatus = document.getElementById('password-strength-status');
            const registerBtn = document.getElementById('register-btn');

            passwordInput.addEventListener('keyup', function() {
                const password = this.value;
                let score = 0;
                let feedbackText = '';

                strengthStatus.classList.remove('weak', 'medium', 'strong');

                if (password.length < 8) {
                    feedbackText = 'Minimal 8 karakter.';
                    strengthStatus.classList.add('weak');
                    registerBtn.disabled = true;
                } else if (/\s/.test(password)) {
                    feedbackText = 'Password tidak boleh mengandung spasi.';
                    strengthStatus.classList.add('weak');
                    registerBtn.disabled = true;
                } else {
                    if (/[a-z]/.test(password)) score++;
                    if (/[A-Z]/.test(password)) score++;
                    if (/[0-9]/.test(password)) score++;
                    if (/[^a-zA-Z0-9]/.test(password)) score++;

                    switch (score) {
                        case 1:
                            feedbackText = 'Password lemah';
                            strengthStatus.classList.add('weak');
                            registerBtn.disabled = true;
                            break;
                        case 2:
                            feedbackText = 'Password cukup kuat';
                            strengthStatus.classList.add('medium');
                            registerBtn.disabled = false;
                            break;
                        case 3:
                        case 4:
                            feedbackText = 'Password kuat';
                            strengthStatus.classList.add('strong');
                            registerBtn.disabled = false;
                            break;
                        default:
                            feedbackText = '';
                            registerBtn.disabled = true;
                            break;
                    }
                }
                strengthStatus.textContent = feedbackText;
            });
        });
    </script>
</body>
</html>