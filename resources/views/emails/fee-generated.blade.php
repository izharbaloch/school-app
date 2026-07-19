@extends('emails.layout')

@section('header_sub', 'Fee Notice')

@section('body')
@php
    $student = $studentFee->student;
@endphp

<p style="margin:0 0 16px;color:#374151;font-size:15px;">
    Dear Parent / Guardian,
</p>

<p style="color:#374151;font-size:14px;margin:0 0 20px;">
    A fee has been generated for <strong>{{ $student->full_name }}</strong>.
    Please find the details below and make the payment before the due date.
</p>

{{-- Fee details card --}}
<table width="100%" cellpadding="0" cellspacing="0" border="0"
       style="border:1px solid #e5e7eb;border-radius:6px;margin-bottom:20px;">
    <tr>
        <td style="padding:16px 20px;border-bottom:1px solid #e5e7eb;background:#f9fafb;">
            <p style="margin:0;font-size:13px;font-weight:600;color:#374151;text-transform:uppercase;letter-spacing:0.5px;">
                Fee Details
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:16px 20px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;width:45%;">Student Name</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;font-weight:600;">{{ $student->full_name }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Class</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;">{{ $student->studentClass?->name }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Fee Type</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;">{{ $studentFee->feeType?->name }}</td>
                </tr>
                @if($studentFee->month)
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Month / Year</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;">{{ $studentFee->month_name }} {{ $studentFee->year }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Fee Amount</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;">PKR {{ number_format($studentFee->amount, 2) }}</td>
                </tr>
                @if($studentFee->discount > 0)
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Discount</td>
                    <td style="padding:5px 0;font-size:13px;color:#10b981;">– PKR {{ number_format($studentFee->discount, 2) }}</td>
                </tr>
                @endif
                @if($studentFee->fine > 0)
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Fine</td>
                    <td style="padding:5px 0;font-size:13px;color:#ef4444;">+ PKR {{ number_format($studentFee->fine, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:8px 0 5px;font-size:14px;color:#374151;font-weight:700;border-top:1px solid #e5e7eb;">Payable Amount</td>
                    <td style="padding:8px 0 5px;font-size:14px;color:#1a56db;font-weight:700;border-top:1px solid #e5e7eb;">PKR {{ number_format($studentFee->payable_amount, 2) }}</td>
                </tr>
                @if($studentFee->due_date)
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Due Date</td>
                    <td style="padding:5px 0;font-size:13px;color:#ef4444;font-weight:600;">{{ $studentFee->due_date->format('d M Y') }}</td>
                </tr>
                @endif
            </table>
        </td>
    </tr>
</table>

<p style="color:#6b7280;font-size:13px;margin:0;">
    Please visit the school office or the fee portal to complete the payment.
    Late payments may incur additional fines.
</p>
@endsection
