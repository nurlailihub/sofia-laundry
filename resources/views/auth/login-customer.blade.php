<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan — Sofia Laundry</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
        }

        .left-panel {
            flex: 1;
            position: relative;
            overflow: hidden;
            display: none;
        }

        @media (min-width: 768px) {
            .left-panel { display: block; }
        }

        .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .slide.active { opacity: 1; }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(15, 23, 42, 0.3) 0%,
                rgba(15, 23, 42, 0.7) 100%
            );
        }

        .slide-content {
            position: absolute;
            bottom: 3rem;
            left: 3rem;
            right: 3rem;
            color: white;
        }

        .slide-content h2 {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 0.75rem;
            text-shadow: 0 2px 8px rgba(0,0,0,0.4);
        }

        .slide-content p {
            font-size: 1rem;
            opacity: 0.85;
            text-shadow: 0 1px 4px rgba(0,0,0,0.4);
        }

        .slide-dots {
            position: absolute;
            bottom: 1.5rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.4);
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
        }

        .dot.active {
            background: white;
            transform: scale(1.3);
        }

        .right-panel {
            width: 100%;
            max-width: 480px;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem;
            box-shadow: -4px 0 32px rgba(0,0,0,0.08);
            position: relative;
        }

        @media (min-width: 768px) {
            .right-panel { width: 440px; flex-shrink: 0; }
        }

        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #1d4ed8, #06b6d4);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: white;
            margin: 0 auto 1rem;
            box-shadow: 0 6px 20px rgba(29, 78, 216, 0.3);
        }

        .brand h1 {
            color: #1e293b;
            font-size: 1.5rem;
            font-weight: 800;
        }

        .brand p {
            color: #64748b;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-card {
            width: 100%;
        }

        .form-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .form-subtitle {
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 1.75rem;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group { margin-bottom: 1.25rem; }

        label {
            display: block;
            color: #374151;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .input-wrap { position: relative; }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 0.9rem;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.8rem 1rem 0.8rem 2.75rem;
            color: #1f2937;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        input::placeholder { color: #9ca3af; }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #2563eb;
        }

        .remember-row span {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(29, 78, 216, 0.3);
        }

        .btn-login:hover { opacity: 0.92; }
        .btn-login:active { transform: scale(0.99); }

        .footer-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: #9ca3af;
        }

        .footer-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-link a:hover { text-decoration: underline; }

        .back-link {
            position: absolute;
            top: 1.25rem;
            left: 1.25rem;
            color: #6b7280;
            font-size: 0.8rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .back-link:hover { color: #2563eb; }
    </style>
</head>
<body>

<div class="left-panel">
    <div class="slide active" id="slide-0">
        <img src="{{ asset('images/login.png') }}" alt="Sofia Laundry">
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <h2>Cucian Bersih,<br>Wangi & Tepat Waktu</h2>
            <p>Layanan laundry profesional dengan teknologi modern</p>
        </div>
    </div>
    <div class="slide" id="slide-1">
        <img src="{{ asset('images/login.png') }}" alt="Sofia Laundry">
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <h2>Pantau Status<br>Laundry Anda</h2>
            <p>Cek progres cucian secara real-time dari dashboard</p>
        </div>
    </div>
    <div class="slide" id="slide-2">
        <img src="{{ asset('images/login.png') }}" alt="Sofia Laundry">
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <h2>Notifikasi<br>WhatsApp Otomatis</h2>
            <p>Anda langsung diberi tahu saat cucian siap diambil</p>
        </div>
    </div>
    <div class="slide-dots">
        <div class="dot active" onclick="goToSlide(0)"></div>
        <div class="dot" onclick="goToSlide(1)"></div>
        <div class="dot" onclick="goToSlide(2)"></div>
    </div>
</div>

<div class="right-panel">
    <a href="{{ route('login') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Admin
    </a>

    <div class="brand">
        <div class="brand-icon"><i class="fas fa-tshirt"></i></div>
        <h1>Sofia Laundry</h1>
        <p>Portal Pelanggan</p>
    </div>

    <div class="form-card">
        <div class="form-title">Selamat Datang!</div>
        <div class="form-subtitle">Masuk untuk cek status laundry Anda</div>

        @if (session('error'))
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('login.customer.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username dari admin"
                        value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password dari admin" required>
                </div>
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <span>Ingat saya</span>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i>
                Masuk
            </button>
        </form>

        <div class="footer-link">
            Belum punya akun? Hubungi admin laundry kami.<br>
            <a href="{{ route('landing.index') }}">Kembali ke halaman utama</a>
        </div>
    </div>
</div>

<script>
let current = 0;
const total = 3;

function goToSlide(n) {
    document.getElementById('slide-' + current).classList.remove('active');
    document.querySelectorAll('.dot')[current].classList.remove('active');
    current = n;
    document.getElementById('slide-' + current).classList.add('active');
    document.querySelectorAll('.dot')[current].classList.add('active');
}

setInterval(() => {
    goToSlide((current + 1) % total);
}, 4000);
</script>
</body>
</html>
