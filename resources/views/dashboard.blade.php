{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - DRRM Compliance')

@push('styles')
<style>
    .announcement-banner {
        position: relative;
        width: 100%;
        padding-top: 25%; /* Reduced height */
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        margin-bottom: 2.5rem;
        background-color: #ffffff;
    }
    @media (max-width: 768px) {
        .announcement-banner {
            padding-top: 56.25%; /* 16:9 on mobile */
        }
    }
    .announcement-banner .carousel-inner {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    .announcement-banner img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain; /* Shows white spaces at sides if not landscape */
        transition: transform 0.5s ease;
    }
    .announcement-banner:hover img {
        transform: scale(1.02);
    }
    .announcement-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.85));
        color: white;
        padding: 40px;
        z-index: 2;
    }
    .announcement-content {
        max-width: 80% ;
    }
    .announcement-meta {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 10px;
    }
    .announcement-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    .announcement-why {
        font-size: 1.1rem;
        line-height: 1.4;
        opacity: 0.95;
    }
    .delete-announcement-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 10;
        background: rgba(220, 53, 69, 0.8);
        border: none;
        color: white;
        padding: 5px 12px;
        border-radius: 5px;
        backdrop-filter: blur(5px);
    }
    .delete-announcement-btn:hover {
        background: #dc3545;
    }
    .carousel-indicators {
        margin-bottom: 2rem;
    }
    .carousel-indicators [data-bs-target] {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgba(255,255,255,0.5);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .carousel-indicators .active {
        background-color: #fff;
        transform: scale(1.2);
    }
    .carousel-control-prev, .carousel-control-next {
        width: 5%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .announcement-banner:hover .carousel-control-prev,
    .announcement-banner:hover .carousel-control-next {
        opacity: 0.8;
    }
    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: rgba(0,0,0,0.3);
        padding: 20px;
        border-radius: 50%;
        background-size: 50% 50%;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        @if($announcements->count() > 0)
            {{-- Announcement carousel will replace welcome text --}}
        @else
            <p class="text-muted mb-0">Welcome back, <strong>{{ Auth::user()->name }}</strong>! Select a compliance system to manage.</p>
        @endif

        @if(Auth::user()->role === 'admin')
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#announceModal">
                <i class="fas fa-bullhorn me-2"></i> Announce
            </button>
        @endif
    </div>

    @if($announcements->count() > 0)
        <div id="announcementCarousel" class="carousel slide announcement-banner mb-5" data-bs-ride="carousel" data-bs-interval="5000">
            @if($announcements->count() > 1)
                <div class="carousel-indicators">
                    @foreach($announcements as $index => $announcement)
                        <button type="button" data-bs-target="#announcementCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif

            <div class="carousel-inner h-100">
                @foreach($announcements as $index => $announcement)
                    <div class="carousel-item h-100 {{ $index === 0 ? 'active' : '' }}">
                        @if(Auth::user()->role === 'admin')
                            <button class="delete-announcement-btn" onclick="deleteAnnouncement({{ $announcement->id }})" title="Remove Announcement">
                                <i class="fas fa-times me-1"></i> Remove
                            </button>
                        @endif
                        <img src="{{ asset('storage/' . $announcement->image_path) }}" class="d-block w-100" alt="Announcement Poster">
                        <div class="announcement-overlay">
                            <div class="announcement-content">
                                <p class="announcement-meta">
                                    <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($announcement->when)->format('F j, Y \a\t h:i A') }}
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $announcement->where }}
                                </p>
                                <h2 class="announcement-title">{{ $announcement->what }}</h2>
                                <p class="announcement-why mb-0">{{ $announcement->why }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($announcements->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#announcementCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#announcementCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    @endif

    <!-- Main System Cards -->
    <div class="row justify-content-center">
        <!-- Fire Safety Compliance -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('fire-safety.dashboard') }}" class="text-decoration-none">
                <div class="card border-0 shadow-lg h-100" style="border-top: 5px solid #D12428;">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-fire fa-4x" style="color: #D12428;"></i>
                        </div>
                        <h3 class="card-title fw-bold" style="color: #D12428;">Fire Safety</h3>
                        <p class="card-text text-muted">
                            Alarm systems, fire extinguishers, building inspections, and evacuation plans management
                        </p>
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <span class="btn" style="background-color: #D12428; color: white;">
                            <i class="fas fa-arrow-right"></i> Enter
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Typhoon/Flooding Compliance -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('typhoon.dashboard') }}" class="text-decoration-none">
                <div class="card border-0 shadow-lg h-100" style="border-top: 5px solid #1B4C6D;">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-umbrella fa-4x" style="color: #1B4C6D;"></i>
                        </div>
                        <h3 class="card-title fw-bold" style="color: #1B4C6D;">Typhoon/Flooding</h3>
                        <p class="card-text text-muted">
                            Casualty tracking, evacuation centers, evacuee management, and real-time monitoring
                        </p>
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <span class="btn" style="background-color: #1B4C6D; color: white;">
                            <i class="fas fa-arrow-right"></i> Enter
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Incidents Compliance -->
        <div class="col-md-4 mb-4">
            @php
                $canAccessIncidents = auth()->check() && (auth()->user()->role === 'admin');
            @endphp
            <a href="{{ $canAccessIncidents ? route('incidents.dashboard') : '#' }}" class="text-decoration-none {{ $canAccessIncidents ? '' : 'disabled' }}">
                <div class="card border-0 shadow-lg h-100" style="border-top: 5px solid #F2C94C;">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-clipboard-list fa-4x" style="color: #F2C94C;"></i>
                        </div>
                        <h3 class="card-title fw-bold" style="color: #F2C94C;">Incident Checklist</h3>
                        <p class="card-text text-muted">
                            Incident recording, victim management, compliance checklists, and remarks tracking
                        </p>
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <span class="btn {{ $canAccessIncidents ? '' : 'disabled' }}" style="background-color: #F2C94C; color: #333; {{ $canAccessIncidents ? '' : 'opacity: 0.7; cursor: not-allowed;' }}">
                            <i class="fas fa-arrow-right"></i> {{ $canAccessIncidents ? 'Enter' : 'Admin Only' }}
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Comprehensive School Safety (4th compliant - below Fire Safety) -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('comprehensive-school-safety.dashboard') }}" class="text-decoration-none">
                <div class="card border-0 shadow-lg h-100" style="border-top: 5px solid #5C4033;">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-school fa-4x" style="color: #5C4033;"></i>
                        </div>
                        <h3 class="card-title fw-bold" style="color: #5C4033;">Comprehensive School Safety</h3>
                        <p class="card-text text-muted">
                            Evaluation compliance system that assesses tools, school disaster risk management, DRR in education, and safe learning facilities.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <span class="btn" style="background-color: #5C4033; color: white;">
                            <i class="fas fa-arrow-right"></i> Enter
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Hazard Mapping (5th compliant) -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('hazard-mapping.dashboard') }}" class="text-decoration-none">
                <div class="card border-0 shadow-lg h-100" style="border-top: 5px solid #0D7377;">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-map-marked-alt fa-4x" style="color: #0D7377;"></i>
                        </div>
                        <h3 class="card-title fw-bold" style="color: #0D7377;">Hazard Mapping</h3>
                        <p class="card-text text-muted">
                            Identify, assess, and map hazards affecting school sites and surrounding areas for risk reduction and preparedness planning.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <span class="btn" style="background-color: #0D7377; color: white;">
                            <i class="fas fa-arrow-right"></i> Enter
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> System Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3">
                                <h2 class="text-primary">0</h2>
                                <p class="text-muted mb-0">Total Active Alerts</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <h2 class="text-success">0</h2>
                                <p class="text-muted mb-0">Completed Today</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <h2 class="text-warning">0</h2>
                                <p class="text-muted mb-0">Pending Actions</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <h2 class="text-info">0</h2>
                                <p class="text-muted mb-0">Total Records</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Announce Modal -->
<div class="modal fade" id="announceModal" tabindex="-1" aria-labelledby="announceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="announceModalLabel"><i class="fas fa-bullhorn me-2"></i> Create System Announcement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="announceForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">What is the event? (Title)</label>
                            <input type="text" name="what" class="form-control" placeholder="e.g. Annual Fire Safety Drill 2026" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">When? (Date & Time)</label>
                            <input type="datetime-local" name="when" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Where will it take place?</label>
                            <input type="text" name="where" class="form-control" placeholder="e.g. Main School Quadrangle" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Why? (Small Description)</label>
                            <textarea name="why" class="form-control" rows="3" placeholder="Explain the purpose of this announcement..." required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Upload Poster/Flyer (Panoramic/Widescreen 16:9 recommended)</label>
                            <p class="text-info small mb-2"><i class="fas fa-info-circle me-1"></i> Note: Uploading an image should've be in a landscape form for best results.</p>
                            <input type="file" name="image" class="form-control" accept="image/*" required onchange="previewImage(this)">
                            <div id="imagePreview" class="mt-3 d-none">
                                <p class="small text-muted mb-2">Image Preview:</p>
                                <div style="width: 100%; padding-top: 25%; position: relative; overflow: hidden; border-radius: 8px; border: 1px solid #ddd; background: #fff;">
                                    <img id="previewImg" src="#" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitAnnounce">
                        <i class="fas fa-paper-plane me-2"></i> Post Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('announceForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = document.getElementById('submitAnnounce');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Posting...';

        fetch("{{ route('announcements.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Posted!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message, 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Post Announcement';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Something went wrong!', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Post Announcement';
        });
    });

    function deleteAnnouncement(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This announcement will be removed from the dashboard.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/announcements/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Removed!', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection
