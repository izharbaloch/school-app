@extends('layouts.app')

@section('title', 'Teachers')

@section('content')
    <div class="section">
        <div class="section-header">
            <h1>Teachers</h1>
        </div>

        <div class="section-body">
            <livewire:teachers.teacher-index />
        </div>
    </div>
@endsection
