@extends('layouts.app')

@section('title', 'Edit Fee Type')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Fee Type</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('fee-types.update', $feeType->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $feeType->name) }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_monthly" value="1"
                                    {{ old('is_monthly', $feeType->is_monthly) ? 'checked' : '' }}>
                                Is Monthly
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="status" value="1"
                                    {{ old('status', $feeType->status) ? 'checked' : '' }}>
                                Active
                            </label>
                        </div>

                        <button class="btn btn-primary">Update</button>
                        <a href="{{ route('fee-types.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
