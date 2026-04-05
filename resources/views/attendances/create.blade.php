@extends('layouts.app')

@section('title', 'Mark Attendance')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Mark Attendance</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Attendance</a></div>
                <div class="breadcrumb-item active">Mark Attendance</div>
            </div>
        </div>

        <div class="section-body">
            <livewire:attendance.attendance-form />
        </div>
    </section>
@endsection
