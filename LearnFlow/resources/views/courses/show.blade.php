<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $course->title }} | LearnFlow</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: "Inter", ui-sans-serif, system-ui, sans-serif;
        }

        .font-display {
            font-family: "Manrope", ui-sans-serif, system-ui, sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24;
            line-height: 1;
        }

        details > summary {
            list-style: none;
        }

        details > summary::-webkit-details-marker {
            display: none;
        }

        details[open] .details-chevron {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-gray-50 text-zinc-900">
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
        $isManager = auth()->check() && (auth()->user()->role === 'admin' || $course->user_id === auth()->id());
        $modules = $course->modules;
        $lessons = $modules->flatMap->lessons;
        $moduleCount = $modules->count();
        $lessonCount = $lessons->count();
        $totalDuration = (int) $modules->sum('duration') + (int) $lessons->sum('duration');
        $reviewCount = $course->reviews->count();
        $ratingValue = $averageRating ? number_format((float) $averageRating, 1) : '0.0';
        $ratingBreakdown = collect(range(5, 1))->mapWithKeys(function (int $rating) use ($course, $reviewCount) {
            $count = $course->reviews->where('rating', $rating)->count();

            return [$rating => $reviewCount > 0 ? round(($count / $reviewCount) * 100) : 0];
        });
        $learningItems = $lessons
            ->pluck('title')
            ->merge($modules->pluck('title'))
            ->filter()
            ->unique()
            ->take(4)
            ->values();
        $instructorName = $course->user?->name ?? 'Course Instructor';
        $instructorInitials = collect(explode(' ', $instructorName))
            ->filter()
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->take(2)
            ->implode('');
        $thumbnailUrl = $course->thumbnail ? asset('storage/' . ltrim($course->thumbnail, '/')) : null;
        $level = $course->level ? ucfirst($course->level) : 'All levels';
    @endphp

    <nav class="sticky top-0 z-50 border-b border-gray-200 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3 font-display text-lg font-extrabold text-zinc-900">
                    <span class="material-symbols-outlined text-blue-950">school</span>
                    LearnFlow
                </a>
                <form class="hidden items-center rounded-full bg-gray-100 px-4 py-2 md:flex" action="{{ route('courses.index') }}" method="GET" role="search">
                    <span class="material-symbols-outlined text-sm text-gray-500">search</span>
                    <input class="w-44 border-0 bg-transparent px-2 py-0 text-sm text-gray-700 outline-none focus:ring-0" type="search" name="search" placeholder="Search courses...">
                </form>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden items-center gap-7 text-sm font-semibold text-zinc-900 md:flex">
                    <a href="{{ route('courses.index') }}">Courses</a>
                    @auth
                        <a href="{{ route('enrollments.index') }}">My Learning</a>
                        @if (in_array(auth()->user()->role, ['host', 'admin'], true))
                            <a href="{{ route('host.courses.index') }}">Manage</a>
                        @endif
                    @endauth
                </div>

                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-full border border-gray-400 px-5 py-2 text-sm font-bold uppercase text-blue-950">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full bg-gradient-to-r from-blue-950 to-indigo-700 px-5 py-2 text-sm font-bold uppercase text-white">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-full border border-gray-400 px-5 py-2 text-sm font-bold uppercase text-blue-950">Log In</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-gradient-to-r from-blue-950 to-indigo-700 px-5 py-2 text-sm font-bold uppercase text-white">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 font-semibold text-emerald-700">{{ session('success') }}</div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 p-4 font-semibold text-red-700">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="max-w-3xl">
            <a href="{{ route('courses.index', ['category' => $course->category_id]) }}" class="inline-flex items-center gap-2 rounded-full bg-indigo-100/70 px-3 py-1.5 text-xs font-bold uppercase text-indigo-700">
                <span class="material-symbols-outlined text-sm">code</span>
                {{ $course->category?->name ?? 'Course' }}
            </a>

            <h1 class="font-display mt-4 max-w-4xl text-4xl font-extrabold leading-tight text-zinc-900 sm:text-5xl lg:text-6xl">
                {{ $course->title }}
            </h1>

            <p class="mt-5 max-w-2xl text-lg leading-8 text-gray-700">
                {{ $course->description ?: 'Master practical skills through structured modules, focused lessons, and hands-on learning designed to help you move with confidence.' }}
            </p>

            <div class="mt-8 flex flex-wrap items-center gap-5">
                <div class="flex items-center gap-4 rounded-full bg-white py-3 pl-3 pr-6 shadow-lg shadow-slate-900/5">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-sm font-black text-emerald-700 ring-4 ring-white">
                        {{ $instructorInitials ?: 'LF' }}
                    </div>
                    <div>
                        <p class="font-display font-bold text-zinc-900">{{ $instructorName }}</p>
                        <p class="text-sm text-gray-600">Course Instructor</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-full bg-white px-5 py-4 shadow-lg shadow-slate-900/5">
                    <div class="flex gap-0.5 text-blue-950" aria-hidden="true">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1, 'wght' 700, 'GRAD' 0, 'opsz' 20;">star</span>
                        @endfor
                    </div>
                    <span class="font-bold text-zinc-900">{{ $ratingValue }}</span>
                    <span class="text-sm text-gray-600">({{ number_format($reviewCount) }} {{ \Illuminate\Support\Str::plural('review', $reviewCount) }})</span>
                </div>
            </div>
        </section>

        <div class="mt-14 grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="space-y-16 lg:col-span-8">
                <section>
                    <h2 class="font-display text-2xl font-bold text-blue-950">About This Course</h2>
                    <div class="mt-5 max-w-2xl space-y-4 text-base leading-7 text-gray-700">
                        <p>{{ $course->description ?: 'This course is designed to help you build real skill through clear explanations, practical lessons, and a curriculum that moves from foundations into applied work.' }}</p>
                        <p>Learn through {{ $moduleCount }} {{ \Illuminate\Support\Str::plural('module', $moduleCount) }} and {{ $lessonCount }} {{ \Illuminate\Support\Str::plural('lesson', $lessonCount) }} built for {{ strtolower($level) }} learners.</p>
                    </div>
                </section>

                <section class="rounded-3xl bg-zinc-100 p-6 shadow-sm sm:p-8">
                    <h2 class="font-display text-2xl font-bold text-blue-950">What You'll Learn</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @forelse ($learningItems as $item)
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined mt-0.5 text-xl text-teal-800">check_circle</span>
                                <span class="text-sm font-medium leading-6 text-zinc-900">{{ $item }}</span>
                            </div>
                        @empty
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined mt-0.5 text-xl text-teal-800">check_circle</span>
                                <span class="text-sm font-medium leading-6 text-zinc-900">Structured course foundations</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined mt-0.5 text-xl text-teal-800">check_circle</span>
                                <span class="text-sm font-medium leading-6 text-zinc-900">Project-ready learning workflow</span>
                            </div>
                        @endforelse
                    </div>
                </section>

                <section>
                    <div class="flex flex-wrap items-end justify-between gap-3">
                        <h2 class="font-display text-2xl font-bold text-blue-950">Curriculum</h2>
                        <p class="text-sm font-semibold text-gray-600">
                            {{ $moduleCount }} {{ \Illuminate\Support\Str::plural('Module', $moduleCount) }} - {{ $lessonCount }} {{ \Illuminate\Support\Str::plural('Lesson', $lessonCount) }}
                        </p>
                    </div>

                    <div class="mt-6 space-y-4">
                        @forelse ($modules as $module)
                            <details class="overflow-hidden rounded-3xl bg-white shadow-lg shadow-slate-900/5" {{ $loop->first ? 'open' : '' }}>
                                <summary class="flex cursor-pointer items-center justify-between gap-4 p-6 transition hover:bg-gray-50">
                                    <span>
                                        <span class="block text-xs font-bold uppercase text-indigo-700">Module {{ $module->order_number }}</span>
                                        <span class="font-display mt-1 block text-lg font-bold text-zinc-900">{{ $module->title }}</span>
                                    </span>
                                    <span class="material-symbols-outlined details-chevron text-gray-700 transition-transform">expand_more</span>
                                </summary>

                                <div class="border-t border-gray-100 p-2">
                                    @forelse ($module->lessons as $lesson)
                                        <a href="{{ route('lessons.show', $lesson) }}" class="flex items-center justify-between gap-4 rounded-2xl p-4 transition hover:bg-gray-50">
                                            <span class="flex min-w-0 items-center gap-3">
                                                <span class="material-symbols-outlined text-gray-600">
                                                    {{ $lesson->type === 'video' ? 'play_circle' : ($lesson->type === 'document' ? 'description' : 'article') }}
                                                </span>
                                                <span class="truncate text-sm font-semibold text-zinc-900">{{ $lesson->title }}</span>
                                            </span>
                                            <span class="shrink-0 text-sm text-gray-600">
                                                {{ $lesson->duration ? $lesson->duration . ' min' : ucfirst($lesson->type) }}
                                                @if ($lesson->is_free)
                                                    - free
                                                @endif
                                            </span>
                                        </a>
                                    @empty
                                        <p class="p-4 text-sm font-medium text-gray-600">No lessons added yet.</p>
                                    @endforelse
                                </div>
                            </details>
                        @empty
                            <div class="rounded-3xl border border-dashed border-gray-300 bg-white p-8 text-center text-gray-600">
                                This course has no modules yet.
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="pb-16">
                    <h2 class="font-display text-2xl font-bold text-blue-950">Student Reviews</h2>

                    <div class="mt-6 max-w-xl rounded-3xl bg-white p-8 shadow-lg shadow-slate-900/5">
                        <div class="flex flex-col gap-8 sm:flex-row sm:items-center">
                            <div class="min-w-36 text-center sm:text-left">
                                <div class="font-display text-6xl font-black leading-none text-zinc-900">{{ $ratingValue }}</div>
                                <div class="mt-3 flex justify-center gap-1 text-blue-950 sm:justify-start" aria-hidden="true">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1, 'wght' 700, 'GRAD' 0, 'opsz' 20;">star</span>
                                    @endfor
                                </div>
                                <p class="mt-2 text-sm font-medium text-gray-600">{{ number_format($reviewCount) }} {{ \Illuminate\Support\Str::plural('rating', $reviewCount) }}</p>
                            </div>

                            <div class="flex-1 space-y-2">
                                @foreach ($ratingBreakdown as $rating => $percent)
                                    <div class="grid grid-cols-[1rem_1fr_2.5rem] items-center gap-3">
                                        <span class="text-sm font-bold text-zinc-900">{{ $rating }}</span>
                                        <span class="h-3 overflow-hidden rounded-full bg-zinc-200">
                                            <span class="block h-full rounded-full bg-gradient-to-r from-blue-950 to-indigo-700" style="width: {{ $percent }}%;"></span>
                                        </span>
                                        <span class="text-right text-sm font-medium text-gray-600">{{ $percent }}%</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @if ($errors->has('review'))
                        <div class="mt-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">{{ $errors->first('review') }}</div>
                    @endif

                    @auth
                        @if ($currentEnrollment && ! $myReview && auth()->id() !== $course->user_id)
                            <form class="mt-6 max-w-xl space-y-4 rounded-3xl bg-white p-6 shadow-lg shadow-slate-900/5" method="POST" action="{{ route('reviews.store', $course) }}">
                                @csrf
                                <div>
                                    <label for="review-rating" class="block text-sm font-bold text-gray-700">Rating</label>
                                    <input id="review-rating" class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" type="number" name="rating" min="1" max="5" value="{{ old('rating') }}" required>
                                    @error('rating')
                                        <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="review-comment" class="block text-sm font-bold text-gray-700">Comment</label>
                                    <textarea id="review-comment" class="mt-2 min-h-32 w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" name="comment" required>{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="rounded-full bg-indigo-700 px-6 py-3 text-sm font-black uppercase text-white shadow-lg shadow-indigo-900/20 transition hover:bg-indigo-800">Post review</button>
                            </form>
                        @elseif ($myReview)
                            <form class="mt-6 max-w-xl space-y-4 rounded-3xl bg-white p-6 shadow-lg shadow-slate-900/5" method="POST" action="{{ route('reviews.update', $myReview) }}">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label for="edit-review-rating" class="block text-sm font-bold text-gray-700">Your rating</label>
                                    <input id="edit-review-rating" class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" type="number" name="rating" min="1" max="5" value="{{ old('rating', $myReview->rating) }}" required>
                                </div>
                                <div>
                                    <label for="edit-review-comment" class="block text-sm font-bold text-gray-700">Your comment</label>
                                    <textarea id="edit-review-comment" class="mt-2 min-h-32 w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" name="comment" required>{{ old('comment', $myReview->comment) }}</textarea>
                                </div>
                                <button type="submit" class="rounded-full bg-indigo-700 px-6 py-3 text-sm font-black uppercase text-white shadow-lg shadow-indigo-900/20 transition hover:bg-indigo-800">Update review</button>
                            </form>

                            <form class="mt-3" method="POST" action="{{ route('reviews.destroy', $myReview) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-full border border-gray-300 bg-white px-6 py-3 text-sm font-black uppercase text-gray-700 transition hover:bg-gray-50">Delete review</button>
                            </form>
                        @elseif (auth()->id() === $course->user_id)
                            <p class="mt-5 rounded-xl bg-gray-100 p-4 text-sm font-semibold text-gray-600">You cannot review your own course.</p>
                        @else
                            <p class="mt-5 rounded-xl bg-gray-100 p-4 text-sm font-semibold text-gray-600">Enroll in this course to leave a review.</p>
                        @endif
                    @else
                        <p class="mt-5 rounded-xl bg-gray-100 p-4 text-sm font-semibold text-gray-600">Log in and enroll to leave a review.</p>
                    @endauth

                    <div class="mt-8 max-w-xl space-y-4">
                        @forelse ($course->reviews as $review)
                            <article class="rounded-3xl bg-white p-5 shadow-lg shadow-slate-900/5">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-xs font-black text-indigo-700">
                                            {{ collect(explode(' ', $review->user->name))->filter()->map(fn ($name) => strtoupper(substr($name, 0, 1)))->take(2)->implode('') }}
                                        </div>
                                        <strong>{{ $review->user->name }}</strong>
                                    </div>
                                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-black text-indigo-700">{{ $review->rating }}/5</span>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-gray-700">{{ $review->comment }}</p>
                            </article>
                        @empty
                            <p class="rounded-3xl bg-white p-6 text-center font-medium text-gray-600 shadow-lg shadow-slate-900/5">No reviews yet.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <aside class="lg:col-span-4">
                <div class="sticky top-28 rounded-3xl border border-neutral-300/20 bg-white p-6 shadow-lg shadow-slate-900/5 sm:p-8">
                    <div class="relative aspect-video overflow-hidden rounded-2xl bg-gray-900">
                        @if ($thumbnailUrl)
                            <img class="h-full w-full object-cover opacity-80" src="{{ $thumbnailUrl }}" alt="{{ $course->title }} preview">
                        @else
                            <div class="h-full w-full bg-gradient-to-br from-gray-800 to-gray-950"></div>
                        @endif
                        <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                            <span class="flex h-16 w-16 items-center justify-center rounded-full bg-white/90 text-blue-950 shadow-lg backdrop-blur-sm">
                                <span class="material-symbols-outlined ml-1 text-4xl" style="font-variation-settings: 'FILL' 1, 'wght' 700, 'GRAD' 0, 'opsz' 40;">play_arrow</span>
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 flex items-end gap-2">
                        <span class="font-display text-4xl font-black leading-none text-zinc-900">${{ number_format((float) $course->price, 2) }}</span>
                        <span class="pb-1 text-sm font-semibold text-gray-500">{{ $course->is_published ? 'Published' : 'Draft' }}</span>
                    </div>

                    <div class="mt-6 space-y-3">
                        @auth
                            @if ($isManager)
                                <a href="{{ route('host.courses.show', $course) }}" class="flex w-full items-center justify-center rounded-full bg-gradient-to-r from-blue-950 to-indigo-700 px-6 py-4 text-sm font-black uppercase text-white shadow-lg shadow-indigo-900/20 transition hover:scale-[1.01]">Host workspace</a>
                                <a href="{{ route('host.courses.edit', $course) }}" class="flex w-full items-center justify-center rounded-full border border-gray-400 bg-white px-6 py-4 text-sm font-black uppercase text-blue-950 transition hover:bg-gray-50">Edit course</a>
                                <form method="POST" action="{{ route('host.courses.publish', $course) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full rounded-full border border-gray-400 bg-white px-6 py-4 text-sm font-black uppercase text-blue-950 transition hover:bg-gray-50">
                                        {{ $course->is_published ? 'Unpublish' : 'Publish' }}
                                    </button>
                                </form>
                            @elseif ($currentEnrollment)
                                <a href="{{ route('enrollments.show', $currentEnrollment) }}" class="flex w-full items-center justify-center rounded-full bg-gradient-to-r from-blue-950 to-indigo-700 px-6 py-4 text-sm font-black uppercase text-white shadow-lg shadow-indigo-900/20 transition hover:scale-[1.01]">Continue learning</a>
                                <a href="{{ route('enrollments.index') }}" class="flex w-full items-center justify-center rounded-full border border-gray-400 bg-white px-6 py-4 text-sm font-black uppercase text-blue-950 transition hover:bg-gray-50">My enrollments</a>
                            @else
                                <a href="{{ route('payment.show', $course) }}" class="flex w-full items-center justify-center rounded-full bg-gradient-to-r from-blue-950 to-indigo-700 px-6 py-4 text-sm font-black uppercase text-white shadow-lg shadow-indigo-900/20 transition hover:scale-[1.01]">Enroll now</a>
                                <a href="{{ route('courses.index') }}" class="flex w-full items-center justify-center rounded-full border border-gray-400 bg-white px-6 py-4 text-sm font-black uppercase text-blue-950 transition hover:bg-gray-50">Browse courses</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="flex w-full items-center justify-center rounded-full bg-gradient-to-r from-blue-950 to-indigo-700 px-6 py-4 text-sm font-black uppercase text-white shadow-lg shadow-indigo-900/20 transition hover:scale-[1.01]">Log in to enroll</a>
                            <a href="{{ route('register') }}" class="flex w-full items-center justify-center rounded-full border border-gray-400 bg-white px-6 py-4 text-sm font-black uppercase text-blue-950 transition hover:bg-gray-50">Create account</a>
                        @endauth
                    </div>

                    <div class="my-6 h-px bg-gray-200"></div>

                    <h3 class="text-sm font-bold uppercase text-zinc-900">Course Includes</h3>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center gap-3 text-sm text-zinc-900">
                            <span class="material-symbols-outlined text-blue-950">schedule</span>
                            <span>{{ $totalDuration ? round($totalDuration / 60, 1) . ' hours of content' : 'Flexible study time' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-zinc-900">
                            <span class="material-symbols-outlined text-blue-950">menu_book</span>
                            <span>{{ $lessonCount }} comprehensive {{ \Illuminate\Support\Str::plural('lesson', $lessonCount) }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-zinc-900">
                            <span class="material-symbols-outlined text-blue-950">workspace_premium</span>
                            <span>Certificate of completion</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-zinc-900">
                            <span class="material-symbols-outlined text-blue-950">all_inclusive</span>
                            <span>Full lifetime access</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>
</body>
</html>
