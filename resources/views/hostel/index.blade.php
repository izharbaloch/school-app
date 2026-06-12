@extends('layouts.app')
@section('title', 'Hostel Management')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-building text-primary"></i> Hostel Management
        </h1>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            @livewire('hostel.hostel-manager')
        </div>
    </div>
</div>
@endsection
