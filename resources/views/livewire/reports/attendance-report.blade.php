<div>
{{-- Filters --}}
<div class="row mb-3 no-print align-items-end">
    <div class="col-md-3">
        <label class="small font-weight-bold">Class</label>
        <select wire:model="filter_class" class="form-control form-control-sm">
            <option value="">All Classes</option>
            @foreach ($classes as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="small font-weight-bold">From Date</label>
        <input type="date" wire:model="filter_from" class="form-control form-control-sm">
    </div>
    <div class="col-md-2">
        <label class="small font-weight-bold">To Date</label>
        <input type="date" wire:model="filter_to" class="form-control form-control-sm">
    </div>
    <div class="col-md-2">
        <div class="form-control form-control-sm bg-light text-center font-weight-bold">
            {{ $workingDays }} school day{{ $workingDays != 1 ? 's' : '' }}
        </div>
        <small class="text-muted">Days marked in range</small>
    </div>
</div>

{{-- Table --}}
<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover mb-0" style="font-size:.85rem">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Adm No.</th>
                <th>Student Name</th>
                <th>Class</th>
                <th>Section</th>
                <th class="text-center text-success">Present</th>
                <th class="text-center text-danger">Absent</th>
                <th class="text-center text-warning">Leave</th>
                <th class="text-center">Marked</th>
                <th class="text-center">%</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $i => $row)
            @php
                $pct = $row->attendance_pct;
                $pctClass = $pct >= 75 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><small>{{ $row->admission_no ?? '—' }}</small></td>
                <td class="font-weight-medium">{{ $row->first_name }} {{ $row->last_name }}</td>
                <td>{{ $row->class_name ?? '—' }}</td>
                <td>{{ $row->section_name ?? '—' }}</td>
                <td class="text-center font-weight-bold text-success">{{ $row->present_count }}</td>
                <td class="text-center font-weight-bold text-danger">{{ $row->absent_count }}</td>
                <td class="text-center font-weight-bold text-warning">{{ $row->leave_count }}</td>
                <td class="text-center">{{ $row->marked_days }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $pctClass }}">{{ $pct }}%</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center text-muted py-4">
                    @if (!$filter_from || !$filter_to)
                        Select a date range to generate the report.
                    @else
                        No attendance data found for the selected filters.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
        @if ($rows->count() > 0)
        <tfoot class="thead-light">
            <tr>
                <th colspan="5" class="text-right">Totals ({{ $rows->count() }} students)</th>
                <th class="text-center text-success">{{ $rows->sum('present_count') }}</th>
                <th class="text-center text-danger">{{ $rows->sum('absent_count') }}</th>
                <th class="text-center text-warning">{{ $rows->sum('leave_count') }}</th>
                <th class="text-center">{{ $rows->sum('marked_days') }}</th>
                <th class="text-center">
                    @php
                        $totalMarked = $rows->sum('marked_days');
                        $avgPct = $totalMarked > 0 ? round(($rows->sum('present_count') / $totalMarked) * 100, 1) : 0;
                    @endphp
                    {{ $avgPct }}%
                </th>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
</div>
