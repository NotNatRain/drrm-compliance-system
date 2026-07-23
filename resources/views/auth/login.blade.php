{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In – DRRM Compliance</title>
    <meta name="description" content="Sign in to access the DRRM Compliance dashboard.">
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
            --navy-lt:  #243558;
            --orange:   #E05C2E;
            --text:     #0D1B36;
            --muted:    #6B7280;
            --border:   #E2E8F0;
            --bg:       #F0F2F5;
            --white:    #FFFFFF;
            --radius:   10px;
            --panel-w:  420px;
        }

        html, body {
            height: 100%;
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

        /* Wave SVG background */
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

        /* Bottom pills */
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
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
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
            margin-bottom: 32px;
            line-height: 1.55;
        }

        /* Card wrapper */
        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px 28px 24px;
            box-shadow: 0 2px 16px rgba(13,27,54,0.07);
            animation: fadeUp 0.6s 0.25s ease both;
            opacity: 0;
        }

        /* Field group */
        .field { margin-bottom: 20px; }

        .field-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
        }

        .field-label svg {
            color: var(--muted);
            flex-shrink: 0;
        }

        .input-wrap {
            position: relative;
        }

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

        .form-input.is-invalid {
            border-color: #EF4444;
        }

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,0.12);
        }

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

        /* Error feedback */
        .feedback {
            font-size: 12px;
            color: #EF4444;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Row between remember-me and forgot */
        .row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .check-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--muted);
            cursor: pointer;
            user-select: none;
        }

        .check-label input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: var(--navy);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 13px;
            font-weight: 600;
            color: var(--navy);
            text-decoration: none;
        }
        .forgot-link:hover { text-decoration: underline; color: var(--orange); }

        /* Submit button */
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
        }

        .btn-submit:hover {
            background: #1a2e55;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(13,27,54,0.22);
        }

        .btn-submit:active { transform: translateY(0); }

        /* Bottom link */
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

        /* Alert */
        .alert-success {
            background: #D1FAE5;
            border: 1px solid #6EE7B7;
            color: #065F46;
            border-radius: var(--radius);
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
        }

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
                padding: 36px 20px 48px;
                animation: none;
            }

            .card { padding: 22px 18px; }
        }

        @media (max-width: 480px) {
            .left { padding: 20px 16px; }
            .right { padding: 24px 12px 36px; }
            .form-title { font-size: 1.4rem; }
            .card { padding: 18px 14px; }
        }
    </style>
</head>
<body>
<div class="split">

    {{-- ===== LEFT PANEL ===== --}}
    <aside class="left">
        {{-- Wave SVG --}}
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
            <h2 class="left-title">Disaster Risk Reduction &amp;<br>Management</h2>
            <p class="left-desc">Sign in to monitor compliance status, submit reports, and coordinate DRRM activities across your organization.</p>
        </div>
    </aside>

    {{-- ===== RIGHT PANEL ===== --}}
    <main class="right">
        <div class="form-box">
            <h1 class="form-title">Log in to your account</h1>
            <p class="form-sub">Enter your credentials to access the compliance dashboard.</p>

            <div class="card">
                @if (session('status'))
                    <div class="alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

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
                               required autocomplete="email" autofocus>
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
                                   placeholder="Enter your password"
                                   required autocomplete="current-password">
                            <button type="button" class="toggle-pw" id="togglePassword" aria-label="Show password">
                                <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="feedback">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="row-between">
                        <label class="check-label" for="remember">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            Remember me
                        </label>
                        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">Forgot password?</a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit" id="loginBtn">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Log in to dashboard
                    </button>
                </form>
            </div>

            <p class="form-footer">
                Don't have an account?&nbsp;<a href="{{ route('register') }}">Register here</a>
            </p>
        </div>
    </main>

</div>

<script>
(function () {
    // Password toggle
    const pw  = document.getElementById('password');
    const btn = document.getElementById('togglePassword');
    const icon = document.getElementById('eyeIcon');

    const eyeOpen   = `<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>`;
    const eyeClosed = `<line x1="17.94" y1="17.94" x2="6.06" y2="6.06"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 10 8 10 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M6.24 6.24A18.5 18.5 0 0 0 2 12s3 8 10 8a9.12 9.12 0 0 0 5.76-2.1"/><line x1="1" y1="1" x2="23" y2="23"/>`;

    if (btn && pw && icon) {
        btn.addEventListener('click', () => {
            const hidden = pw.type === 'password';
            pw.type = hidden ? 'text' : 'password';
            icon.innerHTML = hidden ? eyeClosed : eyeOpen;
            btn.setAttribute('aria-label', hidden ? 'Hide password' : 'Show password');
        });
    }

    // Submit loading state
    const form = document.getElementById('loginForm');
    const submitBtn = document.getElementById('loginBtn');
    if (form && submitBtn) {
        form.addEventListener('submit', () => {
            submitBtn.style.opacity = '0.7';
            submitBtn.style.pointerEvents = 'none';
            submitBtn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 0.8s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Logging in…`;
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
