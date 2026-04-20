@extends('layouts.test-page')

@section('title', 'Subscriber Dashboard')
@section('lead', 'Browse premium learning paths and keep your account details ready for enrollment.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Subscriber dashboard</h2>
            <p>Your subscriber workspace is ready.</p>
            <p class="meta">User: {{ $user->name }} ({{ $user->email }})</p>
        </section>

        <section class="card">
            <h2>Quick links</h2>
            <div class="actions">
                <a class="button" href="{{ route('courses.index') }}">Browse courses</a>
                <a class="button secondary" href="{{ route('profile.show') }}">Profile</a>
                <a class="button secondary" href="{{ route('password.show') }}">Change password</a>
            </div>
        </section>
    </div>
@endsection
