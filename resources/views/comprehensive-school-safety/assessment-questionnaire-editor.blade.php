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
                'division_label' => $section['division_label'] ?? null,
                'items' => collect($section['items'] ?? [])->map(function ($item) {
                    if (is_array($item)) {
                        return [
                            'text' => $item['text'] ?? '',
                            'default_points' => $item['default_points'] ?? 0,
                            'subtitle' => $item['subtitle'] ?? '',
                            'sub_items' => collect($item['sub_items'] ?? [])->map(function ($subItem) {
                                if (is_array($subItem)) {
                                    return [
                                        'text' => $subItem['text'] ?? '',
                                        'default_points' => $subItem['default_points'] ?? 0,
                                    ];
                                }

                                return [
                                    'text' => $subItem,
                                    'default_points' => 0,
                                ];
                            })->values()->all(),
                        ];
                    }

                    return [
                        'text' => $item,
                        'default_points' => 0,
                        'subtitle' => '',
                        'sub_items' => [],
                    ];
                })->values()->all(),
            ];
        })
        ->values()
        ->all();

    $parts = [];
    foreach ($sections as $section) {
        $key = trim((string) ($section['division'] ?? ''));
        if ($key === '') {
            continue;
        }

        if (!array_key_exists($key, $parts)) {
            $parts[$key] = trim((string) ($section['division_label'] ?? ''));
        }

        if (($parts[$key] ?? '') === '') {
            $parts[$key] = collect(explode('_', $key))
                ->filter(fn ($part) => $part !== '')
                ->map(fn ($part) => ucfirst($part))
                ->implode(' ');
        }
    }

    if (empty($parts)) {
        $parts = [
            'checklist_tools' => 'Checklist Tools',
            'pillar_1' => 'Pillar 1',
        ];
    }
@endphp

<h2 class="csss-section-title mb-2">Edit Assessment Questionnaires</h2>
<p class="csss-muted mb-4">Customize assessment parts, section titles, questions, and questionnaire sub-content.</p>

<form method="POST" action="{{ route('comprehensive-school-safety.school.assessments.questionnaire.update', $school->id) }}" id="questionnaireForm">
    @csrf
    <input type="hidden" name="sections_json" id="sectionsJson">

    <div class="csss-card p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <h5 class="mb-0">Assessment Parts</h5>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addPartBtn">
                <i class="fas fa-plus me-1"></i> Add Assessment Part
            </button>
        </div>

        <div id="partsContainer" class="d-flex flex-wrap gap-2"></div>
    </div>

    <div class="csss-card p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <h5 class="mb-0">Questionnaire Sections</h5>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addSectionBtn">
                <i class="fas fa-plus me-1"></i> Add Section
            </button>
        </div>

        <div id="sectionsContainer" class="d-flex flex-column gap-3"></div>
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

<div class="csss-modal-overlay" id="editorModal" aria-hidden="true">
    <div class="csss-modal-card">
        <div class="csss-modal-header">
            <h5 id="editorModalTitle" class="mb-0">Confirm</h5>
            <p id="editorModalMessage" class="mb-0">Message</p>
        </div>
        <div id="editorModalInputWrap" class="mb-3 d-none">
            <input type="text" id="editorModalInput" class="form-control" />
            <small id="editorModalHint" class="text-muted"></small>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="editorModalCancel">Cancel</button>
            <button type="button" class="btn btn-sm btn-danger" id="editorModalConfirm">Confirm</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .question-section {
        border: 1px solid #dbc9ad;
        border-radius: 10px;
        background: #fffaf2;
    }

    .question-item {
        border: 1px solid #eadfce;
        border-radius: 8px;
        background: #ffffff;
        padding: 0.75rem;
    }

    .question-sub-item {
        border: 1px dashed #d9c5a8;
        border-radius: 8px;
        background: #fcf7ef;
        padding: 0.6rem;
    }

    .part-chip {
        border: 1px solid #d7c2a4;
        border-radius: 999px;
        padding: 0.35rem 0.75rem;
        background: #fff8ea;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .csss-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(53, 37, 30, 0.45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2200;
        padding: 1rem;
    }

    .csss-modal-overlay.is-open {
        display: flex;
    }

    .csss-modal-card {
        width: min(460px, 100%);
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5d6bf;
        box-shadow: 0 18px 34px rgba(70, 52, 40, 0.2);
        padding: 0;
        overflow: hidden;
        transform: translateY(14px) scale(0.98);
        opacity: 0;
        transition: transform 220ms ease, opacity 220ms ease;
    }

    .csss-modal-overlay.is-open .csss-modal-card {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .csss-modal-header {
        background: linear-gradient(135deg, #b35f2c 0%, #6f4632 100%);
        color: #fff;
        padding: 1rem 1.1rem;
    }

    .csss-modal-header p {
        font-size: 0.92rem;
        opacity: 0.92;
        margin-top: 0.25rem;
    }

    #editorModalInputWrap,
    .csss-modal-card .d-flex.justify-content-end {
        padding: 1rem 1.1rem 1.1rem;
    }

    #editorModalInputWrap {
        padding-bottom: 0.3rem;
    }

    .csss-modal-card .d-flex.justify-content-end {
        padding-top: 0;
    }

    .question-item .question-text,
    .question-sub-item .sub-item-text,
    .question-item .question-subtitle {
        min-width: 0;
        flex: 1 1 auto;
    }

    .question-item .d-flex.flex-wrap.align-items-center.gap-2.mb-2,
    .question-sub-item {
        align-items: center;
    }
</style>

<script>
(function () {
    const initialSections = @json($sections);
    const initialPartsMap = @json($parts);

    const partsContainer = document.getElementById('partsContainer');
    const sectionsContainer = document.getElementById('sectionsContainer');
    const sectionsJson = document.getElementById('sectionsJson');
    const addPartBtn = document.getElementById('addPartBtn');
    const addSectionBtn = document.getElementById('addSectionBtn');
    const form = document.getElementById('questionnaireForm');

    const modal = document.getElementById('editorModal');
    const modalTitle = document.getElementById('editorModalTitle');
    const modalMessage = document.getElementById('editorModalMessage');
    const modalInputWrap = document.getElementById('editorModalInputWrap');
    const modalInput = document.getElementById('editorModalInput');
    const modalHint = document.getElementById('editorModalHint');
    const modalCancel = document.getElementById('editorModalCancel');
    const modalConfirm = document.getElementById('editorModalConfirm');

    const state = {
        parts: [],
        sections: [],
    };

    let modalOnConfirm = null;

    function slugify(value) {
        return String(value || '')
            .trim()
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '_')
            .replace(/^_+|_+$/g, '') || 'assessment_part';
    }

    function toLabel(key) {
        return String(key || '')
            .split('_')
            .filter(Boolean)
            .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
            .join(' ');
    }

    function esc(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function openModal(config) {
        modalTitle.textContent = config.title || 'Confirm';
        modalMessage.textContent = config.message || '';
        modalConfirm.textContent = config.confirmText || 'Confirm';
        modalConfirm.className = `btn btn-sm ${config.confirmClass || 'btn-danger'}`;

        const withInput = Boolean(config.withInput);
        modalInputWrap.classList.toggle('d-none', !withInput);
        modalInput.value = withInput ? (config.defaultValue || '') : '';
        modalHint.textContent = config.hint || '';

        modalOnConfirm = config.onConfirm || null;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');

        if (withInput) {
            setTimeout(() => modalInput.focus(), 0);
        }
    }

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        modalOnConfirm = null;
    }

    modalCancel.addEventListener('click', closeModal);
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
    modalConfirm.addEventListener('click', () => {
        if (typeof modalOnConfirm === 'function') {
            const continueOpen = modalOnConfirm(modalInput.value);
            if (continueOpen === false) {
                return;
            }
        }

        closeModal();
    });

    function normalizeSubItem(subItem) {
        return {
            text: String(subItem && subItem.text ? subItem.text : '').trim(),
            default_points: Number(subItem && subItem.default_points ? subItem.default_points : 0),
        };
    }

    function normalizeItem(item) {
        return {
            text: String(item && item.text ? item.text : '').trim(),
            default_points: Number(item && item.default_points ? item.default_points : 0),
            subtitle: String(item && item.subtitle ? item.subtitle : '').trim(),
            sub_items: Array.isArray(item && item.sub_items) ? item.sub_items.map(normalizeSubItem).filter((sub) => sub.text !== '') : [],
        };
    }

    function ensureParts() {
        if (!state.parts.length) {
            state.parts.push({ key: 'checklist_tools', label: 'Checklist Tools' });
        }
    }

    function renderParts() {
        partsContainer.innerHTML = '';

        state.parts.forEach((part, index) => {
            const chip = document.createElement('div');
            chip.className = 'part-chip';
            chip.innerHTML = `
                <strong>${esc(part.label)}</strong>
            `;
            partsContainer.appendChild(chip);
        });
    }

    function partOptions(selectedKey) {
        return state.parts.map((part) => {
            const selected = part.key === selectedKey ? 'selected' : '';
            return `<option value="${esc(part.key)}" ${selected}>${esc(part.label)}</option>`;
        }).join('');
    }

    function renderSections() {
        sectionsContainer.innerHTML = '';

        state.sections.forEach((section, sectionIndex) => {
            const sectionEl = document.createElement('div');
            sectionEl.className = 'question-section p-3';
            sectionEl.dataset.sectionIndex = String(sectionIndex);

            sectionEl.innerHTML = `
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="badge text-bg-light">Section ${sectionIndex + 1}</span>
                    <select class="form-select form-select-sm section-division" style="max-width: 230px;">${partOptions(section.division)}</select>
                    <input type="text" class="form-control form-control-sm flex-grow-1 section-title" placeholder="Section title" value="${esc(section.title || '')}">
                    <button type="button" class="btn btn-sm btn-outline-secondary move-up-btn" title="Move up"><i class="fas fa-arrow-up"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary move-down-btn" title="Move down"><i class="fas fa-arrow-down"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-section-btn" title="Remove section"><i class="fas fa-trash"></i></button>
                </div>
                <div class="items-container d-flex flex-column gap-2"></div>
                <div class="mt-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-outline-primary add-item-btn"><i class="fas fa-plus me-1"></i> Add Question</button>
                </div>
            `;

            const itemsContainer = sectionEl.querySelector('.items-container');

            section.items.forEach((item, itemIndex) => {
                const itemEl = document.createElement('div');
                itemEl.className = 'question-item';
                itemEl.dataset.itemIndex = String(itemIndex);
                itemEl.innerHTML = `
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="text-muted item-number" style="min-width: 2rem;">${itemIndex + 1}.</span>
                        <input type="text" class="form-control form-control-sm question-text" placeholder="Question item" value="${esc(item.text || '')}" style="min-width: 240px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary move-item-up-btn" title="Move question up"><i class="fas fa-arrow-up"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary move-item-down-btn" title="Move question down"><i class="fas fa-arrow-down"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" title="Remove question"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="mb-2 ps-4">
                        <input type="text" class="form-control form-control-sm question-subtitle" placeholder="Optional sub-title (example: Academic or Instructional Rooms)" value="${esc(item.subtitle || '')}">
                    </div>
                    <div class="sub-items-container d-flex flex-column gap-2 ps-4"></div>
                    <div class="d-flex justify-content-end mt-2">
                        <button type="button" class="btn btn-sm btn-outline-primary add-sub-item-btn"><i class="fas fa-plus me-1"></i> Add Sub-content</button>
                    </div>
                `;

                const subItemsContainer = itemEl.querySelector('.sub-items-container');
                item.sub_items.forEach((subItem, subIndex) => {
                    const subEl = document.createElement('div');
                    subEl.className = 'question-sub-item d-flex flex-wrap align-items-center gap-2';
                    subEl.dataset.subIndex = String(subIndex);
                    subEl.innerHTML = `
                        <span class="text-muted">${String.fromCharCode(65 + subIndex)}.</span>
                        <input type="text" class="form-control form-control-sm sub-item-text" placeholder="Sub-content" value="${esc(subItem.text || '')}" style="min-width: 220px;">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-sub-item-btn" title="Remove sub-content"><i class="fas fa-times"></i></button>
                    `;
                    subItemsContainer.appendChild(subEl);
                });

                itemsContainer.appendChild(itemEl);
            });

            sectionsContainer.appendChild(sectionEl);
        });
    }

    function syncStateFromDom() {
        const sectionNodes = Array.from(sectionsContainer.querySelectorAll('.question-section'));

        state.sections = sectionNodes.map((sectionNode, sIndex) => {
            const division = sectionNode.querySelector('.section-division').value;
            const title = sectionNode.querySelector('.section-title').value.trim();

            const itemNodes = Array.from(sectionNode.querySelectorAll('.question-item'));
            const items = itemNodes.map((itemNode) => {
                const text = itemNode.querySelector('.question-text').value.trim();
                const subtitle = itemNode.querySelector('.question-subtitle').value.trim();

                const subItems = Array.from(itemNode.querySelectorAll('.question-sub-item')).map((subNode) => ({
                    text: subNode.querySelector('.sub-item-text').value.trim(),
                })).filter((sub) => sub.text !== '');

                return {
                    text,
                    default_points: 0,
                    subtitle,
                    sub_items: subItems,
                };
            }).filter((item) => item.text !== '');

            const part = state.parts.find((entry) => entry.key === division) || state.parts[0];
            return {
                key: `section_${sIndex + 1}`,
                title,
                division: division,
                division_label: part ? part.label : toLabel(division),
                items,
            };
        }).filter((section) => section.title !== '' && section.items.length > 0);
    }

    function addSection() {
        ensureParts();
        state.sections.push({
            key: `section_${state.sections.length + 1}`,
            title: 'New Section',
            division: state.parts[0].key,
            division_label: state.parts[0].label,
            items: [
                {
                    text: 'New Question',
                    default_points: 1,
                    subtitle: '',
                    sub_items: [],
                },
            ],
        });

        renderSections();
    }

    function addPart() {
        openModal({
            title: 'Add Assessment Part',
            message: 'Enter the new assessment part name.',
            withInput: true,
            defaultValue: '',
            hint: 'Example: Pillar 2',
            confirmText: 'Add Part',
            confirmClass: 'btn-primary',
            onConfirm: (inputValue) => {
                const label = String(inputValue || '').trim();
                if (!label) {
                    return false;
                }

                let key = slugify(label);
                let counter = 2;
                while (state.parts.some((entry) => entry.key === key)) {
                    key = `${slugify(label)}_${counter}`;
                    counter += 1;
                }

                state.parts.push({ key, label });
                renderParts();
                renderSections();
                return true;
            },
        });
    }

    function confirmAction(title, message, onConfirm) {
        openModal({
            title,
            message,
            confirmText: 'Continue',
            confirmClass: 'btn-danger',
            onConfirm: () => {
                onConfirm();
                return true;
            },
        });
    }

    addPartBtn.addEventListener('click', addPart);
    addSectionBtn.addEventListener('click', addSection);

    sectionsContainer.addEventListener('click', (event) => {
        const button = event.target.closest('button');
        if (!button) {
            return;
        }

        const sectionEl = button.closest('.question-section');
        if (!sectionEl) {
            return;
        }

        const sectionIndex = Number(sectionEl.dataset.sectionIndex);
        const section = state.sections[sectionIndex];
        if (!section) {
            return;
        }

        if (button.classList.contains('move-up-btn')) {
            if (sectionIndex > 0) {
                const current = state.sections[sectionIndex];
                state.sections[sectionIndex] = state.sections[sectionIndex - 1];
                state.sections[sectionIndex - 1] = current;
                renderSections();
            }
            return;
        }

        if (button.classList.contains('move-down-btn')) {
            if (sectionIndex < state.sections.length - 1) {
                const current = state.sections[sectionIndex];
                state.sections[sectionIndex] = state.sections[sectionIndex + 1];
                state.sections[sectionIndex + 1] = current;
                renderSections();
            }
            return;
        }

        if (button.classList.contains('remove-section-btn')) {
            confirmAction(
                'Remove Section',
                'Remove this section and all of its questionnaires?',
                () => {
                    state.sections.splice(sectionIndex, 1);
                    if (!state.sections.length) {
                        addSection();
                    } else {
                        renderSections();
                    }
                }
            );
            return;
        }

        if (button.classList.contains('add-item-btn')) {
            section.items.push({
                text: 'New Question',
                default_points: 1,
                subtitle: '',
                sub_items: [],
            });
            renderSections();
            return;
        }

        const itemEl = button.closest('.question-item');
        if (!itemEl) {
            return;
        }

        const itemIndex = Number(itemEl.dataset.itemIndex);
        const item = section.items[itemIndex];
        if (!item) {
            return;
        }

        if (button.classList.contains('move-item-up-btn')) {
            if (itemIndex > 0) {
                const current = section.items[itemIndex];
                section.items[itemIndex] = section.items[itemIndex - 1];
                section.items[itemIndex - 1] = current;
                renderSections();
            }
            return;
        }

        if (button.classList.contains('move-item-down-btn')) {
            if (itemIndex < section.items.length - 1) {
                const current = section.items[itemIndex];
                section.items[itemIndex] = section.items[itemIndex + 1];
                section.items[itemIndex + 1] = current;
                renderSections();
            }
            return;
        }

        if (button.classList.contains('remove-item-btn')) {
            confirmAction(
                'Remove Questionnaire Item',
                'Remove this questionnaire item?',
                () => {
                    section.items.splice(itemIndex, 1);
                    if (!section.items.length) {
                        section.items.push({
                            text: 'New Question',
                            default_points: 1,
                            subtitle: '',
                            sub_items: [],
                        });
                    }
                    renderSections();
                }
            );
            return;
        }

        if (button.classList.contains('add-sub-item-btn')) {
            item.sub_items = Array.isArray(item.sub_items) ? item.sub_items : [];
            item.sub_items.push({
                text: `Sub-content ${item.sub_items.length + 1}`,
                default_points: 1,
            });
            renderSections();
            return;
        }

        if (button.classList.contains('remove-sub-item-btn')) {
            const subItemEl = button.closest('.question-sub-item');
            if (!subItemEl) {
                return;
            }

            const subIndex = Number(subItemEl.dataset.subIndex);
            confirmAction(
                'Remove Sub-content',
                'Remove this questionnaire sub-content?',
                () => {
                    item.sub_items.splice(subIndex, 1);
                    renderSections();
                }
            );
        }
    });

    sectionsContainer.addEventListener('input', () => {
        syncStateFromDom();
    });

    sectionsContainer.addEventListener('change', () => {
        syncStateFromDom();
    });

    form.addEventListener('submit', (event) => {
        syncStateFromDom();

        if (!state.sections.length) {
            event.preventDefault();
            openModal({
                title: 'Incomplete Questionnaire',
                message: 'Add at least one section with one questionnaire item before saving.',
                confirmText: 'OK',
                confirmClass: 'btn-primary',
            });
            return;
        }

        sectionsJson.value = JSON.stringify(state.sections);
    });

    state.parts = Object.entries(initialPartsMap || {}).map(([key, label]) => ({
        key: slugify(key),
        label: String(label || '').trim() || toLabel(key),
    }));

    state.sections = Array.isArray(initialSections)
        ? initialSections.map((section, index) => ({
            key: section.key || `section_${index + 1}`,
            title: String(section.title || '').trim(),
            division: slugify(section.division || 'checklist_tools'),
            division_label: String(section.division_label || '').trim(),
            items: Array.isArray(section.items) ? section.items.map(normalizeItem).filter((item) => item.text !== '') : [],
        }))
        : [];

    state.sections.forEach((section) => {
        if (!state.parts.some((part) => part.key === section.division)) {
            state.parts.push({
                key: section.division,
                label: section.division_label || toLabel(section.division),
            });
        }

        section.items = section.items.length ? section.items : [{
            text: 'New Question',
            default_points: 1,
            subtitle: '',
            sub_items: [],
        }];
    });

    ensureParts();

    if (!state.sections.length) {
        addSection();
    } else {
        renderParts();
        renderSections();
    }
})();
</script>
@endpush
