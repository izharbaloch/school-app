<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Students</h4>
                    </div>
                    <div class="card-body">
                        {{ $reportStats['total'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Present</h4>
                    </div>
                    <div class="card-body">
                        {{ $reportStats['present'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Absent</h4>
                    </div>
                    <div class="card-body">
                        {{ $reportStats['absent'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Percentage</h4>
                    </div>
                    <div class="card-body">
                        {{ $reportStats['percentage'] }}%
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Attendance Records</h4>
            <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Mark Attendance
            </a>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <label>Date</label>
                    <input type="date" wire:model.live="attendance_date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Class</label>
                    <select wire:model.live="student_class_id" class="form-control">
                        <option value="">Select Class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Section</label>
                    <select wire:model.live="section_id" class="form-control" @disabled(!$student_class_id)>
                        <option value="">Select Section</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" wire:click="resetFilters" class="btn btn-secondary">
                        Reset
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-md">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Total</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Leave</th>
                            <th>Late</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr>
                                <td>{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d-m-Y') }}</td>
                                <td>{{ $attendance->studentClass->name ?? '-' }}</td>
                                <td>{{ $attendance->section->name ?? '-' }}</td>
                                <td>{{ $attendance->total_students }}</td>
                                <td>{{ $attendance->present_count }}</td>
                                <td>{{ $attendance->absent_count }}</td>
                                <td>{{ $attendance->leave_count }}</td>
                                <td>{{ $attendance->late_count }}</td>
                                <td>
                                    <a href="{{ route('attendances.show', $attendance->id) }}"
                                        class="btn btn-sm btn-info">
                                        View
                                    </a>

                                    <a href="{{ route('attendances.edit', $attendance->id) }}"
                                        class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <button type="button" wire:click="confirmDelete({{ $attendance->id }})"
                                        class="btn btn-sm btn-danger"
                                        onclick="confirm('Are you sure you want to delete this attendance?') || event.stopImmediatePropagation()">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No attendance record found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $attendances->links() }}
            </div>
        </div>
    </div>

    @if ($deleteId)
        <div wire:ignore.self></div>
    @endif
</div>
