@extends('layouts.app')
@section('title', 'Reports')
@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar text-primary"></i> Reports
        </h1>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <a href="{{ route('reports.students') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100" style="border-left: 4px solid #4e73df !important;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div style="width:56px;height:56px;border-radius:14px;background:#eef2ff;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#4e73df;flex-shrink:0">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size:1rem">Student Report</div>
                            <div class="text-muted small">Class-wise student list with filters for gender, status and section. Printable.</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-4">
            <a href="{{ route('reports.attendance') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100" style="border-left: 4px solid #1cc88a !important;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div style="width:56px;height:56px;border-radius:14px;background:#eafaf4;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#1cc88a;flex-shrink:0">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size:1rem">Attendance Report</div>
                            <div class="text-muted small">Per-student attendance summary for any date range. Present / absent / leave counts.</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-4">
            <a href="{{ route('reports.fees') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100" style="border-left: 4px solid #f6c23e !important;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div style="width:56px;height:56px;border-radius:14px;background:#fef9ec;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#d97706;flex-shrink:0">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size:1rem">Fee Collection Report</div>
                            <div class="text-muted small">Payment history with totals by method. Filter by date range and class.</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

</div>
@endsection
