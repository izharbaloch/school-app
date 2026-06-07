@extends('layouts.app')
@section('title', 'Certificate Management')
@section('content')
<div class="section-header">
    <h1>Certificate Management</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Certificates</div>
    </div>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header"><h4>Select Student to Generate Certificate</h4></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Search Student</label>
                        <input type="text" class="form-control" id="studentSearch" placeholder="Type student name or admission no...">
                    </div>
                </div>
            </div>
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                To generate a certificate, go to a student's profile and use the Certificates section, or search above to find a student.
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="card border text-center p-3">
                        <i class="fas fa-certificate fa-3x text-warning mb-2"></i>
                        <h6>Character Certificate</h6>
                        <small class="text-muted">Certifies student's good character</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border text-center p-3">
                        <i class="fas fa-door-open fa-3x text-danger mb-2"></i>
                        <h6>Leaving Certificate</h6>
                        <small class="text-muted">Issued when student leaves school</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border text-center p-3">
                        <i class="fas fa-user-check fa-3x text-success mb-2"></i>
                        <h6>Bonafide Certificate</h6>
                        <small class="text-muted">Confirms enrollment status</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border text-center p-3">
                        <i class="fas fa-id-card fa-3x text-primary mb-2"></i>
                        <h6>Student ID Card</h6>
                        <small class="text-muted">Printable ID with QR code</small>
                    </div>
                </div>
            </div>
            <p class="text-muted mt-3">
                <i class="fas fa-arrow-right mr-1"></i>
                Navigate to <a href="{{ route('students.index') }}">Students</a> → View a student → Certificates tab to generate certificates.
            </p>
        </div>
    </div>
</div>
@endsection
