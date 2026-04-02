@extends('layouts.app')

@section('title', 'Fee Structures')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Fee Structures</h1>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Fee Structure List</h4>
                    <a href="{{ route('fee-structures.create') }}" class="btn btn-primary">Add Fee Structure</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Class</th>
                                <th>Fee Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feeStructures as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->studentClass->name ?? '-' }}</td>
                                    <td>{{ $item->feeType->name ?? '-' }}</td>
                                    <td>{{ number_format($item->amount, 2) }}</td>
                                    <td>{{ $item->status ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        <a href="{{ route('fee-structures.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('fee-structures.destroy', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Delete this fee structure?')"
                                                class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No fee structures found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $feeStructures->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
