<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- ── Filter Card ── --}}
    <div class="card">
        <div class="card-header">
            <h4>Filter Schedule</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label>Exam <span class="text-danger">*</span></label>
                        <select wire:model.live="filter_exam_id" class="form-control">
                            <option value="">-- Select Exam --</option>
                            @foreach ($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->academic_year }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label>Class <span class="text-danger">*</span></label>
                        <select wire:model.live="filter_class_id" class="form-control" @disabled(!$filter_exam_id)>
                            <option value="">-- Select Class --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label>Section</label>
                        <select wire:model.live="filter_section_id" class="form-control" @disabled(!$filter_class_id)>
                            <option value="">All Sections</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    @if ($filter_exam_id && $filter_class_id && $schedules->count())
                        <a href="{{ route('exam-schedule.print', ['exam_id' => $filter_exam_id, 'class_id' => $filter_class_id]) }}"
                            target="_blank" class="btn btn-secondary btn-block" title="Print Timetable">
                            <i class="fas fa-print"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($filter_exam_id && $filter_class_id)

        {{-- ── Add / Edit Form ── --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>
                    @if ($showForm)
                        {{ $editId ? 'Edit Schedule Entry' : 'Add Schedule Entry' }}
                    @else
                        Schedule Entries
                    @endif
                </h4>
                @if (!$showForm)
                    @can('exams.create')
                        <button type="button" class="btn btn-primary" wire:click="openForm">
                            <i class="fas fa-plus mr-1"></i> Add Entry
                        </button>
                    @endcan
                @endif
            </div>

            <div class="card-body">
                @if ($showForm)
                    <form wire:submit.prevent="{{ $editId ? 'update' : 'save' }}">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Subject <span class="text-danger">*</span></label>
                                <select wire:model.defer="subject_id"
                                    class="form-control @error('subject_id') is-invalid @enderror">
                                    <option value="">-- Select Subject --</option>
                                    @foreach ($formSubjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Date <span class="text-danger">*</span></label>
                                <input type="date" wire:model.defer="date"
                                    class="form-control @error('date') is-invalid @enderror">
                                @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group col-md-2">
                                <label>Start Time <span class="text-danger">*</span></label>
                                <input type="time" wire:model.defer="start_time"
                                    class="form-control @error('start_time') is-invalid @enderror">
                                @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group col-md-2">
                                <label>End Time <span class="text-danger">*</span></label>
                                <input type="time" wire:model.defer="end_time"
                                    class="form-control @error('end_time') is-invalid @enderror">
                                @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Room / Hall</label>
                                <input type="text" wire:model.defer="room" placeholder="e.g. Hall A, Room 12"
                                    class="form-control @error('room') is-invalid @enderror">
                                @error('room') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group col-md-2">
                                <label>Status</label>
                                <select wire:model.defer="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="form-group col-md-7">
                                <label>Remarks</label>
                                <input type="text" wire:model.defer="remarks" placeholder="Optional notes"
                                    class="form-control @error('remarks') is-invalid @enderror">
                                @error('remarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-save mr-1"></i> {{ $editId ? 'Update' : 'Save' }}
                            </button>
                            <button type="button" class="btn btn-secondary" wire:click="cancel">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                        </div>
                    </form>
                @else
                    {{-- ── Schedule Table ── --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Duration</th>
                                    <th>Room</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                    <th width="130">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedules as $s)
                                    @php
                                        $start = \Carbon\Carbon::createFromTimeString($s->start_time);
                                        $end   = \Carbon\Carbon::createFromTimeString($s->end_time);
                                        $mins  = $start->diffInMinutes($end);
                                        $dur   = ($mins >= 60 ? floor($mins/60).'h ' : '') . ($mins % 60 ? ($mins % 60).'m' : '');
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $s->subject->name ?? '—' }}</strong></td>
                                        <td>{{ $s->date->format('d M Y') }}</td>
                                        <td>{{ $s->date->format('l') }}</td>
                                        <td>{{ $start->format('h:i A') }}</td>
                                        <td>{{ $end->format('h:i A') }}</td>
                                        <td>{{ $dur }}</td>
                                        <td>{{ $s->room ?? '—' }}</td>
                                        <td>{{ $s->section->name ?? 'All' }}</td>
                                        <td>
                                            @if($s->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('exams.edit')
                                                <button class="btn btn-sm btn-warning" wire:click="edit({{ $s->id }})">
                                                    Edit
                                                </button>
                                            @endcan
                                            @can('exams.delete')
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="delete({{ $s->id }})"
                                                    onclick="confirm('Delete this schedule entry?') || event.stopImmediatePropagation()">
                                                    Del
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
                                            No schedule entries found. Click <strong>Add Entry</strong> to begin.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-1"></i>
            Please select an <strong>Exam</strong> and a <strong>Class</strong> to view or manage the timetable.
        </div>
    @endif
</div>
