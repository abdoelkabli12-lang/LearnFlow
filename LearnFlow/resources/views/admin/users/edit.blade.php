@extends('layouts.test-page')

@section('title', 'Edit User')
@section('lead', 'Adjust user roles and keep account access aligned with each person’s responsibilities.')

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
                    <button type="submit">Save role</button>
                    <a class="button secondary" href="{{ route('admin.users.show', $user) }}">Cancel</a>
                </div>
            </form>
        </section>

        <section class="card">
            <h2>Danger zone</h2>
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                @csrf
                @method('DELETE')
                <button class="danger" type="submit">Delete user</button>
            </form>
        </section>
    </div>
@endsection
