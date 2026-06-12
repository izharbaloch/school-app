@extends('layouts.app')
@section('title', 'Attendance Report')
@push('styles')
<style>
@media print {
    .no-print, .section-header, nav, .sidebar, .navbar { display: none !important; }
    .section-body { padding: 0 !important; }
    .card { box-shadow: none !important; border: none !important; }
    .print-header { display: block !important; }
    body { font-size: 12px; }
}
.print-header { display: none; }
</style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 no-print">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-check text-success"></i> Attendance Report
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Reports
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <div class="print-header text-center mb-3">
        <h4 class="mb-0">Attendance Report</h4>
        <div class="text-muted small">Generated: {{ now()->format('d M Y, h:i A') }}</div>
        <hr>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @livewire('reports.attendance-report')
        </div>
    </div>
</div>
@endsection
