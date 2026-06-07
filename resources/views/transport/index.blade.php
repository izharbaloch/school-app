@extends('layouts.app')
@section('title', 'Transport Management')
@section('content')
<div class="section-header">
    <h1>Transport Management</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Transport</div>
    </div>
</div>
<div class="section-body">
    @livewire('transport.transport-manager')
</div>
@endsection
