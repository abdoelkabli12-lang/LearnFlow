@extends('layouts.test-page')

@section('title', 'Login')
@section('lead', 'Step back into your courses, mentoring sessions, and learning workspace.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Welcome back</h2>
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
                    <button type="submit">Sign in</button>
                    <a class="button secondary" href="{{ route('register') }}">Go to register</a>
                </div>
            </form>
        </section>

        <section class="card">
            <h2>Continue learning</h2>
            <p>Pick up where you left off with saved enrollments, profile tools, and the latest published courses.</p>
            <p class="meta">New here? Create an account to start building your LearnFlow workspace.</p>
        </section>
    </div>
@endsection
