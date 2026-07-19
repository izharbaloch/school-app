<div>
{{-- Filters --}}
<div class="row mb-3 no-print align-items-end">
    <div class="col-md-2">
        <label class="small font-weight-bold">Class</label>
        <select wire:model="filter_class" class="form-control form-control-sm">
            <option value="">All Classes</option>
            @foreach ($classes as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="small font-weight-bold">Section</label>
        <select wire:model="filter_section" class="form-control form-control-sm">
            <option value="">All Sections</option>
            @foreach ($sections as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="small font-weight-bold">Gender</label>
        <select wire:model="filter_gender" class="form-control form-control-sm">
            <option value="">All</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="small font-weight-bold">Status</label>
        <select wire:model="filter_status" class="form-control form-control-sm">
            <option value="">All</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
</div>

{{-- Summary --}}
<div class="d-flex gap-3 mb-3 flex-wrap">
    <span class="badge badge-primary px-3 py-2" style="font-size:.85rem">
        Total: {{ $total }}
    </span>
    <span class="badge badge-info px-3 py-2" style="font-size:.85rem">
        Male: {{ $maleCount }}
    </span>
    <span class="badge badge-danger px-3 py-2" style="font-size:.85rem">
        Female: {{ $femaleCount }}
    </span>
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
                <th>Gender</th>
                <th>DOB</th>
                <th>Phone</th>
                <th>Guardian</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $i => $s)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><small>{{ $s->admission_no ?? '—' }}</small></td>
                <td class="font-weight-medium">{{ $s->full_name }}</td>
                <td>{{ $s->studentClass->name ?? '—' }}</td>
                <td>{{ $s->section->name ?? '—' }}</td>
                <td>{{ ucfirst($s->gender ?? '—') }}</td>
                <td class="text-nowrap">{{ $s->date_of_birth ? $s->date_of_birth->format('d M Y') : '—' }}</td>
                <td>{{ $s->phone ?? '—' }}</td>
                <td>{{ $s->guardian->father_name ?? ($s->father_name ?? '—') }}</td>
                <td>
                    @if ($s->status)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center text-muted py-4">No students match the selected filters.</td>
            </tr>
            @endforelse
        </tbody>
        @if ($students->count() > 0)
        <tfoot class="thead-light">
            <tr>
                <th colspan="3" class="text-right">Total: {{ $total }} students</th>
                <th colspan="7">Male: {{ $maleCount }} &nbsp;|&nbsp; Female: {{ $femaleCount }}</th>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
</div>
