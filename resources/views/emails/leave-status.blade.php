@extends('emails.layout')

@section('header_sub', 'Leave Application Update')

@section('body')
@php
    $isApproved = $application->status === \App\Models\LeaveApplication::STATUS_APPROVED;
    $applicantName = $application->applicant_name;
@endphp

<p style="margin:0 0 16px;color:#374151;font-size:15px;">
    Dear <strong>{{ $applicantName }}</strong>,
</p>

@if($isApproved)
<table width="100%" cellpadding="0" cellspacing="0" border="0"
       style="background:#ecfdf5;border-left:4px solid #10b981;border-radius:4px;margin-bottom:20px;">
    <tr>
        <td style="padding:16px 20px;">
            <p style="margin:0;color:#065f46;font-size:15px;font-weight:600;">
                Your leave application has been approved.
            </p>
        </td>
    </tr>
</table>
<p style="color:#374151;font-size:14px;margin:0 0 20px;">
    We are pleased to inform you that your leave request has been
    <strong>approved</strong> by the administration.
</p>
@else
<table width="100%" cellpadding="0" cellspacing="0" border="0"
       style="background:#fef2f2;border-left:4px solid #ef4444;border-radius:4px;margin-bottom:20px;">
    <tr>
        <td style="padding:16px 20px;">
            <p style="margin:0;color:#991b1b;font-size:15px;font-weight:600;">
                Your leave application has not been approved.
            </p>
        </td>
    </tr>
</table>
<p style="color:#374151;font-size:14px;margin:0 0 20px;">
    Your leave request has <strong>not been approved</strong> at this time.
    @if($application->rejection_reason)
        <br><strong>Reason:</strong> {{ $application->rejection_reason }}
    @endif
</p>
@endif

{{-- Leave details --}}
<table width="100%" cellpadding="0" cellspacing="0" border="0"
       style="border:1px solid #e5e7eb;border-radius:6px;margin-bottom:20px;">
    <tr>
        <td style="padding:16px 20px;border-bottom:1px solid #e5e7eb;background:#f9fafb;">
            <p style="margin:0;font-size:13px;font-weight:600;color:#374151;text-transform:uppercase;letter-spacing:0.5px;">
                Leave Request Details
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:16px 20px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;width:40%;">Leave Type</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;">{{ $application->leaveType?->name }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">From Date</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;">{{ $application->from_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">To Date</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;">{{ $application->to_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Total Days</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;font-weight:600;">{{ $application->total_days }} working day(s)</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Status</td>
                    <td style="padding:5px 0;font-size:13px;font-weight:600;"
                        style="color:{{ $isApproved ? '#10b981' : '#ef4444' }}">
                        {{ ucfirst($application->status) }}
                    </td>
                </tr>
                @if($application->reviewer)
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Reviewed By</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;">{{ $application->reviewer->name }}</td>
                </tr>
                @endif
            </table>
        </td>
    </tr>
</table>

<p style="color:#6b7280;font-size:13px;margin:0;">
    If you have any questions, please contact the administration office.
</p>
@endsection
