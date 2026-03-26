@extends('layouts.test-page')

@section('title', 'Subscriber Dashboard')
@section('lead', 'Minimal dashboard page used when the controller returns the subscriber dashboard view.')

@section('content')
    <section class="card">
        <h2>Subscriber dashboard</h2>
        <p>You reached the subscriber dashboard view.</p>
        <p class="meta">User: {{ $user->name }} ({{ $user->email }})</p>
    </section>
@endsection
