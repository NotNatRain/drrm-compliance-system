<div class="modal fade" id="chooseSchoolModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#1B4C6D;color:white;">
                <h5 class="modal-title">
                    <i class="fas fa-school"></i> Choose Evacuation Center / School
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if(($evacuationCenters ?? collect())->isEmpty())
                    <p class="text-muted mb-0">No evacuation centers yet. Use "Create Evacuation Center/School" to add one.</p>
                @else
                    <div class="row">
                        @foreach($evacuationCenters as $ec)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="fw-bold">
                                            {{ $ec->school->school_name ?? $ec->identification ?? ('Evacuation Center #' . $ec->id) }}
                                        </h6>
                                        <p class="mb-1"><small class="text-muted">{{ $ec->location ?? $ec->school->address ?? 'Location not set' }}</small></p>
                                        <p class="mb-1">
                                            <small>
                                                Capacity: {{ $ec->capacity ?? 0 }} •
                                                Occupancy: {{ $ec->current_occupancy ?? 0 }}
                                            </small>
                                        </p>
                                    </div>
                                    <div class="card-footer bg-white text-end">
                                        <a href="{{ route('typhoon.evacuation-center.show', $ec->id) }}" class="btn btn-sm btn-primary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

