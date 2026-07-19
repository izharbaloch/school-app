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
            <h4>Fee Structure List</h4>

            @if (!$showForm)
                <button type="button" class="btn btn-primary" wire:click="openForm">
                    <i class="fas fa-plus mr-1"></i> Add Fee Structure
                </button>
            @endif
        </div>

        <div class="card-body">

            @if ($showForm)
                <div class="card border-primary shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">{{ $editId ? 'Edit Fee Structure' : 'Create Fee Structure' }}</h6>
                    </div>
                    <div class="card-body">

                    <form wire:submit.prevent="{{ $editId ? 'update' : 'save' }}">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Class</label>
                                <select class="form-control @error('student_class_id') is-invalid @enderror"
                                    wire:model.defer="student_class_id">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('student_class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Fee Type</label>
                                <select class="form-control @error('fee_type_id') is-invalid @enderror"
                                    wire:model.defer="fee_type_id">
                                    <option value="">Select Fee Type</option>
                                    @foreach ($feeTypes as $feeType)
                                        <option value="{{ $feeType->id }}">{{ $feeType->name }}</option>
                                    @endforeach
                                </select>
                                @error('fee_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Amount</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('amount') is-invalid @enderror" wire:model.defer="amount"
                                    placeholder="Enter amount">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
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
                                    <button type="submit" class="btn btn-primary mr-2" wire:loading.attr="disabled" wire:target="update">
                                        <span wire:loading.remove wire:target="update"><i class="fas fa-save mr-1"></i> Update</span>
                                        <span wire:loading wire:target="update"><i class="fas fa-spinner fa-spin"></i></span>
                                    </button>

                                    <button type="button" class="btn btn-secondary" wire:click="cancel">
                                        <i class="fas fa-times mr-1"></i> Cancel
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-primary mr-2" wire:loading.attr="disabled" wire:target="save">
                                        <span wire:loading.remove wire:target="save"><i class="fas fa-save mr-1"></i> Save</span>
                                        <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin"></i></span>
                                    </button>

                                    <button type="button" class="btn btn-secondary" wire:click="cancel">
                                        <i class="fas fa-times mr-1"></i> Cancel
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                        placeholder="Search class, fee type or amount...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-md">
                    <thead>
                        <tr>
                            <th width="70">#</th>
                            <th>Class</th>
                            <th>Fee Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feeStructures as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($feeStructures->currentPage() - 1) * $feeStructures->perPage() }}
                                </td>
                                <td>{{ $item->studentClass->name ?? '-' }}</td>
                                <td>{{ $item->feeType->name ?? '-' }}</td>
                                <td>{{ number_format($item->amount, 2) }}</td>
                                <td>
                                    @if ($item->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        wire:click="edit({{ $item->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        wire:click="delete({{ $item->id }})"
                                        wire:confirm="Delete this fee structure?" title="Delete"
                                        wire:loading.attr="disabled" wire:target="delete({{ $item->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No fee structures found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $feeStructures->links() }}
            </div>
        </div>
    </div>
</div>
