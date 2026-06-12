@extends('layouts.app')

@section('title', 'Leave Types')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tags text-primary"></i> Leave Types
        </h1>
        <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Applications
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @livewire('leave.leave-type-manager')
        </div>
    </div>

</div>
@endsection
