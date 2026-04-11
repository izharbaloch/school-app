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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Fee Type List</h4>

            @if (!$showForm)
                <button type="button" class="btn btn-primary" wire:click="openForm">
                    <i class="fas fa-plus mr-1"></i> Add Fee Type
                </button>
            @endif
        </div>

        <div class="card-body">

            @if ($showForm)
                <div class="border rounded p-3 mb-4">
                    <h5 class="mb-3">
                        {{ $editId ? 'Edit Fee Type' : 'Create Fee Type' }}
                    </h5>

                    <form wire:submit.prevent="{{ $editId ? 'update' : 'save' }}">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    wire:model.defer="name" placeholder="Enter fee type name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Monthly</label>
                                <select class="form-control @error('is_monthly') is-invalid @enderror"
                                    wire:model.defer="is_monthly">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                @error('is_monthly')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Status</label>
                                <select class="form-control @error('status') is-invalid @enderror"
                                    wire:model.defer="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-2 d-flex align-items-end">
                                @if ($editId)
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-save mr-1"></i> Update
                                    </button>

                                    <button type="button" class="btn btn-secondary" wire:click="cancel">
                                        <i class="fas fa-times mr-1"></i> Cancel
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-save mr-1"></i> Save
                                    </button>

                                    <button type="button" class="btn btn-secondary" wire:click="cancel">
                                        <i class="fas fa-times mr-1"></i> Cancel
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                        placeholder="Search fee type...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-md">
                    <thead>
                        <tr>
                            <th width="70">#</th>
                            <th>Name</th>
                            <th>Monthly</th>
                            <th>Status</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feeTypes as $feeType)
                            <tr>
                                <td>{{ $loop->iteration + ($feeTypes->currentPage() - 1) * $feeTypes->perPage() }}</td>
                                <td>{{ $feeType->name }}</td>
                                <td>
                                    @if ($feeType->is_monthly)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($feeType->status)
                                        <span class="badge badge-primary">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning"
                                        wire:click="edit({{ $feeType->id }})">
                                        Edit
                                    </button>

                                    <button type="button" class="btn btn-sm btn-danger"
                                        wire:click="delete({{ $feeType->id }})"
                                        onclick="confirm('Delete this fee type?') || event.stopImmediatePropagation()">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No fee types found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $feeTypes->links() }}
            </div>
        </div>
    </div>
</div>
