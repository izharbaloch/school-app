@extends('layouts.app')

@section('title', 'Results')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Results</h1>
        </div>

        <div class="section-body">
            <livewire:exams.result-index />
        </div>
    </section>
@endsection
