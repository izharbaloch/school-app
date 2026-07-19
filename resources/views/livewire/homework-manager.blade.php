<div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Filters & Add Button --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <input wire:model.live.debounce.400ms="search" type="text" class="form-control form-control-sm" placeholder="Search homework title...">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="filterClass" class="form-control form-control-sm">
                        <option value="">All Classes</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 text-right">
                    @can('homework.create')
                        @unless($showForm)
                            <button wire:click="openForm" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Assign Homework
                            </button>
                        @endunless
                    @endcan
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'homework' ? 'active' : '' }}" wire:click="$set('activeTab','homework')" href="#">
                <i class="fas fa-book"></i> Homework
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'submissions' ? 'active' : '' }}" wire:click="$set('activeTab','submissions')" href="#">
                <i class="fas fa-paper-plane"></i> Submissions
            </a>
        </li>
    </ul>

    {{-- Homework Form --}}
    @if($showForm)
    <div class="card border-primary mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">{{ $homeworkId ? 'Edit Homework' : 'Assign New Homework' }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input wire:model="title" type="text" class="form-control @error('title') is-invalid @enderror" placeholder="Homework title">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <div class="custom-control custom-switch mt-2">
                            <input wire:model="hw_status" type="checkbox" class="custom-control-input" id="hwStatus">
                            <label class="custom-control-label" for="hwStatus">Active</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea wire:model="description" class="form-control" rows="2" placeholder="Instructions or details..."></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Class <span class="text-danger">*</span></label>
                        <select wire:model.live="student_class_id" class="form-control @error('student_class_id') is-invalid @enderror">
                            <option value="">Select Class</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                            @endforeach
                        </select>
                        @error('student_class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Section</label>
                        <select wire:model="section_id" class="form-control">
                            <option value="">All Sections</option>
                            @foreach($this->sections as $sec)
                                <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Subject <span class="text-danger">*</span></label>
                        <select wire:model="subject_id" class="form-control @error('subject_id') is-invalid @enderror">
                            <option value="">Select Subject</option>
                            @foreach($this->subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Teacher</label>
                        <select wire:model="teacher_id" class="form-control">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Assigned Date <span class="text-danger">*</span></label>
                        <input wire:model="assigned_date" type="date" class="form-control @error('assigned_date') is-invalid @enderror">
                        @error('assigned_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Due Date <span class="text-danger">*</span></label>
                        <input wire:model="due_date" type="date" class="form-control @error('due_date') is-invalid @enderror">
                        @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button wire:click="cancel" type="button" class="btn btn-secondary btn-sm mr-2">Cancel</button>
                <button wire:click="save" type="button" class="btn btn-primary btn-sm">
                    <span wire:loading.remove>{{ $homeworkId ? 'Update' : 'Assign' }}</span>
                    <span wire:loading><i class="fas fa-spinner fa-spin"></i></span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Review Form --}}
    @if($showReviewForm)
    <div class="card border-info mb-4">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0">Review Submission</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select wire:model="review_status" class="form-control">
                            <option value="reviewed">Reviewed</option>
                            <option value="late">Late</option>
                            <option value="missing">Missing</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Marks</label>
                        <input wire:model="review_marks" type="number" min="0" class="form-control" placeholder="Optional">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input wire:model="review_remarks" type="text" class="form-control" placeholder="Teacher remarks">
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button wire:click="$set('showReviewForm',false)" class="btn btn-secondary btn-sm mr-2">Cancel</button>
                <button wire:click="saveReview" class="btn btn-info btn-sm">Save Review</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Homework Tab --}}
    @if($activeTab === 'homework')
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Class / Section</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Assigned</th>
                            <th>Due</th>
                            <th>Submissions</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($homework as $hw)
                        <tr>
                            <td>{{ $homework->firstItem() + $loop->index }}</td>
                            <td>{{ $hw->title }}</td>
                            <td>{{ $hw->studentClass->name ?? '—' }} {{ $hw->section ? '/ '.$hw->section->name : '' }}</td>
                            <td>{{ $hw->subject->name ?? '—' }}</td>
                            <td>{{ $hw->teacher->name ?? '—' }}</td>
                            <td>{{ $hw->assigned_date->format('d M Y') }}</td>
                            <td class="{{ $hw->isOverdue() ? 'text-danger font-weight-bold' : '' }}">
                                {{ $hw->due_date->format('d M Y') }}
                            </td>
                            <td><span class="badge badge-secondary">{{ $hw->submissions->count() }}</span></td>
                            <td>
                                @if($hw->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @can('homework.edit')
                                    <button wire:click="edit({{ $hw->id }})" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                @endcan
                                @can('homework.delete')
                                    <button wire:click="delete({{ $hw->id }})" class="btn btn-sm btn-outline-danger"
                                        wire:confirm="Delete this homework?" title="Delete"><i class="fas fa-trash"></i></button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="text-center text-muted py-4">No homework found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($homework->hasPages())
            <div class="p-3">{{ $homework->links() }}</div>
            @endif
        </div>
    </div>
    @endif

    {{-- Submissions Tab --}}
    @if($activeTab === 'submissions')
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Homework</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Marks</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $sub)
                        <tr>
                            <td>{{ $submissions->firstItem() + $loop->index }}</td>
                            <td>{{ $sub->homework->title ?? '—' }}</td>
                            <td>{{ $sub->student->name ?? '—' }}</td>
                            <td>{{ $sub->homework->studentClass->name ?? '—' }}</td>
                            <td>{{ $sub->submitted_date ? \Carbon\Carbon::parse($sub->submitted_date)->format('d M Y') : '—' }}</td>
                            <td>
                                @php
                                    $colors = ['submitted'=>'primary','reviewed'=>'success','late'=>'warning','missing'=>'danger'];
                                    $color = $colors[$sub->status] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $color }}">{{ ucfirst($sub->status) }}</span>
                            </td>
                            <td>{{ $sub->marks ?? '—' }}</td>
                            <td>{{ $sub->teacher_remarks ?? '—' }}</td>
                            <td>
                                @can('homework.edit')
                                    <button wire:click="openReview({{ $sub->id }})" class="btn btn-xs btn-info"><i class="fas fa-check-circle"></i></button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No submissions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($submissions->hasPages())
            <div class="p-3">{{ $submissions->links() }}</div>
            @endif
        </div>
    </div>
    @endif
</div>
