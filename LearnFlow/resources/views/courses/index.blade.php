@extends('layouts.test-page')

@section('title', 'Courses')
@section('lead', 'Published course catalog with simple filters so you can test the public course browsing flow.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Filters</h2>
            <form class="stack" method="GET" action="{{ route('courses.index') }}">
                <div class="field">
                    <label for="search">Search</label>
                    <input id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Search by title">
                </div>

                <div class="field">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>
                                {{ $category->name }} ({{ $category->courses_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="level">Level</label>
                    <select id="level" name="level">
                        <option value="">All levels</option>
                        @foreach (['Beginner', 'Intermediate', 'Advanced', 'All'] as $level)
                            <option value="{{ $level }}" @selected(request('level') === $level)>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="actions">
                    <button type="submit">Apply filters</button>
                    <a class="button secondary" href="{{ route('courses.index') }}">Reset</a>
                </div>
            </form>
        </section>

        <section class="card">
            <h2>Course list</h2>
            @if ($courses->count())
                <div class="stack" style="margin-top: 1rem;">
                    @foreach ($courses as $course)
                        <article class="card" style="padding: 1rem;">
                            <div class="split">
                                <h3>{{ $course->title }}</h3>
                                <span class="pill">{{ $course->level }}</span>
                            </div>
                            <p class="meta">
                                Category: {{ $course->category?->name ?? 'Uncategorized' }} |
                                Host: {{ $course->user?->name ?? 'Unknown' }} |
                                Price: ${{ number_format((float) $course->price, 2) }}
                            </p>
                            <p>{{ \Illuminate\Support\Str::limit($course->description, 140) }}</p>
                            <div class="actions">
                                <a class="button secondary" href="{{ route('courses.show', $course) }}">View course</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <p style="margin-top: 1rem;">No published courses matched the current filters.</p>
            @endif
        </section>
    </div>
@endsection
