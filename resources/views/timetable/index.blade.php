@extends('layouts.app')
@section('title', 'Timetable')
@section('content')
<div class="section-header">
    <h1>Timetable Management</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Timetable</div>
    </div>
</div>
<div class="section-body">
    @livewire('timetable-manager')
</div>
@endsection
