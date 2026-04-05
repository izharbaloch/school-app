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
            <h4>Select Class and Load Students</h4>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="generate">
                <div class="row">
                    <div class="col-md-4">
                        <label>Class</label>
                        <select wire:model.live="student_class_id" class="form-control @error('student_class_id') is-invalid @enderror">
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('student_class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Section</label>
                        <select wire:model.live="section_id" class="form-control @error('section_id') is-invalid @enderror" @disabled(!$student_class_id)>
                            <option value="">All Sections</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('section_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Fee Type</label>
                        <select wire:model.live="fee_type_id" class="form-control @error('fee_type_id') is-invalid @enderror">
                            <option value="">Select Fee Type</option>
                            @foreach ($feeTypes as $feeType)
                                <option value="{{ $feeType->id }}">{{ $feeType->name }}</option>
                            @endforeach
                        </select>
                        @error('fee_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                @if ($student_class_id && $fee_type_id)
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label>Month</label>
                            <input type="number" wire:model.defer="month" class="form-control @error('month') is-invalid @enderror" min="1" max="12">
                            @error('month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label>Year</label>
                            <input type="number" wire:model.defer="year" class="form-control @error('year') is-invalid @enderror">
                            @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label>Due Date</label>
                            <input type="date" wire:model.defer="due_date" class="form-control @error('due_date') is-invalid @enderror">
                            @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label>Amount</label>
                            <input type="number" step="0.01" wire:model.defer="amount" class="form-control @error('amount') is-invalid @enderror">
                            <small class="text-muted">Agar fee structure set hai to amount auto aa jayega.</small>
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label>Discount</label>
                            <input type="number" step="0.01" wire:model.defer="discount" class="form-control @error('discount') is-invalid @enderror">
                            @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label>Fine</label>
                            <input type="number" step="0.01" wire:model.defer="fine" class="form-control @error('fine') is-invalid @enderror">
                            @error('fine') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Remarks</label>
                            <input type="text" wire:model.defer="remarks" class="form-control @error('remarks') is-invalid @enderror">
                            @error('remarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>
                            <input type="checkbox" wire:model.live="select_all"> Select All Students
                        </label>
                    </div>

                    @error('selected_students') <div class="text-danger mb-2">{{ $message }}</div> @enderror

                    <div class="table-responsive mt-2">
                        <table class="table table-bordered table-md">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>#</th>
                                    <th>Roll No</th>
                                    <th>Student Name</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>
                                            <input type="checkbox" wire:model.defer="selected_students" value="{{ $student->id }}">
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student->roll_no ?? '-' }}</td>
                                        <td>{{ $student->full_name ?: ($student->name ?? '-') }}</td>
                                        <td>{{ $student->studentClass->name ?? '-' }}</td>
                                        <td>{{ $student->section->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No students found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($students->count())
                        <button type="submit" class="btn btn-primary">Generate Fee</button>
                    @endif
                @endif

                <a href="{{ route('student-fees.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
