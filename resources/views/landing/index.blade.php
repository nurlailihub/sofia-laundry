<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Sofia — Fresh Precision in Every Fold</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --primary: #2BB1B1;
            --secondary: #E0F2F1;
            --tertiary: #005F73;
            --neutral-bg: #F8FAFC;
            --dark-bg: #2D3E3A;
            --font-headline: 'Montserrat', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        * { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background-color: var(--neutral-bg);
            color: #2D3E3A;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .font-headline {
            font-family: var(--font-headline);
        }

        /* Navbar */
        .navbar-sofia {
            background: #ffffff;
            transition: all 0.3s ease;
            box-shadow: 0 2px 20px rgba(0, 95, 115, 0.05);
            padding: 1rem 0;
        }
        .navbar-brand-text {
            font-family: var(--font-headline);
            font-weight: 800;
            color: var(--tertiary);
            font-size: 1.45rem;
            letter-spacing: -0.5px;
            text-decoration: none;
        }
        .nav-link-sofia {
            font-weight: 600;
            color: #475569 !important;
            font-size: 0.95rem;
            margin: 0 0.75rem;
            transition: color 0.2s ease;
        }
        .nav-link-sofia:hover, .nav-link-sofia.active {
            color: var(--tertiary) !important;
        }
        .btn-book-now {
            background-color: var(--tertiary);
            color: #ffffff !important;
            font-weight: 700;
            padding: 0.6rem 1.8rem;
            border-radius: 9999px;
            border: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(0, 95, 115, 0.2);
            text-decoration: none;
            display: inline-block;
        }
        .btn-book-now:hover {
            background-color: #004B5B;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 95, 115, 0.3);
        }

        /* Hero Section */
        .hero-section {
            padding-top: 130px;
            padding-bottom: 90px;
            background: linear-gradient(180deg, #EBF7F7 0%, var(--neutral-bg) 100%);
            position: relative;
        }
        .hero-badge {
            background-color: #D6F1F1;
            color: var(--tertiary);
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0.45rem 1.1rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 1.5rem;
        }
        .hero-title {
            font-size: clamp(2.3rem, 4.5vw, 3.4rem);
            font-weight: 800;
            line-height: 1.18;
            color: #0F172A;
            margin-bottom: 1.25rem;
        }
        .hero-title span {
            color: var(--tertiary);
        }
        .hero-desc {
            font-size: 1.05rem;
            color: #475569;
            line-height: 1.65;
            max-width: 520px;
            margin-bottom: 2.2rem;
        }
        .btn-primary-sofia {
            background-color: var(--tertiary);
            color: #ffffff !important;
            font-weight: 700;
            padding: 0.85rem 2.2rem;
            border-radius: 9999px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 16px rgba(0, 95, 115, 0.25);
        }
        .btn-primary-sofia:hover {
            background-color: #004B5B;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 95, 115, 0.35);
        }
        .btn-outline-sofia {
            background-color: transparent;
            color: var(--tertiary);
            border: 2px solid var(--tertiary);
            font-weight: 700;
            padding: 0.8rem 2.2rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .btn-outline-sofia:hover {
            background-color: var(--tertiary);
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Hero Image Container */
        .hero-img-wrapper {
            position: relative;
            border-radius: 28px;
            overflow: visible;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            background: #ffffff;
            padding: 12px;
        }
        .hero-img-inner {
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            background: #e2e8f0;
            min-height: 380px;
        }
        .hero-img-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .eco-badge-card {
            position: absolute;
            bottom: -25px;
            left: -20px;
            background: #ffffff;
            border-radius: 16px;
            padding: 1rem 1.4rem;
            box-shadow: 0 10px 30px rgba(0, 95, 115, 0.12);
            display: flex;
            align-items: center;
            gap: 0.85rem;
            z-index: 5;
            border: 1px solid rgba(224, 242, 241, 0.8);
        }
        .eco-icon-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background-color: var(--secondary);
            color: var(--tertiary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        /* Layanan Section */
        .section-header {
            text-align: center;
            margin-bottom: 3.5rem;
        }
        .section-title {
            font-weight: 800;
            font-size: 2.1rem;
            color: #0F172A;
            margin-bottom: 0.75rem;
        }
        .section-underline {
            width: 54px;
            height: 4px;
            background-color: var(--tertiary);
            border-radius: 4px;
            margin: 0 auto;
        }

        .layanan-card-modern {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            padding: 1.75rem;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .layanan-card-modern:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 95, 115, 0.1);
            border-color: var(--primary);
        }
        .layanan-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--tertiary), var(--primary));
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 1.25rem;
        }

        /* How it Works Section */
        .how-it-works-section {
            background-color: #EBF7F7;
            padding: 90px 0;
        }
        .step-circle-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background-color: var(--tertiary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 20px rgba(0, 95, 115, 0.25);
            position: relative;
            z-index: 2;
        }
        .steps-connector-row {
            position: relative;
        }
        @media (min-width: 768px) {
            .steps-connector-row::before {
                content: '';
                position: absolute;
                top: 36px;
                left: 12%;
                right: 12%;
                height: 2px;
                border-top: 2px dashed var(--tertiary);
                z-index: 1;
                opacity: 0.5;
            }
        }
        .step-card {
            position: relative;
            z-index: 2;
        }
        .step-title {
            font-weight: 700;
            font-size: 1.15rem;
            color: #0F172A;
            margin-bottom: 0.5rem;
        }
        .step-desc {
            font-size: 0.9rem;
            color: #475569;
            line-height: 1.5;
        }

        /* Testimonial Section */
        .testimonial-section {
            background-color: #D9EFEF;
            padding: 90px 0;
        }
        .testimonial-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 10px 30px rgba(0, 95, 115, 0.06);
            border: none;
        }
        .quote-icon {
            color: var(--tertiary);
            font-size: 2.2rem;
            font-weight: 800;
            font-family: var(--font-headline);
            line-height: 1;
            margin-bottom: 1rem;
        }
        .testimonial-text {
            font-style: italic;
            color: #334155;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        .user-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
            background-color: var(--secondary);
        }

        /* Cek Status Box */
        .status-box-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 12px 40px rgba(0, 95, 115, 0.08);
            border: 1px solid #E2E8F0;
        }

        /* Status Badge */
        .status-badge-sofia {
            padding: 0.35rem 0.9rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-block;
        }
        .sb-proses    { background:#FEF3C7; color:#92400E; }
        .sb-selesai   { background:#D1FAE5; color:#065F46; }
        .sb-diambil   { background:#E0E7FF; color:#3730A3; }
        .sb-pending   { background:#FEF3C7; color:#92400E; }
        .sb-confirmed { background:#D1FAE5; color:#065F46; }
        .sb-cancelled { background:#FEE2E2; color:#991B1B; }

        /* Footer */
        .footer-sofia {
            background-color: var(--dark-bg);
            color: #94A3B8;
            padding-top: 70px;
            padding-bottom: 35px;
        }
        .footer-brand {
            color: #ffffff;
            font-family: var(--font-headline);
            font-weight: 800;
            font-size: 1.4rem;
            margin-bottom: 1rem;
        }
        .footer-desc {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #CBD5E1;
        }
        .footer-heading {
            color: #ffffff;
            font-family: var(--font-headline);
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 1.2rem;
        }
        .footer-links a {
            color: #CBD5E1;
            text-decoration: none;
            transition: color 0.2s ease;
            font-size: 0.9rem;
            display: block;
            margin-bottom: 0.6rem;
        }
        .footer-links a:hover {
            color: var(--primary);
        }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 3.5rem;
            padding-top: 1.5rem;
            font-size: 0.85rem;
            color: #94A3B8;
        }
        .footer-contact-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-sofia fixed-top">
    <div class="container">
        <a class="navbar-brand navbar-brand-text d-flex align-items-center gap-2" href="#beranda">
            <span class="d-inline-block rounded-circle bg-opacity-10 p-2" style="background-color: var(--secondary); color: var(--tertiary);">
                <i class="fas fa-tshirt fs-5"></i>
            </span>
            Laundry Sofia
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenuSofia">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenuSofia">
            <ul class="navbar-nav mx-auto align-items-center">
                <li class="nav-item"><a class="nav-link nav-link-sofia" href="#layanan">Services</a></li>
                <li class="nav-item"><a class="nav-link nav-link-sofia" href="#layanan">Pricing</a></li>
                <li class="nav-item"><a class="nav-link nav-link-sofia" href="#cara-kerja">How it Works</a></li>
                <li class="nav-item"><a class="nav-link nav-link-sofia" href="#cek-status">Cek Status</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('login') }}" class="btn btn-book-now">Book Now</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section" id="beranda">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="fas fa-sparkles"></i> #1 Laundry Service in Sofia
                </div>
                <h1 class="hero-title">
                    Kesegaran Sempurna untuk <span>Pakaian Anda</span>
                </h1>
                <p class="hero-desc">
                    Nikmati kemudahan layanan laundry profesional dengan sistem antar-jemput gratis. Kami merawat setiap serat pakaian Anda dengan penuh ketelitian.
                </p>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <a href="{{ route('login') }}" class="btn btn-primary-sofia">
                        Pesan Sekarang <i class="fas fa-arrow-right fs-6"></i>
                    </a>
                    <a href="#layanan" class="btn btn-outline-sofia">
                        Lihat Menu
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-img-wrapper">
                    <div class="hero-img-inner">
                        <img src="https://images.unsplash.com/photo-1582735689369-4fe89db7114c?auto=format&fit=crop&w=1000&q=80" alt="Laundry Sofia Clean Linen" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1545173168-9f1947eebb7f?auto=format&fit=crop&w=1000&q=80';">
                    </div>
                    <div class="eco-badge-card">
                        <div class="eco-icon-circle">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">100% Eco-Friendly</div>
                            <div class="text-muted" style="font-size: 0.78rem;">Safe for your skin & planet</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Layanan Section -->
<section class="py-5 bg-white" id="layanan">
    <div class="container py-4">
        <div class="section-header">
            <h2 class="section-title">Layanan Unggulan Kami</h2>
            <div class="section-underline"></div>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse ($layanans as $layanan)
            <div class="col-md-6 col-lg-4">
                <div class="layanan-card-modern">
                    <div class="layanan-icon-box">
                        <i class="fas fa-soap"></i>
                    </div>
                    <h4 class="fw-bold fs-5 mb-2" style="color: #0F172A;">{{ $layanan->nama_layanan }}</h4>
                    <p class="text-muted small mb-4 flex-grow-1">
                        {{ $layanan->keterangan ?? 'Layanan laundry higienis profesional dengan wangi tahan lama dan hasil bebas kuman.' }}
                    </p>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <div>
                            <span class="text-muted small d-block">Mulai dari</span>
                            <span class="fw-bold fs-4" style="color: var(--tertiary);">Rp {{ number_format($layanan->harga_per_kg, 0, ',', '.') }}</span>
                            <span class="text-muted small">/kg</span>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-book-now px-3 py-2 fs-7">Pilih</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-md-6 col-lg-4">
                <div class="layanan-card-modern">
                    <div class="layanan-icon-box"><i class="fas fa-tshirt"></i></div>
                    <h4 class="fw-bold fs-5 mb-2">Cuci & Lipat (Kiloan)</h4>
                    <p class="text-muted small mb-4">Pakaian dicuci higienis, dikeringkan, dan dilipat rapi siap masuk lemari.</p>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <span class="fw-bold fs-4" style="color: var(--tertiary);">Rp 7.000<small class="text-muted fs-6">/kg</small></span>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-book-now px-3 py-2">Pilih</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="layanan-card-modern">
                    <div class="layanan-icon-box"><i class="fas fa-iron"></i></div>
                    <h4 class="fw-bold fs-5 mb-2">Setrika Uap</h4>
                    <p class="text-muted small mb-4">Perawatan setrika uap bebas kusut untuk pakaian kerja dan kasual Anda.</p>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <span class="fw-bold fs-4" style="color: var(--tertiary);">Rp 9.000<small class="text-muted fs-6">/kg</small></span>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-book-now px-3 py-2">Pilih</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="layanan-card-modern">
                    <div class="layanan-icon-box"><i class="fas fa-user-tie"></i></div>
                    <h4 class="fw-bold fs-5 mb-2">Dry Cleaning Jas & Gaun</h4>
                    <p class="text-muted small mb-4">Pencucian khusus bahan lembut seperti jas, kemeja batik, dan gaun mewah.</p>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <span class="fw-bold fs-4" style="color: var(--tertiary);">Rp 25.000<small class="text-muted fs-6">/pcs</small></span>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-book-now px-3 py-2">Pilih</a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works-section" id="cara-kerja">
    <div class="container text-center">
        <div class="mb-5">
            <h2 class="section-title">Proses 4 Langkah Mudah</h2>
            <p class="text-muted">Kami mengurus semuanya, Anda cukup santai di rumah.</p>
        </div>

        <div class="row g-4 steps-connector-row">
            <div class="col-6 col-md-3 step-card">
                <div class="step-circle-icon">
                    <i class="fas fa-th-large"></i>
                </div>
                <h5 class="step-title">1. Pesan</h5>
                <p class="step-desc">Pilih layanan melalui aplikasi atau website kami.</p>
            </div>
            <div class="col-6 col-md-3 step-card">
                <div class="step-circle-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h5 class="step-title">2. Penjemputan</h5>
                <p class="step-desc">Kurir kami akan menjemput pakaian kotor di depan pintu Anda.</p>
            </div>
            <div class="col-6 col-md-3 step-card">
                <div class="step-circle-icon">
                    <i class="fas fa-tint"></i>
                </div>
                <h5 class="step-title">3. Cuci</h5>
                <p class="step-desc">Pakaian Anda dicuci dengan standar kebersihan tertinggi.</p>
            </div>
            <div class="col-6 col-md-3 step-card">
                <div class="step-circle-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h5 class="step-title">4. Pengantaran</h5>
                <p class="step-desc">Pakaian bersih diantar kembali tepat waktu ke rumah Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonial-section">
    <div class="container text-center">
        <div class="mb-5">
            <h2 class="section-title">Apa Kata Pelanggan Kami</h2>
        </div>

        <div class="row g-4 text-start">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="quote-icon">”</div>
                    <p class="testimonial-text">
                        "Layanan yang sangat memuaskan! Pakaian saya yang kotor kena noda kopi hilang tanpa sisa. Pengantarannya pun sangat tepat waktu."
                    </p>
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80" alt="Maya Permata" class="user-avatar">
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">Maya Permata</div>
                            <div class="text-muted" style="font-size: 0.8rem;">Office Executive</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="quote-icon">”</div>
                    <p class="testimonial-text">
                        "Sangat membantu untuk saya yang sibuk. Fitur jemput laundry sangat efisien dan kurirnya ramah sekali. Wangi laundry-nya juara!"
                    </p>
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80" alt="Budi Santoso" class="user-avatar">
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">Budi Santoso</div>
                            <div class="text-muted" style="font-size: 0.8rem;">Freelance Designer</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="quote-icon">”</div>
                    <p class="testimonial-text">
                        "Kualitas dry cleaning jas saya sangat bagus. Tidak ada bau kimia sama sekali dan serat kain tetap terjaga. Highly recommended!"
                    </p>
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=150&q=80" alt="Siska Wijaya" class="user-avatar">
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">Siska Wijaya</div>
                            <div class="text-muted" style="font-size: 0.8rem;">Entrepreneur</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cek Status Section (Direct Database Lookup) -->
<section class="py-5 bg-white border-top" id="cek-status">
    <div class="container py-4">
        <div class="section-header">
            <h2 class="section-title">Cek Status Cucian Anda</h2>
            <p class="text-muted">Masukkan Nomor HP atau Kode Booking / Transaksi untuk memantau status pesanan langsung dari database</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="status-box-card p-4 mb-4">
                    <form action="{{ route('landing.cek-status.post') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 fs-5 text-success">
                                <i class="fas fa-search" style="color: var(--tertiary);"></i>
                            </span>
                            <input type="text" name="keyword"
                                class="form-control border-start-0 ps-0 form-control-lg @error('keyword') is-invalid @enderror"
                                placeholder="Masukkan No. HP atau Kode Booking (contoh: 0812...)"
                                value="{{ old('keyword', request('keyword')) }}" required>
                            <button type="submit" class="btn btn-book-now px-4">
                                Cek Status
                            </button>
                        </div>
                        @error('keyword')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                    </form>
                </div>

                @if (session('error'))
                <div class="alert alert-danger rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2">
                    <i class="fas fa-exclamation-circle fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
                @endif

                @isset($pelanggan)
                <div class="card border-0 rounded-4 shadow-sm p-3 mb-4 d-flex flex-row align-items-center gap-3" style="background: var(--secondary);">
                    <div style="width:48px;height:48px;background:var(--tertiary);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                        <i class="fas fa-user-check fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark fs-6">{{ $pelanggan->nama_pelanggan }}</div>
                        <div class="text-muted small"><i class="fas fa-phone-alt me-1"></i> {{ $pelanggan->no_hp }} &nbsp;·&nbsp; <i class="fas fa-map-marker-alt me-1"></i> {{ $pelanggan->alamat ?? '-' }}</div>
                    </div>
                </div>

                <!-- Booking Status Cards from Database -->
                @if ($bookings->isNotEmpty())
                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-calendar-check me-2" style="color: var(--tertiary);"></i> Status Booking Aktif</h6>
                @foreach ($bookings as $booking)
                <div class="card border-0 shadow-sm rounded-4 p-3 mb-3" style="border-left: 5px solid var(--tertiary) !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="fw-bold text-dark fs-6">{{ $booking->kode_reservasi ?? '#' . str_pad($booking->id_booking, 6, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-muted small ms-2">— {{ $booking->layanan->nama_layanan ?? 'Layanan Laundry' }}</span>
                        </div>
                        <span class="status-badge-sofia sb-{{ $booking->status }}">
                            @switch($booking->status)
                                @case('pending') Menunggu Konfirmasi @break
                                @case('confirmed') Dikonfirmasi @break
                                @case('cancelled') Dibatalkan @break
                                @default {{ ucfirst($booking->status) }}
                            @endswitch
                        </span>
                    </div>
                    <div class="text-muted small d-flex flex-wrap gap-3">
                        <div><i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }} {{ $booking->waktu_booking ? '('.$booking->waktu_booking.')' : '' }}</div>
                        <div><i class="fas fa-weight me-1"></i> {{ $booking->estimasi_berat ?? '-' }} kg</div>
                        <div><i class="fas fa-truck me-1"></i> Tipe: {{ ucfirst($booking->tipe_antar_jemput ?? 'none') }}</div>
                    </div>
                    @if ($booking->dp_bayar > 0)
                    <div class="mt-2 pt-2 border-top d-flex justify-content-between text-muted small">
                        <span>DP Terbayar: <strong class="text-success">Rp {{ number_format($booking->dp_bayar, 0, ',', '.') }}</strong></span>
                        <span>Sisa: <strong class="text-danger">Rp {{ number_format($booking->sisa_bayar, 0, ',', '.') }}</strong></span>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif

                <!-- Transaction Status Cards from Database -->
                @if ($transaksis->isNotEmpty())
                <h6 class="fw-bold text-dark mb-3 mt-4"><i class="fas fa-receipt me-2" style="color: var(--tertiary);"></i> Riwayat Transaksi Cucian</h6>
                @foreach ($transaksis as $transaksi)
                <div class="card border-0 shadow-sm rounded-4 p-3 mb-3" style="border-left: 5px solid var(--primary) !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="fw-bold text-dark fs-6">No. Transaksi #{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <span class="status-badge-sofia sb-{{ $transaksi->status }}">
                            @switch($transaksi->status)
                                @case('proses') Diproses (Sedang Dicuci) @break
                                @case('selesai') Selesai (Siap Diambil) @break
                                @case('diambil') Sudah Diambil @break
                                @default {{ ucfirst($transaksi->status) }}
                            @endswitch
                        </span>
                    </div>
                    <div class="text-muted small d-flex flex-wrap gap-3 mb-2">
                        <div><i class="far fa-calendar-alt me-1"></i> Masuk: {{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d M Y') }}</div>
                        <div><i class="fas fa-weight-hanging me-1"></i> {{ $transaksi->total_berat }} kg</div>
                        <div><i class="fas fa-coins me-1"></i> Total: <strong style="color: var(--tertiary);">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></div>
                    </div>
                    @if ($transaksi->detailTransaksi->isNotEmpty())
                    <div class="bg-light rounded-3 p-2 small text-muted">
                        <strong class="d-block mb-1">Rincian Layanan:</strong>
                        <ul class="mb-0 ps-3">
                            @foreach ($transaksi->detailTransaksi as $detail)
                            <li>{{ $detail->layanan->nama_layanan ?? 'Layanan' }} — {{ $detail->jumlah_kg }} kg (Rp {{ number_format($detail->subtotal, 0, ',', '.') }})</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endforeach
                @elseif ($bookings->isEmpty())
                <div class="text-center text-muted py-4 bg-light rounded-4">
                    <i class="fas fa-inbox fa-2x opacity-25 mb-2"></i>
                    <p class="small mb-0">Belum ada riwayat pesanan/transaksi aktif untuk pelanggan ini.</p>
                </div>
                @endif
                @endisset
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer-sofia" id="kontak">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 me-auto">
                <div class="footer-brand">Laundry Sofia</div>
                <p class="footer-desc mb-4">
                    Memberikan kesegaran presisi untuk setiap pakaian Anda. Profesional, terpercaya, dan ramah lingkungan sejak 2024.
                </p>
                <div class="d-flex gap-2">
                    <a href="#" class="footer-contact-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="footer-contact-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="footer-contact-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h5 class="footer-heading">Layanan</h5>
                <div class="footer-links">
                    <a href="#layanan">Cuci & Lipat</a>
                    <a href="#layanan">Setrika Uap</a>
                    <a href="#layanan">Dry Cleaning</a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h5 class="footer-heading">Perusahaan</h5>
                <div class="footer-links">
                    <a href="#beranda">Tentang Kami</a>
                    <a href="#beranda">Locations</a>
                    <a href="#kontak">Contact</a>
                </div>
            </div>
            <div class="col-lg-3">
                <h5 class="footer-heading">Hubungi Kami</h5>
                <p class="small text-slate mb-2"><i class="fas fa-map-marker-alt me-2 text-primary"></i> Sofia City Center, No. 45</p>
                <p class="small text-slate mb-2"><i class="fab fa-whatsapp me-2 text-primary"></i> +62 812-3456-7890</p>
                <p class="small text-slate mb-2"><i class="fas fa-envelope me-2 text-primary"></i> info@laundrysofia.id</p>
            </div>
        </div>
        <div class="footer-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                &copy; 2024 Laundry Sofia. All rights reserved. Fresh Precision in every fold.
            </div>
            <div class="d-flex gap-3">
                <a href="#" class="text-slate text-decoration-none">Privacy Policy</a>
                <span>|</span>
                <a href="#" class="text-slate text-decoration-none">Terms of Service</a>
                <span>|</span>
                <a href="{{ route('login') }}" class="text-slate text-decoration-none fw-semibold">Login Staff & Pelanggan</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    @isset($pelanggan)
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('cek-status').scrollIntoView({ behavior: 'smooth' });
    });
    @endisset

    @if (session('error'))
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('cek-status').scrollIntoView({ behavior: 'smooth' });
    });
    @endif
</script>
</body>
</html>
