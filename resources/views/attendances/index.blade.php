@extends('layouts.app')

@section('title', 'Attendance List')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Attendance List</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Attendance</div>
            </div>
        </div>

        <div class="section-body">
            <livewire:attendance.attendance-index />
        </div>
    </section>
@endsection
