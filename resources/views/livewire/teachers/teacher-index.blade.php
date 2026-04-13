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
            <h4>Teacher List</h4>

            @if (!$showForm)
                <button type="button" class="btn btn-primary" wire:click="openForm">
                    <i class="fas fa-plus mr-1"></i> Add Teacher
                </button>
            @endif
        </div>

        <div class="card-body">

            @if ($showForm)
                <div class="border rounded p-3 mb-4">
                    <h5 class="mb-3">
                        {{ $teacherId ? 'Edit Teacher' : 'Create Teacher' }}
                    </h5>

                    <form wire:submit.prevent="{{ $teacherId ? 'update' : 'save' }}">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Employee No</label>
                                <input type="text" class="form-control @error('employee_no') is-invalid @enderror"
                                    wire:model.defer="employee_no" placeholder="Enter employee no">
                                @error('employee_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    wire:model.defer="name" placeholder="Enter teacher name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    wire:model.defer="email" placeholder="Enter email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    wire:model.defer="phone" placeholder="Enter phone">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>CNIC</label>
                                <input type="text" class="form-control @error('cnic') is-invalid @enderror"
                                    wire:model.defer="cnic" placeholder="Enter cnic">
                                @error('cnic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Designation</label>
                                <input type="text" class="form-control @error('designation') is-invalid @enderror"
                                    wire:model.defer="designation" placeholder="Enter designation">
                                @error('designation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Class</label>
                                <select wire:model.live="student_class_id"
                                    class="form-control @error('student_class_id') is-invalid @enderror">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('student_class_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Section</label>
                                <select wire:model.defer="section_id"
                                    class="form-control @error('section_id') is-invalid @enderror"
                                    @disabled(!$student_class_id)>
                                    <option value="">All Sections</option>
                                    @foreach ($this->sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                                @error('section_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-9">
                                <label>Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" wire:model.defer="address" rows="3"
                                    placeholder="Enter address"></textarea>
                                @error('address')
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
                        </div>

                        <div class="d-flex mt-3">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-save mr-1"></i>
                                {{ $teacherId ? 'Update Teacher' : 'Save Teacher' }}
                            </button>

                            <button type="button" class="btn btn-secondary" wire:click="cancel">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                        placeholder="Search teacher...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-md">
                    <thead>
                        <tr>
                            <th width="70">#</th>
                            <th>Employee No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr>
                                <td>{{ $loop->iteration + ($teachers->currentPage() - 1) * $teachers->perPage() }}</td>
                                <td>{{ $teacher->employee_no ?: '-' }}</td>
                                <td>{{ $teacher->name }}</td>
                                <td>{{ $teacher->email ?: '-' }}</td>
                                <td>{{ $teacher->phone ?: '-' }}</td>
                                <td>{{ $teacher->studentClass->name ?? '-' }}</td>
                                <td>{{ $teacher->section->name ?? '-' }}</td>
                                <td>
                                    @if ($teacher->status)
                                        <span class="badge badge-primary">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning"
                                        wire:click="edit({{ $teacher->id }})">
                                        Edit
                                    </button>

                                    <button type="button" class="btn btn-sm btn-danger"
                                        wire:click="delete({{ $teacher->id }})"
                                        onclick="confirm('Delete this teacher?') || event.stopImmediatePropagation()">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No teachers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
</div>
