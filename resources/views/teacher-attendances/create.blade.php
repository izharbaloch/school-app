@extends('layouts.app')
@section('title', 'Mark Teacher Attendance')
@section('content')
    <div class="section-header">
        <h1>Mark Teacher Attendance</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{ route('teacher-attendances.index') }}">Teacher Attendance</a>
            </div>
            <div class="breadcrumb-item active">Create</div>
        </div>
    </div>

    <div class="section-body">
        @livewire('attendance.teacher-attendance-form')
    </div>
@endsection
