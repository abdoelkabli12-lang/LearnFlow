@extends('layouts.test-page')

@section('title', 'Manage Courses')
@section('lead', 'Browse your courses and jump into the module and lesson workspace.')

@section('content')
    <section class="card">
        <div class="split">
            <div>
                <span class="eyebrow">Host control room</span>
                <h2 style="margin-top: 0.35rem;">Your courses</h2>
            </div>
            <a class="button" href="{{ route('host.courses.create') }}">Create course</a>
        </div>

        @if ($courses->count())
            <div class="course-card-grid">
                @foreach ($courses as $course)
                    <article class="module-card">
                        <div class="split">
                            <div class="actions">
                                <span class="pill">{{ $course->level }}</span>
                                <span class="pill">{{ $course->is_published ? 'Published' : 'Draft' }}</span>
                            </div>
                            <span class="pill mono">#{{ $course->id }}</span>
                        </div>

                        <h3 style="margin-top: 0.9rem;">{{ $course->title }}</h3>
                        <p class="meta">
                            {{ $course->category?->name ?? 'Uncategorized' }} ·
                            {{ $course->modules_count }} module(s) ·
                            {{ $course->lessons_count }} lesson(s)
                        </p>
                        <p style="margin-top: 0.75rem;">{{ \Illuminate\Support\Str::limit($course->description, 125) }}</p>

                        <div class="actions" style="margin-top: 1rem;">
                            <a class="button" href="{{ route('host.courses.show', $course) }}">Open workspace</a>
                            <a class="button secondary" href="{{ route('courses.show', $course) }}">Public view</a>
                            <a class="button secondary" href="{{ route('host.courses.edit', $course) }}">Edit</a>
                        </div>

                        <div class="actions" style="margin-top: 0.85rem;">
                            <form method="POST" action="{{ route('host.courses.publish', $course) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit">{{ $course->is_published ? 'Unpublish' : 'Publish' }}</button>
                            </form>
                            <form method="POST" action="{{ route('host.courses.destroy', $course) }}">
                                @csrf
                                @method('DELETE')
                                <button class="danger" type="submit">Delete</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            @if (method_exists($courses, 'links'))
                <div style="margin-top: 1rem;">
                    {{ $courses->links() }}
                </div>
            @endif
        @else
            <p style="margin-top: 1rem;">No courses yet. Create your first one to start building the catalog.</p>
        @endif
    </section>
@endsection
