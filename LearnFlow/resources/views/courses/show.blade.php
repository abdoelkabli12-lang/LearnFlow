@extends('layouts.test-page')

@section('title', $course->title)
@section('lead', 'Review the curriculum, modules, instructor details, and learner feedback before you enroll.')

@section('content')
    @php
        $currentEnrollment = auth()->check()
            ? auth()->user()->enrollments()
                ->where('course_id', $course->id)
                ->where('status', 'accepted')
                ->latest()
                ->first()
            : null;
        $myReview = auth()->check()
            ? $course->reviews->firstWhere('user_id', auth()->id())
            : null;
    @endphp

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
                @elseif ($currentEnrollment)
                    <div class="actions">
                        <a class="button" href="{{ route('enrollments.show', $currentEnrollment) }}">Continue learning</a>
                        <a class="button secondary" href="{{ route('enrollments.index') }}">My enrollments</a>
                    </div>
                @else
                    <div class="actions">
                        <a class="button" href="{{ route('payment.show', $course) }}">Enroll in this course</a>
                    </div>
                @endif
            @else
                <div class="actions">
                    <a class="button" href="{{ route('login') }}">Log in to enroll</a>
                    <a class="button secondary" href="{{ route('register') }}">Create an account</a>
                </div>
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

        <section class="card">
            <h2>Reviews</h2>
            <p class="meta">
                {{ $course->reviews->count() }} review(s) submitted for this course.
            </p>

            @if ($errors->has('review'))
                <p style="margin-top: 1rem; color: #b91c1c;">{{ $errors->first('review') }}</p>
            @endif

            @auth
                @if ($currentEnrollment && ! $myReview && auth()->id() !== $course->user_id)
                    <form class="stack" method="POST" action="{{ route('reviews.store', $course) }}" style="margin-top: 1rem;">
                        @csrf

                        <div class="field">
                            <label for="review-rating">Rating</label>
                            <input id="review-rating" type="number" name="rating" min="1" max="5" value="{{ old('rating') }}" required>
                            @error('rating')
                                <p style="margin-top: 0.35rem; color: #b91c1c;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="review-comment">Comment</label>
                            <textarea id="review-comment" name="comment" rows="4" required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <p style="margin-top: 0.35rem; color: #b91c1c;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="actions">
                            <button type="submit">Post review</button>
                        </div>
                    </form>
                @elseif ($myReview)
                    <form class="stack" method="POST" action="{{ route('reviews.update', $myReview) }}" style="margin-top: 1rem;">
                        @csrf
                        @method('PATCH')

                        <div class="field">
                            <label for="edit-review-rating">Your rating</label>
                            <input id="edit-review-rating" type="number" name="rating" min="1" max="5" value="{{ old('rating', $myReview->rating) }}" required>
                            @error('rating')
                                <p style="margin-top: 0.35rem; color: #b91c1c;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="edit-review-comment">Your comment</label>
                            <textarea id="edit-review-comment" name="comment" rows="4" required>{{ old('comment', $myReview->comment) }}</textarea>
                            @error('comment')
                                <p style="margin-top: 0.35rem; color: #b91c1c;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="actions">
                            <button type="submit">Update review</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('reviews.destroy', $myReview) }}" style="margin-top: 0.75rem;">
                        @csrf
                        @method('DELETE')
                        <button class="danger" type="submit">Delete review</button>
                    </form>
                @elseif (auth()->id() === $course->user_id)
                    <p style="margin-top: 1rem;">You cannot review your own course.</p>
                @else
                    <p style="margin-top: 1rem;">Enroll in this course to leave a review.</p>
                @endif
            @else
                <p style="margin-top: 1rem;">Log in and enroll to leave a review.</p>
            @endauth

            <div class="stack" style="margin-top: 1rem;">
                @forelse ($course->reviews as $review)
                    <article class="card" style="padding: 1rem;">
                        <div class="split">
                            <strong>{{ $review->user->name }}</strong>
                            <span class="pill">{{ $review->rating }}/5</span>
                        </div>
                        <p style="margin-top: 0.75rem;">{{ $review->comment }}</p>
                    </article>
                @empty
                    <p style="margin-top: 1rem;">No reviews yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
