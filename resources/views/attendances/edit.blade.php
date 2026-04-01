@extends('layouts.app')

@section('title', 'Edit Attendance')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Attendance</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Attendance</a></div>
                <div class="breadcrumb-item active">Edit</div>
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
                    <h4>Edit Student Attendance</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('attendances.update', $attendance->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4">
                                <label>Date <span class="text-danger">*</span></label>
                                <input type="date" name="attendance_date" class="form-control"
                                    value="{{ old('attendance_date', $attendance->attendance_date->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-4">
                                <label>Class <span class="text-danger">*</span></label>
                                <select name="student_class_id" class="form-control">
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ old('student_class_id', $attendance->student_class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Section <span class="text-danger">*</span></label>
                                <select name="section_id" class="form-control">
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ old('section_id', $attendance->section_id) == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mt-3">
                                <label>General Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $attendance->remarks) }}</textarea>
                            </div>
                        </div>

                        <div class="table-responsive mt-4">
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
                                    @foreach ($attendance->attendanceStudents as $index => $record)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $record->student->roll_no ?? '-' }}</td>
                                            <td>{{ $record->student->full_name ?: $record->student->name ?? '-' }}</td>
                                            <td>
                                                <input type="hidden" name="students[{{ $index }}][student_id]"
                                                    value="{{ $record->student_id }}">

                                                <select name="students[{{ $index }}][status]" class="form-control">
                                                    <option value="present"
                                                        {{ $record->status == 'present' ? 'selected' : '' }}>Present
                                                    </option>
                                                    <option value="absent"
                                                        {{ $record->status == 'absent' ? 'selected' : '' }}>Absent</option>
                                                    <option value="leave"
                                                        {{ $record->status == 'leave' ? 'selected' : '' }}>Leave</option>
                                                    <option value="late"
                                                        {{ $record->status == 'late' ? 'selected' : '' }}>Late</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="students[{{ $index }}][remarks]"
                                                    class="form-control"
                                                    value="{{ old("students.$index.remarks", $record->remarks) }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Attendance</button>
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
