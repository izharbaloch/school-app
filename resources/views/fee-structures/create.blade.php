@extends('layouts.app')

@section('title', 'Create Fee Structure')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Create Fee Structure</h1>
        </div>

        <div class="section-body">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('fee-structures.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Class</label>
                            <select name="student_class_id" class="form-control">
                                <option value="">Select Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Fee Type</label>
                            <select name="fee_type_id" class="form-control">
                                <option value="">Select Fee Type</option>
                                @foreach ($feeTypes as $feeType)
                                    <option value="{{ $feeType->id }}">{{ $feeType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control"
                                value="{{ old('amount') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="status" value="1" checked>
                                Active
                            </label>
                        </div>

                        <button class="btn btn-primary">Save</button>
                        <a href="{{ route('fee-structures.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
