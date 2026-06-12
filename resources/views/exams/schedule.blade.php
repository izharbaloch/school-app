@extends('layouts.app')

@section('title', 'Exam Timetable')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Exam Timetable</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('exams.index') }}">Exams</a></div>
            <div class="breadcrumb-item">Exam Timetable</div>
        </div>
    </div>

    <div class="section-body">
        @livewire('exams.exam-schedule-manager')
    </div>
</section>
@endsection
