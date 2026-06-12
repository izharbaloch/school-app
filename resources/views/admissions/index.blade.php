@extends('layouts.app')

@section('title', 'Admissions')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Admission Applications</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Admissions</div>
        </div>
    </div>

    <div class="section-body">
        @livewire('admission-manager')
    </div>
</section>
@endsection
