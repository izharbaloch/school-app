@extends('layouts.app')
@section('title', 'dashboard')
@section('content')
    <div class="section-header">
        <h1>Academic Setup</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">Academic Setup</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Forms</h2>
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <livewire:academic-setup />
            </div>
        </div>
    </div>
@endsection
