@extends('layouts.app')
@section('title', 'Student Promotion')
@section('content')
<div class="section-header">
    <h1>Student Promotion</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Student Promotion</div>
    </div>
</div>
<div class="section-body">
    @livewire('student-promotion')
</div>
@endsection
