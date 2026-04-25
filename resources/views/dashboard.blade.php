@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="section-header">
        <h1>Dashboard</h1>
    </div>

    <div class="section-body">
        <!-- Top Summary Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Students</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($totalStudents) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-info">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Teachers</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($totalTeachers) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-school"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Classes</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($totalClasses) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-success">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Fee Collected</h4>
                        </div>
                        <div class="card-body">
                            ${{ number_format($totalFeeCollected, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Pending Fees</h4>
                        </div>
                        <div class="card-body">
                            ${{ number_format($pendingFees, 2) }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions inline cards -->
            <div class="col-lg-9 col-md-12 col-sm-12 col-12 d-flex align-items-center">
                <div class="w-100 d-flex justify-content-lg-end justify-content-center flex-wrap mb-4 mb-lg-0" style="gap: 8px;">
                    <button class="btn btn-primary btn-lg shadow-sm"><i class="fas fa-user-plus mr-1"></i> Add Student</button>
                    <button class="btn btn-info btn-lg shadow-sm"><i class="fas fa-chalkboard-teacher mr-1"></i> Add Teacher</button>
                    <button class="btn btn-success btn-lg shadow-sm"><i class="fas fa-hand-holding-usd mr-1"></i> Collect Fee</button>
                    <button class="btn btn-warning btn-lg shadow-sm"><i class="fas fa-clipboard-check mr-1"></i> Take Attendance</button>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4>Monthly Admissions</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="admissionsChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4>Fee Collection</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="feeCollectionChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4>Attendance Overview</h4>
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <canvas id="attendanceChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tables & Recent Activity -->
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Recent Students</h4>
                        <a href="#" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Admission Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentStudents as $student)
                                    <tr>
                                        <td>{{ $student->full_name ?: ($student->name ?? '-') }}</td>
                                        <td>{{ $student->studentClass->name ?? '-' }}</td>
                                        <td>{{ $student->created_at->format('d M Y') }}</td>
                                        <td><div class="badge badge-{{ ($student->status ?? 1) ? 'success' : 'warning' }}">{{ ($student->status ?? 1) ? 'Active' : 'Inactive' }}</div></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No recent students found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Teacher Roster</h4>
                        <a href="#" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($teacherRoster as $teacher)
                                    <tr>
                                        <td>{{ $teacher->name }}</td>
                                        <td>{{ $teacher->designation ?? '-' }}</td>
                                        <td>{{ $teacher->phone ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No teachers found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <!-- Notifications / Alerts -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4>Notifications</h4>
                    </div>
                    <div class="card-body">
                        @if($pendingFeesAlert > 0)
                        <div class="alert alert-danger mb-3">
                            <div class="alert-title"><i class="fas fa-exclamation-triangle mr-2"></i>Pending Fees Alert</div>
                            {{ $pendingFeesAlert }} student(s) have pending fees past their due date.
                        </div>
                        @endif
                        
                        <div class="alert alert-warning mb-3">
                            <div class="alert-title"><i class="fas fa-user-times mr-2"></i>Low Attendance Warning</div>
                            Check attendance report for recent absentees.
                        </div>
                        
                        @if($newAdmissionsAlert > 0)
                        <div class="alert alert-info mb-0">
                            <div class="alert-title"><i class="fas fa-user-plus mr-2"></i>New Admission</div>
                            {{ $newAdmissionsAlert }} new admission(s) recorded in the last 7 days.
                        </div>
                        @else
                        <div class="alert alert-info mb-0">
                            <div class="alert-title"><i class="fas fa-info-circle mr-2"></i>System Notice</div>
                            No new admissions in the last 7 days.
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header">
                        <h4>Recent Activity</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled list-unstyled-border">
                            @forelse($recentActivities as $activity)
                            <li class="media">
                                <img class="mr-3 rounded-circle" width="40" src="https://ui-avatars.com/api/?name={{ urlencode($activity['title']) }}&background=random" alt="avatar">
                                <div class="media-body">
                                    <div class="float-right text-primary">{{ $activity['time_str'] }}</div>
                                    <div class="media-title">{{ $activity['title'] }}</div>
                                    <span class="text-small text-muted">{{ $activity['description'] }}</span>
                                </div>
                            </li>
                            @empty
                            <li class="text-center text-muted py-3">No recent activity.</li>
                            @endforelse
                        </ul>
                        <div class="text-center pt-1 pb-1">
                            <a href="#" class="btn btn-primary btn-lg btn-round">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Monthly Admissions Chart
        var ctxAdmissions = document.getElementById('admissionsChart').getContext('2d');
        new Chart(ctxAdmissions, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartMonths) !!},
                datasets: [{
                    label: 'New Admissions',
                    data: {!! json_encode($admissionsData) !!},
                    borderColor: '#6777ef',
                    backgroundColor: 'rgba(103, 119, 239, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Fee Collection Chart
        var ctxFee = document.getElementById('feeCollectionChart').getContext('2d');
        new Chart(ctxFee, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartMonths) !!},
                datasets: [{
                    label: 'Collected ($)',
                    data: {!! json_encode($feeData) !!},
                    backgroundColor: '#47c363',
                    borderRadius: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Attendance Overview Chart
        var ctxAttendance = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctxAttendance, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent', 'Leave'],
                datasets: [{
                    data: [{{ $attendancePresent }}, {{ $attendanceAbsent }}, {{ $attendanceLeave }}],
                    backgroundColor: ['#47c363', '#fc544b', '#ffa426'],
                    borderWidth: 0
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>
@endpush
