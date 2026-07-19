<div>
@if (session('type_success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('type_success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

{{-- Add / Edit Form --}}
@if ($showForm)
<div class="card shadow-sm border-primary mb-4">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">{{ $editId ? 'Edit Leave Type' : 'Add Leave Type' }}</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" wire:model.defer="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Sick Leave">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Max Days/Year</label>
                    <input type="number" wire:model.defer="max_days" min="0" class="form-control @error('max_days') is-invalid @enderror">
                    <small class="text-muted">0 = unlimited</small>
                    @error('max_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Applicable To</label>
                    <select wire:model.defer="applicable_to" class="form-control @error('applicable_to') is-invalid @enderror">
                        <option value="both">Both</option>
                        <option value="staff">Staff Only</option>
                        <option value="student">Student Only</option>
                    </select>
                    @error('applicable_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Paid Leave?</label>
                    <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" wire:model.defer="is_paid" class="custom-control-input" id="isPaid">
                        <label class="custom-control-label" for="isPaid">Paid</label>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Status</label>
                    <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" wire:model.defer="status" class="custom-control-input" id="ltStatus">
                        <label class="custom-control-label" for="ltStatus">Active</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea wire:model.defer="description" class="form-control" rows="2" placeholder="Optional description"></textarea>
        </div>
        <div class="d-flex gap-2">
            @if ($editId)
                <button wire:click="update" class="btn btn-primary btn-sm" wire:loading.attr="disabled" wire:target="update">
                    <span wire:loading.remove wire:target="update">Update</span>
                    <span wire:loading wire:target="update"><i class="fas fa-spinner fa-spin"></i></span>
                </button>
            @else
                <button wire:click="save" class="btn btn-success btn-sm" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">Save</span>
                    <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin"></i></span>
                </button>
            @endif
            <button wire:click="cancel" class="btn btn-secondary btn-sm">Cancel</button>
        </div>
    </div>
</div>
@else
<div class="mb-3">
    <button wire:click="openForm" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add Leave Type
    </button>
</div>
@endif

{{-- Table --}}
<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover mb-0">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Applicable To</th>
                <th>Max Days</th>
                <th>Paid</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($types as $type)
            <tr>
                <td>{{ $loop->iteration + ($types->currentPage() - 1) * $types->perPage() }}</td>
                <td>
                    {{ $type->name }}
                    @if ($type->description)
                        <div class="text-muted small">{{ Str::limit($type->description, 60) }}</div>
                    @endif
                </td>
                <td><span class="badge badge-info">{{ ucfirst($type->applicable_to) }}</span></td>
                <td>{{ $type->max_days_per_year == 0 ? '∞' : $type->max_days_per_year }}</td>
                <td>
                    @if ($type->is_paid)
                        <span class="badge badge-success">Paid</span>
                    @else
                        <span class="badge badge-secondary">Unpaid</span>
                    @endif
                </td>
                <td>
                    @if ($type->status)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </td>
                <td>
                    <button wire:click="edit({{ $type->id }})" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button wire:click="delete({{ $type->id }})"
                            wire:confirm="Delete '{{ $type->name }}'? Applications using this type will also be affected."
                            class="btn btn-sm btn-outline-danger"
                            wire:loading.attr="disabled" wire:target="delete({{ $type->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">No leave types found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $types->links() }}</div>
</div>{{-- end single root --}}
