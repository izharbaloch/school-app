@extends('emails.layout')

@section('header_sub', 'Payment Receipt')

@section('body')
@php
    $student = $studentFee->student;
@endphp

<p style="margin:0 0 16px;color:#374151;font-size:15px;">
    Dear Parent / Guardian,
</p>

<table width="100%" cellpadding="0" cellspacing="0" border="0"
       style="background:#ecfdf5;border-left:4px solid #10b981;border-radius:4px;margin-bottom:20px;">
    <tr>
        <td style="padding:16px 20px;">
            <p style="margin:0;color:#065f46;font-size:15px;font-weight:600;">
                Payment received. Thank you!
            </p>
        </td>
    </tr>
</table>

<p style="color:#374151;font-size:14px;margin:0 0 20px;">
    A payment of <strong>PKR {{ number_format($payment->amount, 2) }}</strong> has been recorded
    for <strong>{{ $student->full_name }}</strong>.
</p>

{{-- Receipt details --}}
<table width="100%" cellpadding="0" cellspacing="0" border="0"
       style="border:1px solid #e5e7eb;border-radius:6px;margin-bottom:20px;">
    <tr>
        <td style="padding:16px 20px;border-bottom:1px solid #e5e7eb;background:#f9fafb;">
            <p style="margin:0;font-size:13px;font-weight:600;color:#374151;text-transform:uppercase;letter-spacing:0.5px;">
                Payment Receipt
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
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Payment Date</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                </tr>
                @if($payment->payment_method)
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Payment Method</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;">{{ ucfirst($payment->payment_method) }}</td>
                </tr>
                @endif
                @if($payment->reference_no)
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Reference No</td>
                    <td style="padding:5px 0;font-size:13px;color:#111827;">{{ $payment->reference_no }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:8px 0 5px;font-size:14px;color:#374151;font-weight:700;border-top:1px solid #e5e7eb;">Amount Paid</td>
                    <td style="padding:8px 0 5px;font-size:14px;color:#10b981;font-weight:700;border-top:1px solid #e5e7eb;">PKR {{ number_format($payment->amount, 2) }}</td>
                </tr>
                @if($studentFee->remaining_amount > 0)
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Remaining Balance</td>
                    <td style="padding:5px 0;font-size:13px;color:#ef4444;font-weight:600;">PKR {{ number_format($studentFee->remaining_amount, 2) }}</td>
                </tr>
                @else
                <tr>
                    <td style="padding:5px 0;font-size:13px;color:#6b7280;">Status</td>
                    <td style="padding:5px 0;font-size:13px;color:#10b981;font-weight:600;">Fully Paid</td>
                </tr>
                @endif
            </table>
        </td>
    </tr>
</table>

<p style="color:#6b7280;font-size:13px;margin:0;">
    Please retain this email as your payment receipt. For any queries, contact the school accounts office.
</p>
@endsection
