@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'assessments')

@section('content')
@php
    $sections = collect($assessmentSections ?? [])
        ->map(function ($section, $key) {
            return [
                'key' => is_string($key) ? $key : null,
                'title' => $section['title'] ?? '',
                'division' => $section['division'] ?? 'checklist_tools',
                'items' => collect($section['items'] ?? [])->map(function ($item) {
                    if (is_array($item)) {
                        return [
                            'text' => $item['text'] ?? '',
                            'default_points' => $item['default_points'] ?? 0,
                        ];
                    }

                    return [
                        'text' => $item,
                        'default_points' => 0,
                    ];
                })->values()->all(),
            ];
        })
        ->values()
        ->all();
@endphp

<h2 class="csss-section-title mb-2">Edit Assessment Questionnaires</h2>
<p class="csss-muted mb-4">Customize section titles and checklist items used by the assessment form.</p>

<form method="POST" action="{{ route('comprehensive-school-safety.school.assessments.questionnaire.update', $school->id) }}" id="questionnaireForm">
    @csrf

    <div class="csss-card p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <h5 class="mb-0">Questionnaire Sections</h5>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addSectionBtn">
                <i class="fas fa-plus me-1"></i> Add Section
            </button>
        </div>

        <div id="sectionsContainer" class="d-flex flex-column gap-3">
            @foreach(($sections ?? []) as $sectionIndex => $section)
                <div class="question-section border rounded p-3" data-section-index="{{ $sectionIndex }}">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="badge text-bg-light">Section {{ $sectionIndex + 1 }}</span>
                        <select class="form-select form-select-sm section-division" style="max-width: 200px;" name="sections[{{ $sectionIndex }}][division]" required>
                            <option value="checklist_tools" {{ ($section['division'] ?? '') === 'checklist_tools' ? 'selected' : '' }}>Checklist Tools</option>
                            <option value="pillar_1" {{ ($section['division'] ?? '') === 'pillar_1' ? 'selected' : '' }}>Pillar 1</option>
                        </select>
                        <input type="text"
                               class="form-control form-control-sm flex-grow-1 section-title"
                               name="sections[{{ $sectionIndex }}][title]"
                               value="{{ $section['title'] ?? '' }}"
                               placeholder="Section title"
                               required>
                        <button type="button" class="btn btn-sm btn-outline-secondary move-up-btn" title="Move up">
                            <i class="fas fa-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary move-down-btn" title="Move down">
                            <i class="fas fa-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-section-btn" title="Remove section">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                    <div class="items-container d-flex flex-column gap-2">
                        @foreach(($section['items'] ?? []) as $itemIndex => $item)
                            <div class="question-item d-flex align-items-center gap-2">
                                <span class="text-muted item-number" style="min-width: 2rem;">{{ $itemIndex + 1 }}.</span>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][text]"
                                       value="{{ $item['text'] ?? '' }}"
                                       placeholder="Question item"
                                       required>
                                <input type="number"
                                       class="form-control form-control-sm question-points"
                                       style="max-width: 110px;"
                                       min="0"
                                       step="0.01"
                                       name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][default_points]"
                                       value="{{ $item['default_points'] ?? 0 }}"
                                       placeholder="Points"
                                       required>
                                <button type="button" class="btn btn-sm btn-outline-secondary move-item-up-btn" title="Move question up">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary move-item-down-btn" title="Move question down">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" title="Remove question">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3 d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-outline-primary add-item-btn">
                            <i class="fas fa-plus me-1"></i> Add Question
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); color: white;">
            <i class="fas fa-save me-2"></i> Save Questionnaires
        </button>
        <a href="{{ route('comprehensive-school-safety.school.assessments', $school->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-times me-2"></i> Cancel
        </a>
    </div>
</form>
@endsection

@push('scripts')
<script>
(function () {
    const sectionsContainer = document.getElementById('sectionsContainer');
    const addSectionBtn = document.getElementById('addSectionBtn');

    function sectionTemplate() {
        return `
            <div class="question-section border rounded p-3">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="badge text-bg-light">Section</span>
                    <select class="form-select form-select-sm section-division" style="max-width: 200px;" required>
                        <option value="checklist_tools">Checklist Tools</option>
                        <option value="pillar_1">Pillar 1</option>
                    </select>
                    <input type="text" class="form-control form-control-sm flex-grow-1 section-title" placeholder="Section title" required>
                    <button type="button" class="btn btn-sm btn-outline-secondary move-up-btn" title="Move up"><i class="fas fa-arrow-up"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary move-down-btn" title="Move down"><i class="fas fa-arrow-down"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-section-btn" title="Remove section"><i class="fas fa-trash"></i></button>
                </div>
                <div class="items-container d-flex flex-column gap-2"></div>
                <div class="mt-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-outline-primary add-item-btn"><i class="fas fa-plus me-1"></i> Add Question</button>
                </div>
            </div>
        `;
    }

    function itemTemplate() {
        return `
            <div class="question-item d-flex align-items-center gap-2">
                <span class="text-muted item-number" style="min-width: 2rem;">1.</span>
                <input type="text" class="form-control form-control-sm" placeholder="Question item" required>
                <input type="number" class="form-control form-control-sm question-points" style="max-width: 110px;" min="0" step="0.01" placeholder="Points" required>
                <button type="button" class="btn btn-sm btn-outline-secondary move-item-up-btn" title="Move question up"><i class="fas fa-arrow-up"></i></button>
                <button type="button" class="btn btn-sm btn-outline-secondary move-item-down-btn" title="Move question down"><i class="fas fa-arrow-down"></i></button>
                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" title="Remove question"><i class="fas fa-times"></i></button>
            </div>
        `;
    }

    function renumber() {
        const sections = sectionsContainer.querySelectorAll('.question-section');

        sections.forEach((section, sIdx) => {
            section.dataset.sectionIndex = sIdx;
            const badge = section.querySelector('.badge');
            if (badge) {
                badge.textContent = `Section ${sIdx + 1}`;
            }

            const title = section.querySelector('.section-title');
            const division = section.querySelector('.section-division');
            if (title) {
                title.name = `sections[${sIdx}][title]`;
            }
            if (division) {
                division.name = `sections[${sIdx}][division]`;
            }

            const items = section.querySelectorAll('.question-item');
            items.forEach((item, iIdx) => {
                const input = item.querySelector('input[type="text"]');
                const points = item.querySelector('.question-points');
                const number = item.querySelector('.item-number');
                if (number) {
                    number.textContent = `${iIdx + 1}.`;
                }
                if (input) {
                    input.name = `sections[${sIdx}][items][${iIdx}][text]`;
                }
                if (points) {
                    points.name = `sections[${sIdx}][items][${iIdx}][default_points]`;
                }
            });
        });
    }

    function ensureAtLeastOneItem(sectionEl) {
        const itemsContainer = sectionEl.querySelector('.items-container');
        if (!itemsContainer.querySelector('.question-item')) {
            itemsContainer.insertAdjacentHTML('beforeend', itemTemplate());
        }
    }

    addSectionBtn.addEventListener('click', () => {
        sectionsContainer.insertAdjacentHTML('beforeend', sectionTemplate());
        const newSection = sectionsContainer.lastElementChild;
        ensureAtLeastOneItem(newSection);
        renumber();
    });

    sectionsContainer.addEventListener('click', (event) => {
        const target = event.target.closest('button');
        if (!target) {
            return;
        }

        const section = target.closest('.question-section');
        if (!section) {
            return;
        }

        if (target.classList.contains('add-item-btn')) {
            section.querySelector('.items-container').insertAdjacentHTML('beforeend', itemTemplate());
            renumber();
            return;
        }

        if (target.classList.contains('remove-section-btn')) {
            if (!window.confirm('Remove this section and all of its questionnaires?')) {
                return;
            }
            section.remove();
            renumber();
            return;
        }

        if (target.classList.contains('move-up-btn')) {
            const prev = section.previousElementSibling;
            if (prev) {
                sectionsContainer.insertBefore(section, prev);
                renumber();
            }
            return;
        }

        if (target.classList.contains('move-down-btn')) {
            const next = section.nextElementSibling;
            if (next) {
                sectionsContainer.insertBefore(next, section);
                renumber();
            }
            return;
        }

        const item = target.closest('.question-item');
        if (!item) {
            return;
        }

        if (target.classList.contains('remove-item-btn')) {
            if (!window.confirm('Remove this questionnaire item?')) {
                return;
            }
            item.remove();
            ensureAtLeastOneItem(section);
            renumber();
            return;
        }

        if (target.classList.contains('move-item-up-btn')) {
            const prevItem = item.previousElementSibling;
            if (prevItem) {
                item.parentElement.insertBefore(item, prevItem);
                renumber();
            }
            return;
        }

        if (target.classList.contains('move-item-down-btn')) {
            const nextItem = item.nextElementSibling;
            if (nextItem) {
                item.parentElement.insertBefore(nextItem, item);
                renumber();
            }
        }
    });

    const existingSections = sectionsContainer.querySelectorAll('.question-section');
    if (!existingSections.length) {
        addSectionBtn.click();
    } else {
        existingSections.forEach(ensureAtLeastOneItem);
        renumber();
    }
})();
</script>
@endpush
