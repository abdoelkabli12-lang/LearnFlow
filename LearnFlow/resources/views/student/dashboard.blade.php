@extends('layouts.test-page')

@section('title', 'Student Dashboard')
@section('lead', 'Return to your active courses, track momentum, and discover what to learn next.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Student dashboard</h2>
            <p>Your student workspace is ready.</p>
            <p class="meta">User: {{ $user->name }} ({{ $user->email }})</p>
        </section>

        <section class="card">
            <h2>Next checks</h2>
            <p>Explore the catalog, refine your profile, or update account security before your next session.</p>
            <div class="actions">
                <a class="button" href="{{ route('courses.index') }}">Browse courses</a>
                <a class="button secondary" href="{{ route('profile.show') }}">Profile</a>
                <a class="button secondary" href="{{ route('password.show') }}">Change password</a>
            </div>
        </section>
    </div>
@endsection
