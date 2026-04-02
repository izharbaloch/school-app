@extends('layouts.app')

@section('title', 'Create Fee Type')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Create Fee Type</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('fee-types.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_monthly" value="1"
                                    {{ old('is_monthly') ? 'checked' : '' }}>
                                Is Monthly
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="status" value="1" checked>
                                Active
                            </label>
                        </div>

                        <button class="btn btn-primary">Save</button>
                        <a href="{{ route('fee-types.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
