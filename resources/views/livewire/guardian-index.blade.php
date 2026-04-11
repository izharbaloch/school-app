<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Guardians List</h4>

            <button type="button" class="btn btn-primary" wire:click="toggleForm">
                <i class="fas fa-plus mr-1"></i>
                {{ $showForm ? 'Close Form' : 'Add Guardian' }}
            </button>
        </div>

        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($showForm)
                <div class="border rounded p-3 mb-4">
                    <h5 class="mb-3">{{ $guardianId ? 'Edit Guardian' : 'Add Guardian' }}</h5>

                    <form wire:submit.prevent="save">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Father Name <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('father_name') is-invalid @enderror"
                                    wire:model.defer="father_name"
                                    placeholder="Enter father name">
                                @error('father_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Mother Name</label>
                                <input type="text"
                                    class="form-control @error('mother_name') is-invalid @enderror"
                                    wire:model.defer="mother_name"
                                    placeholder="Enter mother name">
                                @error('mother_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Guardian Phone</label>
                                <input type="text"
                                    class="form-control @error('guardian_phone') is-invalid @enderror"
                                    wire:model.defer="guardian_phone"
                                    placeholder="Enter guardian phone">
                                @error('guardian_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Guardian CNIC</label>
                                <input type="text"
                                    class="form-control @error('guardian_cnic_no') is-invalid @enderror"
                                    wire:model.defer="guardian_cnic_no"
                                    placeholder="35202-1234567-1">
                                @error('guardian_cnic_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Email</label>
                                <input type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    wire:model.defer="email"
                                    placeholder="Enter email">
                                @error('email')
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
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <textarea
                                class="form-control @error('address') is-invalid @enderror"
                                wire:model.defer="address"
                                rows="3"
                                placeholder="Enter address"></textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex mt-3">
                            <button type="submit" class="btn btn-success mr-2">
                                <i class="fas fa-save mr-1"></i>
                                {{ $guardianId ? 'Update Guardian' : 'Save Guardian' }}
                            </button>

                            <button type="button" class="btn btn-secondary" wire:click="resetForm">
                                <i class="fas fa-undo mr-1"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text"
                        class="form-control"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by name, cnic, phone, email">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Father Name</th>
                            <th>Mother Name</th>
                            <th>Phone</th>
                            <th>CNIC</th>
                            <th>Email</th>
                            <th>User Account</th>
                            <th>Status</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($parents as $parent)
                            <tr>
                                <td>{{ $parents->firstItem() + $loop->index }}</td>
                                <td>{{ $parent->father_name }}</td>
                                <td>{{ $parent->mother_name ?: '-' }}</td>
                                <td>{{ $parent->guardian_phone ?: '-' }}</td>
                                <td>{{ $parent->guardian_cnic_no ?: '-' }}</td>
                                <td>{{ $parent->email ?: '-' }}</td>
                                <td>{{ $parent->user?->email ?: '-' }}</td>
                                <td>
                                    @if ($parent->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button"
                                        class="btn btn-sm btn-primary"
                                        wire:click="edit({{ $parent->id }})">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No guardians found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $parents->links() }}
            </div>
        </div>
    </div>
</div>
