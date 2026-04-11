@extends('layouts.app')

@section('title', 'Guardians')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Guardians</h1>
        </div>

        <div class="section-body">
            {{-- <p class="text-muted">This section is under development.</p> --}}

            <livewire:guardian-index />
        </div>
    </section>
@endsection
