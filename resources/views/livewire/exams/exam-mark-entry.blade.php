<div>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTERS --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">

                {{-- EXAM --}}
                <div class="col-md-3">
                    <label>Exam</label>
                    <select wire:model.live="exam_id" class="form-control">
                        <option value="">Select Exam</option>
                        @foreach ($exams as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- CLASS --}}
                <div class="col-md-3">
                    <label>Class</label>
                    <select wire:model.live="student_class_id" class="form-control">
                        <option value="">Select Class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- SECTION --}}
                <div class="col-md-3">
                    <label>Section</label>
                    <select wire:model.live="section_id" class="form-control">
                        <option value="">All Sections</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 🔥 STUDENT DROPDOWN --}}
                <div class="col-md-3">
                    <label>Student</label>
                    <select wire:model.live="student_id" class="form-control">
                        <option value="">All Students</option>
                        @foreach ($filteredStudents as $stu)
                            <option value="{{ $stu->id }}">
                                {{ $stu->roll_no }} - {{ $stu->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </div>

    {{-- TABLE --}}
    @if ($exam_id && $student_class_id)

        <div class="card">
            <div class="card-header">
                <h4>Edit / Add Marks</h4>
            </div>

            <div class="card-body">

                <form wire:submit.prevent="save">

                    <div class="form-check mb-3">
                        <input type="checkbox" wire:model="is_promoted" class="form-check-input">
                        <label class="form-check-label">
                            Promote Students (Final Term Only)
                        </label>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Roll No</th>
                                    <th>Name</th>

                                    @foreach ($subjects as $subject)
                                        <th>
                                            {{ $subject->name }} <br>
                                            <small>({{ $subject->total_marks }})</small>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($students as $i => $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student['roll_no'] }}</td>
                                        <td>{{ $student['name'] }}</td>

                                        @foreach ($subjects as $subject)
                                            <td>
                                                <input type="number"
                                                    wire:model.defer="students.{{ $i }}.subjects.{{ $subject->id }}.obtained_marks"
                                                    class="form-control mb-1">

                                                <input type="text" placeholder="Remarks"
                                                    wire:model.defer="students.{{ $i }}.subjects.{{ $subject->id }}.remarks"
                                                    class="form-control">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    @if (count($students))
                        <button class="btn btn-primary mt-2">
                            Save / Update Marks
                        </button>
                    @endif

                </form>

            </div>
        </div>

    @endif

</div>
