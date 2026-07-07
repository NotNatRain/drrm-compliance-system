{{-- resources/views/auth/passwords/reset.blade.php --}}
{{-- Step 3 of 3: Choose new password --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Password – DRRM Compliance</title>
    <meta name="description" content="Set your new DRRM Compliance account password.">
    <link rel="icon" type="image/png" href="{{ asset('images/drrmis-logo-2.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @include('auth.passwords._shared_styles')
</head>
<body>

<div class="page-container">
    <div class="brand-header">
        <img class="brand-logo" src="{{ asset('images/drrmis-logo-2.png') }}" alt="DRRM Logo">
        <h2 class="brand-title">DRRM Compliance System</h2>
    </div>

    <div class="card">
        @include('auth.passwords._steps', ['current' => 3])

        <h1 class="form-title">Set New Password</h1>
        <p class="form-sub">Choose a strong password that you haven't used before.</p>

        {{-- Verified email banner --}}
        <div class="verified-pill">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Verified: <strong>{{ $email ?? old('email') }}</strong>
        </div>

        <form method="POST" action="{{ route('password.update') }}" id="resetForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

            {{-- New password --}}
            <div class="field">
                <label class="field-label" for="password">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    New Password
                </label>
                <div class="input-wrap">
                    <input id="password" type="password" name="password"
                           class="form-input has-toggle {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="Create a strong password"
                           required autocomplete="new-password">
                    <button type="button" class="toggle-pw" id="togglePw1" aria-label="Show password">
                        <svg id="eyeIcon1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
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
                    Confirm Password
                </label>
                <div class="input-wrap">
                    <input id="password-confirm" type="password" name="password_confirmation"
                           class="form-input has-toggle"
                           placeholder="Re-enter your new password"
                           required autocomplete="new-password">
                    <button type="button" class="toggle-pw" id="togglePw2" aria-label="Show password">
                        <svg id="eyeIcon2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <div id="matchFeedback" class="feedback" style="display:none;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Passwords do not match
                </div>
            </div>

            <button type="submit" class="btn-submit" id="resetBtn">
                Reset Password
            </button>
        </form>
    </div>
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
    const pw    = document.getElementById('password');
    const fill  = document.getElementById('pwStrengthFill');
    const label = document.getElementById('pwStrengthLabel');

    if (pw && fill && label) {
        pw.addEventListener('input', () => {
            const v = pw.value;
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
            fill.style.width      = lv.w;
            fill.style.background = lv.bg;
            label.textContent     = lv.text;
            label.style.color     = lv.bg;

            checkMatch();
        });
    }

    const pwConfirm = document.getElementById('password-confirm');
    const matchFb   = document.getElementById('matchFeedback');

    function checkMatch() {
        if (!pw || !pwConfirm || !matchFb) return;
        if (pwConfirm.value.length === 0) { matchFb.style.display = 'none'; return; }
        if (pw.value !== pwConfirm.value) {
            matchFb.style.display = 'flex';
            pwConfirm.style.borderColor = '#EF4444';
        } else {
            matchFb.style.display = 'none';
            pwConfirm.style.borderColor = '#10B981';
        }
    }

    if (pwConfirm) pwConfirm.addEventListener('input', checkMatch);

    const form = document.getElementById('resetForm');
    const btn  = document.getElementById('resetBtn');
    if (form && btn) {
        form.addEventListener('submit', (e) => {
            if (pw && pwConfirm && pw.value !== pwConfirm.value) {
                e.preventDefault();
                checkMatch();
                return;
            }
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';
            btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 0.8s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Resetting…`;
        });
    }
})();
</script>
</body>
</html>
