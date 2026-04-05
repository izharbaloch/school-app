@extends('layouts.app')

@section('title', 'Student Fees')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Student Fees</h1>
    </div>

    <div class="section-body">
        <livewire:fees.student-fee-index />
    </div>
</section>
@endsection
