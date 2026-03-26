@extends('layouts.test-page')

@section('title', 'Welcome')
@section('lead', 'This landing page now doubles as a route hub so you can quickly click through the pages returned by UserController.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Public routes</h2>
            <p class="meta">These should render without authentication.</p>
            <ul class="list">
                <li><a href="{{ route('home') }}">GET /</a></li>
                <li><a href="{{ route('login') }}">GET /login</a></li>
                <li><a href="{{ route('register') }}">GET /register</a></li>
            </ul>
        </section>

        <section class="card">
            <h2>Protected routes</h2>
            <p class="meta">These pages exist now, but they still require a logged-in user because the routes are wrapped in `auth` middleware.</p>
            <ul class="list">
                <li><a href="{{ route('dashboard') }}">GET /dashboard</a></li>
                <li><a href="{{ route('profile.show') }}">GET /profile</a></li>
                <li><a href="{{ route('password.show') }}">GET /change-password</a></li>
                <li><a href="{{ route('admin.users.index') }}">GET /admin/users</a></li>
            </ul>
        </section>

        <section class="card">
            <h2>Route notes</h2>
            <p>These views are intentionally small so you can focus on whether the route resolves, the middleware behaves, and the controller returns the expected page.</p>
            <p>If a form submission fails, that points to controller logic or database wiring rather than a missing view.</p>
        </section>
    </div>
@endsection
