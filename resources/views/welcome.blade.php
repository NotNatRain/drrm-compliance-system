{{-- Landing Page --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DRRM Compliance System</title>
    <meta name="description" content="Monitor, document, and report your organization's DRRM compliance in one clear, centralized dashboard.">
    <link rel="icon" type="image/png" href="{{ asset('images/drrmis-logo-2.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #0D1B36;
            --orange: #E05C2E;
            --text:   #0D1B36;
            --muted:  #6B7280;
            --bg:     #F0F2F5;
            --white:  #FFFFFF;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ===== LAYOUT ===== */
        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 48px 24px 32px;
        }

        /* ===== HERO CONTENT ===== */
        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 24px;
            max-width: 640px;
            width: 100%;
            animation: fadeUp 0.8s ease both;
        }

        .logo-img {
            width: 72px;
            height: 72px;
            object-fit: contain;
            animation: scaleIn 0.6s cubic-bezier(0.34,1.56,0.64,1) both;
        }

        .eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            color: var(--orange);
            text-transform: uppercase;
        }

        .headline {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            line-height: 1.15;
            color: var(--navy);
        }

        .headline .accent {
            color: var(--orange);
        }

        .subtext {
            font-size: 15px;
            color: var(--muted);
            line-height: 1.65;
            max-width: 420px;
        }

        /* ===== CTA BUTTONS ===== */
        .cta-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--navy);
            color: var(--white);
            box-shadow: 0 2px 8px rgba(13,27,54,0.18);
        }

        .btn-primary:hover {
            background: #1a2e55;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13,27,54,0.22);
        }

        .btn-ghost {
            background: transparent;
            color: var(--navy);
            border: 1.5px solid #CBD5E1;
        }

        .btn-ghost:hover {
            border-color: var(--navy);
            background: rgba(13,27,54,0.04);
            transform: translateY(-2px);
        }

        /* ===== FOOTER PILLS ===== */
        .pills {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            justify-content: center;
            animation: fadeUp 1s 0.4s ease both;
            opacity: 0;
        }

        .pill {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--muted);
        }

        .pill-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .pill-dot.orange { background: var(--orange); }
        .pill-dot.blue   { background: #3B82F6; }
        .pill-dot.green  { background: #10B981; }
        .pill-dot.navy   { background: var(--navy); }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.6); }
            to   { opacity: 1; transform: scale(1); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            .page { padding: 36px 16px 24px; }
            .cta-group { flex-direction: column; align-items: stretch; }
            .btn { justify-content: center; }
            .pills { gap: 14px; }
        }
    </style>
</head>
<body>
    <div class="page">
        {{-- Main hero --}}
        <div class="hero">
            <img class="logo-img" src="{{ asset('images/drrmis-logo-2.png') }}" alt="DRRM Logo">

            <div>
                <p class="eyebrow">Disaster Risk Reduction &amp; Management</p>
            </div>

            <h1 class="headline">
                Compliance tracking for<br>
                every phase of <span class="accent">disaster<br>readiness.</span>
            </h1>

            <p class="subtext">
                Monitor, document, and report your organization's DRRM
                compliance in one clear, centralized dashboard.
            </p>

            <div class="cta-group">
                <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>
                <a href="{{ route('register') }}" class="btn btn-ghost">Create an account</a>
            </div>
        </div>

        {{-- Bottom pills --}}
        <div class="pills">
            <div class="pill"><span class="pill-dot blue"></span> Prevention &amp; Mitigation</div>
            <div class="pill"><span class="pill-dot orange"></span> Preparedness</div>
            <div class="pill"><span class="pill-dot green"></span> Response</div>
            <div class="pill"><span class="pill-dot navy"></span> Recovery &amp; Rehabilitation</div>
        </div>
    </div>
</body>
</html>
