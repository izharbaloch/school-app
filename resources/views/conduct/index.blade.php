@extends('layouts.app')

@section('title', 'Student Conduct')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-gavel text-primary"></i> Student Conduct & Discipline
        </h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @livewire('conduct.conduct-manager')
        </div>
    </div>

</div>
@endsection
