@extends('layouts.app')
@section('title', 'dashboard')
@section('content')
    <div class="section-header">
        <h1>Dashboard</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Students</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalStudents ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Teachers</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalTeachers ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Guardians</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalGuardians ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fee Cards -->
        <div class="row mt-4">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Student Fees</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($totalStudentFeesAmount, 2) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ now()->format('F Y') }} Pending Fees</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($monthlyPendingAmount, 2) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ now()->format('F Y') }} Paid Fees</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($monthlyPaidAmount, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Cards -->
        <div class="row mt-4">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Today's Present</h4>
                        </div>
                        <div class="card-body">
                            {{ $todayPresent ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Today's Absent</h4>
                        </div>
                        <div class="card-body">
                            {{ $todayAbsent ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
