@extends('layouts.app')

@section('title', 'Volunteer QR')
@section('hide_main_nav', '1')

@section('content')
<div class="container py-5">
    <div class="card shadow mx-auto" style="max-width:480px;">
        <div class="card-body text-center">
            <h3 class="mb-3" style="color:#1B4C6D;">
                <i class="fas fa-qrcode me-2"></i>
                Volunteer {{ $action === 'check-in' ? 'Check-In' : 'Check-Out' }}
            </h3>
            <p class="lead mb-1">{{ $volunteer->name }}</p>
            <p class="text-muted mb-4">{{ $volunteer->contact }}</p>
            <p class="mb-3">
                Status updated to:
                <span class="badge bg-primary">{{ ucfirst($volunteer->status) }}</span>
            </p>
            <p class="text-muted mb-0">
                This page can be printed or screenshotted as proof of {{ $action }}.
            </p>
        </div>
    </div>
</div>
@endsection

