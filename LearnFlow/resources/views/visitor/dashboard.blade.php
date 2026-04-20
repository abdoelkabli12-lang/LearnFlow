@extends('layouts.test-page')

@section('title', 'Visitor Dashboard')
@section('lead', 'Start with the catalog, shape your profile, and decide where your learning journey begins.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Visitor dashboard</h2>
            <p>Your visitor workspace is ready.</p>
            <p class="meta">User: {{ $user->name }} ({{ $user->email }})</p>
        </section>

        <section class="card">
            <h2>Next checks</h2>
            <p>Begin with course discovery, then complete your profile when you are ready to enroll.</p>
            <div class="actions">
                <a class="button" href="{{ route('courses.index') }}">Browse courses</a>
                <a class="button secondary" href="{{ route('profile.show') }}">Profile</a>
                <a class="button secondary" href="{{ route('password.show') }}">Change password</a>
            </div>
        </section>
    </div>
@endsection
