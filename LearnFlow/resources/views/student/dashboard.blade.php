@extends('layouts.test-page')

@section('title', 'Student Dashboard')
@section('lead', 'Minimal dashboard page used when the controller returns the student dashboard view.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Student dashboard</h2>
            <p>You reached the student dashboard view.</p>
            <p class="meta">User: {{ $user->name }} ({{ $user->email }})</p>
        </section>

        <section class="card">
            <h2>Next checks</h2>
            <p>This is the right place to plug in enrolled courses, progress, and recent activity later.</p>
            <div class="actions">
                <a class="button secondary" href="{{ route('profile.show') }}">Profile</a>
                <a class="button secondary" href="{{ route('password.show') }}">Change password</a>
            </div>
        </section>
    </div>
@endsection
