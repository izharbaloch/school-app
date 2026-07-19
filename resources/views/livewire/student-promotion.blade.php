<div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Promotion Form -->
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header"><h4><i class="fas fa-level-up-alt mr-2"></i>Promote Students</h4></div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        Select source class, choose students, then select destination class and promote.
                    </div>

                    <div class="form-group">
                        <label>From Class <span class="text-danger">*</span></label>
                        <select class="form-control @error('from_class_id') is-invalid @enderror" wire:model.live="from_class_id">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('from_class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>From Section (optional)</label>
                        <select class="form-control" wire:model="from_section_id">
                            <option value="">All Sections</option>
                            @foreach($this->fromSections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Reference Exam (optional)</label>
                        <select class="form-control" wire:model="exam_id">
                            <option value="">No Specific Exam</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->academic_year }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="only_passed" wire:model="promote_only_passed">
                            <label class="custom-control-label" for="only_passed">Auto-select only passing students</label>
                        </div>
                    </div>

                    <button class="btn btn-info btn-block" wire:click="loadStudents" wire:loading.attr="disabled">
                        <span wire:loading wire:target="loadStudents"><span class="spinner-border spinner-border-sm"></span></span>
                        <i class="fas fa-search"></i> Load Students
                    </button>

                    @if($studentsLoaded && count($studentList) > 0)
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Select Students ({{ count($selectedStudents) }}/{{ count($studentList) }})</h6>
                        <div>
                            <button class="btn btn-xs btn-outline-primary" wire:click="selectAll">All</button>
                            <button class="btn btn-xs btn-outline-secondary ml-1" wire:click="deselectAll">None</button>
                        </div>
                    </div>
                    <div style="max-height:200px; overflow-y:auto; border:1px solid #dee2e6; border-radius:4px; padding:8px;">
                        @foreach($studentList as $s)
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="stu_{{ $s['id'] }}"
                                wire:model="selectedStudents" value="{{ $s['id'] }}">
                            <label class="custom-control-label" for="stu_{{ $s['id'] }}">
                                {{ $s['name'] }}
                                @if($s['roll_no']) <small class="text-muted">(Roll: {{ $s['roll_no'] }})</small> @endif
                                @if($s['is_failed']) <span class="badge badge-danger badge-sm ml-1">Failed</span> @endif
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('selectedStudents') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

                    <hr>
                    <div class="form-group">
                        <label>Promote To Class <span class="text-danger">*</span></label>
                        <select class="form-control @error('to_class_id') is-invalid @enderror" wire:model.live="to_class_id">
                            <option value="">Select Target Class</option>
                            @foreach($classes as $class)
                                @if($class->id != $from_class_id)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('to_class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>To Section (optional)</label>
                        <select class="form-control" wire:model="to_section_id">
                            <option value="">Keep Current / Auto Assign</option>
                            @foreach($this->toSections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-success btn-block" wire:click="promote" wire:loading.attr="disabled"
                        wire:confirm="Promote {{ count($selectedStudents) }} student(s) to selected class?">
                        <span wire:loading wire:target="promote"><span class="spinner-border spinner-border-sm"></span></span>
                        <i class="fas fa-level-up-alt mr-1"></i> Promote {{ count($selectedStudents) }} Student(s)
                    </button>
                    @endif

                    @if($studentsLoaded && count($studentList) === 0)
                    <div class="alert alert-warning mt-3">No active students found in selected class/section.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Promotion History -->
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><h4><i class="fas fa-history mr-2"></i>Promotion History</h4></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead>
                                <tr><th>Student</th><th>From Class</th><th>To Class</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                @forelse($history as $promo)
                                <tr>
                                    <td>{{ $promo->student->first_name ?? '' }} {{ $promo->student->last_name ?? '' }}</td>
                                    <td>{{ $promo->fromClass->name ?? '-' }}</td>
                                    <td><i class="fas fa-arrow-right text-success mr-1"></i>{{ $promo->toClass->name ?? '-' }}</td>
                                    <td>{{ $promo->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No promotion history.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($history->hasPages()) <div class="card-footer">{{ $history->links() }}</div> @endif
            </div>
        </div>
    </div>
</div>
