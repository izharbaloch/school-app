@extends('layouts.app')

@section('title', 'Fee Types')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Fee Types</h1>
    </div>

    <div class="section-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Fee Type List</h4>
                <a href="{{ route('fee-types.create') }}" class="btn btn-primary">Add Fee Type</a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Monthly</th>
                                <th>Status</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feeTypes as $feeType)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $feeType->name }}</td>
                                    <td>{{ $feeType->is_monthly ? 'Yes' : 'No' }}</td>
                                    <td>{{ $feeType->status ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        <a href="{{ route('fee-types.edit', $feeType->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('fee-types.destroy', $feeType->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Delete this fee type?')" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No fee types found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $feeTypes->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
