@extends('layouts.app')
@section('title', 'Academic Sessions')
@section('content')
<div class="section-header">
    <h1>Academic Sessions</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Academic Sessions</div>
    </div>
</div>
<div class="section-body">
    @livewire('academic-session-manager')
</div>
@endsection
