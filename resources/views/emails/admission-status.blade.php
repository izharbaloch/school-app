@extends('emails.layout')

@section('header_sub', 'Admission Application Update')

@section('body')
<p style="margin:0 0 16px;color:#374151;font-size:15px;">
    Dear <strong>{{ $admission->father_name }}</strong>,
</p>

@if($admission->status === \App\Models\Admission::STATUS_ACCEPTED)
    <table width="100%" cellpadding="0" cellspacing="0" border="0"
           style="background:#ecfdf5;border-left:4px solid #10b981;border-radius:4px;margin-bottom:20px;">
        <tr>
            <td style="padding:16px 20px;">
                <p style="margin:0;color:#065f46;font-size:15px;font-weight:600;">
                    Congratulations! Your application has been accepted.
                </p>
            </td>
        </tr>
    </table>
    <p style="color:#374151;font-size:14px;margin:0 0 16px;">
        We are pleased to inform you that the admission application for
        <strong>{{ $admission->full_name }}</strong> has been <strong>accepted</strong>.
        The next step is enrollment — please visit the school office to complete the enrollment process.
    </p>

@elseif($admission->status === \App\Models\Admission::STATUS_REJECTED)
    <table width="100%" cellpadding="0" cellspacing="0" border="0"
           style="background:#fef2f2;border-left:4px solid #ef4444;border-radius:4px;margin-bottom:20px;">
        <tr>
            <td style="padding:16px 20px;">
                <p style="margin:0;color:#991b1b;font-size:15px;font-weight:600;">
                    Application Not Accepted
                </p>
            </td>
        </tr>
    </table>
    <p style="color:#374151;font-size:14px;margin:0 0 16px;">
        We regret to inform you that the admission application for
        <strong>{{ $admission->full_name }}</strong> has not been approved at this time.
    </p>
    @if($admission->rejection_reason)
    <p style="color:#374151;font-size:14px;margin:0 0 16px;">
        <strong>Reason:</strong> {{ $admission->rejection_reason }}
    </p>
    @endif
    <p style="color:#374151;font-size:14px;margin:0 0 16px;">
        You may contact the school office for further information or to re-apply in the future.
    </p>

@elseif($admission->status === \App\Models\Admission::STATUS_ENROLLED)
    <table width="100%" cellpadding="0" cellspacing="0" border="0"
           style="background:#eff6ff;border-left:4px solid #3b82f6;border-radius:4px;margin-bottom:20px;">
        <tr>
            <td style="padding:16px 20px;">
                <p style="margin:0;color:#1e40af;font-size:15px;font-weight:600;">
                    Enrollment Complete — Welcome to {{ $schoolName }}!
                </p>
            </td>
        </tr>
    </table>
    <p style="color:#374151;font-size:14px;margin:0 0 16px;">
        <strong>{{ $admission->full_name }}</strong> has been successfully enrolled.
        Below are the login credentials for the parent portal:
    </p>
    <table width="100%" cellpadding="0" cellspacing="0" border="0"
           style="background:#f3f4f6;border-radius:6px;margin-bottom:20px;">
        <tr>
            <td style="padding:16px 20px;">
                <p style="margin:0 0 8px;font-size:13px;color:#6b7280;">Parent Portal Login</p>
                <p style="margin:0 0 4px;font-size:14px;color:#111827;">
                    <strong>Email:</strong> {{ $admission->guardian_email }}
                </p>
                <p style="margin:0;font-size:14px;color:#111827;">
                    <strong>Temporary Password:</strong> changeme123!
                </p>
                <p style="margin:8px 0 0;font-size:12px;color:#ef4444;">
                    Please change your password after first login.
                </p>
            </td>
        </tr>
    </table>
@endif

{{-- Application summary --}}
<table width="100%" cellpadding="0" cellspacing="0" border="0"
       style="border:1px solid #e5e7eb;border-radius:6px;margin-bottom:20px;">
    <tr>
        <td style="padding:16px 20px;border-bottom:1px solid #e5e7eb;background:#f9fafb;">
            <p style="margin:0;font-size:13px;font-weight:600;color:#374151;text-transform:uppercase;letter-spacing:0.5px;">
                Application Details
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:16px 20px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding:4px 0;font-size:13px;color:#6b7280;width:40%;">Application No</td>
                    <td style="padding:4px 0;font-size:13px;color:#111827;font-weight:600;">{{ $admission->application_no }}</td>
                </tr>
                <tr>
                    <td style="padding:4px 0;font-size:13px;color:#6b7280;">Student Name</td>
                    <td style="padding:4px 0;font-size:13px;color:#111827;">{{ $admission->full_name }}</td>
                </tr>
                <tr>
                    <td style="padding:4px 0;font-size:13px;color:#6b7280;">Applied For Class</td>
                    <td style="padding:4px 0;font-size:13px;color:#111827;">{{ $admission->appliedClass?->name }}</td>
                </tr>
                <tr>
                    <td style="padding:4px 0;font-size:13px;color:#6b7280;">Status</td>
                    <td style="padding:4px 0;font-size:13px;color:#111827;font-weight:600;">{{ $admission->status_label }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p style="color:#6b7280;font-size:13px;margin:0;">
    For queries, please contact the school office.
</p>
@endsection
