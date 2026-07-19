<div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

@if ($selectedActivity)
{{-- ═══ ACTIVITY DETAIL VIEW ═══ --}}
<div class="d-flex align-items-center mb-3">
    <button wire:click="deselectActivity" class="btn btn-sm btn-outline-secondary mr-3">
        <i class="fas fa-arrow-left"></i> Back
    </button>
    <div>
        <h5 class="mb-0">
            <span class="badge badge-{{ $selectedActivity->category_badge }} mr-1">{{ ucfirst($selectedActivity->category) }}</span>
            {{ $selectedActivity->name }}
        </h5>
        <small class="text-muted">
            {{ $selectedActivity->active_enrollments_count }} active member(s)
            @if ($selectedActivity->max_members > 0) / {{ $selectedActivity->max_members }} max @endif
            @if ($selectedActivity->coach_name) · Coach: {{ $selectedActivity->coach_name }} @endif
            @if ($selectedActivity->venue) · {{ $selectedActivity->venue }} @endif
        </small>
    </div>
    @if ($canManage && !$showEnrollForm)
    <button wire:click="openEnrollForm" class="btn btn-sm btn-success ml-auto">
        <i class="fas fa-user-plus"></i> Enroll Student
    </button>
    @endif
</div>

@if ($showEnrollForm)
<div class="card border-success shadow-sm mb-3">
    <div class="card-header bg-success text-white">
        <h6 class="mb-0">{{ $editEnrollId ? 'Edit Enrollment' : 'Enroll Student' }}</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Student <span class="text-danger">*</span></label>
                    <select wire:model.defer="enroll_student_id"
                            class="form-control @error('enroll_student_id') is-invalid @enderror">
                        <option value="">-- Select Student --</option>
                        @foreach ($studentsList as $s)
                            <option value="{{ $s->id }}">
                                {{ $s->full_name }} {{ $s->studentClass ? '('.$s->studentClass->name.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('enroll_student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Role</label>
                    <select wire:model.defer="enroll_role" class="form-control">
                        <option value="member">Member</option>
                        <option value="captain">Captain</option>
                        <option value="coordinator">Coordinator</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Joined Date <span class="text-danger">*</span></label>
                    <input type="date" wire:model.defer="enroll_joined_date"
                           class="form-control @error('enroll_joined_date') is-invalid @enderror">
                    @error('enroll_joined_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Status</label>
                    <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" wire:model.defer="enroll_status" class="custom-control-input" id="enrollStatus">
                        <label class="custom-control-label" for="enrollStatus">Active</label>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Notes</label>
                    <input type="text" wire:model.defer="enroll_notes" class="form-control" placeholder="Optional">
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button wire:click="saveEnroll" type="button" class="btn btn-success btn-sm" wire:loading.attr="disabled" wire:target="saveEnroll">
                <span wire:loading.remove wire:target="saveEnroll">Save</span>
                <span wire:loading wire:target="saveEnroll"><i class="fas fa-spinner fa-spin"></i></span>
            </button>
            <button wire:click="cancelForms" class="btn btn-secondary btn-sm">Cancel</button>
        </div>
    </div>
</div>
@endif

<table class="table table-sm table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th>Student</th><th>Class</th><th>Role</th><th>Joined</th><th>Status</th>
            @if ($canManage)<th></th>@endif
        </tr>
    </thead>
    <tbody>
        @forelse ($enrollments as $e)
        <tr>
            <td class="font-weight-medium">{{ $e->student->full_name }}</td>
            <td>{{ $e->student->studentClass->name ?? '—' }}</td>
            <td><span class="badge badge-{{ $e->role === 'captain' ? 'warning text-dark' : ($e->role === 'coordinator' ? 'info' : 'secondary') }}">{{ ucfirst($e->role) }}</span></td>
            <td class="text-nowrap">{{ $e->joined_date->format('d M Y') }}</td>
            <td>
                @if ($e->status) <span class="badge badge-success">Active</span>
                @else <span class="badge badge-danger">Inactive</span> @endif
            </td>
            @if ($canManage)
            <td class="text-nowrap">
                <button wire:click="editEnroll({{ $e->id }})" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>
                <button wire:click="deleteEnroll({{ $e->id }})" wire:confirm="Remove {{ $e->student->full_name }} from this activity?"
                        wire:loading.attr="disabled" wire:target="deleteEnroll"
                        class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
            </td>
            @endif
        </tr>
        @empty
        <tr><td colspan="{{ $canManage ? 6 : 5 }}" class="text-center text-muted py-3">No students enrolled yet.</td></tr>
        @endforelse
    </tbody>
</table>

@else
{{-- ═══ ACTIVITIES LIST ═══ --}}
<div class="row mb-3 align-items-end">
    <div class="col-md-3">
        <select wire:model="filter_category" class="form-control form-control-sm">
            <option value="">-- All Categories --</option>
            <option value="sport">Sport</option>
            <option value="club">Club</option>
            <option value="art">Art</option>
            <option value="other">Other</option>
        </select>
    </div>
    @if ($canManage && !$showActivityForm)
    <div class="col-md-auto ml-auto">
        <button wire:click="openActivityForm" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Activity
        </button>
    </div>
    @endif
</div>

@if ($showActivityForm)
<div class="card border-primary shadow-sm mb-3">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">{{ $editActivityId ? 'Edit Activity' : 'Add Activity / Club' }}</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" wire:model.defer="name"
                           class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Football Team">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Category</label>
                    <select wire:model.defer="category" class="form-control">
                        <option value="sport">Sport</option>
                        <option value="club">Club</option>
                        <option value="art">Art</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Coach</label>
                    <input type="text" wire:model.defer="coach_name" class="form-control" placeholder="Coach name">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Venue</label>
                    <input type="text" wire:model.defer="venue" class="form-control" placeholder="Venue">
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>Max</label>
                    <input type="number" wire:model.defer="max_members" min="0" class="form-control" placeholder="0">
                    <small class="text-muted" style="font-size:10px">0=∞</small>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>Active</label>
                    <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" wire:model.defer="act_status" class="custom-control-input" id="actStatus">
                        <label class="custom-control-label" for="actStatus"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Schedule</label>
                    <input type="text" wire:model.defer="schedule" class="form-control" placeholder="e.g. Mon/Wed 4-5pm">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" wire:model.defer="description" class="form-control" placeholder="Brief description">
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button wire:click="saveActivity" type="button" class="btn btn-success btn-sm" wire:loading.attr="disabled" wire:target="saveActivity">
                <span wire:loading.remove wire:target="saveActivity">Save</span>
                <span wire:loading wire:target="saveActivity"><i class="fas fa-spinner fa-spin"></i></span>
            </button>
            <button wire:click="cancelForms" class="btn btn-secondary btn-sm">Cancel</button>
        </div>
    </div>
</div>
@endif

<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover">
        <thead class="thead-light">
            <tr><th>Name</th><th>Category</th><th>Coach</th><th>Schedule</th><th class="text-center">Members</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse ($activities as $act)
            <tr>
                <td class="font-weight-medium">{{ $act->name }}</td>
                <td><span class="badge badge-{{ $act->category_badge }}">{{ ucfirst($act->category) }}</span></td>
                <td>{{ $act->coach_name ?? '—' }}</td>
                <td><small>{{ $act->schedule ?? '—' }}</small></td>
                <td class="text-center">
                    <span class="{{ $act->is_full ? 'text-danger font-weight-bold' : '' }}">
                        {{ $act->active_enrollments_count }}
                        @if ($act->max_members > 0) / {{ $act->max_members }} @endif
                    </span>
                </td>
                <td>
                    @if ($act->status) <span class="badge badge-success">Active</span>
                    @else <span class="badge badge-danger">Inactive</span> @endif
                </td>
                <td class="text-nowrap">
                    <button wire:click="selectActivity({{ $act->id }})" class="btn btn-sm btn-outline-info" title="View Members">
                        <i class="fas fa-users"></i>
                    </button>
                    @if ($canManage)
                    <button wire:click="editActivity({{ $act->id }})" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>
                    <button wire:click="deleteActivity({{ $act->id }})" wire:confirm="Delete '{{ $act->name }}'? All enrollments will also be removed."
                            wire:loading.attr="disabled" wire:target="deleteActivity"
                            class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No activities yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-2">{{ $activities->links() }}</div>
@endif
</div>
