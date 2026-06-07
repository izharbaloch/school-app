@extends('layouts.app')
@section('title', 'Activity Logs')

@section('content')
<div class="section-header">
    <h1><i class="fas fa-history text-info"></i> Activity Logs</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Activity Logs</div>
    </div>
</div>
<div class="section-body">
    <livewire:activity-log-manager />
</div>
@endsection
