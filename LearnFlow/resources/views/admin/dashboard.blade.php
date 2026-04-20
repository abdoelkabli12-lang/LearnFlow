@extends('layouts.test-page')

@section('title', 'Admin Dashboard')
@section('lead', 'Oversee users, categories, and course operations across the LearnFlow platform.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Admin dashboard</h2>
            <p>Your admin workspace is ready.</p>
            <p class="meta">User: {{ $user->name }} ({{ $user->email }})</p>
        </section>

        <section class="card">
            <h2>Quick links</h2>
            <div class="actions">
                <a class="button" href="{{ route('admin.users.index') }}">Manage users</a>
                <a class="button secondary" href="{{ route('host.courses.index') }}">Manage courses</a>
                <a class="button secondary" href="{{ route('courses.index') }}">Browse courses</a>
                <a class="button secondary" href="{{ route('profile.show') }}">Profile</a>
            </div>
        </section>
    </div>
@endsection
