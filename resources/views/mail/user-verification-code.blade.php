@component('mail::message')
# User verification

Department of Education (DepEd) — **Disaster Risk Reduction and Management (DRRM) Compliance System**

We received a request to reset the password for **{{ $recipientEmail }}**.

@component('mail::panel')
<div style="text-align:center;">
    <div style="font-size:12px; color:#6b7280; margin-bottom:8px;">Your 6-digit verification code</div>
    <div style="font-size:28px; font-weight:800; letter-spacing:6px; color:#111827;">{{ $code }}</div>
    <div style="font-size:12px; color:#6b7280; margin-top:10px;">This code expires in 60 minutes.</div>
</div>
@endcomponent

@component('mail::button', ['url' => $verifyUrl, 'color' => 'primary'])
Verify code
@endcomponent

If you did not request a password reset, you can safely ignore this email.

Thanks,  
**DRRM Compliance System (DepEd)**
@endcomponent
