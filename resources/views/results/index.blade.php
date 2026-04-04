@extends('layouts.app')

@section('title', 'Results')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Results</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Filter Results</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('results.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Exam</label>
                                <select name="exam_id" class="form-control" required>
                                    <option value="">Select Exam</option>
                                    @foreach ($exams as $exam)
                                        <option value="{{ $exam->id }}"
                                            {{ $selectedExamId == $exam->id ? 'selected' : '' }}>
                                            {{ $exam->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

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

                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-info">Show Results</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if (count($results))
                <div class="card">
                    <div class="card-header">
                        <h4>Class Result Summary</h4>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-md">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Roll No</th>
                                    <th>Student Name</th>
                                    <th>Total Obtained</th>
                                    <th>Total Marks</th>
                                    <th>Percentage</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                    <th width="160">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row['student']->roll_no ?? '-' }}</td>
                                        <td>{{ $row['student']->full_name ?: $row['student']->name ?? '-' }}</td>
                                        <td>{{ $row['total_obtained'] }}</td>
                                        <td>{{ $row['total_marks'] }}</td>
                                        <td>{{ $row['percentage'] }}%</td>
                                        <td>{{ $row['grade'] }}</td>
                                        <td>{{ $row['status'] }}</td>
                                        <td>
                                            <a href="{{ route('results.show', [$selectedExamId, $row['student']->id]) }}"
                                                class="btn btn-sm btn-info">
                                                View
                                            </a>
                                            <a href="{{ route('results.print', [$selectedExamId, $row['student']->id]) }}"
                                                target="_blank" class="btn btn-sm btn-secondary">
                                                Print
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
