@extends('layouts.app')
@section('title', 'Teacher Attendance')
@section('content')
    <div class="section-header">
        <h1>Teacher Attendance</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </div>
            <div class="breadcrumb-item active">
                <a href="{{ route('teacher-attendances.index') }}">Teacher Attendance</a>
            </div>
        </div>
    </div>

    <div class="section-body">
        @livewire('attendance.teacher-attendance-index')
    </div>
@endsection
