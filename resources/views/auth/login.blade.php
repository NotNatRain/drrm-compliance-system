{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Login - DRRM Compliance')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="DRRM" style="height: 28px; width: auto; margin-right: 8px;">
                        DRRM Compliance System
                    </h4>
                    <small class="text-light">Disaster Risk Reduction and Management</small>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <h5 class="text-secondary">Login to Access Dashboard</h5>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}"
                                   required autocomplete="email" autofocus
                                   placeholder="Enter your email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <div class="input-group">
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password" required autocomplete="current-password"
                                       placeholder="Enter your password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword"
                                        aria-label="Show password" aria-pressed="false">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember"
                                   id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center text-muted">
                    <small>DRRM Compliance System &copy; {{ date('Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const input = document.getElementById('password');
        const btn = document.getElementById('togglePassword');
        if (!input || !btn) return;

        btn.addEventListener('click', function () {
            const isHidden = input.getAttribute('type') === 'password';
            input.setAttribute('type', isHidden ? 'text' : 'password');

            const icon = btn.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', !isHidden);
                icon.classList.toggle('fa-eye-slash', isHidden);
            }
            btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            btn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
        });
    })();
</script>
@endpush
