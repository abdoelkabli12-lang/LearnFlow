@extends('layouts.test-page')

@section('title', 'Admin User Edit')
@section('lead', 'Minimal edit form for testing the admin user edit, update, and delete routes.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Edit {{ $user->name }}</h2>

            <form class="stack" method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PATCH')

                <div class="field">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="actions">
                    <button type="submit">Test PATCH /admin/users/{{ $user->id }}</button>
                    <a class="button secondary" href="{{ route('admin.users.show', $user) }}">Cancel</a>
                </div>
            </form>
        </section>

        <section class="card">
            <h2>Danger zone</h2>
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                @csrf
                @method('DELETE')
                <button class="danger" type="submit">Test DELETE /admin/users/{{ $user->id }}</button>
            </form>
        </section>
    </div>
@endsection
