<div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Academic Sessions</h4>
                    @can('settings.update')
                    <button class="btn btn-primary" wire:click="openForm">
                        <i class="fas fa-plus"></i> Add Session
                    </button>
                    @endcan
                </div>

                @if($showForm)
                <div class="card-body border-bottom bg-light">
                    <h6 class="mb-3">{{ $sessionId ? 'Edit' : 'Add' }} Academic Session</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Session Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    wire:model="name" placeholder="e.g. 2025-2026">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    wire:model="start_date">
                                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                    wire:model="end_date">
                                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" wire:model="status">
                                    <option value="active">Active</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center mt-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" wire:model="is_active">
                                <label class="custom-control-label" for="is_active">Set as Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Remarks</label>
                                <input type="text" class="form-control" wire:model="remarks" placeholder="Optional remarks">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" wire:click="save" wire:loading.attr="disabled">
                            <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm"></span></span>
                            {{ $sessionId ? 'Update' : 'Save' }}
                        </button>
                        <button class="btn btn-secondary ml-2" wire:click="cancel">Cancel</button>
                    </div>
                </div>
                @endif

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Session Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $session)
                                <tr>
                                    <td>{{ $loop->iteration + ($sessions->currentPage() - 1) * $sessions->perPage() }}</td>
                                    <td>
                                        <strong>{{ $session->name }}</strong>
                                        @if($session->remarks)
                                            <br><small class="text-muted">{{ $session->remarks }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $session->start_date->format('d M Y') }}</td>
                                    <td>{{ $session->end_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $session->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($session->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($session->is_active)
                                            <span class="badge badge-primary"><i class="fas fa-check"></i> Current</span>
                                        @else
                                            @can('settings.update')
                                            <button class="btn btn-xs btn-outline-primary" wire:click="setActive({{ $session->id }})" title="Set as active">
                                                Set Active
                                            </button>
                                            @endcan
                                        @endif
                                    </td>
                                    <td>
                                        @can('settings.update')
                                        <button class="btn btn-sm btn-info" wire:click="edit({{ $session->id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if(!$session->is_active)
                                        <button class="btn btn-sm btn-danger ml-1" wire:click="delete({{ $session->id }})"
                                            onclick="return confirm('Delete this session?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No academic sessions found. Add one to get started.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($sessions->hasPages())
                <div class="card-footer">{{ $sessions->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
