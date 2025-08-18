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

        .form-control {
            border-radius: 12px;
            padding: 0.85rem 1.25rem;
            border: 1px solid #e0e0e0;
            background-color: #fcfcfc;
            transition: all 0.3s ease;
        }

        .form-control:focus {
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

        .form-control.border-start-0 {
            border-left: none;
        }

        .btn-primary {
            background-color: var(--indiegologi-accent);
            border: none;
            border-radius: 12px;
            padding: 0.85rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(244, 183, 4, 0.2);
            transition: all 0.3s ease;
            letter-spacing: 0.03em;
            color: var(--indiegologi-primary);
        }

        .btn-primary:hover {
            background-color: #d49c00;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(244, 183, 4, 0.3);
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

            .form-control {
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
                            <div class="logo-container">
                                {{-- Ikon tangan dengan hati melambangkan budaya caring, love, sharing --}}
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

                            <div class="mb-3">
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

                            <div class="mb-3">
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

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" name="password" id="password" required
                                           class="form-control border-start-0"
                                           placeholder="Masukkan password Anda">
                                </div>
                            </div>

                            <div class="mb-4">
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

                            <button type="submit" class="btn btn-primary w-100 mb-4">
                                <i class="fas fa-user-plus me-2"></i> Daftar Akun
                            </button>

                            <div class="text-center text-muted mb-4">
                                Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-medium text-primary">Masuk di sini</a>
                            </div>

                            <div class="text-center">
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
                            <div class="text-center mt-3">
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
</body>
</html>
