@extends('layouts.test-page')

@section('title', 'My Enrollments')
@section('lead', 'Track the courses you have joined and jump back into your lessons.')

@section('content')
    <section class="card">
        <div class="split">
            <h2>My enrollments</h2>
            <a class="button secondary" href="{{ route('courses.index') }}">Browse courses</a>
        </div>

        @if ($enrollments->isEmpty())
            <p style="margin-top: 1rem;">You have not enrolled in any courses yet.</p>
        @else
            <div class="stack" style="margin-top: 1rem;">
                @foreach ($enrollments as $enrollment)
                    <article class="card" style="padding: 1rem;">
                        <div class="split">
                            <div>
                                <h3>{{ $enrollment->course?->title ?? 'Course unavailable' }}</h3>
                                <p class="meta">
                                    Status: {{ ucfirst($enrollment->status) }} |
                                    Enrolled: {{ optional($enrollment->enrolled_at)->format('M d, Y') ?? 'Unknown' }} |
                                    Progress: {{ $enrollment->progress }}%
                                </p>
                            </div>
                            <div class="actions">
                                <a class="button" href="{{ route('enrollments.show', $enrollment) }}">Open</a>
                                @if ($enrollment->status !== 'cancelled')
                                    <form method="POST" action="{{ route('enrollments.cancel', $enrollment) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="danger">Cancel</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
