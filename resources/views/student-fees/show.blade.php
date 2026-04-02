@extends('layouts.app')

@section('title', 'Student Fee Details')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Student Fee Details</h1>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <p><strong>Student:</strong> {{ $studentFee->student->full_name ?? $studentFee->student->name }}</p>
                    <p><strong>Class:</strong> {{ $studentFee->student->studentClass->name ?? '-' }}</p>
                    <p><strong>Section:</strong> {{ $studentFee->student->section->name ?? '-' }}</p>
                    <p><strong>Fee Type:</strong> {{ $studentFee->feeType->name ?? '-' }}</p>
                    <p><strong>Month/Year:</strong> {{ $studentFee->month ?? '-' }}/{{ $studentFee->year ?? '-' }}</p>
                    <p><strong>Amount:</strong> {{ number_format($studentFee->amount, 2) }}</p>
                    <p><strong>Discount:</strong> {{ number_format($studentFee->discount, 2) }}</p>
                    <p><strong>Fine:</strong> {{ number_format($studentFee->fine, 2) }}</p>
                    <p><strong>Payable:</strong> {{ number_format($studentFee->payable_amount, 2) }}</p>
                    <p><strong>Paid:</strong> {{ number_format($studentFee->paid_amount, 2) }}</p>
                    <p><strong>Remaining:</strong> {{ number_format($studentFee->remaining_amount, 2) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($studentFee->status) }}</p>

                    <a href="{{ route('student-fees.payment.create', $studentFee->id) }}"
                        class="btn btn-success mb-3">Collect Fee</a>

                    <h5>Payment History</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Received By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentFee->payments as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payment->payment_date?->format('d-m-Y') }}</td>
                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_method ?? '-' }}</td>
                                    <td>{{ $payment->reference_no ?? '-' }}</td>
                                    <td>{{ $payment->receivedBy->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No payments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <a href="{{ route('student-fees.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </section>
@endsection
