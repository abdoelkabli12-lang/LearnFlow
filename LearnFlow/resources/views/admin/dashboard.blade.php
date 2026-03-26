@extends('layouts.test-page')

@section('title', 'Admin Dashboard')
@section('lead', 'Minimal admin dashboard returned by the role-aware dashboard action.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Admin dashboard</h2>
            <p>You reached the admin dashboard view.</p>
            <p class="meta">User: {{ $user->name }} ({{ $user->email }})</p>
        </section>

        <section class="card">
            <h2>Quick links</h2>
            <div class="actions">
                <a class="button" href="{{ route('admin.users.index') }}">Manage users</a>
                <a class="button secondary" href="{{ route('profile.show') }}">Profile</a>
            </div>
        </section>
    </div>
@endsection
