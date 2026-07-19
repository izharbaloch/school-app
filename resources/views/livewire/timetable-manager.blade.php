<div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Class Timetable</h4>
                    @can('timetable.create')
                    <button class="btn btn-primary" wire:click="openForm"><i class="fas fa-plus"></i> Add Period</button>
                    @endcan
                </div>

                @if($showForm)
                <div class="card-body border-bottom bg-light">
                    <h6 class="mb-3">{{ $timetableId ? 'Edit' : 'Add' }} Timetable Entry</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Class <span class="text-danger">*</span></label>
                                <select class="form-control @error('student_class_id') is-invalid @enderror" wire:model.live="student_class_id">
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('student_class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Section</label>
                                <select class="form-control" wire:model="section_id">
                                    <option value="">All Sections</option>
                                    @foreach($this->sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Subject <span class="text-danger">*</span></label>
                                <select class="form-control @error('subject_id') is-invalid @enderror" wire:model="subject_id">
                                    <option value="">Select Subject</option>
                                    @foreach($this->subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Teacher</label>
                                <select class="form-control" wire:model="teacher_id">
                                    <option value="">No Specific Teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Day <span class="text-danger">*</span></label>
                                <select class="form-control @error('day_of_week') is-invalid @enderror" wire:model="day_of_week">
                                    <option value="">Select Day</option>
                                    @foreach($days as $num => $day)
                                        <option value="{{ $num }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                                @error('day_of_week') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Start Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror" wire:model="start_time">
                                @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>End Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror" wire:model="end_time">
                                @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Room</label>
                                <input type="text" class="form-control" wire:model="room" placeholder="Room No.">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-success" wire:click="save" wire:loading.attr="disabled">
                            <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm"></span></span>
                            {{ $timetableId ? 'Update' : 'Save' }}
                        </button>
                        <button class="btn btn-secondary ml-2" wire:click="cancel">Cancel</button>
                    </div>
                </div>
                @endif

                <!-- Filters -->
                <div class="card-body border-bottom">
                    <div class="row">
                        <div class="col-md-4">
                            <select class="form-control" wire:model.live="filterClass">
                                <option value="">All Classes</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" wire:model.live="filterDay">
                                <option value="">All Days</option>
                                @foreach($days as $num => $day)
                                    <option value="{{ $num }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @php $grouped = $timetables->groupBy('day_of_week'); @endphp
                    @forelse($days as $dayNum => $dayName)
                        @if($grouped->has($dayNum))
                        <div class="p-3 border-bottom">
                            <h6 class="text-primary mb-2"><i class="fas fa-calendar-day mr-1"></i>{{ $dayName }}</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Time</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>Subject</th>
                                            <th>Teacher</th>
                                            <th>Room</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grouped[$dayNum] as $tt)
                                        <tr>
                                            <td><strong>{{ substr($tt->start_time,0,5) }} – {{ substr($tt->end_time,0,5) }}</strong></td>
                                            <td>{{ $tt->studentClass->name ?? '-' }}</td>
                                            <td>{{ $tt->section->name ?? 'All' }}</td>
                                            <td>{{ $tt->subject->name ?? '-' }}</td>
                                            <td>{{ $tt->teacher->name ?? '-' }}</td>
                                            <td>{{ $tt->room ?? '-' }}</td>
                                            <td>
                                                @can('timetable.edit')
                                                <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $tt->id }})" title="Edit"><i class="fas fa-edit"></i></button>
                                                @endcan
                                                @can('timetable.delete')
                                                <button class="btn btn-sm btn-outline-danger ml-1" wire:click="delete({{ $tt->id }})" wire:confirm="Delete?" title="Delete"><i class="fas fa-trash"></i></button>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    @empty
                    @endforelse
                    @if($timetables->isEmpty())
                    <div class="text-center text-muted py-5">No timetable entries found. Add periods to get started.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
