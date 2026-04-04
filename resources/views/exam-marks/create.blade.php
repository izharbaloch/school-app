@extends('layouts.app')

@section('title', 'Enter Exam Marks')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Enter Exam Marks</h1>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Select Exam Filters</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('exam-marks.create') }}">
                        <div class="row">
                            <div class="col-md-3">
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

                            <div class="col-md-3">
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

                            <div class="col-md-3">
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

                            <div class="col-md-3">
                                <label>Subject</label>
                                <select name="subject_id" class="form-control" required>
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $subjectItem)
                                        <option value="{{ $subjectItem->id }}"
                                            {{ $selectedSubjectId == $subjectItem->id ? 'selected' : '' }}>
                                            {{ $subjectItem->name }}
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

            @if ($selectedExamId && $selectedClassId && $selectedSubjectId)
                <div class="card">
                    <div class="card-header">
                        <h4>Student Marks Entry</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('exam-marks.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="exam_id" value="{{ $selectedExamId }}">
                            <input type="hidden" name="student_class_id" value="{{ $selectedClassId }}">
                            <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">
                            <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">

                            <div class="mb-3">
                                <strong>Total Marks:</strong> {{ $subject->total_marks ?? '-' }} |
                                <strong>Passing Marks:</strong> {{ $subject->passing_marks ?? '-' }}
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Roll No</th>
                                            <th>Student Name</th>
                                            <th>Obtained Marks</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $index => $student)
                                            @php
                                                $existing = $existingResults[$student->id] ?? null;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $student->roll_no ?? '-' }}</td>
                                                <td>{{ $student->full_name ?: $student->name ?? '-' }}</td>
                                                <td>
                                                    <input type="hidden" name="students[{{ $index }}][student_id]"
                                                        value="{{ $student->id }}">
                                                    <input type="number" step="0.01"
                                                        name="students[{{ $index }}][obtained_marks]"
                                                        class="form-control"
                                                        value="{{ old("students.$index.obtained_marks", $existing->obtained_marks ?? 0) }}"
                                                        min="0" max="{{ $subject->total_marks ?? 100 }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="students[{{ $index }}][remarks]"
                                                        class="form-control"
                                                        value="{{ old("students.$index.remarks", $existing->remarks ?? '') }}">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No students found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($students->count())
                                <button type="submit" class="btn btn-primary">Save Marks</button>
                            @endif
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.querySelector('select[name="student_class_id"]');
            const subjectSelect = document.querySelector('select[name="subject_id"]');
            const getSubjectsUrl = '{{ route("exam-marks.get-subjects", ":classId") }}';

            if (classSelect) {
                classSelect.addEventListener('change', function() {
                    const classId = this.value;

                    // Reset subject select
                    subjectSelect.innerHTML = '<option value="">Select Subject</option>';

                    if (!classId) return;

                    // Fetch subjects for the selected class
                    fetch(getSubjectsUrl.replace(':classId', classId))
                        .then(response => response.json())
                        .then(data => {
                            data.subjects.forEach(subject => {
                                const option = document.createElement('option');
                                option.value = subject.id;
                                option.textContent = subject.name;
                                subjectSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error loading subjects:', error));
                });
            }
        });
    </script>
@endsection
