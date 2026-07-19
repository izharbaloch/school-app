<div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

{{-- Status chips --}}
<div class="d-flex flex-wrap gap-2 mb-3">
    <button wire:click="$set('filter_status', '')"
            class="btn btn-sm {{ $filter_status === '' ? 'btn-dark' : 'btn-outline-dark' }}">
        All <span class="badge badge-light ml-1">{{ array_sum($statusCounts->toArray()) }}</span>
    </button>
    <button wire:click="$set('filter_status', 'open')"
            class="btn btn-sm {{ $filter_status === 'open' ? 'btn-primary' : 'btn-outline-primary' }}">
        Open <span class="badge badge-light ml-1">{{ $statusCounts->get('open', 0) }}</span>
    </button>
    <button wire:click="$set('filter_status', 'resolved')"
            class="btn btn-sm {{ $filter_status === 'resolved' ? 'btn-success' : 'btn-outline-success' }}">
        Resolved <span class="badge badge-light ml-1">{{ $statusCounts->get('resolved', 0) }}</span>
    </button>
    <button wire:click="$set('filter_status', 'closed')"
            class="btn btn-sm {{ $filter_status === 'closed' ? 'btn-dark' : 'btn-outline-secondary' }}">
        Closed <span class="badge badge-light ml-1">{{ $statusCounts->get('closed', 0) }}</span>
    </button>
</div>

{{-- Filters --}}
<div class="row mb-3 align-items-end">
    <div class="col-md-3">
        <select wire:model="filter_class" class="form-control form-control-sm">
            <option value="">-- All Classes --</option>
            @foreach ($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select wire:model="filter_type" class="form-control form-control-sm">
            <option value="">-- All Types --</option>
            <option value="warning">Warning</option>
            <option value="detention">Detention</option>
            <option value="suspension">Suspension</option>
            <option value="expulsion">Expulsion</option>
            <option value="misconduct">Misconduct</option>
            <option value="other">Other</option>
        </select>
    </div>
    <div class="col-md-2">
        <select wire:model="filter_severity" class="form-control form-control-sm">
            <option value="">-- All Severity --</option>
            <option value="minor">Minor</option>
            <option value="moderate">Moderate</option>
            <option value="severe">Severe</option>
        </select>
    </div>
    @if ($canCreate)
    <div class="col-md-auto ml-auto">
        <button wire:click="openForm" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Record Incident
        </button>
    </div>
    @endif
</div>

{{-- Form --}}
@if ($showForm)
<div class="card shadow-sm border-primary mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">{{ $editId ? 'Edit Incident' : 'Record New Incident' }}</h6>
        <button wire:click="cancel" class="btn btn-sm btn-light py-0">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Student <span class="text-danger">*</span></label>
                    <select wire:model="student_id" class="form-control @error('student_id') is-invalid @enderror">
                        <option value="">-- Select Student --</option>
                        @foreach ($students as $s)
                            <option value="{{ $s->id }}">
                                {{ $s->full_name }}
                                @if ($s->studentClass) — {{ $s->studentClass->name }}{{ $s->section ? ' / '.$s->section->name : '' }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" wire:model.defer="title"
                           class="form-control @error('title') is-invalid @enderror"
                           placeholder="Brief title of the incident">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Type <span class="text-danger">*</span></label>
                    <select wire:model="incident_type" class="form-control @error('incident_type') is-invalid @enderror">
                        <option value="warning">Warning</option>
                        <option value="detention">Detention</option>
                        <option value="suspension">Suspension</option>
                        <option value="expulsion">Expulsion</option>
                        <option value="misconduct">Misconduct</option>
                        <option value="other">Other</option>
                    </select>
                    @error('incident_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Severity <span class="text-danger">*</span></label>
                    <select wire:model.defer="severity" class="form-control @error('severity') is-invalid @enderror">
                        <option value="minor">Minor</option>
                        <option value="moderate">Moderate</option>
                        <option value="severe">Severe</option>
                    </select>
                    @error('severity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Incident Date <span class="text-danger">*</span></label>
                    <input type="date" wire:model.defer="incident_date"
                           class="form-control @error('incident_date') is-invalid @enderror">
                    @error('incident_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Status</label>
                    <select wire:model.defer="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="open">Open</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            @if ($incident_type === 'suspension')
            <div class="col-md-3">
                <div class="form-group">
                    <label>Suspension From <span class="text-danger">*</span></label>
                    <input type="date" wire:model.defer="suspension_from"
                           class="form-control @error('suspension_from') is-invalid @enderror">
                    @error('suspension_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Suspension To <span class="text-danger">*</span></label>
                    <input type="date" wire:model.defer="suspension_to"
                           class="form-control @error('suspension_to') is-invalid @enderror">
                    @error('suspension_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            @endif
        </div>

        <div class="form-group">
            <label>Description <span class="text-danger">*</span></label>
            <textarea wire:model.defer="description" rows="3"
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Detailed description of the incident..."></textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Action Taken</label>
            <textarea wire:model.defer="action_taken" rows="2"
                      class="form-control"
                      placeholder="What action was taken? (optional)"></textarea>
        </div>

        @if ($status !== 'open')
        <div class="form-group">
            <label>Resolution Notes</label>
            <textarea wire:model.defer="resolution_notes" rows="2"
                      class="form-control"
                      placeholder="How was this resolved?"></textarea>
        </div>
        @endif

        <div class="d-flex gap-2">
            @if ($editId)
                <button wire:click="update" class="btn btn-primary btn-sm" wire:loading.attr="disabled" wire:target="update">
                    <span wire:loading.remove wire:target="update">Update</span>
                    <span wire:loading wire:target="update"><i class="fas fa-spinner fa-spin"></i></span>
                </button>
            @else
                <button wire:click="save" class="btn btn-danger btn-sm" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save"><i class="fas fa-exclamation-triangle"></i> Record Incident</span>
                    <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin"></i> Record Incident</span>
                </button>
            @endif
            <button wire:click="cancel" class="btn btn-secondary btn-sm">Cancel</button>
        </div>
    </div>
</div>
@endif

{{-- Incidents Table --}}
<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover mb-0">
        <thead class="thead-light">
            <tr>
                <th>Date</th>
                <th>Student</th>
                <th>Class</th>
                <th>Title</th>
                <th>Type</th>
                <th>Severity</th>
                <th>Status</th>
                <th>Reported By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($incidents as $inc)
            <tr class="{{ $inc->severity === 'severe' ? 'table-danger' : ($inc->severity === 'moderate' ? 'table-warning' : '') }}">
                <td class="text-nowrap">{{ $inc->incident_date->format('d M Y') }}</td>
                <td>
                    <div class="font-weight-medium">{{ $inc->student->full_name }}</div>
                </td>
                <td class="text-nowrap">
                    {{ $inc->student->studentClass->name ?? '—' }}
                    @if ($inc->student->section) / {{ $inc->student->section->name }} @endif
                </td>
                <td>
                    <div>{{ $inc->title }}</div>
                    @if ($inc->incident_type === 'suspension' && $inc->suspension_from)
                        <small class="text-danger">
                            <i class="fas fa-ban"></i>
                            {{ $inc->suspension_from->format('d M') }} – {{ $inc->suspension_to->format('d M Y') }}
                            ({{ $inc->suspension_days }}d)
                        </small>
                    @endif
                </td>
                <td><span class="badge badge-info">{{ $inc->type_label }}</span></td>
                <td>
                    <span class="badge badge-{{ $inc->severity_badge }}">
                        {{ ucfirst($inc->severity) }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $inc->status_badge }}">
                        {{ ucfirst($inc->status) }}
                    </span>
                </td>
                <td>
                    <div class="small">{{ $inc->reporter->name ?? '—' }}</div>
                </td>
                <td class="text-nowrap">
                    @if ($canEdit)
                    <button wire:click="edit({{ $inc->id }})" class="btn btn-sm btn-outline-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    @endif
                    @if ($canDelete)
                    <button wire:click="delete({{ $inc->id }})"
                            wire:confirm="Delete this incident record? This cannot be undone."
                            wire:loading.attr="disabled" wire:target="delete"
                            class="btn btn-sm btn-outline-danger" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted py-4">No incidents recorded.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $incidents->links() }}</div>
</div>
