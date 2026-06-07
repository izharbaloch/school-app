@extends('layouts.app')
@section('title', 'Events & Calendar')
@section('content')
<div class="section-header">
    <h1>Events & Calendar</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Events</div>
    </div>
</div>
<div class="section-body">
    @livewire('event-manager')
</div>
@endsection
