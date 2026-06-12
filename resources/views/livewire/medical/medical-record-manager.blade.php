<div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

@if ($selectedStudent)
{{-- ═══════════════════════════════════════════════
     STUDENT DETAIL VIEW
═══════════════════════════════════════════════ --}}
<div class="d-flex align-items-center mb-3">
    <button wire:click="deselectStudent" class="btn btn-sm btn-outline-secondary mr-3">
        <i class="fas fa-arrow-left"></i> Back
    </button>
    <h5 class="mb-0">
        {{ $selectedStudent->full_name }}
        <small class="text-muted">
            — {{ $selectedStudent->studentClass->name ?? '' }}
            {{ $selectedStudent->section ? '/ '.$selectedStudent->section->name : '' }}
        </small>
    </h5>
</div>

<div class="row">
    {{-- Medical Record Panel --}}
    <div class="col-md-5">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-heartbeat text-danger"></i> Medical Record</h6>
                @if ($canEdit && !$showRecordForm)
                <button wire:click="openRecordForm" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit"></i> {{ $selectedStudent->medicalRecord ? 'Edit' : 'Add' }}
                </button>
                @endif
            </div>
            <div class="card-body">

                @if ($showRecordForm)
                {{-- Record Edit Form --}}
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Blood Group</label>
                            <select wire:model.defer="blood_group" class="form-control form-control-sm @error('blood_group') is-invalid @enderror">
                                @foreach (['unknown','A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}">{{ $bg === 'unknown' ? 'Unknown' : $bg }}</option>
                                @endforeach
                            </select>
                            @error('blood_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Height (cm)</label>
                            <input type="number" step="0.1" wire:model.defer="height_cm" class="form-control form-control-sm @error('height_cm') is-invalid @enderror" placeholder="e.g. 160">
                            @error('height_cm')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Weight (kg)</label>
                            <input type="number" step="0.1" wire:model.defer="weight_kg" class="form-control form-control-sm @error('weight_kg') is-invalid @enderror" placeholder="e.g. 55">
                            @error('weight_kg')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Vision Left</label>
                            <input type="text" wire:model.defer="vision_left" class="form-control form-control-sm" placeholder="e.g. 6/6">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Vision Right</label>
                            <input type="text" wire:model.defer="vision_right" class="form-control form-control-sm" placeholder="e.g. 6/9">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-2">
                    <label class="small font-weight-bold">Allergies</label>
                    <textarea wire:model.defer="allergies" rows="2" class="form-control form-control-sm" placeholder="List any known allergies..."></textarea>
                </div>
                <div class="form-group mb-2">
                    <label class="small font-weight-bold">Chronic Conditions</label>
                    <textarea wire:model.defer="chronic_conditions" rows="2" class="form-control form-control-sm" placeholder="e.g. Asthma, Diabetes..."></textarea>
                </div>
                <div class="form-group mb-2">
                    <label class="small font-weight-bold">Disabilities</label>
                    <textarea wire:model.defer="disabilities" rows="2" class="form-control form-control-sm" placeholder="Any physical or learning disabilities..."></textarea>
                </div>

                <hr class="my-2">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Emergency Contact</label>
                            <input type="text" wire:model.defer="emergency_contact_name" class="form-control form-control-sm" placeholder="Name">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Contact Phone</label>
                            <input type="text" wire:model.defer="emergency_contact_phone" class="form-control form-control-sm" placeholder="Phone">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Doctor Name</label>
                            <input type="text" wire:model.defer="doctor_name" class="form-control form-control-sm" placeholder="Doctor">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Doctor Phone</label>
                            <input type="text" wire:model.defer="doctor_phone" class="form-control form-control-sm" placeholder="Phone">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-2">
                    <label class="small font-weight-bold">Notes</label>
                    <textarea wire:model.defer="notes" rows="2" class="form-control form-control-sm" placeholder="Additional notes..."></textarea>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button wire:click="saveRecord" class="btn btn-sm btn-success">Save</button>
                    <button wire:click="cancelForm" class="btn btn-sm btn-secondary">Cancel</button>
                </div>

                @elseif ($selectedStudent->medicalRecord)
                {{-- Record Display --}}
                @php $rec = $selectedStudent->medicalRecord; @endphp
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="h4 mb-0 text-danger font-weight-bold">
                                {{ $rec->blood_group === 'unknown' ? '?' : $rec->blood_group }}
                            </div>
                            <small class="text-muted">Blood Group</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="h5 mb-0">{{ $rec->height_cm ? $rec->height_cm.' cm' : '—' }}</div>
                            <small class="text-muted">Height</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="h5 mb-0">{{ $rec->weight_kg ? $rec->weight_kg.' kg' : '—' }}</div>
                            <small class="text-muted">Weight</small>
                        </div>
                    </div>
                </div>

                @if ($rec->bmi)
                <div class="alert alert-light py-1 text-center small mb-2">BMI: <strong>{{ $rec->bmi }}</strong></div>
                @endif

                @if ($rec->vision_left || $rec->vision_right)
                <div class="mb-2">
                    <span class="font-weight-bold small">Vision:</span>
                    L {{ $rec->vision_left ?? '—' }} / R {{ $rec->vision_right ?? '—' }}
                </div>
                @endif

                @if ($rec->allergies)
                <div class="alert alert-warning py-1 small mb-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Allergies:</strong> {{ $rec->allergies }}
                </div>
                @endif

                @if ($rec->chronic_conditions)
                <div class="mb-2 small"><strong>Chronic Conditions:</strong> {{ $rec->chronic_conditions }}</div>
                @endif

                @if ($rec->disabilities)
                <div class="mb-2 small"><strong>Disabilities:</strong> {{ $rec->disabilities }}</div>
                @endif

                @if ($rec->emergency_contact_name || $rec->doctor_name)
                <hr class="my-2">
                @if ($rec->emergency_contact_name)
                <div class="small mb-1">
                    <i class="fas fa-phone text-danger"></i>
                    <strong>Emergency:</strong> {{ $rec->emergency_contact_name }}
                    {{ $rec->emergency_contact_phone ? '— '.$rec->emergency_contact_phone : '' }}
                </div>
                @endif
                @if ($rec->doctor_name)
                <div class="small mb-1">
                    <i class="fas fa-user-md text-primary"></i>
                    <strong>Doctor:</strong> {{ $rec->doctor_name }}
                    {{ $rec->doctor_phone ? '— '.$rec->doctor_phone : '' }}
                </div>
                @endif
                @endif

                @if ($rec->notes)
                <div class="mt-2 small text-muted">{{ $rec->notes }}</div>
                @endif

                <div class="mt-2 text-muted" style="font-size:11px">
                    Last updated: {{ $rec->updated_at->format('d M Y') }}
                </div>

                @else
                <p class="text-muted text-center py-3">
                    <i class="fas fa-notes-medical fa-2x d-block mb-2"></i>
                    No medical record on file.
                    @if ($canEdit) Click <strong>Add</strong> to create one. @endif
                </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Vaccinations Panel --}}
    <div class="col-md-7">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-syringe text-success"></i> Vaccination History</h6>
                @if ($canEdit && !$showVaccinationForm)
                <button wire:click="openVaccinationForm" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-plus"></i> Add Vaccine
                </button>
                @endif
            </div>
            <div class="card-body p-0">

                @if ($showVaccinationForm)
                <div class="p-3 border-bottom">
                    <h6 class="mb-2">{{ $editVaccineId ? 'Edit Vaccination' : 'Record Vaccination' }}</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">Vaccine Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model.defer="vaccine_name"
                                       class="form-control form-control-sm @error('vaccine_name') is-invalid @enderror"
                                       placeholder="e.g. Hepatitis B, MMR, Polio">
                                @error('vaccine_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">Administered By</label>
                                <input type="text" wire:model.defer="administered_by"
                                       class="form-control form-control-sm" placeholder="Doctor / Clinic">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">Date Given <span class="text-danger">*</span></label>
                                <input type="date" wire:model.defer="date_administered"
                                       class="form-control form-control-sm @error('date_administered') is-invalid @enderror">
                                @error('date_administered')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">Next Due Date</label>
                                <input type="date" wire:model.defer="next_due_date"
                                       class="form-control form-control-sm @error('next_due_date') is-invalid @enderror">
                                @error('next_due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">Notes</label>
                                <input type="text" wire:model.defer="vaccine_notes"
                                       class="form-control form-control-sm" placeholder="Optional">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button wire:click="saveVaccination" class="btn btn-sm btn-success">Save</button>
                        <button wire:click="cancelForm" class="btn btn-sm btn-secondary">Cancel</button>
                    </div>
                </div>
                @endif

                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Vaccine</th>
                            <th>Date Given</th>
                            <th>Next Due</th>
                            <th>Given By</th>
                            @if ($canEdit)<th></th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($selectedStudent->vaccinations as $vac)
                        <tr class="{{ $vac->is_due ? 'table-warning' : '' }}">
                            <td>
                                {{ $vac->vaccine_name }}
                                @if ($vac->notes)
                                    <div class="text-muted small">{{ $vac->notes }}</div>
                                @endif
                            </td>
                            <td class="text-nowrap">{{ $vac->date_administered->format('d M Y') }}</td>
                            <td class="text-nowrap">
                                @if ($vac->next_due_date)
                                    <span class="{{ $vac->is_due ? 'text-danger font-weight-bold' : '' }}">
                                        {{ $vac->next_due_date->format('d M Y') }}
                                        @if ($vac->is_due) <i class="fas fa-exclamation-circle"></i> @endif
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $vac->administered_by ?? '—' }}</td>
                            @if ($canEdit)
                            <td class="text-nowrap">
                                <button wire:click="editVaccination({{ $vac->id }})" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="deleteVaccination({{ $vac->id }})"
                                        wire:confirm="Delete this vaccination record?"
                                        class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $canEdit ? 5 : 4 }}" class="text-center text-muted py-3">
                                No vaccinations recorded.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@else
{{-- ═══════════════════════════════════════════════
     STUDENT LIST VIEW
═══════════════════════════════════════════════ --}}
<div class="row mb-3">
    <div class="col-md-3">
        <select wire:model="filter_class" class="form-control form-control-sm">
            <option value="">-- All Classes --</option>
            @foreach ($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select wire:model="filter_blood_group" class="form-control form-control-sm">
            <option value="">-- All Blood Groups --</option>
            @foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-','unknown'] as $bg)
                <option value="{{ $bg }}">{{ $bg === 'unknown' ? 'Unknown' : $bg }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover mb-0">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Class / Section</th>
                <th class="text-center">Blood Group</th>
                <th>Allergies</th>
                <th>Last Updated</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
            <tr>
                <td>{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</td>
                <td class="font-weight-medium">{{ $student->full_name }}</td>
                <td>
                    {{ $student->studentClass->name ?? '—' }}
                    @if ($student->section) / {{ $student->section->name }} @endif
                </td>
                <td class="text-center">
                    @if ($student->medicalRecord && $student->medicalRecord->blood_group !== 'unknown')
                        <span class="badge badge-danger">{{ $student->medicalRecord->blood_group }}</span>
                    @else
                        <span class="text-muted small">Not set</span>
                    @endif
                </td>
                <td>
                    @if ($student->medicalRecord?->allergies)
                        <span class="badge badge-warning text-dark">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ Str::limit($student->medicalRecord->allergies, 40) }}
                        </span>
                    @else
                        <span class="text-muted small">None on record</span>
                    @endif
                </td>
                <td class="small text-muted">
                    {{ $student->medicalRecord ? $student->medicalRecord->updated_at->format('d M Y') : '—' }}
                </td>
                <td>
                    <button wire:click="selectStudent({{ $student->id }})"
                            class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-notes-medical"></i> View
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">No students found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $students->links() }}</div>
@endif
</div>
