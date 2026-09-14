<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sofia Laundry — Pimpinan')</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root { --primary: #2BB1B1; --tertiary: #005F73; }
        body, .main-sidebar, .nav-link, .card-title, table { font-family: 'Inter', sans-serif !important; }
        h1,h2,h3,h4,h5,h6,.brand-text,.content-header h1 { font-family: 'Montserrat', sans-serif !important; }

        .main-sidebar, .main-sidebar::before {
            background: linear-gradient(180deg, #005F73 0%, #004B5B 100%) !important;
            box-shadow: 4px 0 20px rgba(0,95,115,.15) !important;
        }
        .brand-link {
            background: rgba(0,0,0,.18) !important;
            border-bottom: 1px solid rgba(255,255,255,.12) !important;
            color: #fff !important;
            padding: 1.1rem 1.2rem !important;
        }
        .brand-link .brand-text { font-family: 'Montserrat', sans-serif !important; font-weight: 800 !important; font-size: 1.25rem !important; color: #fff !important; }
        .user-panel { border-bottom: 1px solid rgba(255,255,255,.1) !important; padding: 1rem 1.2rem !important; }
        .user-panel .info a { color: rgba(255,255,255,.9) !important; font-weight: 600; }
        .user-panel .info p { color: rgba(255,255,255,.6) !important; font-size: .78rem; }
        .nav-sidebar .nav-link { color: rgba(255,255,255,.8) !important; border-radius: 8px !important; margin: 1px 8px !important; padding: .55rem 1rem !important; }
        .nav-sidebar .nav-link:hover, .nav-sidebar .nav-link.active { background: rgba(255,255,255,.15) !important; color: #fff !important; }
        .nav-header { color: rgba(255,255,255,.4) !important; font-size: .65rem !important; letter-spacing: 1.2px !important; padding: .75rem 1.2rem .25rem !important; }
        .nav-icon { margin-right: .6rem !important; width: 1.1rem !important; text-align: center; opacity: .75; }
        .nav-link.active .nav-icon { opacity: 1; }
        .sidebar-badge { float: right; margin-top: 2px; font-size: .65rem !important; }
        .main-footer { background: linear-gradient(135deg, #005F73, #004B5B); color: #fff; border-top: none; padding: .75rem 1.5rem; }
        .content-wrapper { background: #f4f6f9; }
        .content-header h1 { font-size: 1.4rem !important; font-weight: 700 !important; color: #1a202c; }
        .breadcrumb-item a { color: var(--tertiary); }
    </style>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background:#fff;border-bottom:1px solid #e2e8f0;">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars" style="color:#005F73;"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center gap-2" href="#" data-toggle="dropdown">
                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#005F73,#2BB1B1);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-user-tie" style="color:#fff;font-size:.85rem;"></i>
                    </div>
                    <span style="font-weight:600;font-size:.875rem;color:#1a202c;">{{ auth()->user()->nama_user }}</span>
                    <span class="badge badge-warning" style="font-size:.65rem;">PIMPINAN</span>
                    <i class="fas fa-chevron-down" style="font-size:.7rem;color:#6b7280;margin-left:2px;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('pimpinan.profile') }}" class="dropdown-item">
                        <i class="fas fa-user-cog mr-2"></i>Profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-danger"
                        onclick="event.preventDefault();document.getElementById('logout-form-nav').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                    <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('pimpinan.dashboard') }}" class="brand-link" style="display:flex;align-items:center;gap:.75rem;">
            <i class="fas fa-tshirt" style="font-size:1.4rem;color:#fff;"></i>
            <span class="brand-text">Sofia Laundry</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3">
                <div class="image">
                    <div style="width:35px;height:35px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-user-tie" style="color:#fff;font-size:.9rem;"></i>
                    </div>
                </div>
                <div class="info">
                    <a href="{{ route('pimpinan.profile') }}" class="d-block">{{ auth()->user()->nama_user }}</a>
                    <p class="mb-0">Pimpinan</p>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-item">
                        <a href="{{ route('pimpinan.dashboard') }}" class="nav-link {{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">LAPORAN</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.laporan.transaksi.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.transaksi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>Laporan Transaksi</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.laporan.pendapatan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.pendapatan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>Laporan Pendapatan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.laporan.pertahun.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.pertahun.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Laporan Per Tahun</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.laporan.pelanggan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.pelanggan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Laporan Pelanggan</p>
                        </a>
                    </li>

                    <li class="nav-header">AKUN</li>

                    <li class="nav-item">
                        <a href="{{ route('pimpinan.profile') }}" class="nav-link {{ request()->routeIs('pimpinan.profile*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>Profil</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('landing.index') }}" class="nav-link" target="_blank">
                            <i class="nav-icon fas fa-globe"></i>
                            <p>Lihat Halaman Utama</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"
                            onclick="event.preventDefault();document.getElementById('logout-sidebar').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                        <form id="logout-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-4">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('pimpinan.dashboard') }}">Home</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
                @endif

                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer" style="background:linear-gradient(135deg,#005F73,#004B5B);color:#fff;border-top:none;padding:.75rem 1.5rem;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-tshirt mr-2" style="opacity:.7;"></i>
                <strong>Sofia Laundry</strong>
                <span style="opacity:.6;font-size:.82rem;margin-left:.5rem;">&copy; {{ date('Y') }} — Laundry Management System</span>
            </div>
            <div style="font-size:.8rem;opacity:.7;">Version 1.0.0</div>
        </div>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

@stack('scripts')
</body>
</html>
