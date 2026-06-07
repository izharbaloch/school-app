@extends('layouts.app')
@section('title', 'Library Management')
@section('content')
<div class="section-header">
    <h1>Library Management</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Library</div>
    </div>
</div>
<div class="section-body">
    @livewire('library.library-manager')
</div>
@endsection
