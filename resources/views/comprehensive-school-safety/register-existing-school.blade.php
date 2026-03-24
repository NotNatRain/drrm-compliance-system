@extends('layouts.app')

@section('title', 'Register Existing School')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Register Existing School</h2>
            <p class="text-muted mb-0">Select a school from Fire Safety Compliance and register it into Comprehensive School Safety.</p>
        </div>
        <a href="{{ route('comprehensive-school-safety.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($fireSafetySchools->isEmpty())
                <div class="alert alert-info mb-0">All Fire Safety schools are already registered in Comprehensive School Safety.</div>
            @else
                <form method="POST" action="{{ route('comprehensive-school-safety.schools.register-existing.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Fire Safety School</label>
                        <select name="fire_safety_school_id" class="form-select @error('fire_safety_school_id') is-invalid @enderror" required>
                            <option value="">Select school...</option>
                            @foreach($fireSafetySchools as $school)
                                <option value="{{ $school->id }}" {{ old('fire_safety_school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->school_name }} {{ $school->school_id ? '(' . $school->school_id . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('fire_safety_school_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-1"></i> Register Selected School
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
