<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Amount</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($reportStats['all_amount'], 2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Paid Amount</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($reportStats['paid_amount'], 2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Pending Amount</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($reportStats['pending_amount'], 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Student Fee List</h4>
            <div>
                <a href="{{ route('student-fees.bulk-create') }}" class="btn btn-success mr-2">Generate Class Fee</a>
                <a href="{{ route('student-fees.create') }}" class="btn btn-primary">Assign Single Fee</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-2 mb-3">
                    <label>Class</label>
                    <select wire:model.live="student_class_id" class="form-control">
                        <option value="">Select Class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label>Section</label>
                    <select wire:model.live="section_id" class="form-control" @disabled(!$student_class_id)>
                        <option value="">Select Section</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Student</label>
                    <select wire:model.live="student_id" class="form-control">
                        <option value="">Select Student</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->full_name ?: $student->name ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Fee Type</label>
                    <select wire:model.live="fee_type_id" class="form-control">
                        <option value="">Select Fee Type</option>
                        @foreach ($feeTypes as $feeType)
                            <option value="{{ $feeType->id }}">{{ $feeType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label>Month</label>
                    <input type="number" wire:model.live="month" class="form-control" min="1" max="12">
                </div>

                <div class="col-md-2 mb-3">
                    <label>Year</label>
                    <input type="number" wire:model.live="year" class="form-control" min="2000" max="2100">
                </div>

                <div class="col-md-2 mb-3">
                    <label>Status</label>
                    <select wire:model.live="status" class="form-control">
                        <option value="">Select Status</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="partial">Partial</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>

                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-secondary w-100" wire:click="resetFilters">Reset Filters</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-md">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Slip No</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Fee Type</th>
                            <th>Month/Year</th>
                            <th>Payable</th>
                            <th>Paid</th>
                            <th>Remaining</th>
                            <th>Status</th>
                            <th width="340">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studentFees as $fee)
                            <tr>
                                <td>{{ $loop->iteration + ($studentFees->currentPage() - 1) * $studentFees->perPage() }}
                                </td>
                                <td>{{ $fee->slip_no ?? '-' }}</td>
                                <td>{{ $fee->student->full_name ?? ($fee->student->name ?? '-') }}</td>
                                <td>{{ $fee->student->studentClass->name ?? '-' }}</td>
                                <td>{{ $fee->student->section->name ?? '-' }}</td>
                                <td>{{ $fee->feeType->name ?? '-' }}</td>
                                <td>{{ $fee->month_name }}/{{ $fee->year ?? '-' }}</td>
                                <td>{{ number_format($fee->payable_amount, 2) }}</td>
                                <td>{{ number_format($fee->paid_amount, 2) }}</td>
                                <td>{{ number_format($fee->remaining_amount, 2) }}</td>
                                <td>{{ ucfirst($fee->status) }}</td>
                                <td>
                                    <a href="{{ route('student-fees.show', $fee->id) }}"
                                        class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('student-fees.payment.create', $fee->id) }}"
                                        class="btn btn-sm btn-success">Collect Fee</a>
                                    <a href="{{ route('student-fees.print-slip', $fee->id) }}" target="_blank"
                                        class="btn btn-sm btn-secondary">Print Slip</a>

                                    @if ($fee->paid_amount <= 0)
                                        <button type="button" class="btn btn-sm btn-danger"
                                            wire:click="delete({{ $fee->id }})"
                                            onclick="confirm('Delete this fee?') || event.stopImmediatePropagation()">
                                            Delete
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">No fee records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $studentFees->links() }}
        </div>
    </div>
</div>
