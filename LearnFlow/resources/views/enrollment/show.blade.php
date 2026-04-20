@extends('layouts.test-page')

@section('title', $enrollment->course?->title ? 'Enrollment: ' . $enrollment->course->title : 'Enrollment')
@section('lead', 'View enrollment details, payment status, and course structure in one place.')

@section('content')
    <div class="grid">
        <section class="card">
            <div class="split">
                <h2>{{ $enrollment->course?->title ?? 'Course unavailable' }}</h2>
                <a class="button secondary" href="{{ route('enrollments.index') }}">Back to enrollments</a>
            </div>

            <p class="meta">
                Status: {{ ucfirst($enrollment->status) }} |
                Progress: {{ $enrollment->progress }}% |
                Enrolled: {{ optional($enrollment->enrolled_at)->format('M d, Y') ?? 'Unknown' }}
            </p>

            <p>
                Category: {{ $enrollment->course?->category?->name ?? 'Uncategorized' }} |
                Host: {{ $enrollment->course?->user?->name ?? 'Unknown' }}
            </p>

            @if ($enrollment->payment)
                <p>
                    Payment: ${{ number_format((float) $enrollment->payment->amount, 2) }} |
                    Status: {{ ucfirst($enrollment->payment->status) }} |
                    Date: {{ optional($enrollment->payment->payment_date)->format('M d, Y H:i') ?? 'Unknown' }}
                </p>
            @endif

            @if ($enrollment->status !== 'cancelled')
                <form method="POST" action="{{ route('enrollments.cancel', $enrollment) }}" style="margin-top: 1rem;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="danger">Cancel enrollment</button>
                </form>
            @endif
        </section>

        <section class="card">
            <h2>Course modules</h2>

            @if ($enrollment->course && $enrollment->course->modules->isNotEmpty())
                <div class="stack" style="margin-top: 1rem;">
                    @foreach ($enrollment->course->modules as $module)
                        <article class="card" style="padding: 1rem;">
                            <h3>{{ $module->order_number }}. {{ $module->title }}</h3>
                            <p class="meta">Duration: {{ $module->duration ? $module->duration . ' min' : 'Not set' }}</p>

                            @if ($module->lessons->isNotEmpty())
                                <ul class="list">
                                    @foreach ($module->lessons as $lesson)
                                        <li>
                                            <a href="{{ route('lessons.show', $lesson) }}">
                                                {{ $lesson->order_number }}. {{ $lesson->title }}
                                            </a>
                                            @if ($lesson->is_free)
                                                <span class="pill" style="margin-left: 0.5rem;">Free</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No lessons in this module yet.</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <p style="margin-top: 1rem;">This course does not have modules yet.</p>
            @endif
        </section>
    </div>
@endsection
