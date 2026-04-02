@extends('layouts.app')

@section('title', 'Student Fees')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Student Fees</h1>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Student Fee List</h4>
                    <a href="{{ route('student-fees.create') }}" class="btn btn-primary">Assign Fee</a>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('student-fees.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <select name="student_id" class="form-control">
                                    <option value="">Select Student</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}"
                                            {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->full_name ?? $student->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid
                                    </option>
                                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial
                                    </option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <button class="btn btn-info">Filter</button>
                                <a href="{{ route('student-fees.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Fee Type</th>
                                <th>Month/Year</th>
                                <th>Payable</th>
                                <th>Paid</th>
                                <th>Remaining</th>
                                <th>Status</th>
                                <th width="220">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentFees as $fee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $fee->student->full_name ?? $fee->student->name }}</td>
                                    <td>{{ $fee->feeType->name ?? '-' }}</td>
                                    <td>
                                        {{ $fee->month ? $fee->month : '-' }}/{{ $fee->year ? $fee->year : '-' }}
                                    </td>
                                    <td>{{ number_format($fee->payable_amount, 2) }}</td>
                                    <td>{{ number_format($fee->paid_amount, 2) }}</td>
                                    <td>{{ number_format($fee->remaining_amount, 2) }}</td>
                                    <td>{{ ucfirst($fee->status) }}</td>
                                    <td>
                                        <a href="{{ route('student-fees.show', $fee->id) }}"
                                            class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('student-fees.payment.create', $fee->id) }}"
                                            class="btn btn-sm btn-success">Collect Fee</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No student fee records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $studentFees->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
