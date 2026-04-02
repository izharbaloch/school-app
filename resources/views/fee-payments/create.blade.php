@extends('layouts.app')

@section('title', 'Collect Fee')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Collect Fee</h1>
        </div>

        <div class="section-body">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <p><strong>Student:</strong> {{ $studentFee->student->full_name ?? $studentFee->student->name }}</p>
                    <p><strong>Fee Type:</strong> {{ $studentFee->feeType->name ?? '-' }}</p>
                    <p><strong>Payable Amount:</strong> {{ number_format($studentFee->payable_amount, 2) }}</p>
                    <p><strong>Already Paid:</strong> {{ number_format($studentFee->paid_amount, 2) }}</p>
                    <p><strong>Remaining Amount:</strong> {{ number_format($studentFee->remaining_amount, 2) }}</p>

                    <form action="{{ route('student-fees.payment.store', $studentFee->id) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <label>Payment Date</label>
                                <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-4">
                                <label>Amount</label>
                                <input type="number" step="0.01" name="amount" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>Payment Method</label>
                                <input type="text" name="payment_method" class="form-control"
                                    placeholder="Cash / Bank / Easypaisa">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Reference No</label>
                                <input type="text" name="reference_no" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label>Remarks</label>
                                <input type="text" name="remarks" class="form-control">
                            </div>
                        </div>

                        <button class="btn btn-primary mt-3">Save Payment</button>
                        <a href="{{ route('student-fees.show', $studentFee->id) }}" class="btn btn-secondary mt-3">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
