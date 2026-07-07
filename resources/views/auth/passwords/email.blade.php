{{-- resources/views/auth/passwords/email.blade.php --}}
{{-- Step 1 of 3: Enter email to receive 6-digit code --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password – DRRM Compliance</title>
    <meta name="description" content="Reset your DRRM Compliance account password.">
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
        @include('auth.passwords._steps', ['current' => 1])

        <h1 class="form-title">Reset Password</h1>
        <p class="form-sub">Enter your registered email address. We'll send a 6-digit verification code to help you get back into your account.</p>

        @if (session('status'))
            <div class="alert-success-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" id="emailForm">
            @csrf

            <div class="field">
                <label class="field-label" for="email">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    Email Address
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

            <button type="submit" class="btn-submit" id="submitBtn">
                Send Verification Code
            </button>
        </form>

        <p class="form-footer">
            Remembered it?&nbsp;<a href="{{ route('login') }}">Back to login</a>
        </p>
    </div>
</div>

<script>
(function(){
    const form = document.getElementById('emailForm');
    const btn  = document.getElementById('submitBtn');
    if(form && btn){
        form.addEventListener('submit', () => {
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';
            btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 0.8s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Sending…`;
        });
    }
})();
</script>

</body>
</html>
