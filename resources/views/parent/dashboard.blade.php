@extends('layouts.app')
@section('title', 'Parent Portal')

@section('content')
<div class="section-header">
    <h1><i class="fas fa-users text-success"></i> Parent Portal</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Home</a></div>
        <div class="breadcrumb-item">Parent Dashboard</div>
    </div>
</div>

<div class="section-body">
    @if(!$guardian)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            No guardian profile is linked to your account. Please contact the school administration.
        </div>
    @else
        {{-- Notices --}}
        @if($notices->count())
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bullhorn text-warning"></i> Notices & Announcements</h6>
            </div>
            <div class="card-body py-2">
                @foreach($notices->take(3) as $notice)
                <div class="d-flex align-items-start mb-2">
                    @if($notice->is_pinned)<span class="badge badge-warning mr-2">Pinned</span>@endif
                    <div>
                        <strong>{{ $notice->title }}</strong>
                        <br><small class="text-muted">{{ $notice->publish_date->format('d M Y') }}</small>
                        <p class="mb-0 small">{{ Str::limit($notice->content, 120) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Children Cards --}}
        @foreach($childData as $data)
        @php $student = $data['student']; @endphp
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user-graduate text-primary mr-2"></i>
                    {{ $student->name }}
                    <small class="text-muted ml-2">
                        {{ $student->studentClass->name ?? '' }}
                        @if($student->section) / {{ $student->section->name }} @endif
                    </small>
                </h5>
                <span class="badge badge-info">Roll # {{ $student->roll_no ?? 'N/A' }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Attendance Summary --}}
                    <div class="col-md-4">
                        <div class="card border-left-{{ $data['attendancePct'] >= 75 ? 'success' : 'danger' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">Attendance</div>
                                        <div class="h4 mb-0">{{ $data['attendancePct'] }}%</div>
                                        <small class="text-muted">{{ $data['presentDays'] }} / {{ $data['totalDays'] }} days</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-{{ $data['attendancePct'] >= 75 ? 'success' : 'danger' }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pending Fees --}}
                    <div class="col-md-4">
                        <div class="card border-left-{{ $data['pendingFees'] > 0 ? 'warning' : 'success' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Fees</div>
                                        <div class="h4 mb-0 {{ $data['pendingFees'] > 0 ? 'text-danger' : 'text-success' }}">
                                            Rs. {{ number_format($data['pendingFees'], 0) }}
                                        </div>
                                        <small class="text-muted">
                                            @if($data['pendingFees'] > 0)
                                                Fee outstanding
                                            @else
                                                All fees cleared
                                            @endif
                                        </small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-rupee-sign fa-2x text-{{ $data['pendingFees'] > 0 ? 'warning' : 'success' }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Attendance --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body py-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-2">Last 7 Days Attendance</div>
                                @foreach($data['recentAttendance'] as $att)
                                <span class="badge badge-{{ $att->status === 'present' ? 'success' : ($att->status === 'late' ? 'warning' : 'danger') }} mr-1 mb-1"
                                      title="{{ $att->date->format('d M') }}">
                                    {{ $att->date->format('d') }}
                                    <small>{{ ucfirst(substr($att->status,0,1)) }}</small>
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Links --}}
                <div class="mt-3">
                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-primary mr-2">
                        <i class="fas fa-eye"></i> View Full Profile
                    </a>
                    <a href="{{ route('certificates.id-card', $student->id) }}" class="btn btn-sm btn-outline-secondary mr-2">
                        <i class="fas fa-id-card"></i> ID Card
                    </a>
                    <a href="{{ route('results.index') }}?student={{ $student->id }}" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-chart-bar"></i> Results
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
