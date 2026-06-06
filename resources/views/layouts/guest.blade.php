<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EduManage') }} &mdash; School Management System</title>

    <!-- Bootstrap 4 & FontAwesome from Stisla assets (same stack as the dashboard) -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/fontawesome/css/all.min.css') }}">

    <style>
        /* =========================================
           AUTH LAYOUT — SPLIT SCREEN
           Left: school branding panel
           Right: form panel
           ========================================= */
        :root {
            --sms-primary:     #1a3c5e;
            --sms-primary-mid: #2557a0;
            --sms-accent:      #4a90d9;
            --sms-light-blue:  #e8f0fe;
            --sms-body-bg:     #f0f4f8;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
        }

        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ---- LEFT PANEL ---- */
        .auth-left {
            width: 42%;
            background: linear-gradient(160deg, var(--sms-primary) 0%, var(--sms-primary-mid) 55%, var(--sms-accent) 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 48px 36px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles on left panel */
        .auth-left::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 320px; height: 320px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            pointer-events: none;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -100px; left: -60px;
            width: 350px; height: 350px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            pointer-events: none;
        }

        /* Brand section */
        .auth-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 1;
        }

        .auth-brand .brand-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #fff;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            text-decoration: none;
            flex-shrink: 0;
        }

        .auth-brand .brand-info a {
            text-decoration: none;
        }

        .auth-brand .brand-name {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            display: block;
            letter-spacing: -0.2px;
        }

        .auth-brand .brand-tagline {
            font-size: 0.7rem;
            color: rgba(200,214,229,0.7);
            letter-spacing: 0.4px;
            text-transform: uppercase;
            font-weight: 500;
        }

        /* Hero text area */
        .auth-hero {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 0;
        }

        .auth-hero h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.25;
            margin-bottom: 14px;
            letter-spacing: -0.3px;
        }

        .auth-hero p {
            font-size: 0.925rem;
            color: rgba(255,255,255,0.75);
            line-height: 1.75;
            margin-bottom: 32px;
            max-width: 340px;
        }

        /* Feature list on left panel */
        .auth-features {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .auth-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.85);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 14px;
        }

        .auth-features li .feat-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: #fff;
            flex-shrink: 0;
        }

        /* Decorative big icon background */
        .auth-big-icon {
            position: absolute;
            bottom: 120px;
            right: -20px;
            font-size: 10rem;
            color: rgba(255,255,255,0.05);
            pointer-events: none;
            z-index: 0;
        }

        /* Stats row at bottom of left panel */
        .auth-stats {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,0.12);
        }

        .auth-stat-item {
            text-align: left;
        }

        .auth-stat-number {
            font-size: 1.3rem;
            font-weight: 900;
            color: #fff;
        }

        .auth-stat-label {
            font-size: 0.7rem;
            color: rgba(200,214,229,0.6);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-weight: 600;
        }

        /* ---- RIGHT PANEL ---- */
        .auth-right {
            flex: 1;
            background: var(--sms-body-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            position: relative;
        }

        .auth-form-container {
            background: #fff;
            border-radius: 20px;
            padding: 44px 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 8px 48px rgba(0,0,0,0.08);
        }

        .auth-back-link {
            position: absolute;
            top: 24px;
            left: 24px;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #8a9bb0;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .auth-back-link:hover { color: var(--sms-primary); text-decoration: none; }

        /* Footer note on right side */
        .auth-footer-note {
            position: absolute;
            bottom: 20px;
            text-align: center;
            font-size: 0.75rem;
            color: #b0bec5;
            width: 100%;
            left: 0;
        }

        /* ---- RESPONSIVE: stack on mobile ---- */
        @media (max-width: 991px) {
            .auth-left { display: none; }
            .auth-wrapper { display: block; min-height: 100vh; background: var(--sms-body-bg); }
            .auth-right {
                min-height: 100vh;
                padding: 40px 16px;
            }
            .auth-form-container { padding: 36px 28px; }
        }

        @media (max-width: 480px) {
            .auth-form-container { padding: 28px 20px; border-radius: 16px; }
        }
    </style>
</head>

<body class="nativephp-safe-area">
    <div class="auth-wrapper">

        {{-- ================================================
             LEFT PANEL — school branding + feature list
             (hidden on mobile)
             ================================================ --}}
        <div class="auth-left">
            {{-- Brand --}}
            <div class="auth-brand">
                <a href="/" class="brand-icon">
                    <i class="fas fa-graduation-cap"></i>
                </a>
                <div class="brand-info">
                    <a href="/"><span class="brand-name">EduManage</span></a>
                    <span class="brand-tagline">School Management System</span>
                </div>
            </div>

            {{-- Hero content --}}
            <div class="auth-hero">
                <i class="fas fa-graduation-cap auth-big-icon"></i>
                <h2>Everything Your School Needs, All in One Place</h2>
                <p>
                    Manage students, teachers, attendance, fees, and exam results with a single, powerful dashboard built for modern schools.
                </p>
                <ul class="auth-features">
                    <li>
                        <div class="feat-icon"><i class="fas fa-users"></i></div>
                        Student &amp; Teacher Management
                    </li>
                    <li>
                        <div class="feat-icon"><i class="fas fa-calendar-check"></i></div>
                        Daily Attendance Tracking
                    </li>
                    <li>
                        <div class="feat-icon"><i class="fas fa-money-bill-wave"></i></div>
                        Fee Collection &amp; Reports
                    </li>
                    <li>
                        <div class="feat-icon"><i class="fas fa-file-alt"></i></div>
                        Exams, Marks &amp; Results
                    </li>
                    <li>
                        <div class="feat-icon"><i class="fas fa-user-shield"></i></div>
                        Role-Based Access Control
                    </li>
                </ul>
            </div>

            {{-- Bottom stats --}}
            <div class="auth-stats">
                <div class="auth-stat-item">
                    <div class="auth-stat-number">1,200+</div>
                    <div class="auth-stat-label">Students</div>
                </div>
                <div class="auth-stat-item">
                    <div class="auth-stat-number">80+</div>
                    <div class="auth-stat-label">Teachers</div>
                </div>
                <div class="auth-stat-item">
                    <div class="auth-stat-number">30+</div>
                    <div class="auth-stat-label">Classes</div>
                </div>
            </div>
        </div>

        {{-- ================================================
             RIGHT PANEL — form slot
             ================================================ --}}
        <div class="auth-right">
            {{-- Back to home link --}}
            <a href="/" class="auth-back-link">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>

            {{-- Form card — $slot injected here --}}
            <div class="auth-form-container">
                {{ $slot }}
            </div>

            <div class="auth-footer-note">
                &copy; {{ date('Y') }} EduManage &mdash; School Management System
            </div>
        </div>

    </div>

    <!-- JS -->
    <script src="{{ asset('assets/dashboard/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
