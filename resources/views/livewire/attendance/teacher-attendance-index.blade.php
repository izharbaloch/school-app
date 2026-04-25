<div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Teachers</h4>
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
        <div class="card-header">
            <h4>Teacher Attendance Records</h4>
        </div>
        <div class="card-body">
            <div class="form-group row mb-4">
                <div class="col-md-3">
                    <label>Attendance Date</label>
                    <input type="date" class="form-control @error('attendance_date') is-invalid @enderror"
                        wire:model.live="attendance_date">
                    @error('attendance_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label>Teacher</label>
                    <select class="form-control @error('teacher_id') is-invalid @enderror"
                        wire:model.live="teacher_id">
                        <option value="">All Teachers</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-secondary" wire:click="resetFilters">
                        <i class="fas fa-redo"></i> Reset Filters
                    </button>
                </div>
            </div>

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Total Teachers</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Leave</th>
                            <th>Late</th>
                            <th>Taken By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr>
                                <td>
                                    <a href="{{ route('teacher-attendances.show', $attendance->id) }}" class="text-primary">
                                        {{ $attendance->attendance_date->format('d M Y') }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $attendance->total_teachers }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $attendance->present_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{ $attendance->absent_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ $attendance->leave_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $attendance->late_count }}</span>
                                </td>
                                <td>
                                    {{ $attendance->takenBy?->name ?? 'N/A' }}
                                </td>
                                <td>
                                    <a href="{{ route('teacher-attendances.edit', $attendance->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="confirmDelete({{ $attendance->id }})" class="btn btn-sm btn-danger"
                                        @if ($deleteId === $attendance->id) @endif>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-3">
                                    <p class="text-muted">No teacher attendance records found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <a href="{{ route('teacher-attendances.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Mark New Attendance
                    </a>
                </div>
                <div>
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>

    @if ($deleteId)
        <div class="modal fade show d-block" style="background-color: rgba(0, 0, 0, 0.5);" tabindex="-1" role="dialog"
            aria-modal="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" wire:click="$set('deleteId', null)"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this attendance record? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('deleteId', null)">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="delete()">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
