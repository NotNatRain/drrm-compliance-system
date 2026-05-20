@extends('layouts.app')

@push('styles')
<style>
    .da-header {
        background-color: #6f42c1;
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(111, 66, 193, 0.2);
    }
    .da-header h2 {
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="da-header">
        <h2 class="mb-0"><i class="fas fa-user-injured me-2"></i> Damage Assessment Dashboard</h2>
    </div>
</div>
@endsection