@extends('layouts.test-page')

@section('title', 'Checkout')
@section('lead', 'Confirm the course details below to complete your enrollment.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Checkout</h2>
            <p><strong>Course:</strong> {{ $course->title }}</p>
            <p><strong>Host:</strong> {{ $course->user?->name ?? 'Unknown' }}</p>
            <p><strong>Level:</strong> {{ $course->level }}</p>
            <p><strong>Price:</strong> ${{ number_format((float) $course->price, 2) }}</p>
            <p>{{ $course->description }}</p>

            <form method="POST" action="{{ route('payment.store', $course) }}" style="margin-top: 1rem;">
                @csrf
                <button type="submit">Confirm enrollment</button>
            </form>
        </section>

        <section class="card">
            <h2>What happens next</h2>
            <ul class="list" style="margin-top: 1rem;">
                <li>An enrollment record is created for your account.</li>
                <li>A completed payment record is stored for this purchase.</li>
                <li>You can open protected lessons right away after checkout.</li>
            </ul>
        </section>
    </div>
@endsection
