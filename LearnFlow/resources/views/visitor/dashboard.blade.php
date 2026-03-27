@extends('layouts.test-page')

@section('title', 'Visitor Dashboard')
@section('lead', 'Minimal dashboard page used when the controller returns the visitor dashboard view.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Visitor dashboard</h2>
            <p>You reached the visitor dashboard view.</p>
            <p class="meta">User: {{ $user->name }} ({{ $user->email }})</p>
        </section>

        <section class="card">
            <h2>Next checks</h2>
            <p>This is a simple landing page for newly registered visitors before you decide what they can access.</p>
            <div class="actions">
                <a class="button" href="{{ route('courses.index') }}">Browse courses</a>
                <a class="button secondary" href="{{ route('profile.show') }}">Profile</a>
                <a class="button secondary" href="{{ route('password.show') }}">Change password</a>
            </div>
        </section>
    </div>
@endsection
