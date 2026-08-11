<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Laundry Sofia</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --primary: #2BB1B1;
            --secondary: #E0F2F1;
            --tertiary: #005F73;
            --neutral-bg: #F8FAFC;
            --dark-bg: #2D3E3A;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background-color: var(--neutral-bg);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        .login-split-container {
            min-height: 100vh;
            display: flex;
        }

        /* Left Image Banner Side */
        .login-banner-side {
            flex: 1.1;
            position: relative;
            background: linear-gradient(135deg, rgba(0, 95, 115, 0.85), rgba(45, 62, 58, 0.9)),
                        url("{{ asset('assets/images/login.png') }}") center/cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3.5rem;
            color: #ffffff;
        }

        /* Fallback if local image not found */
        .login-banner-side::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url("https://images.unsplash.com/photo-1582735689369-4fe89db7114c?auto=format&fit=crop&w=1200&q=80") center/cover no-repeat;
            z-index: -1;
        }

        .banner-logo {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .banner-logo-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.25rem;
        }

        .banner-content {
            max-width: 500px;
        }

        .banner-tagline {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.25rem;
        }

        .banner-tagline span {
            color: var(--primary);
        }

        .banner-desc {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.65;
            margin-bottom: 2rem;
        }

        .banner-features {
            display: flex;
            gap: 1.5rem;
        }

        .banner-feature-item {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.85rem 1.25rem;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .banner-footer {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.65);
        }

        /* Right Form Side */
        .login-form-side {
            flex: 0.9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem;
            background-color: #ffffff;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .form-header {
            margin-bottom: 2.2rem;
        }

        .form-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #64748B;
            font-size: 0.92rem;
        }

        .user-badge {
            background-color: var(--secondary);
            color: var(--tertiary);
            font-weight: 700;
            font-size: 0.8rem;
            padding: 0.35rem 0.9rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 1rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #334155;
            margin-bottom: 0.45rem;
        }

        .form-control {
            border-radius: 12px;
            padding: 0.8rem 1.1rem;
            border: 1.5px solid #E2E8F0;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--tertiary);
            box-shadow: 0 0 0 4px rgba(0, 95, 115, 0.12);
        }

        .btn-sofia-submit {
            background-color: var(--tertiary);
            color: #ffffff;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            padding: 0.85rem 1.5rem;
            border-radius: 9999px;
            border: none;
            width: 100%;
            transition: all 0.25s ease;
            box-shadow: 0 8px 22px rgba(0, 95, 115, 0.25);
            font-size: 1rem;
        }

        .btn-sofia-submit:hover {
            background-color: #004B5B;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(0, 95, 115, 0.35);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748B;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 2rem;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--tertiary);
        }

        @media (max-width: 992px) {
            .login-banner-side { display: none; }
            .login-form-side { flex: 1; padding: 2rem; }
        }
    </style>
</head>
<body>

<div class="login-split-container">
    <!-- Left Banner Side (Gambar & Asset) -->
    <div class="login-banner-side">
        <div class="banner-logo">
            <div class="banner-logo-icon">
                <i class="fas fa-tshirt"></i>
            </div>
            Laundry Sofia
        </div>

        <div class="banner-content">
            <h1 class="banner-tagline">
                Kesegaran & Kebersihan <span>Presisi</span>
            </h1>
            <p class="banner-desc">
                Sistem Manajemen Laundry Terpadu. Kelola transaksi, pemantauan pencucian, dan layanan pelanggan secara mudah dan profesional.
            </p>
            <div class="banner-features">
                <div class="banner-feature-item">
                    <i class="fas fa-leaf text-warning fs-5"></i> 100% Eco-Friendly
                </div>
                <div class="banner-feature-item">
                    <i class="fas fa-bolt text-info fs-5"></i> Real-time Monitoring
                </div>
            </div>
        </div>

        <div class="banner-footer">
            &copy; {{ date('Y') }} Laundry Sofia. All rights reserved.
        </div>
    </div>

    <!-- Right Form Side (Form Login Staff & Konsumen) -->
    <div class="login-form-side">
        <div class="login-form-wrapper">
            <div class="form-header">
                <span class="user-badge">
                    <i class="fas fa-user-shield"></i> Portal Staff & Konsumen
                </span>
                <h2 class="form-title">Selamat Datang</h2>
                <p class="form-subtitle">Masukkan username dan password Anda untuk masuk ke sistem</p>
            </div>

            @if (session('error'))
            <div class="alert alert-danger rounded-3 d-flex align-items-center gap-2 small py-2.5 mb-4">
                <i class="fas fa-exclamation-circle fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <div class="mb-3.5 mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control border-start-0 ps-0 @error('username') is-invalid @enderror" 
                               placeholder="Masukkan username Anda" value="{{ old('username') }}" required autofocus>
                    </div>
                    @error('username')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3.5 mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                               placeholder="Masukkan password Anda" required>
                    </div>
                    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                        <label class="form-check-label text-muted small" for="rememberMe">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-sofia-submit">
                    Masuk Sekarang <i class="fas fa-arrow-right ms-2 fs-6"></i>
                </button>
            </form>

            <div class="text-center">
                <a href="{{ route('landing.index') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Kembali ke Landing Page
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
