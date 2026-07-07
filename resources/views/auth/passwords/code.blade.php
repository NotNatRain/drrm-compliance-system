{{-- resources/views/auth/passwords/code.blade.php --}}
{{-- Step 2 of 3: Enter 6-digit verification code --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Code – DRRM Compliance</title>
    <meta name="description" content="Enter the 6-digit code sent to your email.">
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
        @include('auth.passwords._steps', ['current' => 2])

        <h1 class="form-title">Verification Code</h1>
        <p class="form-sub">
            We sent a 6-digit code to
            <strong style="color: var(--navy);">{{ request('email') }}</strong>.<br>
            Please enter it below.
        </p>

        @if (session('status'))
            <div class="alert-success-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.verify-code') }}" id="codeForm">
            @csrf
            <input type="hidden" name="email" value="{{ request('email') }}">

            <div class="field">
                <label class="field-label" for="code" style="justify-content: center;">
                    6-Digit Code
                </label>
                <input id="code" type="text" name="code"
                       class="form-input otp-input {{ $errors->has('code') ? 'is-invalid' : '' }}"
                       value="{{ old('code') }}"
                       placeholder="000000"
                       required autofocus
                       maxlength="6"
                       inputmode="numeric"
                       autocomplete="one-time-code"
                       pattern="[0-9]{6}">
                @error('code')
                    <div class="feedback" style="justify-content: center;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn-submit" id="verifyBtn">
                Verify Code
            </button>
        </form>

        <div class="resend-row">
            <a class="btn-ghost-sm" href="{{ route('password.request') }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                Didn't receive a code? Resend
            </a>
        </div>
    </div>
</div>

<script>
(function(){
    const input = document.getElementById('code');
    const form  = document.getElementById('codeForm');
    const btn   = document.getElementById('verifyBtn');

    if (input) {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
            if (this.value.length === 6 && form) {
                btn.style.opacity = '0.7';
                btn.style.pointerEvents = 'none';
                btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 0.8s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Verifying…`;
                form.submit();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text');
            this.value = pasted.replace(/\D/g, '').slice(0, 6);
            this.dispatchEvent(new Event('input'));
        });
    }

    if (form && btn) {
        form.addEventListener('submit', () => {
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';
            btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 0.8s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Verifying…`;
        });
    }
})();
</script>
</body>
</html>
