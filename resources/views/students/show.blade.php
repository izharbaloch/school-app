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

                {{-- Tab Navigation --}}
                <div class="card-header border-top-0 pt-0 pb-0">
                    <ul class="nav nav-tabs card-header-tabs" id="studentTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab">
                                <i class="fas fa-user"></i> Personal Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="docs-tab" data-toggle="tab" href="#docs" role="tab">
                                <i class="fas fa-paperclip"></i> Documents
                            </a>
                        </li>
                        @can('students.view')
                        <li class="nav-item">
                            <a class="nav-link" id="certs-tab" data-toggle="tab" href="#certs" role="tab">
                                <i class="fas fa-certificate"></i> Certificates & ID
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="studentTabContent">

                    {{-- Tab: Personal Info --}}
                    <div class="tab-pane fade show active" id="info" role="tabpanel">
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
                                    <strong>Guardian CNIC:</strong><br>
                                    {{ $student->guardian_cnic_no ?? '-' }}
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

                    </div>{{-- end row (photo + details) --}}
                    </div>{{-- end tab-pane info --}}

                    {{-- Tab: Documents --}}
                    <div class="tab-pane fade" id="docs" role="tabpanel">
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
                    </div>{{-- end tab-pane docs --}}

                    {{-- Tab: Certificates & ID Card --}}
                    @can('students.view')
                    <div class="tab-pane fade" id="certs" role="tabpanel">
                        <h5 class="mb-4"><i class="fas fa-certificate text-primary mr-2"></i>Certificates & ID Card</h5>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card border-primary h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-scroll fa-3x text-primary mb-3"></i>
                                        <h6>Character Certificate</h6>
                                        <p class="text-muted small">Official character/conduct certificate</p>
                                        <a href="{{ route('certificates.character', $student->id) }}" class="btn btn-outline-primary btn-sm mr-1">
                                            <i class="fas fa-eye"></i> Preview
                                        </a>
                                        <a href="{{ route('certificates.character.print', $student->id) }}" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="fas fa-print"></i> Print
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-success h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-alt fa-3x text-success mb-3"></i>
                                        <h6>Bonafide Certificate</h6>
                                        <p class="text-muted small">Confirms student is enrolled in school</p>
                                        <a href="{{ route('certificates.bonafide', $student->id) }}" class="btn btn-outline-success btn-sm mr-1">
                                            <i class="fas fa-eye"></i> Preview
                                        </a>
                                        <a href="{{ route('certificates.bonafide.print', $student->id) }}" target="_blank" class="btn btn-success btn-sm">
                                            <i class="fas fa-print"></i> Print
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-warning h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-door-open fa-3x text-warning mb-3"></i>
                                        <h6>School Leaving Certificate</h6>
                                        <p class="text-muted small">Transfer / leaving certificate with full details</p>
                                        <a href="{{ route('certificates.leaving', $student->id) }}" class="btn btn-outline-warning btn-sm mr-1">
                                            <i class="fas fa-eye"></i> Preview
                                        </a>
                                        <a href="{{ route('certificates.leaving.print', $student->id) }}" target="_blank" class="btn btn-warning btn-sm">
                                            <i class="fas fa-print"></i> Print
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-id-card fa-3x text-info mb-3"></i>
                                        <h6>Student ID Card</h6>
                                        <p class="text-muted small">CR80 format ID card with QR code</p>
                                        <a href="{{ route('certificates.id-card', $student->id) }}" class="btn btn-outline-info btn-sm mr-1">
                                            <i class="fas fa-eye"></i> Preview
                                        </a>
                                        <a href="{{ route('certificates.id-card.print', $student->id) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-print"></i> Print
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan

                    </div>{{-- end tab-content --}}
                </div>
            </div>
        </div>
    </section>
@endsection
