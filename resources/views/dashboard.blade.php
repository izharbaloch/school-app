@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
    <style>
        /* ══════════════════════════════════════════
                   DASHBOARD — Professional Redesign
                ══════════════════════════════════════════ */

        /* Page background */
        .section-body {
            background: #f4f6fc;
        }

        /* ── Welcome banner ────────────────────── */
        .dash-welcome {
            background: linear-gradient(135deg, #3a5bd9 0%, #1a2d80 100%);
            border-radius: 14px;
            color: #fff;
            padding: 1.4rem 2rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
            box-shadow: 0 4px 20px rgba(58, 91, 217, .3);
        }

        .dash-welcome h2 {
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
        }

        .dash-welcome p {
            font-size: .82rem;
            opacity: .8;
            margin: .15rem 0 0;
        }

        .dash-welcome-meta {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .dash-welcome-meta .wm-item {
            text-align: center;
        }

        .dash-welcome-meta .wm-item .wm-num {
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1;
        }

        .dash-welcome-meta .wm-item .wm-lbl {
            font-size: .7rem;
            opacity: .75;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        /* ── KPI cards (4 per row) ─────────────── */
        .kpi-card {
            background: #fff;
            border: none;
            border-radius: 14px;
            padding: 1.4rem 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            transition: transform .2s, box-shadow .2s;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px rgba(0, 0, 0, .1);
        }

        .kpi-top {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .kpi-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .ki-blue {
            background: #eef2ff;
            color: #4e73df;
        }

        .ki-teal {
            background: #e6f9fc;
            color: #20c9d0;
        }

        .ki-green {
            background: #eafaf4;
            color: #1cc88a;
        }

        .ki-red {
            background: #fdecea;
            color: #e74a3b;
        }

        .ki-orange {
            background: #fef9ec;
            color: #f6a623;
        }

        .ki-purple {
            background: #f2eeff;
            color: #7c3aed;
        }

        .kpi-label {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #9e9e9e;
        }

        .kpi-value {
            font-size: 2.1rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.05;
            margin-top: .15rem;
        }

        .kpi-value small {
            font-size: 1rem;
            font-weight: 600;
            color: #64748b;
        }

        .kpi-sub {
            font-size: .76rem;
            color: #94a3b8;
            margin-top: .3rem;
        }

        .kpi-footer {
            margin-top: .9rem;
            padding-top: .75rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: .76rem;
        }

        .kpi-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: .7rem;
        }

        .kpi-badge.up {
            background: #eafaf4;
            color: #1cc88a;
        }

        .kpi-badge.warn {
            background: #fef9ec;
            color: #d97706;
        }

        .kpi-badge.down {
            background: #fdecea;
            color: #e74a3b;
        }

        /* ── Section headings ──────────────────── */
        .dash-heading {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: #94a3b8;
            margin: 1.75rem 0 .9rem;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .dash-heading::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── Cards ─────────────────────────────── */
        .dash-card {
            background: #fff;
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            height: 100%;
            overflow: hidden;
        }

        .dash-card .dc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem .75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .dash-card .dc-title {
            font-size: .9rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: .5rem;
            margin: 0;
        }

        .dash-card .dc-title i {
            font-size: .85rem;
        }

        .dash-card .dc-body {
            padding: 1.1rem 1.25rem;
        }

        .dash-card .dc-body-flush {
            padding: 0;
        }

        /* ── Chart containers ───────────────────── */
        .chart-wrap {
            position: relative;
            height: 220px;
        }

        .chart-wrap-md {
            position: relative;
            height: 190px;
        }

        /* ── Table styling ──────────────────────── */
        .dash-table {
            font-size: .82rem;
            margin: 0;
        }

        .dash-table th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #94a3b8;
            font-weight: 600;
            padding: .65rem 1rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            border-top: none;
        }

        .dash-table td {
            padding: .6rem 1rem;
            vertical-align: middle;
            border-color: #f1f5f9;
            color: #334155;
        }

        .dash-table tbody tr:hover {
            background: #f8fafc;
        }

        .dash-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* ── List items ─────────────────────────── */
        .dl-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .dl-item:last-child {
            border-bottom: none;
        }

        .dl-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .dl-name {
            font-size: .84rem;
            font-weight: 600;
            color: #1e293b;
        }

        .dl-meta {
            font-size: .72rem;
            color: #94a3b8;
            margin-top: 1px;
        }

        .dl-right {
            margin-left: auto;
            text-align: right;
            flex-shrink: 0;
        }

        .dl-amount {
            font-size: .84rem;
            font-weight: 700;
            color: #1e293b;
        }

        .dl-date {
            font-size: .7rem;
            color: #94a3b8;
        }

        /* ── Calendar pill ──────────────────────── */
        .cal-pill {
            min-width: 46px;
            text-align: center;
            flex-shrink: 0;
            background: #eef2ff;
            border-radius: 10px;
            padding: 6px 8px;
            line-height: 1.1;
        }

        .cal-pill .cp-day {
            font-size: 1.1rem;
            font-weight: 800;
            color: #3a5bd9;
        }

        .cal-pill .cp-month {
            font-size: .6rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .cal-pill.cp-green {
            background: #eafaf4;
        }

        .cal-pill.cp-green .cp-day {
            color: #059669;
        }

        /* ── Doughnut attendance ────────────────── */
        .att-donut-wrap {
            position: relative;
            height: 170px;
        }

        .att-stat-row {
            display: flex;
            justify-content: space-around;
            margin-top: .75rem;
        }

        .att-stat {
            text-align: center;
        }

        .att-stat .as-num {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }

        .att-stat .as-lbl {
            font-size: .65rem;
            color: #94a3b8;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* ── Upcoming items ─────────────────────── */
        .up-item {
            display: flex;
            gap: .75rem;
            padding: .65rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            align-items: flex-start;
        }

        .up-item:last-child {
            border-bottom: none;
        }

        /* ── Notice items ───────────────────────── */
        .nt-item {
            display: flex;
            gap: .75rem;
            padding: .65rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            align-items: flex-start;
        }

        .nt-item:last-child {
            border-bottom: none;
        }

        .nt-pin {
            color: #ef4444;
            font-size: .7rem;
            margin-top: 3px;
        }

        /* ── Activity timeline ───────────────────── */
        .act-item {
            display: flex;
            gap: .75rem;
            padding: .6rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            align-items: center;
        }

        .act-item:last-child {
            border-bottom: none;
        }

        .act-dot {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .7rem;
        }
    </style>
@endpush

@section('content')
    <div class="section-header">
        <h1>Dashboard</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        </div>
    </div>

    <div class="section-body">

        {{-- ══ WELCOME BANNER ══ --}}
        <div class="dash-welcome">
            <div>
                <h2><i class="fas fa-school mr-2" style="opacity:.8"></i> School ERP Dashboard</h2>
                <p>{{ now()->format('l, d F Y') }} &nbsp;·&nbsp; Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <div class="dash-welcome-meta">
                <div class="wm-item">
                    <div class="wm-num">{{ $totalStudents }}</div>
                    <div class="wm-lbl">Students</div>
                </div>
                <div class="wm-item">
                    <div class="wm-num">{{ $totalTeachers }}</div>
                    <div class="wm-lbl">Teachers</div>
                </div>
                <div class="wm-item">
                    <div class="wm-num">{{ $attendancePct }}%</div>
                    <div class="wm-lbl">Attendance</div>
                </div>
                <div class="wm-item">
                    <div class="wm-num">{{ $newAdmissionsThisMonth }}</div>
                    <div class="wm-lbl">Admissions</div>
                </div>
            </div>
        </div>

        {{-- ══ ROW 1: 4 KPI CARDS ══ --}}
        <div class="row mb-1">

            {{-- Students --}}
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="kpi-card">
                    <div class="kpi-top">
                        <div class="kpi-icon ki-blue">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Total Students</div>
                            <div class="kpi-value">{{ number_format($totalStudents) }}</div>
                            <div class="kpi-sub">
                                <span style="color:#3b82f6">{{ $maleStudents }} Male</span>
                                &middot;
                                <span style="color:#ec4899">{{ $femaleStudents }} Female</span>
                            </div>
                        </div>
                    </div>
                    <div class="kpi-footer">
                        <span class="text-muted">{{ $activeStudents }} active</span>
                        <span class="kpi-badge up"><i class="fas fa-arrow-up mr-1"></i>{{ $newAdmissionsThisMonth }} this
                            month</span>
                    </div>
                </div>
            </div>

            {{-- Teachers --}}
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="kpi-card">
                    <div class="kpi-top">
                        <div class="kpi-icon ki-teal">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Teachers &amp; Staff</div>
                            <div class="kpi-value">{{ number_format($totalTeachers) }}</div>
                            <div class="kpi-sub">{{ $activeTeachers }} active &middot; {{ $totalClasses }} classes</div>
                        </div>
                    </div>
                    <div class="kpi-footer">
                        <span class="text-muted">Across all departments</span>
                        <span class="kpi-badge up">Active</span>
                    </div>
                </div>
            </div>

            {{-- Fee Collected --}}
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="kpi-card">
                    <div class="kpi-top">
                        <div class="kpi-icon ki-green">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Fee Collected</div>
                            <div class="kpi-value"><small>Rs.</small> {{ number_format($thisMonthFee) }}</div>
                            <div class="kpi-sub">This month</div>
                        </div>
                    </div>
                    <div class="kpi-footer">
                        <span class="text-muted">Total: Rs. {{ number_format($totalFeeCollected) }}</span>
                        <span class="kpi-badge up"><i class="fas fa-check mr-1"></i>Collected</span>
                    </div>
                </div>
            </div>

            {{-- Pending / Defaulters --}}
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="kpi-card">
                    <div class="kpi-top">
                        <div class="kpi-icon ki-red">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Pending Fees</div>
                            <div class="kpi-value"><small>Rs.</small> {{ number_format($pendingFees) }}</div>
                            <div class="kpi-sub">Overdue amount</div>
                        </div>
                    </div>
                    <div class="kpi-footer">
                        <span class="text-muted">{{ $feeDefaultersCount }}
                            defaulter{{ $feeDefaultersCount != 1 ? 's' : '' }}</span>
                        @if ($feeDefaultersCount > 0)
                            <span class="kpi-badge down"><i class="fas fa-clock mr-1"></i>Overdue</span>
                        @else
                            <span class="kpi-badge up">All clear</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ══ ROW 1B: NEW MODULE QUICK-STATS ══ --}}
        @if (
            !is_null($pendingLeavesCount) ||
                !is_null($openIncidentsCount) ||
                !is_null($hostelOccupied) ||
                !is_null($activeSportsMembers))
            <div class="row mb-1">
                @if (!is_null($pendingLeavesCount))
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                        <div class="kpi-card">
                            <div class="kpi-top">
                                <div class="kpi-icon ki-orange"><i class="fas fa-calendar-times"></i></div>
                                <div>
                                    <div class="kpi-label">Pending Leaves</div>
                                    <div class="kpi-value">{{ $pendingLeavesCount }}</div>
                                    <div class="kpi-sub">Awaiting approval</div>
                                </div>
                            </div>
                            <div class="kpi-footer">
                                @can('leaves.view')
                                    <a href="{{ route('leaves.index') }}" class="text-muted" style="font-size:.75rem">View
                                        All</a>
                                @endcan
                                @if ($pendingLeavesCount > 0)
                                    <span class="kpi-badge warn"><i class="fas fa-clock mr-1"></i>Pending</span>
                                @else
                                    <span class="kpi-badge up">All clear</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if (!is_null($openIncidentsCount))
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                        <div class="kpi-card">
                            <div class="kpi-top">
                                <div class="kpi-icon ki-red"><i class="fas fa-gavel"></i></div>
                                <div>
                                    <div class="kpi-label">Open Incidents</div>
                                    <div class="kpi-value">{{ $openIncidentsCount }}</div>
                                    <div class="kpi-sub">Conduct &amp; discipline</div>
                                </div>
                            </div>
                            <div class="kpi-footer">
                                @can('conduct.view')
                                    <a href="{{ route('conduct.index') }}" class="text-muted" style="font-size:.75rem">View
                                        All</a>
                                @endcan
                                @if ($openIncidentsCount > 0)
                                    <span class="kpi-badge down"><i
                                            class="fas fa-exclamation-triangle mr-1"></i>Open</span>
                                @else
                                    <span class="kpi-badge up">All resolved</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if (!is_null($hostelOccupied))
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                        <div class="kpi-card">
                            <div class="kpi-top">
                                <div class="kpi-icon ki-purple"><i class="fas fa-building"></i></div>
                                <div>
                                    <div class="kpi-label">Hostel Residents</div>
                                    <div class="kpi-value">{{ $hostelOccupied }}</div>
                                    <div class="kpi-sub">Active allocations</div>
                                </div>
                            </div>
                            <div class="kpi-footer">
                                @can('hostel.view')
                                    <a href="{{ route('hostel.index') }}" class="text-muted"
                                        style="font-size:.75rem">Manage</a>
                                @endcan
                                <span class="kpi-badge up">Active</span>
                            </div>
                        </div>
                    </div>
                @endif

                @if (!is_null($activeSportsMembers))
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                        <div class="kpi-card">
                            <div class="kpi-top">
                                <div class="kpi-icon ki-teal"><i class="fas fa-futbol"></i></div>
                                <div>
                                    <div class="kpi-label">Sports Enrollments</div>
                                    <div class="kpi-value">{{ $activeSportsMembers }}</div>
                                    <div class="kpi-sub">Active memberships</div>
                                </div>
                            </div>
                            <div class="kpi-footer">
                                @can('sports.view')
                                    <a href="{{ route('sports.index') }}" class="text-muted"
                                        style="font-size:.75rem">Manage</a>
                                @endcan
                                <span class="kpi-badge up">Active</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- ══ ROW 2: CHARTS ══ --}}
        <p class="dash-heading"><i class="fas fa-chart-area"></i> Analytics</p>

        <div class="row mb-1">

            {{-- Monthly Admissions --}}
            <div class="col-xl-6 col-lg-6 mb-3">
                <div class="dash-card">
                    <div class="dc-header">
                        <h5 class="dc-title"><i class="fas fa-user-plus text-primary"></i> Monthly Admissions</h5>
                    </div>
                    <div class="dc-body">
                        <div class="chart-wrap"><canvas id="admissionsChart"></canvas></div>
                    </div>
                </div>
            </div>

            {{-- Fee Collection --}}
            <div class="col-xl-6 col-lg-6 mb-3">
                <div class="dash-card">
                    <div class="dc-header">
                        <h5 class="dc-title"><i class="fas fa-coins text-success"></i> Fee Collection (Rs.)</h5>
                    </div>
                    <div class="dc-body">
                        <div class="chart-wrap"><canvas id="feeChart"></canvas></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══ ROW 3: STUDENTS BY CLASS + RECENT ADMISSIONS ══ --}}
        <p class="dash-heading"><i class="fas fa-users"></i> Students</p>

        <div class="row mb-1">

            <div class="col-xl-6 col-lg-6 mb-3">
                <div class="dash-card">
                    <div class="dc-header">
                        <h5 class="dc-title"><i class="fas fa-layer-group text-primary"></i> Students by Class</h5>
                    </div>
                    <div class="dc-body">
                        <div class="chart-wrap"><canvas id="classBarsChart"></canvas></div>
                    </div>
                </div>
            </div>

            {{-- Today's Attendance Doughnut --}}
            <div class="col-xl-6 col-lg-6 mb-3">
                <div class="dash-card">
                    <div class="dc-header">
                        <h5 class="dc-title"><i class="fas fa-calendar-check text-info"></i> Today's Attendance</h5>
                    </div>
                    <div class="dc-body">
                        <div class="att-donut-wrap"><canvas id="attendanceChart"></canvas></div>
                        <div class="att-stat-row">
                            <div class="att-stat">
                                <div class="as-num" style="color:#1cc88a">{{ $attendancePresent }}</div>
                                <div class="as-lbl">Present</div>
                            </div>
                            <div class="att-stat">
                                <div class="as-num" style="color:#e74a3b">{{ $attendanceAbsent }}</div>
                                <div class="as-lbl">Absent</div>
                            </div>
                            <div class="att-stat">
                                <div class="as-num" style="color:#f6c23e">{{ $attendanceLeave }}</div>
                                <div class="as-lbl">Leave</div>
                            </div>
                            <div class="att-stat">
                                <div class="as-num" style="color:#4e73df">{{ $attendancePct }}%</div>
                                <div class="as-lbl">Rate</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12">
            <div class="dash-card">
                <div class="dc-header">
                    <h5 class="dc-title"><i class="fas fa-user-plus text-primary"></i> Recent Admissions</h5>
                    @can('student.view')
                        <a href="{{ route('students.index') }}" class="btn btn-sm btn-light" style="font-size:.75rem">View
                            All</a>
                    @endcan
                </div>
                <div class="dc-body-flush">
                    <table class="table dash-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Adm No</th>
                                <th>Class</th>
                                <th>Gender</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAdmissions as $s)
                                <tr>
                                    <td>
                                        <a href="{{ route('students.show', $s) }}" class="font-weight-600 text-dark">
                                            {{ $s->first_name }} {{ $s->last_name }}
                                        </a>
                                    </td>
                                    <td><span class="badge badge-light"
                                            style="font-size:.75rem">{{ $s->admission_no ?? '—' }}</span></td>
                                    <td>{{ $s->studentClass->name ?? '—' }}</td>
                                    <td>
                                        @if ($s->gender == 'male')
                                            <span style="color:#3b82f6;font-size:.8rem"><i
                                                    class="fas fa-mars mr-1"></i>Male</span>
                                        @else
                                            <span style="color:#ec4899;font-size:.8rem"><i
                                                    class="fas fa-venus mr-1"></i>Female</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $s->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No admissions yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══ ROW 4: RECENT PAYMENTS + FEE DEFAULTERS ══ --}}
        <p class="dash-heading"><i class="fas fa-money-bill-wave"></i> Finance</p>

        <div class="row mb-1">

            <div class="col-xl-7 col-lg-6 mb-3">
                <div class="dash-card">
                    <div class="dc-header">
                        <h5 class="dc-title"><i class="fas fa-receipt text-success"></i> Recent Payments</h5>
                        @can('fee.view')
                            <a href="{{ route('student-fees.index') }}" class="btn btn-sm btn-light"
                                style="font-size:.75rem">View All</a>
                        @endcan
                    </div>
                    <div class="dc-body-flush">
                        <table class="table dash-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $pay)
                                    @php $st = $pay->studentFee->student ?? null; @endphp
                                    <tr>
                                        <td class="font-weight-600">
                                            {{ $st ? $st->first_name . ' ' . $st->last_name : '—' }}</td>
                                        <td>{{ $st->studentClass->name ?? '—' }}</td>
                                        <td><span class="text-success font-weight-700">Rs.
                                                {{ number_format($pay->amount) }}</span></td>
                                        <td class="text-muted">{{ $pay->created_at->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No payments yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-lg-6 mb-3">
                <div class="dash-card">
                    <div class="dc-header">
                        <h5 class="dc-title">
                            <i class="fas fa-exclamation-circle text-danger"></i> Fee Defaulters
                            <span class="badge badge-danger ml-1"
                                style="font-size:.7rem">{{ $feeDefaultersCount }}</span>
                        </h5>
                        @can('fee.view')
                            <a href="{{ route('student-fees.index') }}" class="btn btn-sm btn-light"
                                style="font-size:.75rem">View All</a>
                        @endcan
                    </div>
                    @forelse($feeDefaulters as $sf)
                        @php $st = $sf->student; @endphp
                        <div class="dl-item">
                            <div class="dl-avatar ki-red" style="background:#fdecea;color:#e74a3b">
                                {{ strtoupper(substr($st->first_name ?? '?', 0, 1)) }}
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="dl-name text-truncate">
                                    {{ ($st->first_name ?? '') . ' ' . ($st->last_name ?? '') }}</div>
                                <div class="dl-meta">{{ $st->studentClass->name ?? '—' }}</div>
                            </div>
                            <div class="dl-right">
                                <div class="dl-amount text-danger">Rs.
                                    {{ number_format($sf->payable_amount - $sf->paid_amount) }}</div>
                                <div class="dl-date">Due {{ $sf->due_date->format('d M') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="dc-body text-center text-muted py-3">
                            <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                            No defaulters
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ══ ROW 5: UPCOMING EXAMS, EVENTS, NOTICES ══ --}}
        <p class="dash-heading"><i class="fas fa-calendar-alt"></i> Upcoming &amp; Notices</p>

        <div class="row mb-1">

            {{-- Upcoming Exams --}}
            <div class="col-xl-4 col-lg-4 mb-3">
                <div class="dash-card">
                    <div class="dc-header">
                        <h5 class="dc-title"><i class="fas fa-file-alt text-primary"></i> Upcoming Exams</h5>
                        @can('exam.view')
                            <a href="{{ route('exams.index') }}" class="btn btn-sm btn-light"
                                style="font-size:.75rem">All</a>
                        @endcan
                    </div>
                    @forelse($upcomingExams as $exam)
                        <div class="up-item">
                            <div class="cal-pill">
                                <div class="cp-day">{{ $exam->start_date->format('d') }}</div>
                                <div class="cp-month">{{ $exam->start_date->format('M') }}</div>
                            </div>
                            <div>
                                <div class="dl-name">{{ $exam->name }}</div>
                                <div class="dl-meta">{{ $exam->academic_year ?? 'Academic Year' }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="dc-body text-center text-muted py-3">
                            <i class="fas fa-calendar-times fa-2x mb-2 d-block" style="color:#cbd5e1"></i>
                            No upcoming exams
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Upcoming Events --}}
            <div class="col-xl-4 col-lg-4 mb-3">
                <div class="dash-card">
                    <div class="dc-header">
                        <h5 class="dc-title"><i class="fas fa-calendar-star text-success"></i> Upcoming Events</h5>
                        @can('event.view')
                            <a href="{{ route('events.index') }}" class="btn btn-sm btn-light"
                                style="font-size:.75rem">All</a>
                        @endcan
                    </div>
                    @forelse($upcomingEvents as $event)
                        <div class="up-item">
                            <div class="cal-pill cp-green">
                                <div class="cp-day">{{ $event->start_date->format('d') }}</div>
                                <div class="cp-month">{{ $event->start_date->format('M') }}</div>
                            </div>
                            <div>
                                <div class="dl-name">{{ $event->title }}</div>
                                <div class="dl-meta">{{ ucfirst($event->audience ?? 'All') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="dc-body text-center text-muted py-3">
                            <i class="fas fa-calendar-times fa-2x mb-2 d-block" style="color:#cbd5e1"></i>
                            No upcoming events
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Notices --}}
            <div class="col-xl-4 col-lg-4 mb-3">
                <div class="dash-card">
                    <div class="dc-header">
                        <h5 class="dc-title"><i class="fas fa-bullhorn text-warning"></i> Recent Notices</h5>
                        @can('notice.view')
                            <a href="{{ route('notices.index') }}" class="btn btn-sm btn-light"
                                style="font-size:.75rem">All</a>
                        @endcan
                    </div>
                    @forelse($recentNotices as $notice)
                        <div class="nt-item">
                            @if ($notice->is_pinned)
                                <i class="fas fa-thumbtack nt-pin"></i>
                            @else
                                <i class="fas fa-circle" style="font-size:.45rem;color:#cbd5e1;margin-top:6px"></i>
                            @endif
                            <div>
                                <div class="dl-name">{{ $notice->title }}</div>
                                <div class="dl-meta">
                                    {{ \App\Models\Notice::$audiences[$notice->audience] ?? ucfirst($notice->audience) }}
                                    &middot; {{ $notice->publish_date->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="dc-body text-center text-muted py-3">
                            <i class="fas fa-bullhorn fa-2x mb-2 d-block" style="color:#cbd5e1"></i>
                            No active notices
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ══ ROW 6: RECENT ACTIVITIES ══ --}}
        <p class="dash-heading"><i class="fas fa-history"></i> Recent Activities</p>

        <div class="row mb-3">
            <div class="col-12">
                <div class="dash-card">
                    <div class="dc-header">
                        <h5 class="dc-title"><i class="fas fa-history text-secondary"></i> Activity Log</h5>
                        @can('settings.view')
                            <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-light"
                                style="font-size:.75rem">View All</a>
                        @endcan
                    </div>
                    <div class="dc-body-flush">
                        @forelse($recentActivities as $log)
                            @php
                                $actColors = [
                                    'create' => ['bg' => '#eafaf4', 'color' => '#059669', 'icon' => 'fa-plus'],
                                    'update' => ['bg' => '#fef9ec', 'color' => '#d97706', 'icon' => 'fa-pen'],
                                    'delete' => ['bg' => '#fdecea', 'color' => '#e74a3b', 'icon' => 'fa-trash'],
                                    'login' => ['bg' => '#eef2ff', 'color' => '#4e73df', 'icon' => 'fa-sign-in-alt'],
                                    'logout' => ['bg' => '#f1f5f9', 'color' => '#64748b', 'icon' => 'fa-sign-out-alt'],
                                ];
                                $ac = $actColors[$log->action] ?? [
                                    'bg' => '#f1f5f9',
                                    'color' => '#64748b',
                                    'icon' => 'fa-circle',
                                ];
                            @endphp
                            <div class="act-item">
                                <div class="act-dot" style="background:{{ $ac['bg'] }};color:{{ $ac['color'] }}">
                                    <i class="fas {{ $ac['icon'] }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div style="font-size:.83rem;color:#1e293b;font-weight:500">{{ $log->description }}
                                    </div>
                                    <div style="font-size:.71rem;color:#94a3b8">
                                        {{ $log->user->name ?? 'System' }}
                                        @if ($log->model_type)
                                            &middot; {{ class_basename($log->model_type) }}@if ($log->model_id)
                                                #{{ $log->model_id }}
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div style="font-size:.71rem;color:#94a3b8;white-space:nowrap;flex-shrink:0">
                                    {{ $log->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <div class="dc-body text-center text-muted py-4">
                                <i class="fas fa-history fa-2x mb-2 d-block" style="color:#cbd5e1"></i>
                                No activity recorded yet
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /section-body --}}
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        (function() {
            const months = @json($chartMonths);
            const admissions = @json($admissionsData);
            const feeColl = @json($feeCollectedData);
            const attPresent = {{ $attendancePresent }};
            const attAbsent = {{ $attendanceAbsent }};
            const attLeave = {{ $attendanceLeave }};
            const clasNames = @json($studentsByClass->pluck('name'));
            const clasCounts = @json($studentsByClass->pluck('student_count'));

            const gridColor = 'rgba(0,0,0,.04)';
            const tickColor = '#94a3b8';
            const baseAxis = {
                grid: {
                    color: gridColor
                },
                ticks: {
                    color: tickColor,
                    font: {
                        size: 11
                    }
                }
            };

            /* Monthly Admissions — Line */
            new Chart(document.getElementById('admissionsChart'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Admissions',
                        data: admissions,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78,115,223,.08)',
                        tension: .45,
                        fill: true,
                        pointBackgroundColor: '#4e73df',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: baseAxis,
                        y: {
                            ...baseAxis,
                            beginAtZero: true,
                            ticks: {
                                ...baseAxis.ticks,
                                stepSize: 1
                            }
                        }
                    },
                },
            });

            /* Fee Collection — Bar */
            new Chart(document.getElementById('feeChart'), {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Collected (Rs.)',
                        data: feeColl,
                        backgroundColor: 'rgba(28,200,138,.75)',
                        borderColor: '#1cc88a',
                        borderWidth: 0,
                        borderRadius: 6,
                        borderSkipped: false,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: baseAxis,
                        y: {
                            ...baseAxis,
                            beginAtZero: true
                        }
                    },
                },
            });

            /* Attendance — Doughnut */
            new Chart(document.getElementById('attendanceChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Present', 'Absent', 'Leave/Late'],
                    datasets: [{
                        data: [attPresent, attAbsent, attLeave],
                        backgroundColor: ['#1cc88a', '#e74a3b', '#f6c23e'],
                        borderWidth: 0,
                        hoverOffset: 4,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ctx.label + ': ' + ctx.parsed
                            }
                        },
                    },
                },
            });

            /* Students by Class — Horizontal bar */
            new Chart(document.getElementById('classBarsChart'), {
                type: 'bar',
                data: {
                    labels: clasNames,
                    datasets: [{
                        label: 'Students',
                        data: clasCounts,
                        backgroundColor: clasCounts.map((_, i) => ['#4e73df', '#1cc88a', '#36b9cc',
                            '#f6c23e', '#e74a3b', '#6f42c1', '#fd7e14', '#20c997'
                        ][i % 8]),
                        borderRadius: 4,
                        borderSkipped: false,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ...baseAxis,
                            beginAtZero: true,
                            ticks: {
                                ...baseAxis.ticks,
                                stepSize: 1
                            }
                        },
                        y: baseAxis
                    },
                },
            });
        })();
    </script>
@endpush
