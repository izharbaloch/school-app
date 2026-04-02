@extends('layouts.app')

@section('title', 'Generate Class Fee')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Generate Class Fee</h1>
        </div>

        <div class="section-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Select Class and Load Students</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('student-fees.bulk-create') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Class</label>
                                <select name="student_class_id" class="form-control" required>
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Section</label>
                                <select name="section_id" class="form-control">
                                    <option value="">All Sections</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ $selectedSectionId == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Fee Type</label>
                                <select name="fee_type_id" class="form-control" required>
                                    <option value="">Select Fee Type</option>
                                    @foreach ($feeTypes as $feeType)
                                        <option value="{{ $feeType->id }}"
                                            {{ $selectedFeeTypeId == $feeType->id ? 'selected' : '' }}>
                                            {{ $feeType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-info">Load Students</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if ($selectedClassId && $selectedFeeTypeId)
                <div class="card">
                    <div class="card-header">
                        <h4>Generate Fee for Students</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('student-fees.bulk-store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="student_class_id" value="{{ $selectedClassId }}">
                            <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">
                            <input type="hidden" name="fee_type_id" value="{{ $selectedFeeTypeId }}">

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Month</label>
                                    <input type="number" name="month" class="form-control" min="1" max="12"
                                        value="{{ old('month', date('n')) }}">
                                </div>

                                <div class="col-md-3">
                                    <label>Year</label>
                                    <input type="number" name="year" class="form-control"
                                        value="{{ old('year', date('Y')) }}">
                                </div>

                                <div class="col-md-3">
                                    <label>Due Date</label>
                                    <input type="date" name="due_date" class="form-control"
                                        value="{{ old('due_date') }}">
                                </div>

                                <div class="col-md-3">
                                    <label>Amount</label>
                                    <input type="number" step="0.01" name="amount" class="form-control"
                                        value="{{ old('amount', $structureAmount) }}">
                                    <small class="text-muted">Agar fee structure set hai to amount auto aa jayega.</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Discount</label>
                                    <input type="number" step="0.01" name="discount" class="form-control"
                                        value="{{ old('discount', 0) }}">
                                </div>

                                <div class="col-md-3">
                                    <label>Fine</label>
                                    <input type="number" step="0.01" name="fine" class="form-control"
                                        value="{{ old('fine', 0) }}">
                                </div>

                                <div class="col-md-6">
                                    <label>Remarks</label>
                                    <input type="text" name="remarks" class="form-control" value="{{ old('remarks') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>
                                    <input type="checkbox" id="checkAll"> Select All Students
                                </label>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>#</th>
                                            <th>Roll No</th>
                                            <th>Student Name</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $student)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="selected_students[]"
                                                        value="{{ $student->id }}" class="student-checkbox">
                                                </td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $student->roll_no ?? '-' }}</td>
                                                <td>{{ $student->full_name ?: $student->name ?? '-' }}</td>
                                                <td>{{ $student->studentClass->name ?? '-' }}</td>
                                                <td>{{ $student->section->name ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No students found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($students->count())
                                <button type="submit" class="btn btn-primary">Generate Fee</button>
                            @endif

                            <a href="{{ route('student-fees.index') }}" class="btn btn-secondary">Back</a>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('.student-checkbox');

            if (checkAll) {
                checkAll.addEventListener('change', function() {
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = checkAll.checked;
                    });
                });
            }
        });
    </script>
@endsection
