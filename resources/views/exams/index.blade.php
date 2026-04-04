@extends('layouts.app')

@section('title', 'Exams')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Exams</h1>
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
                    <h4>Exam List</h4>
                    <a href="{{ route('exams.create') }}" class="btn btn-primary">Add Exam</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exams as $exam)
                                <tr>
                                    <td>{{ $loop->iteration + ($exams->currentPage() - 1) * $exams->perPage() }}</td>
                                    <td>{{ $exam->name }}</td>
                                    <td>{{ $exam->start_date?->format('d-m-Y') ?? '-' }}</td>
                                    <td>{{ $exam->end_date?->format('d-m-Y') ?? '-' }}</td>
                                    <td>{{ $exam->status ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        <a href="{{ route('exams.edit', $exam->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>

                                        <form action="{{ route('exams.destroy', $exam->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Delete this exam?')"
                                                class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No exams found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $exams->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
