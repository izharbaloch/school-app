@extends('layouts.app')

@section('title', 'Assign Fees From Structure')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Assign Fees From Structure</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <p><strong>Student:</strong> {{ $student->full_name ?? $student->name }}</p>
                    <p><strong>Class:</strong> {{ $student->studentClass->name ?? '-' }}</p>

                    <form action="{{ route('students.store-assigned-fees', $student->id) }}" method="POST">
                        @csrf

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fee Type</th>
                                    <th>Amount</th>
                                    <th>Month</th>
                                    <th>Year</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($structures as $index => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $item->feeType->name ?? '-' }}
                                            <input type="hidden" name="fees[{{ $index }}][fee_type_id]"
                                                value="{{ $item->fee_type_id }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="fees[{{ $index }}][amount]"
                                                class="form-control" value="{{ $item->amount }}">
                                        </td>
                                        <td>
                                            <input type="number" name="fees[{{ $index }}][month]"
                                                class="form-control" min="1" max="12">
                                        </td>
                                        <td>
                                            <input type="number" name="fees[{{ $index }}][year]"
                                                class="form-control" value="{{ date('Y') }}">
                                        </td>
                                        <td>
                                            <input type="date" name="fees[{{ $index }}][due_date]"
                                                class="form-control">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No fee structures found for this class.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if ($structures->count())
                            <button class="btn btn-primary">Assign Fees</button>
                        @endif

                        <a href="{{ route('student-fees.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
