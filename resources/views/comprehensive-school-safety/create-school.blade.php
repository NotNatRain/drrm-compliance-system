@extends('layouts.app')

@section('title', 'Create Comprehensive School')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Create New School</h2>
            <p class="text-muted mb-0">Add a school directly to the Comprehensive School Safety module.</p>
        </div>
        <a href="{{ route('comprehensive-school-safety.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('comprehensive-school-safety.schools.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">School Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">School ID Number</label>
                        <input type="text" name="school_id_number" value="{{ old('school_id_number') }}" class="form-control @error('school_id_number') is-invalid @enderror">
                        @error('school_id_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">District</label>
                        <input type="text" name="district" value="{{ old('district') }}" class="form-control @error('district') is-invalid @enderror">
                        @error('district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Division</label>
                        <input type="text" name="division" value="{{ old('division') }}" class="form-control @error('division') is-invalid @enderror">
                        @error('division')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Region</label>
                        <input type="text" name="region" value="{{ old('region') }}" class="form-control @error('region') is-invalid @enderror">
                        @error('region')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">School Head</label>
                        <input type="text" name="school_head" value="{{ old('school_head') }}" class="form-control @error('school_head') is-invalid @enderror">
                        @error('school_head')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" value="{{ old('contact_number') }}" class="form-control @error('contact_number') is-invalid @enderror">
                        @error('contact_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save School
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
