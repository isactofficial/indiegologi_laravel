<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Indiegologi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Indiegologi Brand Colors */
        :root {
            --indiegologi-primary: #0C2C5A;
            --indiegologi-accent: #F4B704;
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
            overflow-x: hidden;
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
            background-color: var(--indiegologi-primary);
            border: none;
            border-radius: 12px;
            padding: 0.85rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(12, 44, 90, 0.2);
            transition: all 0.3s ease;
            letter-spacing: 0.03em;
            position: relative;
        }

        .btn-primary:hover {
            background-color: #082142;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(12, 44, 90, 0.3);
        }

        .btn-primary:disabled {
            background-color: #6c757d;
            transform: none;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        .alert {
            border-radius: 12px;
            border: none;
            font-weight: 500;
        }

        .alert-success {
            background-color: rgba(60, 179, 113, 0.1);
            color: #3cb371;
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

        /* Cart notification badge */
        .cart-notification {
            background-color: rgba(12, 44, 90, 0.1);
            border-left: 4px solid var(--indiegologi-primary);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .cart-notification i {
            color: var(--indiegologi-primary);
        }

        /* Animasi CSS untuk animasi staggered fade-in */
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
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-4 py-sm-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-8 col-md-7 col-lg-5 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4 animate-item delay-1">
                        <div class="logo-container">
                            <i class="fas fa-heart logo-icon"></i>
                        </div>
                        <h1 class="h3 fw-bold mb-1">Selamat Datang di Indiegologi</h1>
                        <p class="text-muted mb-0">Masuk ke akun Anda dan <strong>mulai kembangkan ide brilian</strong>!</p>
                    </div>

                    <!-- Cart notification (will be shown by JavaScript if cart has items) -->
                    <div id="cart-notification" class="cart-notification animate-item delay-2" style="display: none;">
                        <i class="fas fa-shopping-cart me-2"></i>
                        <span id="cart-message">Anda memiliki <strong id="cart-count">0</strong> item di keranjang yang akan dipindahkan ke akun Anda setelah login.</span>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center mb-3 animate-item delay-2">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger d-flex align-items-center mb-3 animate-item delay-2">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="login-form">
                        @csrf
                        <input type="hidden" name="temp_cart_data" id="temp-cart-input">

                        <div class="mb-3 animate-item delay-2">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email" id="email" required
                                       class="form-control border-start-0"
                                       placeholder="nama@indiegologi.com"
                                       value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="mb-2 animate-item delay-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" id="password" required
                                       class="form-control border-start-0"
                                       placeholder="Password Anda">
                            </div>
                        </div>

                        <div class="mb-4 text-end animate-item delay-3">
                            <a href="{{ route('password.request') }}" class="text-decoration-none small fw-medium text-primary">
                                Lupa Password?
                            </a>
                        </div>

                        <div class="animate-item delay-4">
                            <button type="submit" class="btn btn-primary w-100 mb-4" id="login-button">
                                <span id="login-text">
                                    <i class="fas fa-sign-in-alt me-2"></i> Masuk Akun
                                </span>
                                <span id="login-loading" class="d-none">
                                    <span class="spinner-border spinner-border-sm me-2"></span> Memproses...
                                </span>
                            </button>
                        </div>
                        
                        <div class="text-center text-muted mb-4 animate-item delay-5">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-decoration-none fw-medium text-primary">Daftar di sini</a>
                        </div>

                        <div class="text-center animate-item delay-6">
                            <p class="text-muted mb-3">Atau masuk dengan</p>
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
                    </form>
                    <div class="text-center mt-3 animate-item delay-7">
                        <a href="{{ route('front.index') }}" class="btn btn-back w-100">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const loginButton = document.getElementById('login-button');
    const loginText = document.getElementById('login-text');
    const loginLoading = document.getElementById('login-loading');
    const cartNotification = document.getElementById('cart-notification');
    const cartCountElement = document.getElementById('cart-count');
    const cartMessage = document.getElementById('cart-message');
    
    // Function to get temp cart data
    function getTempCart() {
        try {
            const tempCart = localStorage.getItem('tempCart');
            return tempCart ? JSON.parse(tempCart) : {};
        } catch (error) {
            console.error('Error getting temp cart:', error);
            return {};
        }
    }
    
    // Function to analyze temp cart contents with enhanced support for free consultation
    function analyzeTempCart() {
        const tempCart = getTempCart();
        const analysis = {
            total: 0,
            regular_services: 0,
            legacy_free_consultation: 0,
            new_free_consultation: 0,
            details: []
        };

        Object.keys(tempCart).forEach(key => {
            const item = tempCart[key];
            analysis.total++;
            
            if (key === 'free-consultation') {
                analysis.legacy_free_consultation++;
                analysis.details.push({
                    type: 'legacy_free_consultation',
                    key: key,
                    description: 'Konsultasi Gratis (Legacy)'
                });
            } else if (item && item.consultation_type === 'free-consultation-new') {
                analysis.new_free_consultation++;
                analysis.details.push({
                    type: 'new_free_consultation',
                    key: key,
                    description: 'Konsultasi Gratis (Baru)',
                    type_id: item.free_consultation_type_id,
                    schedule_id: item.free_consultation_schedule_id
                });
            } else if (key.startsWith('free-consultation-')) {
                analysis.new_free_consultation++;
                analysis.details.push({
                    type: 'new_free_consultation',
                    key: key,
                    description: 'Konsultasi Gratis (Baru)',
                    type_id: item.free_consultation_type_id,
                    schedule_id: item.free_consultation_schedule_id
                });
            } else {
                analysis.regular_services++;
                analysis.details.push({
                    type: 'regular_service',
                    key: key,
                    description: 'Layanan Reguler'
                });
            }
        });

        return analysis;
    }
    
    // Function to display cart notification
    function showCartNotification() {
        const analysis = analyzeTempCart();
        
        if (analysis.total > 0) {
            cartCountElement.textContent = analysis.total;
            
            // Create detailed message
            let message = `Anda memiliki <strong>${analysis.total}</strong> item di keranjang: `;
            let messageDetails = [];
            
            if (analysis.regular_services > 0) {
                messageDetails.push(`${analysis.regular_services} layanan reguler`);
            }
            if (analysis.legacy_free_consultation > 0) {
                messageDetails.push(`${analysis.legacy_free_consultation} konsultasi gratis (legacy)`);
            }
            if (analysis.new_free_consultation > 0) {
                messageDetails.push(`${analysis.new_free_consultation} konsultasi gratis (baru)`);
            }
            
            message += messageDetails.join(', ') + '. Item akan dipindahkan ke akun Anda setelah login.';
            cartMessage.innerHTML = message;
            
            cartNotification.style.display = 'block';
            
            console.log('Cart analysis:', analysis);
            
            return true;
        }
        
        return false;
    }

    // Handle form submission with enhanced temp cart support
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            // Show loading state
            loginButton.disabled = true;
            loginText.classList.add('d-none');
            loginLoading.classList.remove('d-none');
            
            // Get temp cart data from localStorage
            const tempCart = getTempCart();
            
            if (Object.keys(tempCart).length > 0) {
                try {
                    const analysis = analyzeTempCart();
                    
                    if (analysis.total > 0) {
                        // Set temp cart data to hidden input
                        const tempCartInput = document.getElementById('temp-cart-input');
                        tempCartInput.value = JSON.stringify(tempCart);
                        
                        console.log(`Preparing to transfer ${analysis.total} items from temp cart:`, analysis);
                        
                        // Enhanced logging for free consultation items
                        analysis.details.forEach(detail => {
                            if (detail.type === 'new_free_consultation') {
                                console.log(`New free consultation detected - Type ID: ${detail.type_id}, Schedule ID: ${detail.schedule_id}`);
                            } else if (detail.type === 'legacy_free_consultation') {
                                console.log('Legacy free consultation detected');
                            }
                        });
                    }
                } catch (error) {
                    console.error('Error processing temp cart data:', error);
                }
            }

            // Reset loading state after 10 seconds (fallback)
            setTimeout(() => {
                if (loginButton.disabled) {
                    loginButton.disabled = false;
                    loginText.classList.remove('d-none');
                    loginLoading.classList.add('d-none');
                }
            }, 10000);
        });
    }

    // Handle page visibility change to reset button if needed
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && loginButton && loginButton.disabled) {
            // Reset button state when page becomes visible again
            setTimeout(() => {
                loginButton.disabled = false;
                loginText.classList.remove('d-none');
                loginLoading.classList.add('d-none');
            }, 1000);
        }
    });

    // Clear temp cart on successful login (based on URL parameters)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('login_success') === '1' || urlParams.get('cart_transferred') === '1') {
        localStorage.removeItem('tempCart');
        console.log('Temp cart cleared based on URL parameter');
    }

    // Initialize cart notification display
    const hasCartItems = showCartNotification();
    if (hasCartItems) {
        console.log('Cart notification displayed');
    }
    
    // For debugging - expose functions to window
    window.debugCart = {
        getTempCart: getTempCart,
        analyzeTempCart: analyzeTempCart,
        showCartNotification: showCartNotification
    };
});
</script>
</body>
</html>