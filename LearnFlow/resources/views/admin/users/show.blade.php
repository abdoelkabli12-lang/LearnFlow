@extends('layouts.test-page')

@section('title', 'Admin User Detail')
@section('lead', 'Minimal user detail page for the admin show route.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>User details</h2>
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Phone:</strong> {{ $user->phone }}</p>
            <p><strong>Role:</strong> {{ $user->role }}</p>
        </section>

        <section class="card">
            <h2>Actions</h2>
            <div class="actions">
                <a class="button secondary" href="{{ route('admin.users.edit', $user) }}">Edit user</a>
                <a class="button secondary" href="{{ route('admin.users.index') }}">Back to list</a>
            </div>
        </section>
    </div>
@endsection
