<div class="modal fade" id="chooseSchoolModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="background: var(--bg-dark); border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-bottom border-white-50" style="background: rgba(0, 210, 255, 0.1); color: #00d2ff; padding: 1.5rem 2rem;">
                <h5 class="modal-title fw-bold" style="font-family: 'Rajdhani', sans-serif; letter-spacing: 1px;">
                    <i class="fas fa-school me-2"></i> SELECT EVACUATION CENTER HUB
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                @if(($evacuationCenters ?? collect())->isEmpty())
                    <div class="text-center py-5 opacity-50">
                        <i class="fas fa-satellite fa-4x mb-4 text-white"></i>
                        <h4 class="text-white">NO REGISTERED CENTERS</h4>
                        <p class="text-white-50 mb-0">Use "Add Center" to register a new school into the monitoring system.</p>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($evacuationCenters as $ec)
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm transition-transform hover-lift" 
                                     style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1) !important; border-radius: 12px; transition: all 0.3s ease;">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-info bg-opacity-10 p-2 rounded-circle me-3">
                                                <i class="fas fa-hotel text-info"></i>
                                            </div>
                                            <h6 class="fw-bold text-white mb-0" style="font-family: 'Rajdhani', sans-serif;">
                                                {{ strtoupper($ec->school_name ?? $ec->identification ?? ('Center #' . $ec->id)) }}
                                            </h6>
                                        </div>
                                        <div class="small text-white-50 mb-3">
                                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                            {{ Str::limit($ec->location ?? $ec->address ?? 'No Location Set', 50) }}
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center bg-black bg-opacity-20 p-2 rounded">
                                            <div class="text-center flex-grow-1">
                                                <div class="small text-white-50 text-uppercase" style="font-size: 0.6rem; letter-spacing: 1px;">Capacity</div>
                                                <div class="fw-bold text-white">{{ $ec->evacuation_capacity ?? 0 }}</div>
                                            </div>
                                            <div style="width: 1px; height: 20px; background: rgba(255,255,255,0.1);"></div>
                                            <div class="text-center flex-grow-1">
                                                <div class="small text-white-50 text-uppercase" style="font-size: 0.6rem; letter-spacing: 1px;">Current</div>
                                                <div class="fw-bold text-info">{{ $ec->current_occupancy ?? 0 }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-top border-white-10 p-3">
                                        <a href="{{ route('typhoon.evacuation-center.show', $ec->id) }}" class="btn btn-sm btn-outline-info w-100 fw-bold py-2">
                                            <i class="fas fa-external-link-alt me-2"></i> ACCESS HUB INTELLIGENCE
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="modal-footer bg-black bg-opacity-20 border-top border-white-10">
                <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

