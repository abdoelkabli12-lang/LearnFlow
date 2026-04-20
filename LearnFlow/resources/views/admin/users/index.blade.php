@extends('layouts.test-page')

@section('title', 'Admin Users')
@section('lead', 'Review learner, host, and admin accounts across the platform.')

@section('content')
    <section class="card">
        <div class="split">
            <h2>Users</h2>
            <span class="pill">{{ $users->total() }} total</span>
        </div>

        @if ($users->count())
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td class="actions">
                                <a class="button secondary" href="{{ route('admin.users.show', $user) }}">Show</a>
                                <a class="button secondary" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No users are available yet.</p>
        @endif
    </section>
@endsection
