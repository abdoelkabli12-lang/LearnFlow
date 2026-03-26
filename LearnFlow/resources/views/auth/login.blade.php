@extends('layouts.test-page')

@section('title', 'Login')
@section('lead', 'Small login form for checking that GET /login renders and POST /login receives form data.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Login form</h2>
            <form class="stack" method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" placeholder="Password" required>
                </div>

                <label class="actions" for="remember">
                    <input id="remember" type="checkbox" name="remember" value="1" style="width: auto;">
                    <span>Remember me</span>
                </label>

                <div class="actions">
                    <button type="submit">Test POST /login</button>
                    <a class="button secondary" href="{{ route('register') }}">Go to register</a>
                </div>
            </form>
        </section>

        <section class="card">
            <h2>What this page proves</h2>
            <p>The controller can now return `auth.login` without a missing-view error.</p>
            <p class="meta">If the submit fails, that means the controller logic is the next thing to fix.</p>
        </section>
    </div>
@endsection
