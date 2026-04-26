@extends('layouts.app')

@section('title', 'Students')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Students</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Students</div>
            </div>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Student List</h4>
                    <a href="{{ route('students.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Add Student
                    </a>
                </div>

                <div class="card-body">
                    <livewire:student.student-index />
                </div>
            </div>
        </div>
    </section>
@endsection
