@extends('layouts.app')

@section('title', 'Application — ' . $admission->application_no)

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Application Detail</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></div>
            <div class="breadcrumb-item">{{ $admission->application_no }}</div>
        </div>
    </div>

    <div class="section-body">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            {{-- ── Left: Application Details ── --}}
            <div class="col-md-8">

                {{-- Status banner --}}
                <div class="alert alert-{{ $admission->status_badge }} mb-3">
                    <strong>Status: {{ $admission->status_label }}</strong>
                    @if ($admission->status === 'rejected' && $admission->rejection_reason)
                        &nbsp;— {{ $admission->rejection_reason }}
                    @endif
                    @if ($admission->status === 'enrolled' && $admission->enrolledStudent)
                        &nbsp;— Student ID: <a href="{{ route('students.show', $admission->enrolled_student_id) }}">
                            {{ $admission->enrolledStudent->admission_no }}
                        </a>
                    @endif
                </div>

                {{-- Applicant info --}}
                <div class="card">
                    <div class="card-header"><h4>Applicant Information</h4></div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="180">Application No.</th>
                                <td>{{ $admission->application_no }}</td>
                                <th>Academic Year</th>
                                <td>{{ $admission->academic_year }}</td>
                            </tr>
                            <tr>
                                <th>Full Name</th>
                                <td>{{ $admission->full_name }}</td>
                                <th>Gender</th>
                                <td>{{ ucfirst($admission->gender ?? '—') }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth</th>
                                <td>{{ $admission->date_of_birth?->format('d M Y') ?? '—' }}</td>
                                <th>Applied Class</th>
                                <td>
                                    {{ $admission->appliedClass->name ?? '—' }}
                                    @if ($admission->appliedSection)
                                        &nbsp;/ Section {{ $admission->appliedSection->name }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Previous School</th>
                                <td colspan="3">{{ $admission->previous_school ?? '—' }}</td>
                            </tr>
                            @if ($admission->remarks)
                            <tr>
                                <th>Remarks</th>
                                <td colspan="3">{{ $admission->remarks }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- Guardian info --}}
                <div class="card">
                    <div class="card-header"><h4>Guardian / Parent Information</h4></div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="180">Father Name</th>
                                <td>{{ $admission->father_name }}</td>
                                <th>Mother Name</th>
                                <td>{{ $admission->mother_name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $admission->guardian_phone ?? '—' }}</td>
                                <th>CNIC</th>
                                <td>{{ $admission->guardian_cnic_no ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $admission->guardian_email ?? '—' }}</td>
                                <th>Address</th>
                                <td>{{ $admission->address ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>

            {{-- ── Right: Actions ── --}}
            <div class="col-md-4">

                @can('admissions.process')

                    {{-- Accept --}}
                    @if (in_array($admission->status, ['pending', 'under_review', 'rejected']))
                        <div class="card border-success">
                            <div class="card-header bg-success text-white"><h4 class="mb-0">Accept Application</h4></div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">
                                    Mark this application as accepted. The applicant can then be enrolled as a student.
                                </p>
                                <form action="{{ route('admissions.accept', $admission) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block"
                                        onclick="return confirm('Accept this application?')">
                                        <i class="fas fa-check mr-1"></i> Accept
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- Enroll --}}
                    @if ($admission->status === 'accepted')
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white"><h4 class="mb-0">Enroll as Student</h4></div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">
                                    Creates a student record, guardian account, and portal login from this application.
                                    Default password: <code>changeme123!</code>
                                </p>
                                <form action="{{ route('admissions.enroll', $admission) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-block"
                                        onclick="return confirm('Enroll this applicant as a student? This cannot be undone.')">
                                        <i class="fas fa-user-plus mr-1"></i> Enroll Student
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- Reject --}}
                    @if (!in_array($admission->status, ['enrolled']))
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white"><h4 class="mb-0">Reject Application</h4></div>
                            <div class="card-body">
                                <form action="{{ route('admissions.reject', $admission) }}" method="POST">
                                    @csrf
                                    @error('rejection_reason')
                                        <div class="alert alert-danger py-1 small">{{ $message }}</div>
                                    @enderror
                                    <div class="form-group">
                                        <label class="small">Reason <span class="text-danger">*</span></label>
                                        <textarea name="rejection_reason" class="form-control form-control-sm" rows="2"
                                            placeholder="State reason for rejection" required>{{ old('rejection_reason') }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-block btn-sm"
                                        onclick="return confirm('Reject this application?')">
                                        <i class="fas fa-times mr-1"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                @endcan

                {{-- Audit info --}}
                <div class="card">
                    <div class="card-header"><h4>Audit Trail</h4></div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="small">Submitted</th>
                                <td class="small">{{ $admission->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th class="small">Created By</th>
                                <td class="small">{{ $admission->createdBy->name ?? '—' }}</td>
                            </tr>
                            @if ($admission->reviewed_at)
                            <tr>
                                <th class="small">Reviewed</th>
                                <td class="small">{{ $admission->reviewed_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th class="small">Reviewed By</th>
                                <td class="small">{{ $admission->reviewer->name ?? '—' }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <a href="{{ route('admissions.index') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>

            </div>
        </div>

    </div>
</section>
@endsection
