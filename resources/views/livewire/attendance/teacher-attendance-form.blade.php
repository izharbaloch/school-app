<div>
    <div class="card">
        <div class="card-header">
            <h4>{{ $isEdit ? 'Edit Teacher Attendance' : 'Mark Teacher Attendance' }}</h4>
        </div>
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="form-group row mb-3">
                <div class="col-md-6">
                    <label for="attendance_date" class="form-label">Attendance Date <span class="text-danger">*</span></label>
                    <input type="date" id="attendance_date" class="form-control @error('attendance_date') is-invalid @enderror"
                        wire:model.live="attendance_date" {{ $isEdit ? 'disabled' : '' }}>
                    @error('attendance_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <div class="col-md-12">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea id="remarks" class="form-control @error('remarks') is-invalid @enderror"
                        wire:model="remarks" rows="2"></textarea>
                    @error('remarks')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="table-responsive mt-4">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Employee No</th>
                            <th>Teacher Name</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teachers as $index => $teacher)
                            <tr>
                                <td>
                                    <small>{{ $teacher['employee_no'] }}</small>
                                </td>
                                <td>
                                    {{ $teacher['name'] }}
                                </td>
                                <td>
                                    <select class="form-select form-select-sm @error('teachers.' . $index . '.status') is-invalid @enderror"
                                        wire:model="teachers.{{ $index }}.status">
                                        <option value="">Select Status</option>
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="leave">Leave</option>
                                        <option value="late">Late</option>
                                    </select>
                                    @error('teachers.' . $index . '.status')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm @error('teachers.' . $index . '.remarks') is-invalid @enderror"
                                        wire:model="teachers.{{ $index }}.remarks"
                                        placeholder="Remarks">
                                    @error('teachers.' . $index . '.remarks')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">
                                    <p class="text-muted mb-0">No teachers found. Please select an attendance date first.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @error('teachers')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-4">
                <button type="button" class="btn btn-primary" wire:click="save">
                    <i class="fas fa-save"></i> {{ $isEdit ? 'Update Attendance' : 'Save Attendance' }}
                </button>
                <a href="{{ route('teacher-attendances.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </div>
    </div>
</div>
