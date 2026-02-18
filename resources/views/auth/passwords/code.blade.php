@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fas fa-key me-2"></i>Verify Reset Code
                </div>

                <div class="card-body p-4">
                    <p class="text-center mb-4">A 6-digit verification code has been sent to <strong>{{ request('email') }}</strong>. Please enter it below to reset your password.</p>

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.verify-code') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ request('email') }}">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-6 text-center">
                                <label for="code" class="form-label fw-bold">6-Digit Code</label>
                                <input id="code" type="text" class="form-control form-control-lg text-center letter-spacing-lg @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required autofocus maxlength="6" placeholder="000000">

                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-2 text-center">
                                <button type="submit" class="btn btn-primary px-4">
                                    Verify Code & Continue
                                </button>
                                <div class="mt-3">
                                    <a class="btn btn-link text-muted small" href="{{ route('password.request') }}">
                                        Didn't receive a code? Try again
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.letter-spacing-lg {
    letter-spacing: 0.5rem;
    font-size: 1.5rem;
    font-weight: bold;
}
</style>
@endsection
