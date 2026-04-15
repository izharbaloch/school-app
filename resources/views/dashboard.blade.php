@extends('layouts.app')
@section('title', 'dashboard')
@section('content')
    <div class="section-header">
        <h1>Dashboard</h1>
    </div>

    <div class="section-body">
         <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
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
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="fas fa-chalkboard-user"></i>
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
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
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
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                  <i class="fas fa-file-alt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Exams</h4>
                  </div>
                  <div class="card-body">
                    {{ $totalExams ?? 0 }}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                  <i class="fas fa-receipt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Fees</h4>
                  </div>
                  <div class="card-body">
                    {{ $totalFees ?? 0 }}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-secondary">
                  <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Fees Paid</h4>
                  </div>
                  <div class="card-body">
                    {{ $feesPaid ?? 0 }}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-purple">
                  <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Attendances</h4>
                  </div>
                  <div class="card-body">
                    {{ $totalAttendances ?? 0 }}
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
@endsection
