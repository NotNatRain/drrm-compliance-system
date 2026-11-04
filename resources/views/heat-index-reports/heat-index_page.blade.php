{{-- resources/views/heat-index-reports/heat-index_page.blade.php --}}
@extends('layouts.app')

@section('title', 'Heat Index Reports & Monitoring - DRRM Compliance')

@push('styles')
<style>
    .heat-header {
        background: linear-gradient(135deg, #FF6B35 0%, #EA580C 50%, #C2410C 100%);
        color: white;
        padding: 2.25rem 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(234, 88, 12, 0.25);
    }
    .heat-stat-card {
        background: #FFFFFF;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        transition: transform 0.2s;
    }
    .heat-stat-card:hover {
        transform: translateY(-3px);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 px-lg-5 py-4">
    <!-- Header Banner -->
    <div class="heat-header d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white text-dark fw-bold px-2 py-1">DepEd DO 037, s. 2022</span>
                <span class="badge bg-dark text-white fw-normal px-2 py-1">PAGASA Standard</span>
            </div>
            <h1 class="fw-bolder mb-1" style="font-family: var(--font-display, 'Sora', sans-serif);">
                <i class="fas fa-temperature-high me-2"></i> Heat Index Report & Weather Monitoring
            </h1>
            <p class="mb-0 text-white-50">
                Track real-time campus heat indices, assess health risks, and manage DepEd class suspension advisories.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light px-3 py-2 fw-semibold">
                <i class="fas fa-arrow-left me-1"></i> Dashboard
            </a>
            <button type="button" class="btn btn-light text-danger fw-bold px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#heatIndexReportModal">
                <i class="fas fa-plus-circle me-1"></i> Log Reading / Open Tool
            </button>
        </div>
    </div>

    <!-- Quick Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="heat-stat-card">
                <div class="text-muted small fw-bold text-uppercase">PAGASA Danger Threshold</div>
                <div class="h3 fw-bolder text-danger my-1">42°C+</div>
                <div class="small text-muted">Triggers in-person class suspension review</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="heat-stat-card">
                <div class="text-muted small fw-bold text-uppercase">Extreme Caution Range</div>
                <div class="h3 fw-bolder text-warning my-1">33°C – 41°C</div>
                <div class="small text-muted">Limit outdoor P.E. and playground exposure</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="heat-stat-card">
                <div class="text-muted small fw-bold text-uppercase">Monitored Schools</div>
                <div class="h3 fw-bolder text-dark my-1">{{ count($schools) }}</div>
                <div class="small text-muted">Active division campuses</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="heat-stat-card">
                <div class="text-muted small fw-bold text-uppercase">Recommended Modality</div>
                <div class="h3 fw-bolder text-primary my-1">MDL / ADM</div>
                <div class="small text-muted">Alternative delivery mode on extreme heat</div>
            </div>
        </div>
    </div>

    <!-- Main Content Trigger Card -->
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 mx-auto" 
             style="width: 80px; height: 80px; background: #FFEDD5; color: #EA580C; font-size: 2.5rem;">
            <i class="fas fa-temperature-high"></i>
        </div>
        <h3 class="fw-bold text-dark mb-2" style="font-family: var(--font-display, 'Sora', sans-serif);">
            Heat Index Reporting & Calculation Tool
        </h3>
        <p class="text-muted mx-auto mb-4" style="max-width: 600px;">
            Compute accurate heat indices using official ambient temperature and humidity readings, evaluate danger levels, record school reports, and print advisories.
        </p>
        <div>
            <button type="button" class="btn text-white px-4 py-2 fw-bold shadow-sm" 
                    style="background: linear-gradient(135deg, #EA580C, #C2410C); font-size: 1.05rem;"
                    data-bs-toggle="modal" data-bs-target="#heatIndexReportModal">
                <i class="fas fa-calculator me-2"></i> Launch Heat Index Tool & Modal
            </button>
        </div>
    </div>
</div>

<!-- Heat Index Report Modal -->
@include('heat-index-reports.heat-index_dashboard')
@endsection
