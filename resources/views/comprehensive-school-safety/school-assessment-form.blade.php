@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'assessments')

@section('content')
@php
    $formMode = $formMode ?? 'create';
    $isView = $formMode === 'view';
    $isEdit = $formMode === 'edit';
    $assessmentCode = $assessmentCode ?? 'ASMT-001';
    $assessmentSummary = $assessmentSummary ?? '';
    $titleText = $isView ? ('View ' . $assessmentCode) : ($isEdit ? ('Edit Safety Assessment ' . $assessmentCode) : 'New Safety Assessment');
    $formAction = $isEdit
        ? route('comprehensive-school-safety.school.assessments.update', [$school->id, $assessment->id])
        : route('comprehensive-school-safety.school.assessments.store', $school->id);
    $groupedSections = [];
    $divisionLabels = [];

    foreach (($assessmentSections ?? []) as $sectionKey => $section) {
        $division = trim((string) ($section['division'] ?? 'checklist_tools'));
        if ($division === '') {
            $division = 'checklist_tools';
        }

        if (!array_key_exists($division, $groupedSections)) {
            $groupedSections[$division] = [];
        }

        if (!array_key_exists($division, $divisionLabels)) {
            $divisionLabels[$division] = trim((string) ($section['division_label'] ?? ''));
        }

        if (($divisionLabels[$division] ?? '') === '') {
            $divisionLabels[$division] = collect(explode('_', $division))
                ->filter(fn ($part) => $part !== '')
                ->map(fn ($part) => ucfirst($part))
                ->implode(' ');
        }

        $groupedSections[$division][$sectionKey] = $section;
    }
@endphp

<div class="d-flex justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h2 class="csss-section-title mb-1">{{ $titleText }}</h2>
        <p class="csss-muted mb-0">Complete the checklist below for the school facility.</p>
    </div>
    @if($isView && isset($assessment))
        <a href="{{ route('comprehensive-school-safety.school.assessments.edit', [$school->id, $assessment->id]) }}" class="btn btn-outline-dark">
            <i class="fas fa-edit me-1"></i> Edit Assessment
        </a>
    @endif
</div>

<div class="csss-card p-3 mb-4 d-flex flex-wrap gap-2 sticky-top" style="top: 1rem; z-index: 3;">
    @foreach($divisionLabels as $divisionKey => $divisionLabel)
        @php
            $anchorId = 'division-' . preg_replace('/[^a-z0-9]+/i', '-', strtolower((string) $divisionKey));
            $icon = str_contains(strtolower((string) $divisionLabel), 'pillar') ? 'fas fa-layer-group' : 'fas fa-list-check';
        @endphp
        <a href="#{{ $anchorId }}" class="btn {{ $loop->first ? 'btn-dark' : 'btn-outline-dark' }} btn-sm px-3">
            <i class="{{ $icon }} me-1"></i> {{ $divisionLabel }}
        </a>
    @endforeach
</div>

<form method="POST" action="{{ $formAction }}" class="position-relative">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <!-- General Information Section -->
    <div id="checklist-tools" class="csss-card p-4 mb-4">
        <h5 class="fw-bold mb-3">General Information</h5>
        <p class="csss-muted small mb-3">Complete the checklist below for the school facility.</p>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-600">School Name</label>
                <input type="text" class="form-control" value="{{ $school->name }}" disabled>
                <input type="hidden" name="school_name" value="{{ $school->name }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">Date Visited</label>
                <input type="date" name="date_visited" class="form-control @error('date_visited') is-invalid @enderror" value="{{ old('date_visited', isset($assessment) ? optional($assessment->date_visited)->format('Y-m-d') : '') }}" {{ $isView ? 'disabled' : '' }} required>
                @error('date_visited')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">Assessed By</label>
                <input type="text" name="assessed_by" class="form-control @error('assessed_by') is-invalid @enderror" placeholder="Full Name of Assessor" value="{{ old('assessed_by', $assessment->assessed_by ?? '') }}" {{ $isView ? 'disabled' : '' }} required>
                @error('assessed_by')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    @foreach($groupedSections as $divisionKey => $sectionsByDivision)
        @php
            $divisionAnchorId = 'division-' . preg_replace('/[^a-z0-9]+/i', '-', strtolower((string) $divisionKey));
            $divisionLabel = $divisionLabels[$divisionKey] ?? 'Assessment Part';
        @endphp
        <div id="{{ $divisionAnchorId }}" class="mb-3">
            <h5 class="fw-bold mb-3">{{ $divisionLabel }}</h5>
        </div>

        @foreach($sectionsByDivision as $sectionKey => $section)
            <div class="csss-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                    <h5 class="fw-bold mb-0">{{ $section['title'] }}</h5>
                    <span class="badge text-bg-light">Subtotal: <span class="js-section-subtotal" data-section="{{ $sectionKey }}">0</span></span>
                </div>

                @foreach(($section['items'] ?? []) as $index => $item)
                    @php
                        $itemText = is_array($item) ? trim((string) ($item['text'] ?? '')) : trim((string) $item);
                        $itemSubtitle = is_array($item) ? trim((string) ($item['subtitle'] ?? '')) : '';
                        $defaultPoints = is_array($item) ? (float) ($item['default_points'] ?? 0) : 0;
                        $subItems = is_array($item) ? (array) ($item['sub_items'] ?? []) : [];
                    @endphp

                    @if(!empty($subItems))
                        <div class="assessment-item mb-4">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <strong class="text-primary flex-shrink-0">{{ $index + 1 }}</strong>
                                <span>{{ $itemText }}</span>
                            </div>

                            @if($itemSubtitle !== '')
                                <div class="ps-5 mb-2 text-muted small fw-semibold">{{ $itemSubtitle }}</div>
                            @endif

                            <div class="ps-5 d-flex flex-column gap-3">
                                @foreach($subItems as $subIndex => $subItem)
                                    @php
                                        $subText = is_array($subItem) ? trim((string) ($subItem['text'] ?? '')) : trim((string) $subItem);
                                        $subDefaultPoints = is_array($subItem) ? (float) ($subItem['default_points'] ?? 0) : 0;
                                        $criteriaLabel = collect([$itemText, $itemSubtitle !== '' ? $itemSubtitle : null, $subText])->filter(fn ($part) => $part !== null && $part !== '')->implode(' - ');
                                        $fieldSuffix = $index . '_sub_' . $subIndex;
                                        $responseKey = $sectionKey . ':' . md5($criteriaLabel);
                                        $saved = $assessmentResponses[$responseKey] ?? null;
                                        $selectedValue = old($sectionKey . '_' . $fieldSuffix, isset($saved['is_compliant']) ? ($saved['is_compliant'] ? 'yes' : 'no') : null);
                                        $remarksValue = old($sectionKey . '_remarks_' . $fieldSuffix, $saved['remarks'] ?? '');
                                        $pointsValue = old($sectionKey . '_points_' . $fieldSuffix, $saved['points'] ?? $subDefaultPoints);
                                    @endphp
                                    <div class="border rounded p-3 bg-light-subtle">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="fw-semibold text-secondary">{{ chr(65 + $subIndex) }}.</span>
                                            <span>{{ $subText }}</span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-3 mb-2 align-items-end">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="{{ $sectionKey }}_{{ $fieldSuffix }}" value="yes" id="{{ $sectionKey }}_yes_{{ $fieldSuffix }}" {{ $selectedValue === 'yes' ? 'checked' : '' }} {{ $isView ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="{{ $sectionKey }}_yes_{{ $fieldSuffix }}">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="{{ $sectionKey }}_{{ $fieldSuffix }}" value="no" id="{{ $sectionKey }}_no_{{ $fieldSuffix }}" {{ $selectedValue === 'no' ? 'checked' : '' }} {{ $isView ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="{{ $sectionKey }}_no_{{ $fieldSuffix }}">No</label>
                                            </div>
                                            <div>
                                                <label class="form-label small text-muted mb-1">Points</label>
                                                <input type="number" min="0" step="0.01" name="{{ $sectionKey }}_points_{{ $fieldSuffix }}" value="{{ $pointsValue }}" class="form-control form-control-sm js-points" data-section="{{ $sectionKey }}" data-division="{{ $divisionKey }}" style="width: 120px;" {{ $isView ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        <textarea name="{{ $sectionKey }}_remarks_{{ $fieldSuffix }}" class="form-control form-control-sm" placeholder="Add remarks/observations..." rows="2" {{ $isView ? 'disabled' : '' }}>{{ $remarksValue }}</textarea>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        @php
                            $responseKey = $sectionKey . ':' . md5($itemText);
                            $saved = $assessmentResponses[$responseKey] ?? null;
                            $selectedValue = old($sectionKey . '_' . $index, isset($saved['is_compliant']) ? ($saved['is_compliant'] ? 'yes' : 'no') : null);
                            $remarksValue = old($sectionKey . '_remarks_' . $index, $saved['remarks'] ?? '');
                            $pointsValue = old($sectionKey . '_points_' . $index, $saved['points'] ?? $defaultPoints);
                        @endphp
                        <div class="assessment-item mb-4">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <strong class="text-primary flex-shrink-0">{{ $index + 1 }}</strong>
                                <span>{{ $itemText }}</span>
                            </div>
                            <div class="ps-5 d-flex flex-wrap gap-3 mb-2 align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="{{ $sectionKey }}_{{ $index }}" value="yes" id="{{ $sectionKey }}_yes_{{ $index }}" {{ $selectedValue === 'yes' ? 'checked' : '' }} {{ $isView ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="{{ $sectionKey }}_yes_{{ $index }}">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="{{ $sectionKey }}_{{ $index }}" value="no" id="{{ $sectionKey }}_no_{{ $index }}" {{ $selectedValue === 'no' ? 'checked' : '' }} {{ $isView ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="{{ $sectionKey }}_no_{{ $index }}">No</label>
                                </div>
                                <div>
                                    <label class="form-label small text-muted mb-1">Points</label>
                                    <input type="number" min="0" step="0.01" name="{{ $sectionKey }}_points_{{ $index }}" value="{{ $pointsValue }}" class="form-control form-control-sm js-points" data-section="{{ $sectionKey }}" data-division="{{ $divisionKey }}" style="width: 120px;" {{ $isView ? 'disabled' : '' }}>
                                </div>
                            </div>
                            <textarea name="{{ $sectionKey }}_remarks_{{ $index }}" class="form-control form-control-sm ps-5" placeholder="Add remarks/observations..." rows="2" {{ $isView ? 'disabled' : '' }}>{{ $remarksValue }}</textarea>
                        </div>
                    @endif
                @endforeach
            </div>
        @endforeach
    @endforeach

    <div class="csss-card p-4 mb-4">
        <div class="row g-3">
            @foreach($divisionLabels as $divisionKey => $divisionLabel)
                <div class="col-md-4">
                    <div class="border rounded p-3 bg-light">
                        <small class="text-muted d-block">{{ $divisionLabel }} Total</small>
                        <strong class="fs-5 js-division-total" data-division-total="{{ $divisionKey }}">0</strong>
                    </div>
                </div>
            @endforeach
            <div class="col-md-4">
                <div class="border rounded p-3" style="background: #efe5d6;">
                    <small class="text-muted d-block">Grand Total</small>
                    <strong class="fs-5" id="grandTotal">0</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="csss-card p-4 mb-4">
        <h5 class="fw-bold mb-3">Summary Sheet</h5>
        <textarea name="summary_sheet" class="form-control" rows="5" placeholder="Add assessment summary, key findings, and recommendations..." {{ $isView ? 'disabled' : '' }}>{{ old('summary_sheet', $assessmentSummary) }}</textarea>
    </div>

    <!-- Action Buttons -->
    <div class="csss-card p-4 d-flex gap-2 sticky-bottom">
        @if(!$isView)
            <button type="submit" class="btn" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); color: white;">
                <i class="fas fa-save me-2"></i> {{ $isEdit ? 'Update Assessment' : 'Save Assessment' }}
            </button>
        @endif
        <a href="{{ route('comprehensive-school-safety.school.assessments', $school->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-times me-2"></i> {{ $isView ? 'Back' : 'Cancel' }}
        </a>
    </div>
</form>

<style>
    .assessment-item {
        padding: 1rem;
        background: #f9f7f4;
        border-radius: 8px;
    }

    .assessment-item strong {
        background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .sticky-bottom {
        position: sticky;
        bottom: 1.5rem;
        box-shadow: 0 -8px 20px rgba(92, 64, 51, 0.12);
    }
</style>

<script>
(function () {
    const pointInputs = Array.from(document.querySelectorAll('.js-points'));

    function parseNumber(value) {
        const parsed = parseFloat(value);
        return Number.isFinite(parsed) ? parsed : 0;
    }

    function updateTotals() {
        const sectionTotals = {};
        const divisionTotals = {};

        pointInputs.forEach((input) => {
            const value = Math.max(0, parseNumber(input.value));
            const section = input.dataset.section;
            const division = input.dataset.division || 'checklist_tools';

            sectionTotals[section] = (sectionTotals[section] || 0) + value;
            divisionTotals[division] = (divisionTotals[division] || 0) + value;
        });

        document.querySelectorAll('.js-section-subtotal').forEach((el) => {
            const key = el.dataset.section;
            el.textContent = (sectionTotals[key] || 0).toFixed(2);
        });

        let grandTotal = 0;
        document.querySelectorAll('.js-division-total').forEach((node) => {
            const key = node.dataset.divisionTotal;
            const total = divisionTotals[key] || 0;
            node.textContent = total.toFixed(2);
            grandTotal += total;
        });

        const grandNode = document.getElementById('grandTotal');
        if (grandNode) grandNode.textContent = grandTotal.toFixed(2);
    }

    pointInputs.forEach((input) => {
        input.addEventListener('input', updateTotals);
    });

    updateTotals();
})();
</script>
@endsection
