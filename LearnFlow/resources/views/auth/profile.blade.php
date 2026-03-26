@extends('layouts.test-page')

@section('title', 'Profile')
@section('lead', 'Simple profile page for testing the authenticated profile route and its update form.')

@section('content')
    <div class="grid">
        <section class="card">
            <div class="split">
                <h2>Your profile</h2>
                <span class="pill">Auth required</span>
            </div>

            <form class="stack" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" style="margin-top: 1rem;">
                @csrf
                @method('PATCH')

                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="field">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" placeholder="Short bio for testing">{{ old('bio', $user->bio ?? '') }}</textarea>
                </div>

                <div class="field">
                    <label for="avatar">Avatar</label>
                    <input id="avatar" type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp">
                </div>

                <button type="submit">Test PATCH /profile</button>
            </form>
        </section>

        <section class="card">
            <h2>Current user snapshot</h2>
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ $user->role }}</p>
        </section>
    </div>
@endsection
