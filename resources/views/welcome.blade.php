<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EduManage &mdash; Smart School Management System</title>

    <!-- Bootstrap 4 & FontAwesome from existing Stisla assets -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/fontawesome/css/all.min.css') }}">

    <style>
        /* =========================================
           CSS VARIABLES
           ========================================= */
        :root {
            --primary:     #1a3c5e;
            --primary-mid: #2557a0;
            --accent:      #4a90d9;
            --light-blue:  #e8f0fe;
            --text-dark:   #1a3c5e;
            --text-muted:  #6c7a8d;
            --white:       #ffffff;
            --bg-light:    #f5f7fa;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            color: #3d4f63;
            overflow-x: hidden;
        }

        /* =========================================
           NAVBAR
           ========================================= */
        .landing-nav {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(26,60,94,0.08);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand-logo .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--accent), var(--primary));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(26,60,94,0.3);
        }

        .brand-logo .brand-text {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.3px;
        }

        .brand-logo .brand-sub {
            font-size: 0.65rem;
            color: var(--text-muted);
            display: block;
            font-weight: 400;
            letter-spacing: 0.3px;
        }

        .nav-link-item {
            color: #5a6e7f !important;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 6px 14px !important;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .nav-link-item:hover {
            color: var(--primary) !important;
            background: var(--light-blue);
        }

        .btn-nav-login {
            border: 2px solid var(--primary);
            color: var(--primary) !important;
            font-weight: 700;
            padding: 8px 22px;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-nav-login:hover {
            background: var(--primary);
            color: #fff !important;
            text-decoration: none;
        }

        .btn-nav-start {
            background: linear-gradient(135deg, var(--accent), var(--primary));
            color: #fff !important;
            font-weight: 700;
            padding: 8px 22px;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
        }

        .btn-nav-start:hover {
            background: linear-gradient(135deg, #5ea3ec, var(--primary-mid));
            box-shadow: 0 4px 15px rgba(26,60,94,0.3);
            color: #fff !important;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* =========================================
           HERO SECTION
           ========================================= */
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 55%, var(--accent) 100%);
            min-height: 90vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 80px 0;
        }

        /* Decorative shapes */
        .hero-section::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 600px; height: 600px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -150px; left: -80px;
            width: 500px; height: 500px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            color: #fff;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            margin-bottom: 20px;
            letter-spacing: 0.3px;
        }

        .hero-title {
            font-size: 3.2rem;
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }

        .hero-title .highlight {
            color: #ffd166;
            display: block;
        }

        .hero-description {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.82);
            line-height: 1.7;
            margin-bottom: 36px;
            max-width: 520px;
        }

        .btn-hero-primary {
            background: #ffffff;
            color: var(--primary) !important;
            font-weight: 800;
            padding: 14px 34px;
            border-radius: 10px;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 6px 24px rgba(0,0,0,0.2);
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(0,0,0,0.25);
            color: var(--primary-mid) !important;
            text-decoration: none;
        }

        .btn-hero-secondary {
            background: rgba(255,255,255,0.12);
            border: 2px solid rgba(255,255,255,0.4);
            color: #fff !important;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            margin-left: 12px;
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,0.2);
            color: #fff !important;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* Hero card floating preview */
        .hero-card-preview {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            position: relative;
            z-index: 2;
        }

        .hero-card-preview .preview-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f0f2f5;
        }

        .preview-dot { width: 10px; height: 10px; border-radius: 50%; }
        .preview-dot.red { background: #ff6b6b; }
        .preview-dot.yellow { background: #ffd166; }
        .preview-dot.green { background: #56cc9d; }

        .mini-stat {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .mini-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .mini-stat-label { font-size: 0.72rem; color: #8a9bb0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .mini-stat-value { font-size: 1.2rem; font-weight: 800; color: var(--primary); }

        .mini-chart-bar {
            height: 6px;
            border-radius: 3px;
            background: #e4eaf2;
            margin-top: 12px;
            overflow: hidden;
        }
        .mini-chart-bar-fill {
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(90deg, var(--accent), var(--primary));
            animation: fillBar 1.5s ease-out forwards;
        }
        @keyframes fillBar { from { width: 0; } }

        /* =========================================
           STATS STRIP
           ========================================= */
        .stats-strip {
            background: #fff;
            padding: 40px 0;
            border-bottom: 1px solid #e4eaf2;
        }

        .stat-item {
            text-align: center;
            padding: 10px 0;
        }

        .stat-number {
            font-size: 2.4rem;
            font-weight: 900;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-number span { color: var(--accent); }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-divider {
            width: 1px;
            height: 50px;
            background: #e4eaf2;
            margin: auto;
        }

        /* =========================================
           FEATURES SECTION
           ========================================= */
        .section-title {
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 12px;
            letter-spacing: -0.3px;
        }

        .section-subtitle {
            font-size: 1rem;
            color: var(--text-muted);
            max-width: 540px;
            margin: 0 auto 50px;
            line-height: 1.7;
        }

        .feature-card {
            background: #fff;
            border-radius: 18px;
            padding: 32px 28px;
            height: 100%;
            transition: all 0.25s;
            border: 1px solid #e8edf5;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            border-radius: 18px 18px 0 0;
        }

        .feature-card.fc-blue::before   { background: linear-gradient(90deg, #4a90d9, #1a3c5e); }
        .feature-card.fc-teal::before   { background: linear-gradient(90deg, #48cae4, #0096c7); }
        .feature-card.fc-green::before  { background: linear-gradient(90deg, #56cc9d, #1a936f); }
        .feature-card.fc-orange::before { background: linear-gradient(90deg, #ffd166, #ef8c00); }
        .feature-card.fc-red::before    { background: linear-gradient(90deg, #ff6b6b, #c0392b); }
        .feature-card.fc-purple::before { background: linear-gradient(90deg, #a78bfa, #7c3aed); }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(0,0,0,0.1);
            border-color: transparent;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #fff;
            margin-bottom: 20px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }

        .fc-blue .feature-icon   { background: linear-gradient(135deg, #4a90d9, #1a3c5e); }
        .fc-teal .feature-icon   { background: linear-gradient(135deg, #48cae4, #0096c7); }
        .fc-green .feature-icon  { background: linear-gradient(135deg, #56cc9d, #1a936f); }
        .fc-orange .feature-icon { background: linear-gradient(135deg, #ffd166, #ef8c00); }
        .fc-red .feature-icon    { background: linear-gradient(135deg, #ff6b6b, #c0392b); }
        .fc-purple .feature-icon { background: linear-gradient(135deg, #a78bfa, #7c3aed); }

        .feature-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .feature-description {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin: 0;
        }

        /* =========================================
           HOW IT WORKS
           ========================================= */
        .how-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .how-section::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }

        .step-card {
            text-align: center;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .step-number {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 900;
            color: #fff;
            margin: 0 auto 20px;
        }

        .step-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }

        .step-description {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.72);
            line-height: 1.7;
        }

        .step-arrow {
            color: rgba(255,255,255,0.3);
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding-top: 20px;
        }

        /* =========================================
           TESTIMONIAL / HIGHLIGHT BAND
           ========================================= */
        .highlight-band {
            background: var(--bg-light);
            padding: 70px 0;
        }

        .highlight-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 16px 0;
        }

        .highlight-icon {
            width: 48px;
            height: 48px;
            background: var(--light-blue);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: var(--accent);
            flex-shrink: 0;
        }

        .highlight-title {
            font-size: 0.975rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 4px;
        }

        .highlight-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin: 0;
        }

        /* =========================================
           CTA SECTION
           ========================================= */
        .cta-section {
            padding: 90px 0;
            background: #fff;
            text-align: center;
        }

        .cta-box {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 60%, var(--accent) 100%);
            border-radius: 24px;
            padding: 60px 40px;
            position: relative;
            overflow: hidden;
        }

        .cta-box::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .cta-title {
            font-size: 2.2rem;
            font-weight: 900;
            color: #fff;
            margin-bottom: 14px;
        }

        .cta-subtitle {
            font-size: 1rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 36px;
        }

        .btn-cta {
            background: #fff;
            color: var(--primary) !important;
            font-weight: 800;
            padding: 14px 38px;
            border-radius: 10px;
            font-size: 1.05rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 6px 24px rgba(0,0,0,0.15);
        }

        .btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(0,0,0,0.2);
            color: var(--primary-mid) !important;
            text-decoration: none;
        }

        /* =========================================
           FOOTER
           ========================================= */
        .landing-footer {
            background: var(--primary);
            padding: 50px 0 24px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .footer-brand .f-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #fff;
        }

        .footer-brand-name {
            font-size: 1.15rem;
            font-weight: 800;
            color: #fff;
        }

        .footer-tagline {
            font-size: 0.82rem;
            color: rgba(200,214,229,0.65);
            margin-bottom: 20px;
            line-height: 1.6;
            max-width: 280px;
        }

        .footer-heading {
            color: #fff;
            font-weight: 700;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 16px;
        }

        .footer-link {
            display: block;
            color: rgba(200,214,229,0.7);
            font-size: 0.875rem;
            text-decoration: none;
            margin-bottom: 10px;
            transition: color 0.2s;
        }

        .footer-link:hover { color: #fff; text-decoration: none; }

        .footer-divider {
            border-color: rgba(255,255,255,0.1);
            margin: 28px 0 20px;
        }

        .footer-bottom {
            font-size: 0.82rem;
            color: rgba(200,214,229,0.55);
        }

        /* =========================================
           RESPONSIVE
           ========================================= */
        @media (max-width: 991px) {
            .hero-title { font-size: 2.4rem; }
            .hero-card-preview { margin-top: 40px; }
        }

        @media (max-width: 767px) {
            .hero-section { padding: 60px 0; min-height: auto; }
            .hero-title { font-size: 2rem; }
            .btn-hero-secondary { margin-left: 0; margin-top: 12px; }
            .stat-divider { display: none; }
            .cta-title { font-size: 1.7rem; }
            .cta-box { padding: 40px 24px; }
            .section-title { font-size: 1.7rem; }
        }
    </style>
</head>
<body class="nativephp-safe-area">

    {{-- =============================================
         TOP NAVBAR
         ============================================= --}}
    <nav class="landing-nav">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                {{-- Brand --}}
                <a href="/" class="brand-logo">
                    <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div>
                        <div class="brand-text">EduManage</div>
                        <span class="brand-sub">School Management System</span>
                    </div>
                </a>

                {{-- Nav links (desktop) --}}
                <div class="d-none d-md-flex align-items-center" style="gap:6px;">
                    <a href="#features" class="nav-link-item">Features</a>
                    <a href="#how-it-works" class="nav-link-item">How it Works</a>
                    <a href="#about" class="nav-link-item">About</a>
                </div>

                {{-- Auth links --}}
                <div class="d-flex align-items-center" style="gap:10px;">
                    @if(auth()->check())
                        <a href="{{ route('dashboard') }}" class="btn-nav-start">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-nav-login">Sign In</a>
                        {{-- Only show Register if the route exists --}}
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-nav-start">Get Started</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- =============================================
         HERO SECTION
         ============================================= --}}
    <section class="hero-section">
        <div class="container" style="position:relative;z-index:2;">
            <div class="row align-items-center">
                {{-- Left: Content --}}
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="hero-badge">
                        <i class="fas fa-shield-alt" style="font-size:0.7rem;"></i>
                        Trusted School Management Platform
                    </div>
                    <h1 class="hero-title">
                        Manage Your School
                        <span class="highlight">Smarter &amp; Faster</span>
                    </h1>
                    <p class="hero-description">
                        EduManage brings all your school operations — students, attendance, fees,
                        exams, and teachers — into one powerful, easy-to-use dashboard.
                    </p>
                    <div class="d-flex flex-wrap align-items-center" style="gap:12px;">
                        @if(auth()->check())
                            <a href="{{ route('dashboard') }}" class="btn-hero-primary">
                                <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-hero-primary">
                                <i class="fas fa-sign-in-alt"></i> Sign In Now
                            </a>
                            <a href="#features" class="btn-hero-secondary">
                                <i class="fas fa-play-circle"></i> See Features
                            </a>
                        @endif
                    </div>
                    {{-- Trust indicators --}}
                    <div class="d-flex align-items-center mt-4" style="gap:20px;">
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-check-circle" style="color:#56cc9d;font-size:0.9rem;"></i>
                            <span style="color:rgba(255,255,255,0.75);font-size:0.82rem;font-weight:500;">Role-based Access</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-check-circle" style="color:#56cc9d;font-size:0.9rem;"></i>
                            <span style="color:rgba(255,255,255,0.75);font-size:0.82rem;font-weight:500;">Real-time Data</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-check-circle" style="color:#56cc9d;font-size:0.9rem;"></i>
                            <span style="color:rgba(255,255,255,0.75);font-size:0.82rem;font-weight:500;">Mobile Friendly</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Dashboard preview card --}}
                <div class="col-lg-5 offset-lg-1">
                    <div class="hero-card-preview">
                        <div class="preview-header">
                            <div class="preview-dot red"></div>
                            <div class="preview-dot yellow"></div>
                            <div class="preview-dot green"></div>
                            <span style="font-size:0.78rem;color:#8a9bb0;margin-left:8px;font-weight:600;">Dashboard Overview</span>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-icon" style="background:linear-gradient(135deg,#4a90d9,#1a3c5e);">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <div class="mini-stat-label">Total Students</div>
                                <div class="mini-stat-value">1,248</div>
                            </div>
                            <div class="ml-auto" style="color:#56cc9d;font-size:0.78rem;font-weight:700;">
                                <i class="fas fa-arrow-up"></i> 12%
                            </div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-icon" style="background:linear-gradient(135deg,#48cae4,#0096c7);">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div>
                                <div class="mini-stat-label">Total Teachers</div>
                                <div class="mini-stat-value">84</div>
                            </div>
                            <div class="ml-auto" style="color:#56cc9d;font-size:0.78rem;font-weight:700;">
                                <i class="fas fa-arrow-up"></i> 4%
                            </div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-icon" style="background:linear-gradient(135deg,#56cc9d,#1a936f);">
                                <i class="fas fa-money-check-alt"></i>
                            </div>
                            <div>
                                <div class="mini-stat-label">Fee Collected</div>
                                <div class="mini-stat-value">$42,800</div>
                            </div>
                            <div class="ml-auto" style="color:#56cc9d;font-size:0.78rem;font-weight:700;">
                                <i class="fas fa-arrow-up"></i> 8%
                            </div>
                        </div>
                        <div class="mini-chart-bar mt-3">
                            <div class="mini-chart-bar-fill" style="width:73%;"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1" style="font-size:0.72rem;color:#8a9bb0;">
                            <span>Attendance Rate</span><span style="font-weight:700;color:#1a936f;">73% Present</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =============================================
         STATS STRIP
         ============================================= --}}
    <section class="stats-strip">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">1,200<span>+</span></div>
                        <div class="stat-label">Students Managed</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">80<span>+</span></div>
                        <div class="stat-label">Teachers Enrolled</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">30<span>+</span></div>
                        <div class="stat-label">Active Classes</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">98<span>%</span></div>
                        <div class="stat-label">User Satisfaction</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =============================================
         FEATURES SECTION
         ============================================= --}}
    <section id="features" class="py-5" style="background:var(--bg-light);padding:80px 0 !important;">
        <div class="container">
            <div class="text-center mb-2">
                <span style="background:var(--light-blue);color:var(--accent);font-size:0.75rem;font-weight:700;padding:5px 14px;border-radius:20px;letter-spacing:0.5px;text-transform:uppercase;">
                    Everything You Need
                </span>
            </div>
            <h2 class="section-title text-center mt-3">Powerful Features for Modern Schools</h2>
            <p class="section-subtitle text-center">
                From student admissions to final exam results — EduManage covers the complete school management lifecycle.
            </p>

            <div class="row" style="gap:0;">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card fc-blue h-100">
                        <div class="feature-icon"><i class="fas fa-user-graduate"></i></div>
                        <div class="feature-title">Student Management</div>
                        <p class="feature-description">
                            Manage student admissions, profiles, class assignments, and academic records with ease. Full history in one place.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card fc-teal h-100">
                        <div class="feature-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="feature-title">Teacher Management</div>
                        <p class="feature-description">
                            Maintain teacher profiles, designations, contact details, and attendance records all from a central dashboard.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card fc-green h-100">
                        <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                        <div class="feature-title">Attendance Tracking</div>
                        <p class="feature-description">
                            Mark and view daily attendance for both students and teachers. Get instant reports and identify absentee patterns.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card fc-orange h-100">
                        <div class="feature-icon"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="feature-title">Fee Management</div>
                        <p class="feature-description">
                            Create fee structures, generate student fees in bulk, track collections, and manage overdue payments effortlessly.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card fc-red h-100">
                        <div class="feature-icon"><i class="fas fa-file-alt"></i></div>
                        <div class="feature-title">Exam &amp; Results</div>
                        <p class="feature-description">
                            Schedule exams, enter marks per class and subject, and generate class-wise result cards with pass/fail summary.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card fc-purple h-100">
                        <div class="feature-icon"><i class="fas fa-user-shield"></i></div>
                        <div class="feature-title">Role-Based Access</div>
                        <p class="feature-description">
                            Assign roles like Super Admin, Admin, Teacher, Accountant, and Guardian — each sees only what they need.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =============================================
         HOW IT WORKS
         ============================================= --}}
    <section id="how-it-works" class="how-section">
        <div class="container" style="position:relative;z-index:1;">
            <div class="text-center mb-5">
                <span style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);font-size:0.75rem;font-weight:700;padding:5px 14px;border-radius:20px;letter-spacing:0.5px;text-transform:uppercase;">
                    Simple Process
                </span>
                <h2 class="section-title mt-3" style="color:#fff;">Get Started in 3 Easy Steps</h2>
                <p style="color:rgba(255,255,255,0.72);font-size:0.975rem;max-width:480px;margin:0 auto;">
                    Setting up your school on EduManage takes minutes, not days.
                </p>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-title">Sign In</div>
                        <p class="step-description">Log in with your credentials. Each user role has a tailored view.</p>
                    </div>
                </div>
                <div class="col-lg-1 d-none d-lg-flex">
                    <div class="step-arrow"><i class="fas fa-chevron-right"></i></div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-title">Configure</div>
                        <p class="step-description">Set up classes, fee structures, academic sessions, and user roles.</p>
                    </div>
                </div>
                <div class="col-lg-1 d-none d-lg-flex">
                    <div class="step-arrow"><i class="fas fa-chevron-right"></i></div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-title">Manage</div>
                        <p class="step-description">Add students, track attendance, collect fees, and view results instantly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =============================================
         HIGHLIGHTS BAND
         ============================================= --}}
    <section id="about" class="highlight-band">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <span style="background:var(--light-blue);color:var(--accent);font-size:0.75rem;font-weight:700;padding:5px 14px;border-radius:20px;letter-spacing:0.5px;text-transform:uppercase;">
                        Why EduManage?
                    </span>
                    <h2 class="section-title mt-3" style="margin-bottom:14px;">Built for Real School Environments</h2>
                    <p style="color:var(--text-muted);line-height:1.8;font-size:0.95rem;">
                        EduManage was designed with school administrators, teachers, and accountants in mind.
                        Every feature solves a real daily challenge in school management.
                    </p>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <div class="highlight-item">
                        <div class="highlight-icon"><i class="fas fa-bolt"></i></div>
                        <div>
                            <div class="highlight-title">Fast &amp; Responsive</div>
                            <p class="highlight-desc">Optimized for speed. Works smoothly on desktop, tablet, and mobile browsers.</p>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon"><i class="fas fa-lock"></i></div>
                        <div>
                            <div class="highlight-title">Secure by Design</div>
                            <p class="highlight-desc">Role-based permissions ensure every user sees only what they're authorized to access.</p>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon"><i class="fas fa-chart-bar"></i></div>
                        <div>
                            <div class="highlight-title">Insightful Reports</div>
                            <p class="highlight-desc">Visual charts and tables give you a clear picture of attendance, fees, and academic performance.</p>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon"><i class="fas fa-print"></i></div>
                        <div>
                            <div class="highlight-title">Printable Documents</div>
                            <p class="highlight-desc">Generate fee slips, result cards, and attendance reports ready for printing in one click.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =============================================
         CTA SECTION
         ============================================= --}}
    <section class="cta-section">
        <div class="container">
            <div class="cta-box">
                <i class="fas fa-graduation-cap" style="font-size:4rem;color:rgba(255,255,255,0.1);display:block;margin-bottom:16px;position:relative;z-index:1;"></i>
                <h2 class="cta-title" style="position:relative;z-index:1;">Ready to Transform Your School?</h2>
                <p class="cta-subtitle" style="position:relative;z-index:1;">Sign in to your account and start managing your school the smart way.</p>
                <div style="position:relative;z-index:1;">
                    @if(auth()->check())
                        <a href="{{ route('dashboard') }}" class="btn-cta">
                            <i class="fas fa-tachometer-alt"></i> Open Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-cta">
                            <i class="fas fa-sign-in-alt"></i> Sign In to EduManage
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- =============================================
         FOOTER
         ============================================= --}}
    <footer class="landing-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-brand">
                        <div class="f-icon"><i class="fas fa-graduation-cap"></i></div>
                        <span class="footer-brand-name">EduManage</span>
                    </div>
                    <p class="footer-tagline">
                        A comprehensive school management system designed to simplify administration and improve learning outcomes.
                    </p>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <div class="footer-heading">Navigation</div>
                    <a href="#features" class="footer-link">Features</a>
                    <a href="#how-it-works" class="footer-link">How it Works</a>
                    <a href="#about" class="footer-link">About</a>
                    <a href="{{ route('login') }}" class="footer-link">Sign In</a>
                </div>
                <div class="col-lg-3 col-6 mb-4">
                    <div class="footer-heading">Modules</div>
                    <a href="#" class="footer-link">Student Management</a>
                    <a href="#" class="footer-link">Attendance</a>
                    <a href="#" class="footer-link">Fee Management</a>
                    <a href="#" class="footer-link">Exam &amp; Results</a>
                </div>
                <div class="col-lg-3 mb-4">
                    <div class="footer-heading">Contact</div>
                    <a href="mailto:izhar@pakcr.org.pk" class="footer-link">
                        <i class="fas fa-envelope mr-2"></i> izhar@pakcr.org.pk
                    </a>
                    <p class="footer-link" style="cursor:default;">
                        <i class="fas fa-map-marker-alt mr-2"></i> School Management Suite
                    </p>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                <div class="footer-bottom">
                    &copy; {{ date('Y') }} EduManage &mdash; School Management System. Developed by <a href="#" style="color:rgba(200,214,229,0.8);">Izhar Baloch</a>.
                </div>
                <div class="footer-bottom">
                    <i class="fas fa-circle" style="font-size:0.45rem;color:#56cc9d;vertical-align:middle;"></i>
                    All Systems Operational
                </div>
            </div>
        </div>
    </footer>

    {{-- JS from Stisla assets --}}
    <script src="{{ asset('assets/dashboard/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/bootstrap/js/bootstrap.min.js') }}"></script>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
