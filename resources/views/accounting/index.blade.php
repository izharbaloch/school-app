@extends('layouts.app')
@section('title', 'Accounting & Finance')

@section('content')
<div class="section-header">
    <h1><i class="fas fa-coins text-warning"></i> Accounting & Finance</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Accounting</div>
    </div>
</div>
<div class="section-body">
    <livewire:accounting.accounting-manager />
</div>
@endsection
