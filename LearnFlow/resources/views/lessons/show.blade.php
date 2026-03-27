@extends('layouts.test-page')

@section('title', $lesson->title)
@section('lead', 'Simple lesson viewer for testing access rules, free previews, and lesson content rendering.')

@section('content')
    @php
        $isManager = auth()->check() && (auth()->user()->role === 'admin' || $course->user_id === auth()->id());
        $backRoute = $isManager ? route('host.courses.show', $course) : route('courses.show', $course);
        $documentUrl = $lesson->type === 'document' ? asset('storage/' . ltrim($lesson->content, '/')) : null;
    @endphp

    <div class="grid">
        <section class="card">
            <div class="split">
                <div class="stack" style="gap: 0.45rem;">
                    <span class="eyebrow">Lesson viewer</span>
                    <h2>{{ $lesson->title }}</h2>
                    <p class="meta">
                        <strong>Course:</strong> {{ $course->title }}
                        · <strong>Module:</strong> {{ $lesson->module->title }}
                        · <strong>Type:</strong> {{ ucfirst($lesson->type) }}
                        · <strong>Duration:</strong> {{ $lesson->duration ? $lesson->duration . ' min' : 'Not set' }}
                    </p>
                </div>

                <div class="actions">
                    @if ($lesson->is_free)
                        <span class="pill">Free preview</span>
                    @else
                        <span class="pill">Protected lesson</span>
                    @endif
                    <a class="button secondary" href="{{ $backRoute }}">Back to course</a>
                </div>
            </div>

            <div class="metric-grid" style="margin-top: 1.25rem;">
                <article class="metric-card">
                    <span class="eyebrow">Lesson ID</span>
                    <strong>{{ $lesson->id }}</strong>
                    <p class="meta">Useful when you are testing updates and reorder responses.</p>
                </article>
                <article class="metric-card">
                    <span class="eyebrow">Order</span>
                    <strong>{{ $lesson->order_number }}</strong>
                    <p class="meta">Position inside the current module.</p>
                </article>
                <article class="metric-card">
                    <span class="eyebrow">Access</span>
                    <strong>{{ $lesson->is_free ? 'Open' : 'Enrolled only' }}</strong>
                    <p class="meta">This page is where your lesson access checks land.</p>
                </article>
            </div>
        </section>

        <section class="card">
            <span class="eyebrow">Rendered content</span>
            <h2 style="margin-top: 0.35rem;">Lesson body</h2>

            @if ($lesson->type === 'video')
                <div class="content-box" style="margin-top: 1rem;">
                    <p><strong>Video URL</strong></p>
                    <p class="mono">{{ $lesson->content }}</p>
                    <div class="actions" style="margin-top: 1rem;">
                        <a class="button" href="{{ $lesson->content }}" target="_blank" rel="noreferrer">Open video link</a>
                    </div>
                </div>
            @elseif ($lesson->type === 'document')
                <div class="content-box" style="margin-top: 1rem;">
                    <p><strong>Stored document</strong></p>
                    <p class="mono">{{ $lesson->content }}</p>
                    <div class="actions" style="margin-top: 1rem;">
                        <a class="button" href="{{ $documentUrl }}" target="_blank" rel="noreferrer">Open document</a>
                    </div>
                </div>
            @else
                <div class="content-box" style="margin-top: 1rem;">{{ $lesson->content }}</div>
            @endif
        </section>
    </div>
@endsection
