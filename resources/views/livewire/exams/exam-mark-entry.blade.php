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
        <div class="card-header">
            <h4>Select Exam Filters</h4>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label>Exam</label>
                    <select wire:model.live="exam_id" class="form-control @error('exam_id') is-invalid @enderror">
                        <option value="">Select Exam</option>
                        @foreach ($exams as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                        @endforeach
                    </select>
                    @error('exam_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label>Class</label>
                    <select wire:model.live="student_class_id" class="form-control @error('student_class_id') is-invalid @enderror">
                        <option value="">Select Class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('student_class_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label>Section</label>
                    <select wire:model.live="section_id" class="form-control @error('section_id') is-invalid @enderror" @disabled(!$student_class_id)>
                        <option value="">All Sections</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('section_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label>Subject</label>
                    <select wire:model.live="subject_id" class="form-control @error('subject_id') is-invalid @enderror" @disabled(!$student_class_id)>
                        <option value="">Select Subject</option>
                        @foreach ($subjects as $subjectItem)
                            <option value="{{ $subjectItem->id }}">{{ $subjectItem->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    @if ($exam_id && $student_class_id && $subject_id)
        <div class="card">
            <div class="card-header">
                <h4>Student Marks Entry</h4>
            </div>

            <div class="card-body">
                <form wire:submit.prevent="save">
                    <div class="mb-3">
                        <strong>Total Marks:</strong> {{ $subject->total_marks ?? '-' }} |
                        <strong>Passing Marks:</strong> {{ $subject->passing_marks ?? '-' }}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Roll No</th>
                                    <th>Student Name</th>
                                    <th>Obtained Marks</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student['roll_no'] }}</td>
                                        <td>{{ $student['name'] }}</td>
                                        <td>
                                            <input type="hidden" wire:model="students.{{ $index }}.student_id">

                                            <input type="number"
                                                   step="0.01"
                                                   min="0"
                                                   max="{{ $subject->total_marks ?? 100 }}"
                                                   wire:model.defer="students.{{ $index }}.obtained_marks"
                                                   class="form-control @error('students.' . $index . '.obtained_marks') is-invalid @enderror">

                                            @error('students.' . $index . '.obtained_marks')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text"
                                                   wire:model.defer="students.{{ $index }}.remarks"
                                                   class="form-control @error('students.' . $index . '.remarks') is-invalid @enderror">

                                            @error('students.' . $index . '.remarks')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No students found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if (count($students))
                        <button type="submit" class="btn btn-primary">Save Marks</button>
                    @endif
                </form>
            </div>
        </div>
    @endif
</div>
