@extends('layouts.app')

@section('title', 'Assign Single Student Fee')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Assign Single Student Fee</h1>
    </div>

    <div class="section-body">
        <livewire:fees.student-fee-create />
    </div>
</section>
@endsection
