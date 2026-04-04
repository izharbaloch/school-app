@extends('layouts.app')

@section('title', 'Edit Exam')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Exam</h1>
        </div>

        <div class="section-body">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('exams.update', $exam->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Exam Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $exam->name) }}"
                                required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ old('start_date', $exam->start_date?->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-6">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ old('end_date', $exam->end_date?->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $exam->remarks) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="status" value="1"
                                    {{ old('status', $exam->status) ? 'checked' : '' }}>
                                Active
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('exams.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
