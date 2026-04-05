@extends('layouts.app')

@section('title', 'Enter Exam Marks')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Enter Exam Marks</h1>
        </div>

        <div class="section-body">
            <livewire:exams.exam-mark-entry />
        </div>
    </section>
@endsection
