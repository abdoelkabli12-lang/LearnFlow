@extends('layouts.test-page')

@section('title', $course->title)
@section('lead', 'Course detail page for testing public viewing, host previews, and module rendering.')

@section('content')
    <div class="grid">
        <section class="card">
            <div class="split">
                <h2>{{ $course->title }}</h2>
                <div class="actions">
                    <span class="pill">{{ $course->is_published ? 'Published' : 'Draft' }}</span>
                    <a class="button secondary" href="{{ route('courses.index') }}">Back to courses</a>
                    @auth
                        @if (auth()->user()->role === 'admin' || $course->user_id === auth()->id())
                            <a class="button secondary" href="{{ route('host.courses.show', $course) }}">Open host workspace</a>
                        @endif
                    @endauth
                </div>
            </div>
            <p class="meta">
                Category: {{ $course->category?->name ?? 'Uncategorized' }} |
                Host: {{ $course->user?->name ?? 'Unknown' }} |
                Level: {{ $course->level }} |
                Price: ${{ number_format((float) $course->price, 2) }}
            </p>
            <p><strong>Average rating:</strong> {{ $averageRating ? number_format((float) $averageRating, 1) : 'No ratings yet' }}</p>
            <p><strong>Total students:</strong> {{ $totalStudents }}</p>
            <p>{{ $course->description }}</p>

            @auth
                @if (auth()->user()->role === 'admin' || $course->user_id === auth()->id())
                    <div class="actions">
                        <a class="button secondary" href="{{ route('host.courses.edit', $course) }}">Edit course</a>
                        <form method="POST" action="{{ route('host.courses.publish', $course) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit">{{ $course->is_published ? 'Unpublish' : 'Publish' }}</button>
                        </form>
                    </div>
                @endif
            @endauth
        </section>

        <section class="card">
            <h2>Modules</h2>
            @if ($course->modules->count())
                <div class="stack" style="margin-top: 1rem;">
                    @foreach ($course->modules as $module)
                        <article class="card" id="module-{{ $module->id }}" style="padding: 1rem;">
                            <h3>{{ $module->order_number }}. {{ $module->title }}</h3>
                            <p class="meta">Duration: {{ $module->duration ? $module->duration . ' min' : 'Not set' }}</p>
                            @if ($module->lessons->count())
                                <ul class="list">
                                    @foreach ($module->lessons as $lesson)
                                        <li>
                                            <a href="{{ route('lessons.show', $lesson) }}">
                                                {{ $lesson->order_number }}. {{ $lesson->title }}
                                            </a>
                                            ({{ $lesson->type }})
                                            @if ($lesson->is_free)
                                                - free preview
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No lessons added yet.</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <p style="margin-top: 1rem;">This course has no modules yet.</p>
            @endif
        </section>
    </div>
@endsection
