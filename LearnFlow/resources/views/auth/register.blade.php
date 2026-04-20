@extends('layouts.test-page')

@section('title', 'Register')
@section('lead', 'Create your learner profile and start shaping a focused course catalog around your goals.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Create an account</h2>
            <form class="stack" method="POST" action="{{ route('register.store') }}">
                @csrf
                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="field">
                    <label for="phone">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <div class="actions">
                    <button type="submit">Create account</button>
                    <a class="button secondary" href="{{ route('login') }}">Back to login</a>
                </div>
            </form>
        </section>

        <section class="card">
            <h2>Your learning home</h2>
            <p>Use one profile for course discovery, enrollment history, progress, and account settings.</p>
        </section>
    </div>
@endsection
