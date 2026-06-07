@extends('layouts.app')
@section('title', 'Student ID Card — ' . $student->first_name . ' ' . $student->last_name)

@section('content')
<div class="section-header">
    <h1>Student ID Card</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></div>
        <div class="breadcrumb-item">ID Card</div>
    </div>
</div>
<div class="section-body">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-3">
                <a href="{{ route('certificates.id-card.print', $student->id) }}" target="_blank" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print ID Card (PDF)
                </a>
                <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left"></i> Back to Student
                </a>
            </div>

            {{-- Preview Card --}}
            <div style="display:inline-block; border: 2px solid #1a5276; border-radius:8px; overflow:hidden; width:340px; font-family: sans-serif;">
                <div style="background:#1a5276; color:white; text-align:center; padding:6px 10px; font-size:11px; font-weight:bold;">
                    {{ \App\Models\SchoolSetting::get('school_name', config('app.name')) }} — STUDENT ID CARD
                </div>
                <div style="display:flex; padding:10px 12px; gap:10px; background:#fff;">
                    <div style="width:80px; height:100px; border:1px solid #ddd; background:#f5f5f5; border-radius:4px; overflow:hidden; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                        @if($student->profilePhoto)
                            <img src="{{ asset('storage/'.$student->profilePhoto->file_path) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <span style="font-size:11px; color:#aaa;">No Photo</span>
                        @endif
                    </div>
                    <div style="flex:1; font-size:11px;">
                        <div style="font-size:13px; font-weight:bold; color:#1a5276; margin-bottom:4px;">{{ $student->first_name }} {{ $student->last_name }}</div>
                        <div><b>Adm No:</b> {{ $student->admission_no }}</div>
                        <div><b>Class:</b> {{ $student->studentClass->name ?? 'N/A' }} @if($student->section)/ {{ $student->section->name }}@endif</div>
                        <div><b>Roll No:</b> {{ $student->roll_no ?? 'N/A' }}</div>
                        <div><b>Father:</b> {{ $student->father_name }}</div>
                        <div><b>Phone:</b> {{ $student->guardian_phone ?? 'N/A' }}</div>
                    </div>
                    @if(!empty($qrDataUri))
                    <div style="display:flex; align-items:flex-end; flex-shrink:0;">
                        <img src="{{ $qrDataUri }}" style="width:60px; height:60px;" alt="QR Code">
                    </div>
                    @endif
                </div>
                <div style="background:#1a5276; color:white; display:flex; justify-content:space-between; padding:4px 10px; font-size:10px;">
                    <span>Valid: {{ date('Y') }}–{{ date('Y')+1 }}</span>
                    <span style="font-style:italic; font-size:9px;">If found, return to school</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
