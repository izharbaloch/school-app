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
            <h4>Exam List</h4>

            @if (!$showForm)
                <button type="button" class="btn btn-primary" wire:click="openForm">
                    <i class="fas fa-plus mr-1"></i> Add Exam
                </button>
            @endif
        </div>

        <div class="card-body">

            @if ($showForm)
                <div class="border rounded p-3 mb-4">
                    <h5 class="mb-3">
                        {{ $editId ? 'Edit Exam' : 'Create Exam' }}
                    </h5>

                    <form wire:submit.prevent="{{ $editId ? 'update' : 'save' }}">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Exam Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    wire:model.defer="name" placeholder="Enter exam name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Academic Year</label>
                                <input type="text" class="form-control @error('academic_year') is-invalid @enderror"
                                    wire:model.defer="academic_year" placeholder="Enter academic year">
                                @error('academic_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    wire:model.defer="start_date">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                    wire:model.defer="end_date">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-2">
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

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Remarks</label>
                                <textarea class="form-control @error('remarks') is-invalid @enderror" wire:model.defer="remarks" rows="3"
                                    placeholder="Enter remarks">{{ $remarks }}</textarea>
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-2 d-flex align-items-end">
                                @if ($editId)
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-save mr-1"></i> Update
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-save mr-1"></i> Save
                                    </button>
                                @endif

                                <button type="button" class="btn btn-secondary" wire:click="cancel">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                        placeholder="Search exam...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-md">
                    <thead>
                        <tr>
                            <th width="70">#</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                            <tr>
                                <td>{{ $loop->iteration + ($exams->currentPage() - 1) * $exams->perPage() }}</td>
                                <td>{{ $exam->name }}</td>
                                <td>{{ $exam->start_date ? $exam->start_date->format('d-m-Y') : '-' }}</td>
                                <td>{{ $exam->end_date ? $exam->end_date->format('d-m-Y') : '-' }}</td>
                                <td>
                                    @if ($exam->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $exam->remarks ?: '-' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning"
                                        wire:click="edit({{ $exam->id }})">
                                        Edit
                                    </button>

                                    <button type="button" class="btn btn-sm btn-danger"
                                        wire:click="delete({{ $exam->id }})"
                                        onclick="confirm('Delete this exam?') || event.stopImmediatePropagation()">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No exams found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $exams->links() }}
            </div>
        </div>
    </div>
</div>
