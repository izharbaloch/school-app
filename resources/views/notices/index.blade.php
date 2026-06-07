@extends('layouts.app')
@section('title', 'Notice Board')
@section('content')
<div class="section-header">
    <h1>Notice Board</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Notices</div>
    </div>
</div>
<div class="section-body">
    @livewire('notice-manager')
</div>
@endsection
