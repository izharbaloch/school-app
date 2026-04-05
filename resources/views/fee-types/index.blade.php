@extends('layouts.app')

@section('title', 'Fee Types')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Fee Types</h1>
        </div>

        <div class="section-body">
            <livewire:fees.fee-type />
        </div>
    </section>
@endsection
