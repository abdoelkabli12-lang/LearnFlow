@extends('layouts.test-page')

@section('title', 'Change Password')
@section('lead', 'Keep your LearnFlow account secure with a fresh password.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Change password</h2>
            <form class="stack" method="POST" action="{{ route('password.update') }}">
                @csrf

                <div class="field">
                    <label for="current_password">Current password</label>
                    <input id="current_password" type="password" name="current_password" required>
                </div>

                <div class="field">
                    <label for="password">New password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm new password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <button type="submit">Update password</button>
            </form>
        </section>

        <section class="card">
            <h2>Account security</h2>
            <p>Choose a password you do not reuse anywhere else and keep your active learning workspace protected.</p>
        </section>
    </div>
@endsection
