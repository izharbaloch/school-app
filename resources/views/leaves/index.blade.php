@extends('layouts.app')

@section('title', 'Leave Applications')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-times text-primary"></i> Leave Applications
        </h1>
        @can('leaves.manage')
        <a href="{{ route('leaves.types') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-cog"></i> Manage Leave Types
        </a>
        @endcan
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @livewire('leave.leave-application-manager')
        </div>
    </div>

</div>
@endsection
