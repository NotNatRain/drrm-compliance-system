{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account – DRRM Compliance</title>
    <meta name="description" content="Create your account to start tracking DRRM compliance across your organization.">
    <link rel="icon" type="image/png" href="{{ asset('images/drrmis-logo-2.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:     #0D1B36;
            --navy-mid: #1A2F55;
            --orange:   #E05C2E;
            --text:     #0D1B36;
            --muted:    #6B7280;
            --border:   #E2E8F0;
            --bg:       #F0F2F5;
            --white:    #FFFFFF;
            --radius:   10px;
            --panel-w:  440px;
        }

        html, body {
            min-height: 100%;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ============================================================
           SPLIT LAYOUT
        ============================================================ */
        .split {
            display: flex;
            min-height: 100vh;
        }

        /* ---------- LEFT PANEL ---------- */
        .left {
            flex: 0 0 38%;
            background: var(--navy);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 36px 40px;
            overflow: hidden;
            animation: slideInLeft 0.7s cubic-bezier(0.22,1,0.36,1) both;
        }

        .wave-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0.12;
            pointer-events: none;
        }

        .left-top {
            position: relative;
            z-index: 2;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .back-link:hover { color: #fff; }

        .left-middle {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 0 32px;
        }

        .left-logo {
            width: 52px;
            height: 52px;
            object-fit: contain;
            margin-bottom: 24px;
        }

        .left-title {
            font-size: clamp(1.4rem, 2.5vw, 1.9rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .left-desc {
            font-size: 14px;
            color: rgba(255,255,255,0.65);
            line-height: 1.65;
            max-width: 280px;
        }

        .left-pills {
            position: relative;
            z-index: 2;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .left-pill {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 500;
            color: rgba(255,255,255,0.65);
        }

        .left-pill-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .dot-blue   { background: #60A5FA; }
        .dot-orange { background: var(--orange); }
        .dot-green  { background: #34D399; }
        .dot-white  { background: rgba(255,255,255,0.55); }

        /* ---------- RIGHT PANEL ---------- */
        .right {
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 48px 24px;
            overflow-y: auto;
            animation: fadeInRight 0.8s 0.15s ease both;
        }

        .form-box {
            width: 100%;
            max-width: var(--panel-w);
        }

        .form-eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.13em;
            color: var(--orange);
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .form-title {
            font-size: clamp(1.6rem, 3vw, 2rem);
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .form-sub {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 28px;
            line-height: 1.55;
        }

        /* Card */
        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px 28px 24px;
            box-shadow: 0 2px 16px rgba(13,27,54,0.07);
            animation: fadeUp 0.6s 0.25s ease both;
            opacity: 0;
        }

        .field { margin-bottom: 18px; }

        .field-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
        }

        .field-label svg { color: var(--muted); flex-shrink: 0; }

        .input-wrap { position: relative; }

        .form-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-input::placeholder { color: #A0AEC0; }

        .form-input:focus {
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(13,27,54,0.09);
        }

        .form-input.is-invalid { border-color: #EF4444; }
        .form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.12); }

        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            padding: 4px;
            display: flex;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: var(--navy); }

        .form-input.has-toggle { padding-right: 42px; }

        .feedback {
            font-size: 12px;
            color: #EF4444;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Password strength */
        .pw-strength-bar {
            height: 4px;
            background: var(--border);
            border-radius: 9999px;
            margin-top: 8px;
            overflow: hidden;
        }
        .pw-strength-fill {
            height: 100%;
            border-radius: 9999px;
            width: 0%;
            transition: width 0.35s ease, background 0.35s ease;
        }
        .pw-strength-label {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: var(--navy);
            color: #fff;
            border: none;
            border-radius: var(--radius);
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background 0.2s, transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 2px 8px rgba(13,27,54,0.18);
            margin-top: 4px;
        }
        .btn-submit:hover {
            background: #1a2e55;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(13,27,54,0.22);
        }
        .btn-submit:active { transform: translateY(0); }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: var(--muted);
        }
        .form-footer a {
            color: var(--navy);
            font-weight: 700;
            text-decoration: none;
        }
        .form-footer a:hover { color: var(--orange); text-decoration: underline; }

        /* ============================================================
           ANIMATIONS
        ============================================================ */
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 820px) {
            .split { flex-direction: column; }
            .left {
                flex: none;
                padding: 28px 24px;
                min-height: auto;
                animation: none;
            }
            .left-middle { padding: 28px 0 20px; }
            .left-title   { font-size: 1.4rem; }
            .left-desc    { max-width: 100%; }
            .right {
                padding: 32px 20px 48px;
                animation: none;
                align-items: flex-start;
            }
            .card { padding: 22px 18px; }
        }

        @media (max-width: 480px) {
            .left  { padding: 20px 16px; }
            .right { padding: 20px 12px 36px; }
            .form-title { font-size: 1.4rem; }
            .card  { padding: 18px 14px; }
        }
    </style>
</head>
<body>
<div class="split">

    {{-- ===== LEFT PANEL ===== --}}
    <aside class="left">
        <svg class="wave-bg" viewBox="0 0 400 700" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
            <path d="M-60 120 C80 80, 200 180, 340 120 S520 60, 460 140 C400 200, 250 160, 100 200 S-80 280, -60 240 Z" fill="white"/>
            <path d="M-40 280 C60 240, 180 320, 360 260 S540 200, 500 290 C460 360, 300 330, 150 360 S-80 420, -40 390 Z" fill="white"/>
            <path d="M-20 450 C100 410, 220 490, 380 430 S560 370, 520 460 C480 530, 320 500, 160 530 S-60 590, -20 560 Z" fill="white"/>
            <path d="M-80 610 C80 570, 200 650, 380 600 S560 540, 520 630 C480 700, 280 670, 100 690 S-100 740, -80 700 Z" fill="white"/>
        </svg>

        <div class="left-top">
            <a class="back-link" href="{{ url('/') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Back
            </a>
        </div>

        <div class="left-middle">
            <img class="left-logo" src="{{ asset('images/drrmis-logo-2.png') }}" alt="DRRM Logo">
            <h2 class="left-title">Join the DRRM Compliance Network</h2>
            <p class="left-desc">Create an account to start tracking your organization's disaster risk reduction and management compliance.</p>
        </div>
    </aside>

    {{-- ===== RIGHT PANEL ===== --}}
    <main class="right">
        <div class="form-box">
            <h1 class="form-title">Create your account</h1>
            <p class="form-sub">Fill in your details to register for dashboard access.</p>

            <div class="card">
                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    {{-- Full name --}}
                    <div class="field">
                        <label class="field-label" for="name">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Full name
                        </label>
                        <input id="name" type="text" name="name"
                               class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name') }}"
                               placeholder="Juan Dela Cruz"
                               required autocomplete="name" autofocus>
                        @error('name')
                            <div class="feedback">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="field">
                        <label class="field-label" for="email">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            Email address
                        </label>
                        <input id="email" type="email" name="email"
                               class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email') }}"
                               placeholder="you@agency.gov.ph"
                               required autocomplete="email">
                        @error('email')
                            <div class="feedback">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="field">
                        <label class="field-label" for="password">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Password
                        </label>
                        <div class="input-wrap">
                            <input id="password" type="password" name="password"
                                   class="form-input has-toggle {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="Create a password"
                                   required autocomplete="new-password">
                            <button type="button" class="toggle-pw" id="togglePw1" aria-label="Show password">
                                <svg id="eyeIcon1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        {{-- Password strength --}}
                        <div class="pw-strength-bar">
                            <div class="pw-strength-fill" id="pwStrengthFill"></div>
                        </div>
                        <div class="pw-strength-label" id="pwStrengthLabel"></div>
                        @error('password')
                            <div class="feedback">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Confirm password --}}
                    <div class="field">
                        <label class="field-label" for="password-confirm">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Confirm password
                        </label>
                        <div class="input-wrap">
                            <input id="password-confirm" type="password" name="password_confirmation"
                                   class="form-input has-toggle"
                                   placeholder="Re-enter your password"
                                   required autocomplete="new-password">
                            <button type="button" class="toggle-pw" id="togglePw2" aria-label="Show password">
                                <svg id="eyeIcon2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit" id="registerBtn">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                        Create account
                    </button>
                </form>
            </div>

            <p class="form-footer">
                Already have an account?&nbsp;<a href="{{ route('login') }}">Login here</a>
            </p>
        </div>
    </main>

</div>

<script>
(function () {
    const eyeOpen   = `<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>`;
    const eyeClosed = `<line x1="17.94" y1="17.94" x2="6.06" y2="6.06"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 10 8 10 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M6.24 6.24A18.5 18.5 0 0 0 2 12s3 8 10 8a9.12 9.12 0 0 0 5.76-2.1"/><line x1="1" y1="1" x2="23" y2="23"/>`;

    function makeToggle(inputId, btnId, iconId) {
        const input = document.getElementById(inputId);
        const btn   = document.getElementById(btnId);
        const icon  = document.getElementById(iconId);
        if (!input || !btn || !icon) return;
        btn.addEventListener('click', () => {
            const hidden = input.type === 'password';
            input.type = hidden ? 'text' : 'password';
            icon.innerHTML = hidden ? eyeClosed : eyeOpen;
            btn.setAttribute('aria-label', hidden ? 'Hide password' : 'Show password');
        });
    }

    makeToggle('password', 'togglePw1', 'eyeIcon1');
    makeToggle('password-confirm', 'togglePw2', 'eyeIcon2');

    // Password strength meter
    const pwInput = document.getElementById('password');
    const fill    = document.getElementById('pwStrengthFill');
    const label   = document.getElementById('pwStrengthLabel');

    if (pwInput && fill && label) {
        pwInput.addEventListener('input', () => {
            const v = pwInput.value;
            let score = 0;
            if (v.length >= 8)          score++;
            if (/[A-Z]/.test(v))        score++;
            if (/[0-9]/.test(v))        score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;

            const levels = [
                { w: '0%',   bg: 'transparent', text: '' },
                { w: '25%',  bg: '#EF4444',      text: 'Weak' },
                { w: '50%',  bg: '#F59E0B',      text: 'Fair' },
                { w: '75%',  bg: '#3B82F6',      text: 'Good' },
                { w: '100%', bg: '#10B981',      text: 'Strong' },
            ];

            const lv = v.length === 0 ? levels[0] : levels[score];
            fill.style.width = lv.w;
            fill.style.background = lv.bg;
            label.textContent = lv.text;
            label.style.color = lv.bg;
        });
    }

    // Submit loading state
    const form = document.getElementById('registerForm');
    const btn  = document.getElementById('registerBtn');
    if (form && btn) {
        form.addEventListener('submit', () => {
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';
            btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 0.8s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Creating account…`;
        });
    }
})();
</script>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}
</style>
</body>
</html>
