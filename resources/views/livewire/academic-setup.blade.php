<div>
    <div class="row">

        {{-- ===================== CLASS ===================== --}}
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $classEditId ? 'Edit Class' : 'Add Class' }}</h4>
                </div>
                <div class="card-body">

                    @if (session()->has('class_success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('class_success') }}
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="{{ $classEditId ? 'updateClass' : 'saveClass' }}">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Class Name</label>
                                <input type="text" class="form-control @error('class_name') is-invalid @enderror"
                                    wire:model.defer="class_name" placeholder="Enter class name">
                                @error('class_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Numeric Name</label>
                                <input type="number"
                                    class="form-control @error('class_numeric_name') is-invalid @enderror"
                                    wire:model.defer="class_numeric_name" placeholder="Enter numeric class name">
                                @error('class_numeric_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-2">
                                <label>Fee</label>
                                <input type="number" class="form-control @error('class_fee') is-invalid @enderror"
                                    wire:model.defer="class_fee" placeholder="Enter Class Fee">
                                @error('class_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-2">
                                <label>Status</label>
                                <select class="form-control @error('class_status') is-invalid @enderror"
                                    wire:model.defer="class_status">
                                    <option value="">Select status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('class_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-2 d-flex align-items-end">
                                @if ($classEditId)
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-save mr-1"></i> Update
                                    </button>

                                    <button type="button" class="btn btn-secondary" wire:click="cancelClassEdit">
                                        <i class="fas fa-times mr-1"></i> Cancel
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Save
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Numeric Name</th>
                                    <th>Fee</th>
                                    <th>Status</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($classes as $class)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $class->name }}</td>
                                        <td>{{ $class->numeric_name ?? '-' }}</td>
                                        <td>{{ $class->fee ?? '-' }}</td>
                                        <td>
                                            @if ($class->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                wire:click="editClass({{ $class->id }})" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Delete this class?')) { @this.call('deleteClass', {{ $class->id }}) }"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No classes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===================== SECTION ===================== --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>{{ $sectionEditId ? 'Edit Section' : 'Add Section' }}</h4>
                </div>
                <div class="card-body">

                    @if (session()->has('section_success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('section_success') }}
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="{{ $sectionEditId ? 'updateSection' : 'saveSection' }}">
                        <div class="form-group">
                            <label>Section Name</label>
                            <input type="text" class="form-control @error('section_name') is-invalid @enderror"
                                wire:model.defer="section_name" placeholder="Enter section name">
                            @error('section_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control @error('section_status') is-invalid @enderror"
                                wire:model.defer="section_status">
                                <option value="">Select status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('section_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($sectionEditId)
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-save mr-1"></i> Update Section
                            </button>

                            <button type="button" class="btn btn-secondary" wire:click="cancelSectionEdit">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                        @else
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save Section
                            </button>
                        @endif
                    </form>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sections as $section)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $section->name }}</td>
                                        <td>
                                            @if ($section->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                wire:click="editSection({{ $section->id }})" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Delete this section?')) { @this.call('deleteSection', {{ $section->id }}) }"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No sections found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===================== SUBJECT ===================== --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>{{ $subjectEditId ? 'Edit Subject' : 'Add Subject' }}</h4>
                </div>
                <div class="card-body">

                    @if (session()->has('subject_success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('subject_success') }}
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="{{ $subjectEditId ? 'updateSubject' : 'saveSubject' }}">
                        <div class="form-group">
                            <label>Subject Name</label>
                            <input type="text" class="form-control @error('subject_name') is-invalid @enderror"
                                wire:model.defer="subject_name" placeholder="Enter subject name">
                            @error('subject_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Total Marks</label>
                            <input type="number"
                                class="form-control @error('subject_total_marks') is-invalid @enderror"
                                wire:model.defer="subject_total_marks" placeholder="Enter total marks">
                            @error('subject_total_marks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Passing Marks</label>
                            <input type="number"
                                class="form-control @error('subject_passing_marks') is-invalid @enderror"
                                wire:model.defer="subject_passing_marks" placeholder="Enter passing marks">
                            @error('subject_passing_marks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control @error('subject_status') is-invalid @enderror"
                                wire:model.defer="subject_status">
                                <option value="">Select status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('subject_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($subjectEditId)
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-save mr-1"></i> Update Subject
                            </button>

                            <button type="button" class="btn btn-secondary" wire:click="cancelSubjectEdit">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                        @else
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save Subject
                            </button>
                        @endif
                    </form>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Total Marks</th>
                                    <th>Passing Marks</th>
                                    <th>Status</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subjects as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->name }}</td>
                                        <td>{{ $subject->total_marks }}</td>
                                        <td>{{ $subject->passing_marks }}</td>
                                        <td>
                                            @if ($subject->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                wire:click="editSubject({{ $subject->id }})" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Delete this subject?')) { @this.call('deleteSubject', {{ $subject->id }}) }"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No subjects found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="row">

        {{-- ===================== CLASS SECTION ASSIGNMENT ===================== --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>Assign Section To Class</h4>
                </div>
                <div class="card-body">

                    @if (session()->has('assignment_section_success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('assignment_section_success') }}
                            </div>
                        </div>
                    @endif

                    @if (session()->has('assignment_section_error'))
                        <div class="alert alert-danger alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('assignment_section_error') }}
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="assignSectionToClass">
                        <div class="form-group">
                            <label>Class</label>
                            <select class="form-control @error('assign_class_id') is-invalid @enderror"
                                wire:model.defer="assign_class_id">
                                <option value="">Select class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assign_class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Select Sections</label>

                            @foreach ($sections as $section)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="{{ $section->id }}"
                                        wire:model="assign_section_ids">

                                    <label class="form-check-label">
                                        {{ $section->name }}
                                    </label>
                                </div>
                            @endforeach

                            @error('assign_section_ids')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-link mr-1"></i> Assign Section
                        </button>
                    </form>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Class</th>
                                    <th>Assigned Sections</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($classSectionAssignments as $class)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $class->name }}</td>
                                        <td>
                                            @forelse ($class->sections as $section)
                                                <span class="badge badge-primary mr-1 mb-1">
                                                    {{ $section->name }}
                                                    <a href="javascript:void(0)" class="text-white ml-1"
                                                        wire:click="removeSectionAssignment({{ $class->id }}, {{ $section->id }})"
                                                        title="Remove">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </span>
                                            @empty
                                                <span class="text-muted">No sections assigned</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No class-section assignments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===================== CLASS SUBJECT ASSIGNMENT ===================== --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>Assign Subject To Class</h4>
                </div>
                <div class="card-body">

                    @if (session()->has('assignment_subject_success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('assignment_subject_success') }}
                            </div>
                        </div>
                    @endif

                    @if (session()->has('assignment_subject_error'))
                        <div class="alert alert-danger alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('assignment_subject_error') }}
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="assignSubjectToClass">
                        <div class="form-group">
                            <label>Class</label>
                            <select class="form-control @error('assign_subject_class_id') is-invalid @enderror"
                                wire:model.defer="assign_subject_class_id">
                                <option value="">Select class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assign_subject_class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Select Subjects</label>

                            @foreach ($subjects as $subject)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="{{ $subject->id }}"
                                        wire:model="assign_subject_ids">

                                    <label class="form-check-label">
                                        {{ $subject->name }}
                                    </label>
                                </div>
                            @endforeach

                            @error('assign_subject_ids')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-link mr-1"></i> Assign Subject
                        </button>
                    </form>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Class</th>
                                    <th>Assigned Subjects</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($classSubjectAssignments as $class)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $class->name }}</td>
                                        <td>
                                            @forelse ($class->subjects as $subject)
                                                <span class="badge badge-info mr-1 mb-1">
                                                    {{ $subject->name }}
                                                    <a href="javascript:void(0)" class="text-white ml-1"
                                                        wire:click="removeSubjectAssignment({{ $class->id }}, {{ $subject->id }})"
                                                        title="Remove">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </span>
                                            @empty
                                                <span class="text-muted">No subjects assigned</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No class-subject assignments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
