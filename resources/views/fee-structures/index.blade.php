@extends('layouts.app')

@section('title', 'Fee Structures')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Fee Structures</h1>
        </div>

        <div class="section-body">
            <livewire:fees.fee-structure />
        </div>
    </section>
@endsection
