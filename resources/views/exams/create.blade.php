@extends('layouts.app')

@section('title', 'Create Exam')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create Exam</h1>
    </div>

    <div class="section-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('exams.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Exam Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                        </div>

                        <div class="col-md-6">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="status" value="1" checked>
                            Active
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('exams.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
