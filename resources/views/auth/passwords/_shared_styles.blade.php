{{-- Shared inline styles for all 3 password-reset steps --}}
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --navy:   #0D1B36;
        --orange: #E05C2E;
        --text:   #0D1B36;
        --muted:  #6B7280;
        --border: #E2E8F0;
        --bg:     #F0F2F5;
        --white:  #FFFFFF;
        --radius: 12px;
        --container-w: 480px;
    }

    html, body {
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
        background: var(--bg);
        color: var(--text);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .page-container {
        width: 100%;
        max-width: var(--container-w);
        animation: fadeUp 0.6s ease both;
    }

    .brand-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .brand-logo {
        width: 64px;
        height: 64px;
        object-fit: contain;
        margin-bottom: 16px;
    }

    .brand-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--navy);
    }

    /* ===== STEP INDICATOR ===== */
    .steps {
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 32px;
        padding: 0 16px;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        position: relative;
        z-index: 2;
    }

    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        border: 2px solid var(--border);
        background: var(--bg);
        color: var(--muted);
        transition: all 0.3s ease;
    }

    .step-item.active .step-circle {
        border-color: var(--navy);
        background: var(--navy);
        color: #fff;
    }

    .step-item.done .step-circle {
        border-color: #10B981;
        background: #10B981;
        color: #fff;
    }

    .step-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        text-align: center;
    }

    .step-item.active .step-label { color: var(--navy); }
    .step-item.done .step-label   { color: #10B981; }

    .step-connector-wrapper {
        flex: 1;
        position: relative;
        height: 32px;
        display: flex;
        align-items: center;
        margin: 0 -8px;
        z-index: 1;
        margin-bottom: 22px; /* align with circles */
    }

    .step-connector {
        width: 100%;
        height: 2px;
        background: var(--border);
        transition: background 0.4s ease;
    }

    .step-connector.done { background: #10B981; }

    /* ===== FORM ELEMENTS ===== */
    .card {
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: var(--radius);
        padding: 40px 32px;
        box-shadow: 0 10px 25px rgba(13,27,54,0.05);
    }

    .form-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 8px;
        text-align: center;
    }

    .form-sub {
        font-size: 14px;
        color: var(--muted);
        margin-bottom: 32px;
        line-height: 1.55;
        text-align: center;
    }

    .field { margin-bottom: 20px; }

    .field-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 8px;
    }

    .field-label svg { color: var(--muted); flex-shrink: 0; }

    .input-wrap { position: relative; }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 15px;
        font-family: inherit;
        color: var(--text);
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }

    .form-input::placeholder { color: #A0AEC0; }

    .form-input:focus {
        border-color: var(--navy);
        box-shadow: 0 0 0 3px rgba(13,27,54,0.09);
    }

    .form-input.is-invalid { border-color: #EF4444; }
    .form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.12); }
    .form-input.has-toggle { padding-right: 44px; }

    .toggle-pw {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: var(--muted);
        padding: 4px;
        display: flex;
        transition: color 0.2s;
    }
    .toggle-pw:hover { color: var(--navy); }

    .feedback {
        font-size: 13px;
        color: #EF4444;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Verified email pill */
    .verified-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: #ECFDF5;
        border: 1px solid #6EE7B7;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
        color: #065F46;
        font-weight: 500;
    }

    .verified-pill svg { flex-shrink: 0; color: #10B981; }

    /* OTP input */
    .otp-input {
        text-align: center;
        letter-spacing: 0.5em;
        font-size: 1.8rem;
        font-weight: 700;
        padding: 16px;
    }

    /* Alerts */
    .alert-success-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #ECFDF5;
        border: 1px solid #6EE7B7;
        color: #065F46;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        margin-bottom: 24px;
        line-height: 1.5;
    }

    .alert-success-box svg { flex-shrink: 0; margin-top: 2px; }

    .alert-error-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #FEF2F2;
        border: 1px solid #FCA5A5;
        color: #991B1B;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        margin-bottom: 24px;
        line-height: 1.5;
    }

    .alert-error-box svg { flex-shrink: 0; margin-top: 2px; }

    /* Password strength */
    .pw-strength-bar {
        height: 4px;
        background: var(--border);
        border-radius: 9999px;
        margin-top: 8px;
        overflow: hidden;
    }

    .pw-strength-fill {
        height: 100%;
        border-radius: 9999px;
        width: 0%;
        transition: width 0.35s ease, background 0.35s ease;
    }

    .pw-strength-label {
        font-size: 12px;
        color: var(--muted);
        margin-top: 6px;
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: var(--navy);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: background 0.2s, transform 0.15s, box-shadow 0.15s;
        box-shadow: 0 4px 12px rgba(13,27,54,0.15);
        margin-top: 24px;
    }

    .btn-submit:hover {
        background: #1a2e55;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(13,27,54,0.22);
    }

    .btn-submit:active { transform: translateY(0); }

    .btn-ghost-sm {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        font-weight: 500;
        color: var(--muted);
        background: none;
        border: none;
        cursor: pointer;
        text-decoration: none;
        padding: 0;
        transition: color 0.2s;
        font-family: inherit;
    }

    .btn-ghost-sm:hover { color: var(--navy); }

    .form-footer {
        text-align: center;
        margin-top: 24px;
        font-size: 14px;
        color: var(--muted);
    }

    .form-footer a {
        color: var(--navy);
        font-weight: 700;
        text-decoration: none;
    }

    .form-footer a:hover { color: var(--orange); text-decoration: underline; }

    .resend-row {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 480px) {
        body { padding: 16px; }
        .card { padding: 32px 20px; }
        .form-title { font-size: 1.25rem; }
        .steps { margin-bottom: 24px; }
    }
</style>
