@extends('layouts.app')

@section('title', 'Exams')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Exams</h1>
        </div>

        <div class="section-body">
            @livewire('exams.exam-type')
        </div>
    </section>
@endsection
