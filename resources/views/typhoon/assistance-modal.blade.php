<style>
    .assistance-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 1050;
        display: none;
        place-items: center;
        padding: 20px;
        background: rgba(15, 23, 42, 0.58);
    }

    .assistance-modal-backdrop.is-open {
        display: grid;
    }

    .assistance-modal-backdrop.secondary-modal {
        display: none;
        z-index: 1060;
    }

    .assistance-modal-backdrop.secondary-modal.is-open {
        display: grid;
    }

    .assistance-modal {
        width: min(520px, 100%);
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.28);
        animation: modal-enter 180ms ease-out;
    }

    @keyframes modal-enter {
        from { opacity: 0; transform: translateY(10px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .assistance-modal .modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 24px 26px 20px;
        color: #ffffff;
        background: #0f172a;
        border-bottom: 4px solid #ef4444;
    }

    .assistance-modal .modal-title-wrap {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .assistance-modal .modal-icon {
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        border-radius: 9px;
        color: #00d2ff;
        background: rgba(0, 210, 255, 0.12);
        font-size: 18px;
    }

    .assistance-modal .modal-header h1 {
        margin: 0 0 4px;
        font-family: 'Rajdhani', sans-serif;
        font-size: 22px;
        line-height: 1.1;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        color: #ffffff;
    }

    .assistance-modal .modal-header p {
        margin: 0;
        color: #94a3b8;
        font-size: 12px;
    }

    .assistance-modal .close-button {
        display: grid;
        place-items: center;
        width: 32px;
        height: 32px;
        padding: 0;
        border: 0;
        border-radius: 6px;
        color: #cbd5e1;
        background: transparent;
        font-size: 18px;
        cursor: pointer;
    }

    .assistance-modal .close-button:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.1);
    }

    .assistance-modal .modal-body {
        padding: 24px 26px 26px;
    }

    .assistance-modal .field-label {
        display: block;
        margin-bottom: 8px;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .assistance-modal .selection-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
    }

    .assistance-modal .selection-copy strong {
        display: block;
        margin-bottom: 4px;
        color: #1e293b;
        font-size: 13px;
    }

    .assistance-modal .selection-copy span {
        color: #64748b;
        font-size: 11px;
        line-height: 1.5;
    }

    .assistance-modal .selection-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .assistance-modal .select-all-button {
        display: inline-flex;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 38px;
        padding: 9px 13px;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        color: #1d4ed8;
        background: #eff6ff;
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
    }

    .assistance-modal .select-all-button:hover {
        border-color: #93c5fd;
        background: #dbeafe;
    }

    .assistance-modal .select-all-button.selected {
        border-color: #059669;
        color: #047857;
        background: #ecfdf5;
    }

    .assistance-modal .unselect-all-button {
        display: inline-flex;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 38px;
        padding: 9px 13px;
        border: 1px solid #fecaca;
        border-radius: 6px;
        color: #dc2626;
        background: #fef2f2;
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
    }

    .assistance-modal .unselect-all-button:hover {
        border-color: #fca5a5;
        background: #fee2e2;
    }

    .assistance-modal .description-group {
        margin-top: 20px;
    }

    .assistance-modal .description-text-input {
        display: block;
        width: 100%;
        min-height: 44px;
        padding: 12px 13px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        outline: none;
        color: #1e293b;
        background: #f8fafc;
        font: 13px 'Inter', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    .assistance-modal .description-text-input::placeholder {
        color: #94a3b8;
    }

    .assistance-modal .description-text-input:focus {
        border-color: #3b82f6;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .assistance-modal .field-hint {
        display: block;
        margin-top: 6px;
        color: #64748b;
        font-size: 11px;
    }

    .assistance-modal .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 26px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .assistance-modal .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 40px;
        padding: 10px 17px;
        border: 0;
        border-radius: 6px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s, transform 0.2s;
    }

    .assistance-modal .button:active {
        transform: translateY(1px);
    }

    .assistance-modal .cancel-button {
        color: #475569;
        background: #e2e8f0;
    }

    .assistance-modal .cancel-button:hover {
        background: #cbd5e1;
    }

    .assistance-modal .give-button {
        color: #ffffff;
        background: #0284c7;
    }

    .assistance-modal .give-button:hover {
        background: #0369a1;
    }

    .assistance-modal .submit-button {
        color: #ffffff;
        background: #2563eb;
    }

    .assistance-modal .submit-button:hover {
        background: #1d4ed8;
    }

    .preview-message {
        position: fixed;
        right: 20px;
        bottom: 20px;
        max-width: calc(100% - 40px);
        padding: 12px 16px;
        border-radius: 7px;
        color: #ffffff;
        background: #059669;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.18);
        font-size: 13px;
        opacity: 0;
        transform: translateY(8px);
        pointer-events: none;
        transition: 0.2s ease;
        z-index: 1100;
    }

    .preview-message.show {
        opacity: 1;
        transform: translateY(0);
    }

    .preview-message.preview-error {
        background: #ef4444;
    }

    .preview-message.preview-warning {
        background: #f59e0b;
    }

    .preview-message.preview-success {
        background: #059669;
    }

    @media (max-width: 520px) {
        .assistance-modal .modal-header,
        .assistance-modal .modal-body {
            padding-right: 20px;
            padding-left: 20px;
        }
        .assistance-modal .modal-footer {
            padding-right: 20px;
            padding-left: 20px;
        }
        .assistance-modal .selection-card {
            align-items: flex-start;
            flex-direction: column;
        }
        .assistance-modal .selection-actions {
            width: 100%;
        }
        .assistance-modal .select-all-button,
        .assistance-modal .unselect-all-button {
            flex: 1 1 auto;
        }
    }
</style>

<!-- Select All Families / Provide Assistance Modal -->
<div class="assistance-modal-backdrop" id="assistanceModalBackdrop" role="dialog" aria-modal="true" aria-labelledby="assistanceModalTitle" aria-hidden="true">
    <section class="assistance-modal">
        <header class="modal-header">
            <div class="modal-title-wrap">
                <div class="modal-icon"><i class="fas fa-hand-holding-heart" aria-hidden="true"></i></div>
                <div>
                    <h1 id="assistanceModalTitle">Provide Assistance</h1>
                    <p>Record assistance for affected families</p>
                </div>
            </div>
            <button type="button" class="close-button" aria-label="Close modal" data-close-modal>
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </header>

        <form id="assistanceForm">
            <div class="modal-body">
                <label class="field-label">Families to receive assistance</label>
                <div class="selection-card">
                    <div class="selection-copy">
                        <strong>Family selection</strong>
                        <span id="selectionStatus">Choose all families for this assistance distribution.</span>
                    </div>
                    <div class="selection-actions">
                        <button type="button" class="select-all-button" id="selectAllFamilies">
                            <i class="fas fa-check-double" aria-hidden="true"></i>
                            <span>Select All Families</span>
                        </button>
                        <button type="button" class="unselect-all-button" id="unselectAllFamilies">
                            <i class="fas fa-times" aria-hidden="true"></i>
                            <span>Unselect All</span>
                        </button>
                    </div>
                </div>

                <div class="description-group">
                    <label class="field-label" for="allAssistanceDescription">Assistance Description</label>
                    <input class="description-text-input" type="text" id="allAssistanceDescription" name="assistance_description" placeholder="Describe the assistance to be given" required>
                    <span class="field-hint">This description will be applied to all selected families upon submission.</span>
                </div>
            </div>

            <footer class="modal-footer">
                <button type="button" class="button cancel-button" data-close-modal>Cancel</button>
                <button type="submit" class="button submit-button" id="submitAllAssistance">
                    <i class="fas fa-paper-plane" aria-hidden="true"></i> Submit
                </button>
            </footer>
        </form>
    </section>
</div>

<!-- Give to Selected Families Modal -->
<div class="assistance-modal-backdrop secondary-modal" id="giveModalBackdrop" role="dialog" aria-modal="true" aria-labelledby="giveModalTitle" aria-hidden="true">
    <section class="assistance-modal">
        <header class="modal-header">
            <div class="modal-title-wrap">
                <div class="modal-icon"><i class="fas fa-hand-holding-heart" aria-hidden="true"></i></div>
                <div>
                    <h1 id="giveModalTitle">Give to Selected Families</h1>
                    <p>Describe the assistance to be provided</p>
                </div>
            </div>
            <button type="button" class="close-button" aria-label="Close modal" data-close-give-modal>
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </header>

        <form id="giveAssistanceForm">
            <div class="modal-body">
                <label class="field-label" for="giveAssistanceDescription">Assistance Description</label>
                <input class="description-text-input" type="text" id="giveAssistanceDescription" name="assistance_description" placeholder="Describe the assistance to be given" required>
            </div>
            <footer class="modal-footer">
                <button type="button" class="button cancel-button" data-close-give-modal>Cancel</button>
                <button type="submit" class="button submit-button">
                    <i class="fas fa-paper-plane" aria-hidden="true"></i> Submit
                </button>
            </footer>
        </form>
    </section>
</div>

<div class="preview-message" id="previewMessage" role="status" aria-live="polite"></div>
