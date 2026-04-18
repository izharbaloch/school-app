@extends('layouts.app')

@section('title', 'Student Result')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Student Result</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <p><strong>Exam:</strong> {{ $exam->name }}</p>
                <p><strong>Student:</strong> {{ $student->full_name ?: ($student->name ?? '-') }}</p>
                <p><strong>Class:</strong> {{ $studentClass->name ?? '-' }}</p>
                <p><strong>Section:</strong> {{ $student->section->name ?? '-' }}</p>
                <p><strong>Roll No:</strong> {{ $student->roll_no ?? '-' }}</p>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Total Marks</th>
                            <th>Passing Marks</th>
                            <th>Obtained Marks</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $result)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $result->subject->name ?? '-' }}</td>
                                <td>{{ $result->total_marks }}</td>
                                <td>{{ $result->passing_marks }}</td>
                                <td>{{ $result->obtained_marks }}</td>
                                <td>{{ $result->is_pass ? 'Pass' : 'Fail' }}</td>
                                <td>{{ $result->remarks ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No result found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    <p><strong>Total Obtained:</strong> {{ $totalObtained }}</p>
                    <p><strong>Total Marks:</strong> {{ $totalMarks }}</p>
                    <p><strong>Percentage:</strong> {{ round($percentage, 2) }}%</p>
                    <p><strong>Grade:</strong> {{ $grade }}</p>
                    <p><strong>Status:</strong> {{ $status }}</p>
                </div>

                <a href="{{ route('results.print', [$exam->id, $student->id]) }}" target="_blank" class="btn btn-secondary">Print Result Card</a>
                <a href="{{ route('results.index', ['exam_id' => $exam->id, 'student_class_id' => $student->student_class_id, 'section_id' => $student->section_id]) }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>
@endsection
