@extends('layouts.app')

@section('title', 'Mark Attendance')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Mark Attendance</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Attendance</a></div>
                <div class="breadcrumb-item active">Mark Attendance</div>
            </div>
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
                    <h4>Select Filters</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('attendances.create') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Date <span class="text-danger">*</span></label>
                                <input type="date" name="attendance_date" class="form-control"
                                    value="{{ old('attendance_date', $attendanceDate) }}">
                            </div>

                            <div class="col-md-4">
                                <label>Class <span class="text-danger">*</span></label>
                                <select name="student_class_id" class="form-control">
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
                                <label>Section <span class="text-danger">*</span></label>
                                <select name="section_id" class="form-control">
                                    <option value="">Select Section</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ $selectedSectionId == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
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

            @if ($selectedClassId && $selectedSectionId)
                <div class="card">
                    <div class="card-header">
                        <h4>Student Attendance</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('attendances.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="attendance_date" value="{{ $attendanceDate }}">
                            <input type="hidden" name="student_class_id" value="{{ $selectedClassId }}">
                            <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">

                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Roll No</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($students as $index => $student)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $student->roll_no ?? '-' }}</td>
                                                <td>{{ $student->full_name ?: $student->name ?? '-' }}</td>
                                                <td>
                                                    <input type="hidden" name="students[{{ $index }}][student_id]"
                                                        value="{{ $student->id }}">

                                                    <select name="students[{{ $index }}][status]"
                                                        class="form-control">
                                                        <option value="present">Present</option>
                                                        <option value="absent">Absent</option>
                                                        <option value="leave">Leave</option>
                                                        <option value="late">Late</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="students[{{ $index }}][remarks]"
                                                        class="form-control" value="{{ old("students.$index.remarks") }}">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No students found for selected class
                                                    and section.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($students->count())
                                <button type="submit" class="btn btn-primary">Save Attendance</button>
                            @endif
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
