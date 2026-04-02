@extends('layouts.app')

@section('title', 'Assign Single Student Fee')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Assign Single Student Fee</h1>
        </div>

        <div class="section-body">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('student-fees.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Student</label>
                            <select name="student_id" class="form-control" required>
                                <option value="">Select Student</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->full_name ?: $student->name ?? '-' }}
                                        - {{ $student->studentClass->name ?? '-' }}
                                        - {{ $student->section->name ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Fee Type</label>
                            <select name="fee_type_id" class="form-control" required>
                                <option value="">Select Fee Type</option>
                                @foreach ($feeTypes as $feeType)
                                    <option value="{{ $feeType->id }}">{{ $feeType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label>Month</label>
                                <input type="number" name="month" class="form-control" min="1" max="12">
                            </div>

                            <div class="col-md-3">
                                <label>Year</label>
                                <input type="number" name="year" class="form-control" value="{{ date('Y') }}">
                            </div>

                            <div class="col-md-3">
                                <label>Amount</label>
                                <input type="number" step="0.01" name="amount" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label>Due Date</label>
                                <input type="date" name="due_date" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Discount</label>
                                <input type="number" step="0.01" name="discount" value="0" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label>Fine</label>
                                <input type="number" step="0.01" name="fine" value="0" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label>Remarks</label>
                                <input type="text" name="remarks" class="form-control">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save Fee</button>
                        <a href="{{ route('student-fees.index') }}" class="btn btn-secondary mt-3">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
