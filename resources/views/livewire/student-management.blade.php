<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Student Management</h4>

            @if (!$showForm)
                <button type="button" class="btn btn-primary" wire:click="openForm">
                    <i class="fas fa-plus mr-1"></i> Add Student
                </button>
            @endif
        </div>

        <div class="card-body">

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

            @if ($showForm)
                <div class="border rounded p-3 mb-4">
                    <h5 class="mb-3">{{ $editId ? 'Edit Student' : 'Add Student' }}</h5>

                    <form wire:submit.prevent="{{ $editId ? 'updateStudent' : 'saveStudent' }}">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Admission No</label>
                                <input type="text" class="form-control" wire:model.defer="admission_no" readonly>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Roll No</label>
                                <input type="text" class="form-control" wire:model.defer="roll_no" readonly>
                            </div>

                            <div class="form-group col-md-3">
                                <label>First Name</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    wire:model.defer="first_name" placeholder="Enter first name">
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    wire:model.defer="last_name" placeholder="Enter last name">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Gender</label>
                                <select class="form-control @error('gender') is-invalid @enderror"
                                    wire:model.defer="gender">
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Date of Birth</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                    wire:model.defer="date_of_birth">
                                @error('date_of_birth')
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

                            <div class="form-group col-md-3">
                                <label>Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    wire:model.defer="email" placeholder="Enter email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Father Name</label>
                                <input type="text" class="form-control @error('father_name') is-invalid @enderror"
                                    wire:model.defer="father_name" placeholder="Enter father name">
                                @error('father_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Mother Name</label>
                                <input type="text" class="form-control @error('mother_name') is-invalid @enderror"
                                    wire:model.defer="mother_name" placeholder="Enter mother name">
                                @error('mother_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Guardian Phone</label>
                                <input type="text" class="form-control @error('guardian_phone') is-invalid @enderror"
                                    wire:model.defer="guardian_phone" placeholder="Enter guardian phone">
                                @error('guardian_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Admission Date</label>
                                <input type="date" class="form-control @error('admission_date') is-invalid @enderror"
                                    wire:model.defer="admission_date">
                                @error('admission_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="student_class_id">Class <span class="text-danger">*</span></label>
                                <select id="student_class_id"
                                    class="form-control @error('student_class_id') is-invalid @enderror"
                                    wire:model.live="student_class_id">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('student_class_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="section_id">Section <span class="text-danger">*</span></label>
                                <select id="section_id" class="form-control @error('section_id') is-invalid @enderror"
                                    wire:model="section_id" {{ count($sections) ? '' : 'disabled' }}>
                                    <option value="">Select Section</option>

                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                                @error('section_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Status</label>
                                <select class="form-control @error('status') is-invalid @enderror"
                                    wire:model.defer="status">
                                    <option value="">Select status</option>
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
                            <textarea class="form-control @error('address') is-invalid @enderror" rows="3" wire:model.defer="address"
                                placeholder="Enter address"></textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        <h6 class="mb-3">Student Documents</h6>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Student Profile Photo</label>
                                <input type="file"
                                    class="form-control @error('student_photo') is-invalid @enderror"
                                    wire:model="student_photo">
                                @error('student_photo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Student B-Form</label>
                                <input type="file"
                                    class="form-control @error('student_bform') is-invalid @enderror"
                                    wire:model="student_bform">
                                @error('student_bform')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Student CNIC</label>
                                <input type="file"
                                    class="form-control @error('student_cnic') is-invalid @enderror"
                                    wire:model="student_cnic">
                                @error('student_cnic')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Father CNIC</label>
                                <input type="file" class="form-control @error('father_cnic') is-invalid @enderror"
                                    wire:model="father_cnic">
                                @error('father_cnic')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Mother CNIC</label>
                                <input type="file" class="form-control @error('mother_cnic') is-invalid @enderror"
                                    wire:model="mother_cnic">
                                @error('mother_cnic')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Guardian CNIC</label>
                                <input type="file"
                                    class="form-control @error('guardian_cnic') is-invalid @enderror"
                                    wire:model="guardian_cnic">
                                @error('guardian_cnic')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Other Documents</label>
                            <input type="file"
                                class="form-control @error('other_documents.*') is-invalid @enderror"
                                wire:model="other_documents" multiple>
                            @error('other_documents.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($student_photo)
                            <div class="mb-3">
                                <strong>Photo Preview:</strong><br>
                                <img src="{{ $student_photo->temporaryUrl() }}" alt="Preview" width="80"
                                    class="mt-2 rounded border">
                            </div>
                        @endif

                        @if (!empty($oldAttachments))
                            <div class="mt-3">
                                <h6>Existing Documents</h6>
                                <div class="row">
                                    @foreach ($oldAttachments as $type => $files)
                                        @foreach ($files as $file)
                                            <div class="col-md-3 mb-2">
                                                <div class="border rounded p-2 h-100">
                                                    <small class="d-block font-weight-bold mb-1">
                                                        {{ $file['title'] ?? $type }}
                                                    </small>

                                                    <a href="{{ asset('storage/' . $file['file_path']) }}"
                                                        target="_blank">
                                                        View File
                                                    </a>

                                                    <button type="button" class="btn btn-sm btn-danger mt-2"
                                                        wire:click="deleteAttachment({{ $file['id'] }})">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="d-flex mt-3">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-save mr-1"></i>
                                {{ $editId ? 'Update Student' : 'Save Student' }}
                            </button>

                            <button type="button" class="btn btn-secondary" wire:click="closeForm">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Admission No</th>
                            <th>Roll No</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Father Name</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($student->profilePhoto)
                                        <img src="{{ asset('storage/' . $student->profilePhoto->file_path) }}"
                                            alt="Student Photo" width="45" height="45"
                                            class="rounded border">
                                    @else
                                        <span class="text-muted">No Photo</span>
                                    @endif
                                </td>
                                <td>{{ $student->admission_no }}</td>
                                <td>{{ $student->roll_no ?? '-' }}</td>
                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td>{{ ucfirst($student->gender ?? '-') }}</td>
                                <td>{{ $student->father_name }}</td>
                                <td>{{ $student->studentClass->name ?? '-' }}</td>
                                <td>{{ $student->section->name ?? '-' }}</td>
                                <td>
                                    @if ($student->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary"
                                        wire:click="editStudent({{ $student->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="if(confirm('Delete this student?')) { @this.call('deleteStudent', {{ $student->id }}) }"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
