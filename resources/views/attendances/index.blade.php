@extends('layouts.app')

@section('title', 'Attendance List')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Attendance List</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Attendance</div>
            </div>
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
                    <h4>Attendance Records</h4>
                    <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Mark Attendance
                    </a>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('attendances.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Date</label>
                                <input type="date" name="attendance_date" class="form-control"
                                    value="{{ request('attendance_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label>Class</label>
                                <select name="student_class_id" class="form-control">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ request('student_class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Section</label>
                                <select name="section_id" class="form-control">
                                    <option value="">Select Section</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-info mr-2">Filter</button>
                                <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Total</th>
                                    <th>Present</th>
                                    <th>Absent</th>
                                    <th>Leave</th>
                                    <th>Late</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendances as $attendance)
                                    <tr>
                                        <td>{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}
                                        </td>
                                        <td>{{ $attendance->attendance_date->format('d-m-Y') }}</td>
                                        <td>{{ $attendance->studentClass->name ?? '-' }}</td>
                                        <td>{{ $attendance->section->name ?? '-' }}</td>
                                        <td>{{ $attendance->attendanceStudents->count() }}</td>
                                        <td>{{ $attendance->attendanceStudents->where('status', 'present')->count() }}</td>
                                        <td>{{ $attendance->attendanceStudents->where('status', 'absent')->count() }}</td>
                                        <td>{{ $attendance->attendanceStudents->where('status', 'leave')->count() }}</td>
                                        <td>{{ $attendance->attendanceStudents->where('status', 'late')->count() }}</td>
                                        <td>
                                            <a href="{{ route('attendances.show', $attendance->id) }}"
                                                class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('attendances.edit', $attendance->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>

                                            <form action="{{ route('attendances.destroy', $attendance->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this attendance?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No attendance record found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
