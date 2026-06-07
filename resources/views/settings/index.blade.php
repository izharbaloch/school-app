@extends('layouts.app')
@section('title', 'System Settings')

@section('content')
<div class="section-header">
    <h1><i class="fas fa-cog text-secondary"></i> System Settings</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Settings</div>
    </div>
</div>
<div class="section-body">
    <livewire:school-settings-manager />
</div>
@endsection
