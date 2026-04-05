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
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="col-md-3">
                        <label>Class</label>
                        <select wire:model.live="student_class_id"
                            class="form-control @error('student_class_id') is-invalid @enderror">
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
                        <select wire:model.live="section_id"
                            class="form-control @error('section_id') is-invalid @enderror" @disabled(!$student_class_id)>
                            <option value="">Select Section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('section_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label>Student</label>
                        <select wire:model.defer="student_id"
                            class="form-control @error('student_id') is-invalid @enderror" @disabled(!$section_id)>
                            <option value="">Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">
                                    {{ $student->full_name ?: $student->name ?? '-' }}
                                    - {{ $student->studentClass->name ?? '-' }}
                                    - {{ $student->section->name ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-3">
                        <label>Fee Type</label>
                        <select wire:model.live="fee_type_id"
                            class="form-control @error('fee_type_id') is-invalid @enderror">
                            <option value="">Select Fee Type</option>
                            @foreach ($feeTypes as $feeType)
                                <option value="{{ $feeType->id }}">{{ $feeType->name }}</option>
                            @endforeach
                        </select>
                        @error('fee_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Month</label>
                        <input type="number" wire:model.defer="month"
                            class="form-control @error('month') is-invalid @enderror" min="1" max="12">
                        @error('month')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Year</label>
                        <input type="number" wire:model.defer="year"
                            class="form-control @error('year') is-invalid @enderror">
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Amount</label>
                        <input type="number" step="0.01" wire:model.defer="amount"
                            class="form-control @error('amount') is-invalid @enderror">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-3">
                        <label>Due Date</label>
                        <input type="date" wire:model.defer="due_date"
                            class="form-control @error('due_date') is-invalid @enderror">
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Discount</label>
                        <input type="number" step="0.01" wire:model.defer="discount"
                            class="form-control @error('discount') is-invalid @enderror">
                        @error('discount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Fine</label>
                        <input type="number" step="0.01" wire:model.defer="fine"
                            class="form-control @error('fine') is-invalid @enderror">
                        @error('fine')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Remarks</label>
                        <input type="text" wire:model.defer="remarks"
                            class="form-control @error('remarks') is-invalid @enderror">
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save Fee</button>
                <a href="{{ route('student-fees.index') }}" class="btn btn-secondary mt-3">Back</a>
            </form>
        </div>
    </div>
</div>
