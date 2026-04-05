@extends('layouts.app')

@section('title', 'Collect Fee')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Collect Fee</h1>
        </div>

        <div class="section-body">
            <livewire:fees.fee-payment-create :studentFee="$studentFee" />
        </div>
    </section>
@endsection
