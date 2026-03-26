@extends('layouts.app')
@section('title', 'dashboard')
@section('content')
    <div class="section-header">
        <h1>Student Management</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">Student Management</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Forms</h2>
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <livewire:student-management />
            </div>
        </div>
    </div>
@endsection
