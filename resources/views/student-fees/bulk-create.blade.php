@extends('layouts.app')

@section('title', 'Generate Class Fee')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Generate Class Fee</h1>
        </div>

        <div class="section-body">
            <livewire:fees.student-fee-bulk-generate />
        </div>
    </section>
@endsection
