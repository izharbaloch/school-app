@extends('layouts.app')

@section('title', 'Attendance Details')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Attendance Details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Attendance</a></div>
                <div class="breadcrumb-item active">Details</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Attendance Information</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Date:</strong> {{ $attendance->attendance_date->format('d-m-Y') }}
                        </div>
                        <div class="col-md-3"><strong>Class:</strong> {{ $attendance->studentClass->name ?? '-' }}</div>
                        <div class="col-md-3"><strong>Section:</strong> {{ $attendance->section->name ?? '-' }}</div>
                        <div class="col-md-3"><strong>Taken By:</strong> {{ $attendance->takenBy->name ?? '-' }}</div>
                    </div>

                    <div class="mb-3">
                        <strong>General Remarks:</strong><br>
                        {{ $attendance->remarks ?: '-' }}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Roll No</th>
                                    <th>Student Name</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendance->attendanceStudents as $record)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $record->student->roll_no ?? '-' }}</td>
                                        <td>{{ $record->student->full_name ?: $record->student->name ?? '-' }}</td>
                                        <td class="text-capitalize">{{ $record->status }}</td>
                                        <td>{{ $record->remarks ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Back</a>
                    <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-warning">Edit</a>
                </div>
            </div>
        </div>
    </section>
@endsection
