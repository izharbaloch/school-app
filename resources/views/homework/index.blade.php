@extends('layouts.app')
@section('title', 'Homework & Assignments')

@section('content')
<div class="section-header">
    <h1><i class="fas fa-book text-primary"></i> Homework & Assignments</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Homework</div>
    </div>
</div>
<div class="section-body">
    <livewire:homework-manager />
</div>
@endsection
