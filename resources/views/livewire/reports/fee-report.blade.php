<div>
{{-- Filters --}}
<div class="row mb-3 no-print align-items-end">
    <div class="col-md-2">
        <label class="small font-weight-bold">From Date</label>
        <input type="date" wire:model="filter_from" class="form-control form-control-sm">
    </div>
    <div class="col-md-2">
        <label class="small font-weight-bold">To Date</label>
        <input type="date" wire:model="filter_to" class="form-control form-control-sm">
    </div>
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
        <label class="small font-weight-bold">Payment Method</label>
        <select wire:model="filter_mode" class="form-control form-control-sm">
            <option value="">All Methods</option>
            <option value="cash">Cash</option>
            <option value="bank">Bank Transfer</option>
            <option value="cheque">Cheque</option>
            <option value="online">Online</option>
        </select>
    </div>
</div>

{{-- Summary Chips --}}
@if ($payments->count() > 0)
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card border-0 bg-success text-white text-center p-2">
            <div style="font-size:1.4rem;font-weight:800">Rs. {{ number_format($total) }}</div>
            <div style="font-size:.75rem;opacity:.85">Total Collected ({{ $payments->count() }} payments)</div>
        </div>
    </div>
    @foreach ($byMethod as $method => $amount)
    <div class="col-md-2">
        <div class="card border-0 bg-light text-center p-2">
            <div style="font-size:1.1rem;font-weight:700">Rs. {{ number_format($amount) }}</div>
            <div style="font-size:.75rem;color:#666">{{ ucfirst($method) }}</div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Table --}}
<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover mb-0" style="font-size:.85rem">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Student</th>
                <th>Adm No.</th>
                <th>Class</th>
                <th>Fee Type</th>
                <th>Method</th>
                <th class="text-right">Amount</th>
                <th>Receipt No.</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $i => $pay)
            @php $st = $pay->studentFee->student ?? null; @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="text-nowrap">{{ $pay->payment_date->format('d M Y') }}</td>
                <td class="font-weight-medium">{{ $st ? $st->full_name : '—' }}</td>
                <td><small>{{ $st->admission_no ?? '—' }}</small></td>
                <td>{{ $st->studentClass->name ?? '—' }}</td>
                <td>{{ $pay->studentFee->feeType->name ?? '—' }}</td>
                <td><span class="badge badge-secondary">{{ ucfirst($pay->payment_method ?? '—') }}</span></td>
                <td class="text-right font-weight-bold text-success">Rs. {{ number_format($pay->amount) }}</td>
                <td><small>{{ $pay->receipt_no ?? '—' }}</small></td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted py-4">No payments found for the selected filters.</td>
            </tr>
            @endforelse
        </tbody>
        @if ($payments->count() > 0)
        <tfoot class="thead-light">
            <tr>
                <th colspan="7" class="text-right">Grand Total ({{ $payments->count() }} payments)</th>
                <th class="text-right text-success">Rs. {{ number_format($total) }}</th>
                <th></th>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
</div>
