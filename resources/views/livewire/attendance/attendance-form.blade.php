<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4>{{ $isEdit ? 'Edit Attendance' : 'Select Filters' }}</h4>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="col-md-4">
                        <label>Date <span class="text-danger">*</span></label>
                        <input type="date" wire:model.live="attendance_date" class="form-control">
                        @error('attendance_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Class <span class="text-danger">*</span></label>
                        <select wire:model.live="student_class_id" class="form-control">
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('student_class_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Section <span class="text-danger">*</span></label>
                        <select wire:model.live="section_id" class="form-control" @disabled(!$student_class_id)>
                            <option value="">Select Section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('section_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12 mt-3">
                        <label>Remarks</label>
                        <textarea wire:model.defer="remarks" class="form-control" rows="3"></textarea>
                        @error('remarks')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                @if ($student_class_id && $section_id)
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Student Attendance</h5>

                            <div>
                                <button type="button" wire:click="markAllPresent" class="btn btn-sm btn-success mr-2">
                                    Mark All Present
                                </button>

                                <button type="button" wire:click="markAllAbsent" class="btn btn-sm btn-danger">
                                    Mark All Absent
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-md">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Roll No</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($students as $index => $student)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $student['roll_no'] }}</td>
                                            <td>{{ $student['name'] ?: '-' }}</td>
                                            <td>
                                                <select wire:model.defer="students.{{ $index }}.status"
                                                    class="form-control">
                                                    <option value="present">Present</option>
                                                    <option value="absent">Absent</option>
                                                    <option value="leave">Leave</option>
                                                    <option value="late">Late</option>
                                                </select>

                                                @error('students.' . $index . '.status')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                    wire:model.defer="students.{{ $index }}.remarks"
                                                    class="form-control">

                                                <input type="hidden"
                                                    wire:model="students.{{ $index }}.student_id">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                No students found for selected class and section.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @error('students')
                            <small class="text-danger d-block mb-2">{{ $message }}</small>
                        @enderror

                        @if (count($students))
                            <button type="submit" class="btn btn-primary">
                                {{ $isEdit ? 'Update Attendance' : 'Save Attendance' }}
                            </button>
                        @endif
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
