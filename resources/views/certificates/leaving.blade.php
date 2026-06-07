@extends('layouts.app')
@section('title', 'School Leaving Certificate')
@section('content')
<div class="section-header">
    <h1>School Leaving Certificate</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></div>
        <div class="breadcrumb-item">Leaving Certificate</div>
    </div>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0">Leaving Certificate — {{ $student->first_name }} {{ $student->last_name }}</h6>
            <div>
                <a href="{{ route('certificates.leaving.print', $student->id) }}" target="_blank" class="btn btn-warning btn-sm">
                    <i class="fas fa-print"></i> Print PDF
                </a>
                <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary btn-sm ml-2">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                Click "Print PDF" to generate the school leaving/transfer certificate.
            </div>
            <table class="table table-sm">
                <tr><th>Student Name</th><td>{{ $student->first_name }} {{ $student->last_name }}</td></tr>
                <tr><th>Admission No</th><td>{{ $student->admission_no }}</td></tr>
                <tr><th>Father Name</th><td>{{ $student->father_name }}</td></tr>
                <tr><th>Class</th><td>{{ $student->studentClass->name ?? 'N/A' }} {{ $student->section ? '/ '.$student->section->name : '' }}</td></tr>
                <tr><th>Date of Birth</th><td>{{ $student->date_of_birth ?? 'N/A' }}</td></tr>
                <tr><th>Admission Date</th><td>{{ $student->admission_date ?? 'N/A' }}</td></tr>
            </table>
        </div>
    </div>
</div>
@endsection
