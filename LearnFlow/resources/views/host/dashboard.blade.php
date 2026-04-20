@extends('layouts.test-page')

@section('title', 'Host Dashboard')
@section('lead', 'Manage course creation, publishing, and learner-facing content from one workspace.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Host dashboard</h2>
            <p>Your host workspace is ready.</p>
            <p class="meta">User: {{ $user->name }} ({{ $user->email }})</p>
        </section>

        <section class="card">
            <h2>Course tools</h2>
            <div class="actions">
                <a class="button" href="{{ route('host.courses.index') }}">Manage courses</a>
                <a class="button secondary" href="{{ route('host.courses.create') }}">Create course</a>
                <a class="button secondary" href="{{ route('courses.index') }}">Browse published courses</a>
            </div>
        </section>
    </div>
@endsection
