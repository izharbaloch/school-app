@extends('layouts.app')

@section('title', 'View Student')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>View Student</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></div>
                <div class="breadcrumb-item">View</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Student Details</h4>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            @if ($student->profilePhoto)
                                <img src="{{ asset('storage/' . $student->profilePhoto->file_path) }}" alt="Student Photo"
                                    class="img-fluid rounded border" style="max-height: 220px;">
                            @else
                                <div class="border rounded p-5 text-muted">No Photo</div>
                            @endif
                        </div>

                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <strong>Admission No:</strong><br>
                                    {{ $student->admission_no ?? '-' }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Roll No:</strong><br>
                                    {{ $student->roll_no ?? '-' }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Status:</strong><br>
                                    @if ($student->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>First Name:</strong><br>
                                    {{ $student->first_name ?? '-' }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Last Name:</strong><br>
                                    {{ $student->last_name ?? '-' }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Gender:</strong><br>
                                    {{ ucfirst($student->gender ?? '-') }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Date of Birth:</strong><br>
                                    {{ $student->date_of_birth ?? '-' }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Phone:</strong><br>
                                    {{ $student->phone ?? '-' }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Email:</strong><br>
                                    {{ $student->email ?? '-' }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Father Name:</strong><br>
                                    {{ $student->father_name ?? '-' }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Mother Name:</strong><br>
                                    {{ $student->mother_name ?? '-' }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Guardian Phone:</strong><br>
                                    {{ $student->guardian_phone ?? '-' }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Admission Date:</strong><br>
                                    {{ $student->admission_date ?? '-' }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Class:</strong><br>
                                    {{ $student->studentClass->name ?? '-' }}
                                </div>

                                <div class="col-md-4 mb-3">
                                    <strong>Section:</strong><br>
                                    {{ $student->section->name ?? '-' }}
                                </div>

                                <div class="col-md-12 mb-3">
                                    <strong>Address:</strong><br>
                                    {{ $student->address ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3">Documents</h5>

                    <div class="row">
                        @forelse ($attachments as $type => $files)
                            @foreach ($files as $file)
                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-3 h-100">
                                        <strong>{{ $file->title ?? $type }}</strong>
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-eye mr-1"></i> View File
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @empty
                            <div class="col-12">
                                <div class="alert alert-light border mb-0">No documents found.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
