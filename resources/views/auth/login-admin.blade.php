<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Sofia Laundry</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 1.5rem;
        }

        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 24px rgba(29, 78, 216, 0.4);
        }

        .brand h1 {
            color: white;
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand p {
            color: #64748b;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .card {
            background: #1e293b;
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid #334155;
        }

        .card-title {
            color: #e2e8f0;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .card-subtitle {
            color: #64748b;
            font-size: 0.8rem;
            margin-bottom: 1.75rem;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            color: #94a3b8;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #475569;
            font-size: 0.9rem;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            color: #e2e8f0;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        input::placeholder { color: #475569; }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #3b82f6;
        }

        .remember-row label {
            text-transform: none;
            letter-spacing: 0;
            font-weight: 400;
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
            cursor: pointer;
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
        }

        .btn-login:hover { opacity: 0.92; }
        .btn-login:active { transform: scale(0.99); }

        .divider {
            border: none;
            border-top: 1px solid #1e293b;
            margin: 1.5rem 0;
        }

        .customer-link {
            text-align: center;
            color: #64748b;
            font-size: 0.8rem;
        }

        .customer-link a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
        }

        .customer-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="brand">
        <div class="brand-icon"><i class="fas fa-tshirt"></i></div>
        <h1>Sofia Laundry</h1>
        <p>Sistem Manajemen Laundry</p>
    </div>

    <div class="card">
        <div class="card-title">Masuk sebagai Admin</div>
        <div class="card-subtitle">Khusus Admin & Pimpinan</div>

        @if (session('error'))
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Masukkan username"
                        value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i>
                Masuk
            </button>
        </form>

        <hr class="divider">

        <div class="customer-link">
            Pelanggan? <a href="{{ route('login.customer') }}">Login di sini</a>
        </div>
    </div>
</div>
</body>
</html>
