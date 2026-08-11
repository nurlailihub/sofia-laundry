<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sofia Laundry') — Dashboard Pelanggan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
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

        body {
            background: var(--neutral-bg);
            font-family: 'Inter', sans-serif;
            color: #2D3E3A;
        }

        h1, h2, h3, h4, h5, h6, .sidebar-brand {
            font-family: 'Montserrat', sans-serif;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #005F73 0%, #004B5B 100%);
            position: fixed;
            left: 0; top: 0;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0, 95, 115, 0.15);
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            color: white;
            font-size: 1.25rem;
            font-weight: 800;
            border-bottom: 1px solid rgba(255,255,255,.12);
            background: rgba(0,0,0,0.15);
        }

        .sidebar-user {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
            margin-bottom: 0.5rem;
        }

        .sidebar-user .avatar {
            width: 42px; height: 42px;
            background: rgba(255,255,255,.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.1rem;
        }

        .sidebar-nav {
            padding: 0 0.5rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem 1rem;
            color: rgba(255,255,255,.85);
            text-decoration: none;
            font-size: .9rem;
            font-weight: 500;
            border-radius: 12px;
            margin-bottom: 0.25rem;
            transition: all .2s ease;
        }

        .sidebar-nav a:hover {
            background: rgba(255,255,255,.12);
            color: white;
            transform: translateX(3px);
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, #2BB1B1 0%, #009696 100%);
            color: white;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(43, 177, 177, 0.4);
        }

        .sidebar-nav a i { width: 18px; text-align: center; }

        .sidebar-nav .nav-divider {
            border-top: 1px solid rgba(255,255,255,.1);
            margin: .75rem 0;
        }

        .sidebar-nav form button {
            all: unset;
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem 1.5rem;
            color: rgba(255,255,255,.8);
            font-size: .9rem;
            cursor: pointer;
            width: 100%;
            transition: all .15s;
        }

        .sidebar-nav form button:hover {
            background: rgba(255,255,255,.15);
            color: white;
        }

        .main-content {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
        }

        .top-bar {
            background: white;
            padding: .75rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 8px rgba(0,0,0,.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            border: none;
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; padding: 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-tshirt me-2"></i>Sofia Laundry
    </div>
    <div class="sidebar-user d-flex align-items-center gap-2 mt-2">
        <div class="avatar flex-shrink-0"><i class="fas fa-user"></i></div>
        <div>
            <div class="text-white fw-semibold small">{{ auth()->user()->nama_user }}</div>
            <div style="color:rgba(255,255,255,.6);font-size:.75rem;">Pelanggan</div>
        </div>
    </div>
    <nav class="sidebar-nav mt-2">
        <a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('customer.riwayat') }}" class="{{ request()->routeIs('customer.riwayat') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Cek Status Laundry
        </a>
        <a href="{{ route('customer.booking') }}" class="{{ request()->routeIs('customer.booking') ? 'active' : '' }}">
            <i class="fas fa-calendar-plus"></i> Booking Layanan
        </a>
        <div class="nav-divider"></div>
        <a href="{{ route('landing.index') }}" target="_blank">
            <i class="fas fa-globe"></i> Halaman Utama
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">
                <i class="fas fa-sign-out-alt" style="width:18px;text-align:center;"></i> Keluar
            </button>
        </form>
    </nav>
</div>

<div class="main-content">
    <div class="top-bar">
        <h6 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h6>
        <span class="text-muted small">
            <i class="fas fa-clock me-1"></i>{{ now()->format('d F Y, H:i') }}
        </span>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
