@extends('layouts.app')

@section('title', 'Student Fees')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Student Fees</h1>
    </div>

    <div class="section-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Student Fee List</h4>
                <div>
                    <a href="{{ route('student-fees.bulk-create') }}" class="btn btn-success mr-2">Generate Class Fee</a>
                    <a href="{{ route('student-fees.create') }}" class="btn btn-primary">Assign Single Fee</a>
                </div>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('student-fees.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Student</label>
                            <select name="student_id" class="form-control">
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->full_name ?: ($student->name ?? '-') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Fee Type</label>
                            <select name="fee_type_id" class="form-control">
                                <option value="">Select Fee Type</option>
                                @foreach($feeTypes as $feeType)
                                    <option value="{{ $feeType->id }}" {{ request('fee_type_id') == $feeType->id ? 'selected' : '' }}>
                                        {{ $feeType->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Month</label>
                            <input type="number" name="month" class="form-control" min="1" max="12" value="{{ request('month') }}">
                        </div>

                        <div class="col-md-2">
                            <label>Year</label>
                            <input type="number" name="year" class="form-control" min="2000" max="2100" value="{{ request('year') }}">
                        </div>

                        <div class="col-md-2">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>

                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-info mr-1">Filter</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-md">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Slip No</th>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Fee Type</th>
                                <th>Month/Year</th>
                                <th>Payable</th>
                                <th>Paid</th>
                                <th>Remaining</th>
                                <th>Status</th>
                                <th width="280">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentFees as $fee)
                                <tr>
                                    <td>{{ $loop->iteration + ($studentFees->currentPage() - 1) * $studentFees->perPage() }}</td>
                                    <td>{{ $fee->slip_no }}</td>
                                    <td>{{ $fee->student->full_name ?: ($fee->student->name ?? '-') }}</td>
                                    <td>{{ $fee->student->studentClass->name ?? '-' }}</td>
                                    <td>{{ $fee->student->section->name ?? '-' }}</td>
                                    <td>{{ $fee->feeType->name ?? '-' }}</td>
                                    <td>{{ $fee->month_name }}/{{ $fee->year ?? '-' }}</td>
                                    <td>{{ number_format($fee->payable_amount, 2) }}</td>
                                    <td>{{ number_format($fee->paid_amount, 2) }}</td>
                                    <td>{{ number_format($fee->remaining_amount, 2) }}</td>
                                    <td>{{ ucfirst($fee->status) }}</td>
                                    <td>
                                        <a href="{{ route('student-fees.show', $fee->id) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('student-fees.payment.create', $fee->id) }}" class="btn btn-sm btn-success">Collect Fee</a>
                                        <a href="{{ route('student-fees.print-slip', $fee->id) }}" target="_blank" class="btn btn-sm btn-secondary">Print Slip</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center">No fee records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $studentFees->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
