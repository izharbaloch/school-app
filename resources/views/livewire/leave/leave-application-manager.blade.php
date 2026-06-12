<div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

{{-- Status Summary Chips --}}
<div class="d-flex flex-wrap gap-2 mb-3">
    <button wire:click="$set('filter_status', '')"
            class="btn btn-sm {{ $filter_status === '' ? 'btn-dark' : 'btn-outline-dark' }}">
        All
        <span class="badge badge-light ml-1">{{ array_sum($statusCounts->toArray()) }}</span>
    </button>
    <button wire:click="$set('filter_status', 'pending')"
            class="btn btn-sm {{ $filter_status === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
        Pending
        <span class="badge badge-light ml-1">{{ $statusCounts->get('pending', 0) }}</span>
    </button>
    <button wire:click="$set('filter_status', 'approved')"
            class="btn btn-sm {{ $filter_status === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
        Approved
        <span class="badge badge-light ml-1">{{ $statusCounts->get('approved', 0) }}</span>
    </button>
    <button wire:click="$set('filter_status', 'rejected')"
            class="btn btn-sm {{ $filter_status === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
        Rejected
        <span class="badge badge-light ml-1">{{ $statusCounts->get('rejected', 0) }}</span>
    </button>
</div>

{{-- Filters --}}
<div class="row mb-3">
    <div class="col-md-4">
        <select wire:model="filter_type" class="form-control form-control-sm">
            <option value="">-- All Leave Types --</option>
            @foreach ($allTypes as $t)
                <option value="{{ $t->id }}">{{ $t->name }}</option>
            @endforeach
        </select>
    </div>
    @if ($isApprover)
    <div class="col-md-3">
        <select wire:model="filter_scope" class="form-control form-control-sm">
            <option value="">-- All Applicants --</option>
            <option value="staff">Staff Only</option>
            <option value="student">Students Only</option>
        </select>
    </div>
    @endif
    @if ($canApply)
    <div class="col-md-auto ml-auto">
        <button wire:click="openForm" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Apply for Leave
        </button>
    </div>
    @endif
</div>

{{-- Apply / Edit Form --}}
@if ($showForm)
<div class="card shadow-sm border-primary mb-4">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">{{ $editId ? 'Edit Application' : 'Apply for Leave' }}</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Leave Type <span class="text-danger">*</span></label>
                    <select wire:model="leave_type_id" class="form-control @error('leave_type_id') is-invalid @enderror">
                        <option value="">-- Select Leave Type --</option>
                        @foreach ($leaveTypes as $lt)
                            <option value="{{ $lt->id }}">
                                {{ $lt->name }}
                                @if ($lt->max_days_per_year > 0) (Max {{ $lt->max_days_per_year }}d/yr) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('leave_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>From Date <span class="text-danger">*</span></label>
                    <input type="date" wire:model="from_date" class="form-control @error('from_date') is-invalid @enderror">
                    @error('from_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>To Date <span class="text-danger">*</span></label>
                    <input type="date" wire:model="to_date" class="form-control @error('to_date') is-invalid @enderror">
                    @error('to_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Working Days</label>
                    <div class="form-control bg-light text-center font-weight-bold {{ $total_days > 0 ? 'text-primary' : 'text-muted' }}">
                        {{ $total_days > 0 ? $total_days : '—' }}
                    </div>
                    <small class="text-muted">Excludes weekends</small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Reason <span class="text-danger">*</span></label>
            <textarea wire:model.defer="reason" rows="3"
                      class="form-control @error('reason') is-invalid @enderror"
                      placeholder="Please describe the reason for your leave request (min 10 characters)..."></textarea>
            @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="d-flex gap-2">
            @if ($editId)
                <button wire:click="update" class="btn btn-primary btn-sm">Update Application</button>
            @else
                <button wire:click="save" class="btn btn-success btn-sm">Submit Application</button>
            @endif
            <button wire:click="cancel" class="btn btn-secondary btn-sm">Cancel</button>
        </div>
    </div>
</div>
@endif

{{-- Applications Table --}}
<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover mb-0">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                @if ($isApprover)<th>Applicant</th>@endif
                <th>Leave Type</th>
                <th>From</th>
                <th>To</th>
                <th class="text-center">Days</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Reviewed By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($applications as $app)
            <tr>
                <td>{{ $loop->iteration + ($applications->currentPage() - 1) * $applications->perPage() }}</td>
                @if ($isApprover)
                <td>
                    <div class="font-weight-medium">{{ $app->applicant_name }}</div>
                    <small class="badge badge-{{ $app->applicant_type === 'staff' ? 'info' : 'secondary' }}">
                        {{ ucfirst($app->applicant_type) }}
                    </small>
                </td>
                @endif
                <td>{{ $app->leaveType->name ?? '—' }}</td>
                <td>{{ $app->from_date->format('d M Y') }}</td>
                <td>{{ $app->to_date->format('d M Y') }}</td>
                <td class="text-center"><span class="badge badge-light border">{{ $app->total_days }}</span></td>
                <td>
                    <span title="{{ $app->reason }}">{{ Str::limit($app->reason, 50) }}</span>
                    @if ($app->rejection_reason)
                        <div class="text-danger small">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ Str::limit($app->rejection_reason, 50) }}
                        </div>
                    @endif
                </td>
                <td>
                    <span class="badge badge-{{ $app->status_badge }}">
                        {{ ucfirst($app->status) }}
                    </span>
                </td>
                <td>
                    @if ($app->reviewer)
                        <div class="small">{{ $app->reviewer->name }}</div>
                        <div class="text-muted" style="font-size:11px">{{ $app->reviewed_at?->format('d M Y') }}</div>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td class="text-nowrap">
                    {{-- Approver actions --}}
                    @if ($isApprover && $app->status === 'pending')
                        <button wire:click="approve({{ $app->id }})"
                                wire:confirm="Approve this leave application?"
                                class="btn btn-sm btn-success" title="Approve">
                            <i class="fas fa-check"></i>
                        </button>
                        <button wire:click="reject({{ $app->id }}, 'Rejected by administrator.')"
                                wire:confirm="Reject this leave application?"
                                class="btn btn-sm btn-danger" title="Reject">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif

                    {{-- Own pending application --}}
                    @if ($app->status === 'pending')
                        <button wire:click="edit({{ $app->id }})" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="withdraw({{ $app->id }})"
                                wire:confirm="Withdraw this application? It will be permanently deleted."
                                class="btn btn-sm btn-outline-secondary" title="Withdraw">
                            <i class="fas fa-undo"></i>
                        </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $isApprover ? 10 : 9 }}" class="text-center text-muted py-4">
                    No leave applications found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $applications->links() }}</div>
</div>{{-- end single root --}}
