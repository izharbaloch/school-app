<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- ── Status Summary Chips ── --}}
    <div class="row mb-3">
        @foreach ([
            'pending'      => ['label' => 'Pending',      'color' => 'secondary'],
            'under_review' => ['label' => 'Under Review', 'color' => 'info'],
            'accepted'     => ['label' => 'Accepted',     'color' => 'success'],
            'rejected'     => ['label' => 'Rejected',     'color' => 'danger'],
            'enrolled'     => ['label' => 'Enrolled',     'color' => 'primary'],
        ] as $key => $meta)
            <div class="col-auto mb-2">
                <span class="badge badge-{{ $meta['color'] }}" style="font-size:13px; padding:6px 12px; cursor:pointer;"
                    wire:click="$set('filter_status', '{{ $key }}')">
                    {{ $meta['label'] }}: {{ $statusCounts[$key] ?? 0 }}
                </span>
            </div>
        @endforeach
        @if ($filter_status)
            <div class="col-auto mb-2">
                <button class="btn btn-sm btn-light" wire:click="$set('filter_status', '')">
                    <i class="fas fa-times"></i> Clear Filter
                </button>
            </div>
        @endif
    </div>

    {{-- ── Filter + Add ── --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex gap-2" style="gap:8px;">
                <input type="text" class="form-control form-control-sm"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search name, app no, phone..." style="width:220px;">

                <select wire:model.live="filter_class" class="form-control form-control-sm" style="width:160px;">
                    <option value="">All Classes</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filter_status" class="form-control form-control-sm" style="width:160px;">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="under_review">Under Review</option>
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                    <option value="enrolled">Enrolled</option>
                </select>
            </div>

            @can('admissions.create')
                @if (!$showForm)
                    <button class="btn btn-primary btn-sm" wire:click="openCreate">
                        <i class="fas fa-plus mr-1"></i> New Application
                    </button>
                @endif
            @endcan
        </div>

        {{-- ── Inline Create / Edit Form ── --}}
        @if ($showForm)
        <div class="card-body border-bottom bg-light">
            <h5 class="mb-3">{{ $editId ? 'Edit Application' : 'New Admission Application' }}</h5>

            <form wire:submit.prevent="{{ $editId ? 'update' : 'save' }}">
                <div class="row">
                    {{-- Student Info --}}
                    <div class="col-12"><h6 class="text-muted mb-2">Student Information</h6></div>

                    <div class="form-group col-md-3">
                        <label>First Name <span class="text-danger">*</span></label>
                        <input type="text" wire:model.defer="first_name"
                            class="form-control @error('first_name') is-invalid @enderror" placeholder="First name">
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label>Last Name</label>
                        <input type="text" wire:model.defer="last_name" class="form-control" placeholder="Last name">
                    </div>
                    <div class="form-group col-md-2">
                        <label>Gender</label>
                        <select wire:model.defer="gender" class="form-control">
                            <option value="">Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Date of Birth</label>
                        <input type="date" wire:model.defer="date_of_birth" class="form-control">
                    </div>

                    {{-- Guardian Info --}}
                    <div class="col-12"><h6 class="text-muted mb-2 mt-1">Guardian Information</h6></div>

                    <div class="form-group col-md-3">
                        <label>Father Name <span class="text-danger">*</span></label>
                        <input type="text" wire:model.defer="father_name"
                            class="form-control @error('father_name') is-invalid @enderror" placeholder="Father's name">
                        @error('father_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label>Mother Name</label>
                        <input type="text" wire:model.defer="mother_name" class="form-control" placeholder="Mother's name">
                    </div>
                    <div class="form-group col-md-2">
                        <label>Phone</label>
                        <input type="text" wire:model.defer="guardian_phone" class="form-control" placeholder="Phone">
                    </div>
                    <div class="form-group col-md-2">
                        <label>CNIC</label>
                        <input type="text" wire:model.defer="guardian_cnic_no" class="form-control" placeholder="CNIC no">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Email</label>
                        <input type="email" wire:model.defer="guardian_email" class="form-control" placeholder="Guardian email">
                    </div>
                    <div class="form-group col-md-5">
                        <label>Address</label>
                        <input type="text" wire:model.defer="address" class="form-control" placeholder="Home address">
                    </div>

                    {{-- Application Details --}}
                    <div class="col-12"><h6 class="text-muted mb-2 mt-1">Application Details</h6></div>

                    <div class="form-group col-md-3">
                        <label>Applied Class <span class="text-danger">*</span></label>
                        <select wire:model.live="applied_class_id"
                            class="form-control @error('applied_class_id') is-invalid @enderror">
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('applied_class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group col-md-2">
                        <label>Section Preference</label>
                        <select wire:model.defer="applied_section_id" class="form-control" @disabled(!$applied_class_id)>
                            <option value="">Any</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section['id'] }}">{{ $section['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Academic Year <span class="text-danger">*</span></label>
                        <input type="text" wire:model.defer="academic_year"
                            class="form-control @error('academic_year') is-invalid @enderror"
                            placeholder="e.g. 2025-2026">
                        @error('academic_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label>Previous School</label>
                        <input type="text" wire:model.defer="previous_school" class="form-control" placeholder="Previous school name">
                    </div>
                    <div class="form-group col-md-8">
                        <label>Remarks</label>
                        <input type="text" wire:model.defer="remarks" class="form-control" placeholder="Any additional notes">
                    </div>
                </div>

                <div class="d-flex" style="gap:8px;">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="{{ $editId ? 'update' : 'save' }}">
                        <span wire:loading.remove wire:target="{{ $editId ? 'update' : 'save' }}"><i class="fas fa-save mr-1"></i> {{ $editId ? 'Update' : 'Submit Application' }}</span>
                        <span wire:loading wire:target="{{ $editId ? 'update' : 'save' }}"><i class="fas fa-spinner fa-spin mr-1"></i> {{ $editId ? 'Update' : 'Submit Application' }}</span>
                    </button>
                    <button type="button" class="btn btn-secondary" wire:click="cancel">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
        @endif

        {{-- ── Applications Table ── --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-md table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>App No.</th>
                            <th>Applicant</th>
                            <th>Father Name</th>
                            <th>Class</th>
                            <th>Year</th>
                            <th>Status</th>
                            <th>Applied On</th>
                            <th width="130">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admissions as $app)
                            <tr>
                                <td><small class="text-muted">{{ $app->application_no }}</small></td>
                                <td>
                                    <strong>{{ $app->full_name }}</strong>
                                    @if ($app->guardian_phone)
                                        <br><small class="text-muted">{{ $app->guardian_phone }}</small>
                                    @endif
                                </td>
                                <td>{{ $app->father_name }}</td>
                                <td>{{ $app->appliedClass->name ?? '—' }}</td>
                                <td>{{ $app->academic_year }}</td>
                                <td>
                                    <span class="badge badge-{{ $app->status_badge }}">
                                        {{ $app->status_label }}
                                    </span>
                                </td>
                                <td><small>{{ $app->created_at->format('d M Y') }}</small></td>
                                <td>
                                    <a href="{{ route('admissions.show', $app->id) }}"
                                        class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>

                                    @can('admissions.edit')
                                        @if (!in_array($app->status, ['enrolled']))
                                            <button class="btn btn-sm btn-outline-primary"
                                                wire:click="edit({{ $app->id }})" title="Edit"><i class="fas fa-edit"></i></button>
                                        @endif
                                    @endcan

                                    @can('admissions.delete')
                                        @if ($app->status === 'pending')
                                            <button class="btn btn-sm btn-outline-danger"
                                                wire:click="delete({{ $app->id }})"
                                                wire:confirm="Delete this application?"
                                                wire:loading.attr="disabled" wire:target="delete" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No applications found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $admissions->links() }}
        </div>
    </div>
</div>
