<div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <ul class="nav nav-tabs mb-4">
        @foreach(['general'=>'<i class="fas fa-school"></i> General','academic'=>'<i class="fas fa-graduation-cap"></i> Academic','fee'=>'<i class="fas fa-coins"></i> Fees','attendance'=>'<i class="fas fa-calendar-check"></i> Attendance','library'=>'<i class="fas fa-book"></i> Library'] as $tab => $label)
        <li class="nav-item">
            <a class="nav-link {{ $activeTab===$tab ? 'active' : '' }}" wire:click="$set('activeTab','{{ $tab }}')" href="#">{!! $label !!}</a>
        </li>
        @endforeach
    </ul>

    {{-- GENERAL --}}
    @if($activeTab === 'general')
    <div class="card">
        <div class="card-header"><h6 class="mb-0">General Information</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>School Name <span class="text-danger">*</span></label>
                        <input wire:model="school_name" type="text" class="form-control @error('school_name') is-invalid @enderror">
                        @error('school_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Phone</label>
                        <input wire:model="school_phone" type="text" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input wire:model="school_email" type="email" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Website</label>
                        <input wire:model="school_website" type="text" class="form-control" placeholder="https://...">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Address</label>
                        <textarea wire:model="school_address" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Established Year</label>
                        <input wire:model="established" type="number" class="form-control" placeholder="1990">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>School Motto</label>
                        <input wire:model="school_motto" type="text" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="d-block font-weight-bold">Email Notifications</label>
                        <div class="custom-control custom-switch mt-1">
                            <input wire:model="notifications_enabled" type="checkbox" class="custom-control-input"
                                   id="notifications_enabled">
                            <label class="custom-control-label" for="notifications_enabled">
                                Enable email notifications
                            </label>
                        </div>
                        <small class="text-muted">
                            When enabled, emails are sent for: admission decisions, fee notices, payment receipts, and leave approvals.
                        </small>
                    </div>
                </div>
            </div>
            <button wire:click="saveGeneral" class="btn btn-primary">
                <span wire:loading.remove wire:target="saveGeneral"><i class="fas fa-save"></i> Save General Settings</span>
                <span wire:loading wire:target="saveGeneral"><i class="fas fa-spinner fa-spin"></i> Saving...</span>
            </button>
        </div>
    </div>
    @endif

    {{-- ACADEMIC --}}
    @if($activeTab === 'academic')
    <div class="card">
        <div class="card-header"><h6 class="mb-0">Academic Settings</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Academic Year Start</label>
                        <input wire:model="academic_year_start" type="date" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Academic Year End</label>
                        <input wire:model="academic_year_end" type="date" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>School Start Time</label>
                        <input wire:model="school_timing_start" type="time" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>School End Time</label>
                        <input wire:model="school_timing_end" type="time" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Working Days (comma-separated: Mon,Tue,Wed,Thu,Fri)</label>
                        <input wire:model="working_days" type="text" class="form-control" placeholder="Mon,Tue,Wed,Thu,Fri">
                        <small class="text-muted">Example: Mon,Tue,Wed,Thu,Fri,Sat</small>
                    </div>
                </div>
            </div>
            <button wire:click="saveAcademic" class="btn btn-primary">
                <span wire:loading.remove wire:target="saveAcademic"><i class="fas fa-save"></i> Save Academic Settings</span>
                <span wire:loading wire:target="saveAcademic"><i class="fas fa-spinner fa-spin"></i> Saving...</span>
            </button>
        </div>
    </div>
    @endif

    {{-- FEE --}}
    @if($activeTab === 'fee')
    <div class="card">
        <div class="card-header"><h6 class="mb-0">Fee Settings</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Currency Symbol</label>
                        <input wire:model="currency_symbol" type="text" class="form-control" placeholder="Rs.">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fee Due Day of Month</label>
                        <input wire:model="fee_due_day" type="number" class="form-control @error('fee_due_day') is-invalid @enderror" min="1" max="31">
                        @error('fee_due_day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Late Fee Per Day (Rs.)</label>
                        <input wire:model="late_fee_per_day" type="number" step="0.01" class="form-control @error('late_fee_per_day') is-invalid @enderror">
                        @error('late_fee_per_day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Receipt No. Prefix</label>
                        <input wire:model="fee_receipt_prefix" type="text" class="form-control" placeholder="RCT">
                    </div>
                </div>
            </div>
            <button wire:click="saveFee" class="btn btn-primary">
                <span wire:loading.remove wire:target="saveFee"><i class="fas fa-save"></i> Save Fee Settings</span>
                <span wire:loading wire:target="saveFee"><i class="fas fa-spinner fa-spin"></i> Saving...</span>
            </button>
        </div>
    </div>
    @endif

    {{-- ATTENDANCE --}}
    @if($activeTab === 'attendance')
    <div class="card">
        <div class="card-header"><h6 class="mb-0">Attendance Settings</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Minimum Attendance % Required</label>
                        <div class="input-group">
                            <input wire:model="min_attendance_pct" type="number" class="form-control" min="0" max="100">
                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Mark Late After (minutes)</label>
                        <div class="input-group">
                            <input wire:model="late_mark_after" type="number" class="form-control" min="0">
                            <div class="input-group-append"><span class="input-group-text">min</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <button wire:click="saveAttendance" class="btn btn-primary">
                <span wire:loading.remove wire:target="saveAttendance"><i class="fas fa-save"></i> Save Attendance Settings</span>
                <span wire:loading wire:target="saveAttendance"><i class="fas fa-spinner fa-spin"></i> Saving...</span>
            </button>
        </div>
    </div>
    @endif

    {{-- LIBRARY --}}
    @if($activeTab === 'library')
    <div class="card">
        <div class="card-header"><h6 class="mb-0">Library Settings</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Max Books per Student</label>
                        <input wire:model="max_books_per_student" type="number" class="form-control" min="1">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Fine Per Day (Rs.)</label>
                        <input wire:model="fine_per_day" type="number" step="0.01" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Default Loan Period (days)</label>
                        <input wire:model="loan_period_days" type="number" class="form-control" min="1">
                    </div>
                </div>
            </div>
            <button wire:click="saveLibrary" class="btn btn-primary">
                <span wire:loading.remove wire:target="saveLibrary"><i class="fas fa-save"></i> Save Library Settings</span>
                <span wire:loading wire:target="saveLibrary"><i class="fas fa-spinner fa-spin"></i> Saving...</span>
            </button>
        </div>
    </div>
    @endif
</div>
