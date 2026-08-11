<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laundry Management System')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

    <!-- Google Fonts Inter & Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2BB1B1;
            --secondary: #E0F2F1;
            --tertiary: #005F73;
            --dark-bg: #2D3E3A;
        }

        body, .main-sidebar, .nav-link, .card-title, table {
            font-family: 'Inter', sans-serif !important;
        }

        h1, h2, h3, h4, h5, h6, .brand-text, .content-header h1 {
            font-family: 'Montserrat', sans-serif !important;
        }

        /* Complete Unified Admin Sidebar Styling */
        .main-sidebar, .main-sidebar::before {
            background-color: var(--tertiary) !important;
            background: linear-gradient(180deg, #005F73 0%, #004B5B 100%) !important;
            box-shadow: 4px 0 20px rgba(0, 95, 115, 0.15) !important;
        }

        .brand-link {
            background: rgba(0, 0, 0, 0.18) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
            padding: 1.1rem 1.2rem !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
        }

        .brand-link .brand-image {
            font-size: 1.4rem !important;
            color: #ffffff !important;
            margin: 0 !important;
            opacity: 1 !important;
        }

        .brand-link .brand-text {
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 800 !important;
            font-size: 1.25rem !important;
            color: #ffffff !important;
            letter-spacing: -0.3px !important;
        }

        /* Sidebar User Panel */
        .user-panel {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 1rem 1.2rem !important;
            margin-bottom: 0.5rem !important;
        }

        .user-panel .image i {
            color: #ffffff !important;
            font-size: 2.2rem !important;
        }

        .user-panel .info {
            padding-left: 0.8rem !important;
        }

        .user-panel .info a {
            font-weight: 700 !important;
            color: #ffffff !important;
            font-size: 0.95rem !important;
        }

        .user-panel .info .badge {
            background-color: var(--secondary) !important;
            color: var(--tertiary) !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            padding: 0.25rem 0.65rem !important;
            border-radius: 9999px !important;
            margin-top: 0.2rem !important;
            display: inline-block !important;
        }

        /* Sidebar Nav Headers */
        .nav-sidebar .nav-header {
            color: #CBD5E1 !important;
            font-size: 0.75rem !important;
            font-weight: 800 !important;
            letter-spacing: 1.2px !important;
            text-transform: uppercase !important;
            padding: 1.2rem 1.2rem 0.4rem !important;
            background: transparent !important;
        }

        /* Sidebar Nav Links */
        .nav-sidebar .nav-item {
            margin-bottom: 0.25rem !important;
            padding: 0 0.5rem !important;
        }

        .nav-sidebar .nav-link {
            border-radius: 12px !important;
            padding: 0.65rem 1rem !important;
            color: rgba(255, 255, 255, 0.88) !important;
            font-size: 0.9rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
            display: flex !important;
            align-items: center !important;
        }

        .nav-sidebar .nav-link .nav-icon {
            font-size: 1.1rem !important;
            width: 1.6rem !important;
            text-align: center !important;
            margin-right: 0.6rem !important;
            color: rgba(255, 255, 255, 0.8) !important;
            transition: color 0.2s ease !important;
        }

        /* Hover State */
        .nav-sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
            transform: translateX(3px) !important;
        }

        .nav-sidebar .nav-link:hover .nav-icon {
            color: #ffffff !important;
        }

        /* ACTIVE Link State - Unified Teal Accent */
        .nav-sidebar .nav-item > .nav-link.active,
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active,
        .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            background: linear-gradient(135deg, #2BB1B1 0%, #009696 100%) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 14px rgba(43, 177, 177, 0.4) !important;
        }

        .nav-sidebar .nav-item > .nav-link.active .nav-icon {
            color: #ffffff !important;
        }

        /* Badges inside Sidebar Links */
        .nav-sidebar .nav-link .badge {
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            border-radius: 9999px !important;
            padding: 0.3rem 0.65rem !important;
            margin-left: auto !important;
        }

        /* Custom Button & Card Overrides */
        .btn-primary, .bg-primary {
            background-color: var(--tertiary) !important;
            border-color: var(--tertiary) !important;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: #004B5B !important;
            border-color: #004B5B !important;
        }

        .btn-info, .bg-info {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #ffffff !important;
        }

        .card-primary.card-outline {
            border-top-color: var(--tertiary) !important;
        }

        .card-primary:not(.card-outline) > .card-header {
            background-color: var(--tertiary) !important;
        }

        .page-item.active .page-link {
            background-color: var(--tertiary) !important;
            border-color: var(--tertiary) !important;
        }

        .badge-primary {
            background-color: var(--tertiary) !important;
        }

        /* =====================
           TOP NAVBAR STYLING
           ===================== */
        .main-header.navbar {
            background: #ffffff !important;
            border-bottom: 2px solid #E0F2F1 !important;
            box-shadow: 0 2px 16px rgba(0, 95, 115, 0.08) !important;
            min-height: 58px !important;
            padding: 0 1rem !important;
        }

        .main-header .nav-link {
            color: #2D3E3A !important;
            font-weight: 500 !important;
            font-size: 0.88rem !important;
            padding: 0 0.85rem !important;
            height: 58px !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.4rem !important;
            transition: color 0.2s !important;
        }

        .main-header .nav-link:hover {
            color: #005F73 !important;
        }

        .main-header .pushmenu-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: #F0FAF9;
            border: none;
            display: flex; align-items: center; justify-content: center;
            color: #005F73;
            cursor: pointer;
            transition: background 0.2s;
        }

        .main-header .pushmenu-btn:hover {
            background: #E0F2F1;
        }

        /* Navbar brand indicator / page title strip */
        .navbar-page-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.88rem;
            font-weight: 600;
            color: #005F73;
        }

        .navbar-page-indicator .separator {
            color: #CBD5E1;
            font-weight: 300;
        }

        /* Navbar user chip */
        .navbar-user-chip {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.4rem 0.85rem 0.4rem 0.5rem;
            border-radius: 9999px;
            background: #F0FAF9;
            border: 1.5px solid #E0F2F1;
            color: #005F73;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .navbar-user-chip:hover {
            background: #E0F2F1;
            color: #004B5B;
        }

        .navbar-user-chip .avatar-chip {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2BB1B1, #005F73);
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 0.75rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        /* Navbar action icons */
        .navbar-icon-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: transparent;
            border: none;
            display: flex; align-items: center; justify-content: center;
            color: #64748B;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .navbar-icon-btn:hover {
            background: #F0FAF9;
            color: #005F73;
        }

        /* Dropdown menu */
        .navbar-user-chip + .dropdown-menu {
            border: none !important;
            border-radius: 14px !important;
            box-shadow: 0 8px 30px rgba(0,95,115,0.15) !important;
            padding: 0.5rem !important;
            min-width: 200px !important;
        }

        .navbar-user-chip + .dropdown-menu .dropdown-item {
            border-radius: 10px !important;
            padding: 0.6rem 1rem !important;
            font-size: 0.88rem !important;
            font-weight: 500 !important;
            color: #2D3E3A !important;
            transition: background 0.15s !important;
        }

        .navbar-user-chip + .dropdown-menu .dropdown-item:hover {
            background: #F0FAF9 !important;
            color: #005F73 !important;
        }

        /* =====================
           CONTENT HEADER
           ===================== */
        .content-header {
            background: linear-gradient(135deg, #005F73 0%, #007A8A 60%, #2BB1B1 100%) !important;
            padding: 1.1rem 1.5rem !important;
            margin-bottom: 0 !important;
            position: relative;
            overflow: hidden;
        }

        .content-header::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }

        .content-header::after {
            content: '';
            position: absolute;
            bottom: -20px; right: 120px;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            pointer-events: none;
        }

        .content-header h1 {
            color: #ffffff !important;
            font-size: 1.3rem !important;
            font-weight: 800 !important;
            margin: 0 !important;
            letter-spacing: -0.3px !important;
            text-shadow: 0 1px 4px rgba(0,0,0,0.1) !important;
        }

        .content-header .breadcrumb {
            background: rgba(255,255,255,0.15) !important;
            border-radius: 9999px !important;
            padding: 0.35rem 1rem !important;
            margin: 0 !important;
            backdrop-filter: blur(4px);
        }

        .content-header .breadcrumb-item {
            font-size: 0.8rem !important;
            font-weight: 500 !important;
            color: rgba(255,255,255,0.8) !important;
        }

        .content-header .breadcrumb-item a {
            color: rgba(255,255,255,0.75) !important;
            text-decoration: none !important;
        }

        .content-header .breadcrumb-item a:hover {
            color: #ffffff !important;
        }

        .content-header .breadcrumb-item.active {
            color: #ffffff !important;
            font-weight: 700 !important;
        }

        .content-header .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255,255,255,0.5) !important;
        }

        /* Content wrapper bg */
        .content-wrapper {
            background: #F8FAFC !important;
        }

        /* Spacing below content-header */
        .content {
            padding-top: 1.5rem !important;
        }

        .content-header + .content {
            padding-top: 1.5rem !important;
        }
    </style>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand">
        <!-- Left navbar links -->
        <ul class="navbar-nav align-items-center" style="gap:0.5rem;">
            <li class="nav-item">
                <button class="pushmenu-btn" data-widget="pushmenu" role="button" style="background:transparent;border:none;width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#005F73;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='#F0FAF9'" onmouseout="this.style.background='transparent'">
                    <i class="fas fa-bars" style="font-size:1rem;"></i>
                </button>
            </li>
            <li class="nav-item d-none d-sm-flex align-items-center" style="gap:0.4rem;">
                <i class="fas fa-home" style="color:#94A3B8;font-size:0.8rem;"></i>
                <a href="{{ route('admin.dashboard') }}" style="font-size:0.85rem;font-weight:500;color:#94A3B8;text-decoration:none;">Home</a>
                <span style="color:#CBD5E1;">/</span>
                <span style="font-size:0.85rem;font-weight:600;color:#005F73;">@yield('page-title', 'Dashboard')</span>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto align-items-center" style="gap:0.5rem;">
            <li class="nav-item">
                <a class="navbar-icon-btn" data-widget="fullscreen" href="#" role="button" title="Fullscreen"
                   style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#64748B;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#F0FAF9';this.style.color='#005F73'" onmouseout="this.style.background='transparent';this.style.color='#64748B'">
                    <i class="fas fa-expand-arrows-alt" style="font-size:0.9rem;"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="navbar-user-chip dropdown-toggle" data-toggle="dropdown" href="#" id="userDropdown"
                   style="display:flex;align-items:center;gap:0.55rem;padding:0.35rem 0.9rem 0.35rem 0.45rem;border-radius:9999px;background:#F0FAF9;border:1.5px solid #E0F2F1;color:#005F73;font-size:0.82rem;font-weight:700;cursor:pointer;transition:all 0.2s;text-decoration:none;">
                    <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#2BB1B1,#005F73);display:flex;align-items:center;justify-content:center;color:white;font-size:0.7rem;font-weight:800;flex-shrink:0;">
                        {{ strtoupper(substr(auth()->user()->nama_user ?? 'A', 0, 1)) }}
                    </div>
                    <span class="d-none d-md-inline">{{ auth()->user()->nama_user ?? 'Admin' }}</span>
                    <i class="fas fa-chevron-down" style="font-size:0.65rem;opacity:0.7;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" style="border:none;border-radius:14px;box-shadow:0 8px 30px rgba(0,95,115,0.15);padding:0.5rem;min-width:200px;margin-top:0.5rem;">
                    <div style="padding:0.75rem 1rem 0.5rem;border-bottom:1px solid #F0F0F0;margin-bottom:0.4rem;">
                        <div style="font-weight:700;font-size:0.88rem;color:#2D3E3A;">{{ auth()->user()->nama_user ?? 'Admin' }}</div>
                        <div style="font-size:0.78rem;color:#94A3B8;">{{ ucfirst(auth()->user()->role ?? '') }}</div>
                    </div>
                    <a href="{{ route('admin.profile') }}" class="dropdown-item" style="border-radius:10px;padding:0.6rem 1rem;font-size:0.85rem;font-weight:500;color:#2D3E3A;display:flex;align-items:center;gap:0.5rem;">
                        <i class="fas fa-user" style="width:16px;color:#005F73;"></i> Profil Saya
                    </a>
                    <div style="border-top:1px solid #F0F0F0;margin:0.4rem 0;"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" style="border-radius:10px;padding:0.6rem 1rem;font-size:0.85rem;font-weight:500;color:#DC2626;display:flex;align-items:center;gap:0.5rem;"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt" style="width:16px;"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <i class="fas fa-tshirt brand-image"></i>
            <span class="brand-text font-weight-light">Sofia Laundry</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <i class="fas fa-user-circle fa-2x text-white"></i>
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ auth()->user()->nama_user ?? 'Admin' }}</a>
                    <span class="badge badge-light">{{ ucfirst(auth()->user()->role ?? '') }}</span>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Lihat Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">DATA MASTER</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.pelanggans.index') }}" class="nav-link {{ request()->routeIs('admin.pelanggans.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Kelola Data Pelanggan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.layanans.index') }}" class="nav-link {{ request()->routeIs('admin.layanans.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-concierge-bell"></i>
                            <p>Kelola Data Layanan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.stok_barangs.index') }}" class="nav-link {{ request()->routeIs('admin.stok_barangs.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Kelola Stok Barang</p>
                        </a>
                    </li>

                    <li class="nav-header">TRANSAKSI</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.monitoring.index') }}" class="nav-link {{ request()->routeIs('admin.monitoring.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-satellite-dish"></i>
                            <p>Update Status Cucian</p>
                            @php $aktif = \App\Models\Transaksi::whereNotIn('status', ['diambil'])->count(); @endphp
                            @if ($aktif > 0)
                            <span class="badge badge-primary right">{{ $aktif }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>Kelola Booking</p>
                            @php
                                $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
                            @endphp
                            @if ($pendingCount > 0)
                            <span class="badge badge-warning right">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.transaksis.index') }}" class="nav-link {{ request()->routeIs('admin.transaksis.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Buat Transaksi Laundry</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.pengembalians.index') }}" class="nav-link {{ request()->routeIs('admin.pengembalians.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-undo"></i>
                            <p>Kelola Pengambilan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.pembayarans.index') }}" class="nav-link {{ request()->routeIs('admin.pembayarans.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p>Catat Pembayaran</p>
                            @php $belumLunas = \App\Models\Pembayaran::where('status_bayar','belum')->count(); @endphp
                            @if ($belumLunas > 0)
                            <span class="badge badge-danger right">{{ $belumLunas }}</span>
                            @endif
                        </a>
                    </li>
                    @endif

                    <li class="nav-header">LAPORAN</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.laporan.transaksi.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.transaksi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>Cetak Laporan Transaksi</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.laporan.pertahun.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.pertahun.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Cetak Laporan Pertahun</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.laporan.pelanggan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.pelanggan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Laporan Pelanggan</p>
                        </a>
                    </li>

                    @if(auth()->user()->role === 'admin')
                    <li class="nav-header">AKUN</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>Kelola Data Pengguna</p>
                        </a>
                    </li>


                    @endif

                    <li class="nav-header">SISTEM</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>Profil & Pengaturan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('landing.index') }}" class="nav-link" target="_blank">
                            <i class="nav-icon fas fa-globe"></i>
                            <p>Lihat Landing Page</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"
                           onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="width:42px;height:42px;border-radius:12px;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-tachometer-alt" style="color:#ffffff;font-size:1.1rem;"></i>
                            </div>
                            <div>
                                <h1 class="m-0" style="font-size:1.22rem;font-weight:800;color:#fff;letter-spacing:-0.3px;">@yield('page-title', 'Dashboard')</h1>
                                <div style="font-size:0.78rem;color:rgba(255,255,255,0.72);margin-top:2px;">@yield('page-subtitle', 'Laundry Sofia Management System')</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 d-none d-sm-block text-right">
                        <span style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.8rem;color:rgba(255,255,255,0.75);font-weight:500;">
                            <i class="fas fa-home" style="font-size:0.72rem;"></i>
                            <span>Home</span>
                            <i class="fas fa-chevron-right" style="font-size:0.65rem;opacity:0.6;"></i>
                            <span style="color:#ffffff;font-weight:700;">@yield('page-title', 'Dashboard')</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="#">Laundry Management System</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    });
</script>

@stack('scripts')
</body>
</html>
