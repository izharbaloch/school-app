@extends('layouts.app')
@section('title', 'Teacher Attendance Details')
@section('content')
    <div class="section-header">
        <h1>Teacher Attendance Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{ route('teacher-attendances.index') }}">Teacher Attendance</a>
            </div>
            <div class="breadcrumb-item active">Details</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Attendance Record - {{ $attendance->attendance_date->format('d M Y') }}</h4>
                <div class="card-header-action">
                    <a href="{{ route('teacher-attendances.edit', $attendance->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('teacher-attendances.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Attendance Date:</strong> {{ $attendance->attendance_date->format('d M Y') }}</p>
                        <p><strong>Taken By:</strong> {{ $attendance->takenBy?->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Teachers:</strong> <span class="badge bg-primary">{{ $attendance->attendanceTeachers->count() }}</span></p>
                        <p><strong>Remarks:</strong> {{ $attendance->remarks ?? 'N/A' }}</p>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Present</h4>
                                </div>
                                <div class="card-body">
                                    {{ $attendance->presentCount() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-user-times"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Absent</h4>
                                </div>
                                <div class="card-body">
                                    {{ $attendance->absentCount() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Leave</h4>
                                </div>
                                <div class="card-body">
                                    {{ $attendance->leaveCount() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-info">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Late</h4>
                                </div>
                                <div class="card-body">
                                    {{ $attendance->lateCount() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Employee No</th>
                                <th>Teacher Name</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendance->attendanceTeachers as $record)
                                <tr>
                                    <td>
                                        <small>{{ $record->teacher->employee_no ?? '-' }}</small>
                                    </td>
                                    <td>{{ $record->teacher->name }}</td>
                                    <td>
                                        @if ($record->status === 'present')
                                            <span class="badge bg-success">Present</span>
                                        @elseif ($record->status === 'absent')
                                            <span class="badge bg-danger">Absent</span>
                                        @elseif ($record->status === 'leave')
                                            <span class="badge bg-warning">Leave</span>
                                        @elseif ($record->status === 'late')
                                            <span class="badge bg-info">Late</span>
                                        @endif
                                    </td>
                                    <td>{{ $record->remarks ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
