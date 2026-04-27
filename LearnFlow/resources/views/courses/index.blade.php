<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Explore Courses | LearnFlow</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: "Inter", ui-sans-serif, system-ui, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(99, 102, 241, 0.12), transparent 24rem),
                radial-gradient(circle at top right, rgba(20, 184, 166, 0.10), transparent 22rem),
                linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
        }

        .font-display {
            font-family: "Manrope", ui-sans-serif, system-ui, sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24;
            line-height: 1;
        }
    </style>
</head>
<body class="min-h-screen text-slate-900">
    @php
        $viewer = auth()->user();
        $viewerName = $viewer?->name ?? 'Guest Explorer';
        $viewerLabel = $viewer
            ? strtoupper(($viewer->role === 'student' ? 'Active learner' : $viewer->role) . ' account')
            : 'BROWSE PUBLIC CATALOG';
        $viewerInitials = collect(explode(' ', $viewerName))
            ->filter()
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->take(2)
            ->implode('');
        $viewerAvatar = $viewer?->avatar
            ? (\Illuminate\Support\Str::startsWith($viewer->avatar, ['http://', 'https://'])
                ? $viewer->avatar
                : asset('storage/' . ltrim($viewer->avatar, '/')))
            : null;

        $courseItems = $courses->getCollection()->values();
        $topCourses = $courseItems->take(4);
        $featuredCourse = $courseItems->count() > 4 ? $courseItems->get(4) : null;
        $bottomCourses = $courseItems->slice(5, 2);
        $remainingCourses = $courseItems->slice(7);

        $categoryTheme = [
            'development' => ['badge' => 'bg-indigo-50 text-indigo-900', 'panel' => 'from-indigo-950 via-indigo-800 to-indigo-600'],
            'design' => ['badge' => 'bg-amber-50 text-amber-900', 'panel' => 'from-amber-950 via-orange-700 to-amber-400'],
            'marketing' => ['badge' => 'bg-emerald-50 text-emerald-900', 'panel' => 'from-emerald-950 via-teal-700 to-emerald-400'],
            'business' => ['badge' => 'bg-slate-100 text-slate-900', 'panel' => 'from-slate-950 via-slate-700 to-slate-400'],
        ];

        $paginationWindowStart = max(1, $courses->currentPage() - 1);
        $paginationWindowEnd = min($courses->lastPage(), $courses->currentPage() + 1);
        $paginationPages = $courses->lastPage() > 0 ? range($paginationWindowStart, $paginationWindowEnd) : [];
    @endphp

    <nav class="sticky top-0 z-50 border-b border-white/50 bg-white/80 backdrop-blur-xl">
        <div class="mx-auto flex max-w-[1440px] items-center justify-between gap-6 px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="font-display text-xl font-extrabold tracking-[-0.06em] text-indigo-950">
                    LearnFlow
                </a>

                <div class="hidden items-center gap-6 md:flex">
                    <a href="{{ route('courses.index') }}" class="border-b-2 border-indigo-700 pb-1 text-sm font-semibold text-indigo-700">Courses</a>
                    <a href="{{ route('home') }}#courses" class="text-sm font-medium text-slate-600 transition hover:text-indigo-700">Featured</a>
                    <a href="{{ route('home') }}#courses" class="text-sm font-medium text-slate-600 transition hover:text-indigo-700">Resources</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 transition hover:text-indigo-700">Dashboard</a>
                    @endauth
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                <form action="{{ route('courses.index') }}" method="GET" role="search" class="relative hidden lg:block">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search courses..."
                        class="w-64 rounded-full border border-slate-200 bg-slate-100 py-2 pl-10 pr-4 text-sm text-slate-700 outline-none transition focus:border-indigo-300 focus:bg-white"
                    >
                    <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-base text-slate-400">search</span>
                </form>

                @auth
                    <a href="{{ route('profile.show') }}" class="hidden text-sm font-semibold text-indigo-950 sm:inline-flex">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-indigo-950 transition hover:bg-slate-100">
                            Sign Out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hidden text-sm font-semibold text-indigo-950 sm:inline-flex">Sign In</a>
                @endauth

                <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="rounded-full bg-gradient-to-r from-[#000666] to-[#4355b9] px-5 py-2.5 text-xs font-bold uppercase tracking-[0.22em] text-white shadow-lg shadow-indigo-950/15 transition hover:-translate-y-0.5">
                    {{ auth()->check() ? 'Open Dashboard' : 'Get Started' }}
                </a>
            </div>
        </div>
    </nav>

    <div class="mx-auto flex max-w-[1440px] flex-col gap-10 px-4 py-6 sm:px-6 lg:px-8 lg:flex-row lg:items-start">
        <aside class="w-full shrink-0 lg:sticky lg:top-24 lg:w-[320px]">
            <div class="overflow-hidden rounded-[28px] border border-white/70 bg-white/85 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] backdrop-blur">
                <div class="flex items-center gap-4">
                    @if ($viewerAvatar)
                        <img src="{{ $viewerAvatar }}" alt="{{ $viewerName }}" class="h-12 w-12 rounded-full object-cover ring-4 ring-indigo-50">
                    @else
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-indigo-700 text-sm font-extrabold text-white ring-4 ring-indigo-50">
                            {{ $viewerInitials ?: 'LF' }}
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-indigo-950">{{ $viewerName }}</p>
                        <p class="text-[11px] font-semibold tracking-[0.22em] text-slate-500">{{ $viewerLabel }}</p>
                    </div>
                </div>

                <form class="mt-8 space-y-8" method="GET" action="{{ route('courses.index') }}">
                    <div class="lg:hidden">
                        <label for="sidebar-search" class="mb-2 block text-xs font-semibold uppercase tracking-[0.22em] text-indigo-950">Search</label>
                        <div class="relative">
                            <input
                                id="sidebar-search"
                                type="search"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Search courses..."
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-indigo-300 focus:bg-white"
                            >
                            <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-base text-slate-400">search</span>
                        </div>
                    </div>

                    <fieldset>
                        <legend class="text-xs font-semibold uppercase tracking-[0.22em] text-indigo-950">Category</legend>
                        <div class="mt-4 space-y-2">
                            @foreach ($categories as $category)
                                <label class="flex cursor-pointer items-center justify-between rounded-2xl border border-transparent px-3 py-2.5 transition hover:border-indigo-100 hover:bg-indigo-50/60">
                                    <span class="flex items-center gap-3">
                                        <input
                                            type="checkbox"
                                            name="category[]"
                                            value="{{ $category->id }}"
                                            @checked(in_array($category->id, $selectedCategoryIds, true))
                                            class="h-4 w-4 rounded border-slate-300 text-indigo-700 focus:ring-indigo-500"
                                        >
                                        <span class="text-sm font-medium text-slate-700">{{ $category->name }}</span>
                                    </span>
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">{{ $category->courses_count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend class="text-xs font-semibold uppercase tracking-[0.22em] text-indigo-950">Level</legend>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($levels as $level)
                                <label class="cursor-pointer">
                                    <input type="radio" name="level" value="{{ $level }}" class="peer sr-only" @checked($selectedLevel === $level)>
                                    <span class="inline-flex rounded-full border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-500 transition peer-checked:border-indigo-200 peer-checked:bg-indigo-50 peer-checked:text-indigo-700">
                                        {{ $level }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend class="text-xs font-semibold uppercase tracking-[0.22em] text-indigo-950">Rating</legend>
                        <div class="mt-4 space-y-2">
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-transparent px-3 py-2.5 transition hover:border-indigo-100 hover:bg-indigo-50/60">
                                <input type="radio" name="rating" value="0" class="h-4 w-4 border-slate-300 text-indigo-700 focus:ring-indigo-500" @checked($selectedRating === 0)>
                                <span class="text-sm font-medium text-slate-700">Any rating</span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-transparent px-3 py-2.5 transition hover:border-indigo-100 hover:bg-indigo-50/60">
                                <input type="radio" name="rating" value="4" class="h-4 w-4 border-slate-300 text-indigo-700 focus:ring-indigo-500" @checked($selectedRating === 4)>
                                <span class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                    <span class="flex items-center gap-0.5 text-amber-400">
                                        @for ($i = 0; $i < 4; $i++)
                                            <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1;">star</span>
                                        @endfor
                                    </span>
                                    4.0 &amp; Up
                                </span>
                            </label>
                        </div>
                    </fieldset>

                    <div class="space-y-3">
                        <button type="submit" class="w-full rounded-full bg-gradient-to-r from-[#000666] to-[#4355b9] px-5 py-3 text-xs font-bold uppercase tracking-[0.22em] text-white shadow-lg shadow-indigo-950/20 transition hover:-translate-y-0.5">
                            Apply Filters
                        </button>
                        <a href="{{ route('courses.index') }}" class="block w-full rounded-full border border-slate-300 px-5 py-3 text-center text-xs font-bold uppercase tracking-[0.22em] text-slate-600 transition hover:bg-slate-100">
                            Reset
                        </a>
                    </div>
                </form>

                <section class="relative mt-8 overflow-hidden rounded-[24px] bg-[#0f1a72] p-6 text-white">
                    <div class="absolute -bottom-6 -right-6 h-28 w-28 rounded-full bg-white/10 blur-2xl"></div>
                    <p class="text-xs font-semibold tracking-[0.22em] text-indigo-200">PRO ACCESS</p>
                    <h2 class="font-display mt-3 text-2xl font-extrabold leading-tight">Master new skills without limits.</h2>
                    <p class="mt-3 text-sm leading-6 text-indigo-100">
                        Save your learning paths, unlock premium lessons, and track every milestone in one place.
                    </p>
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="relative mt-5 inline-flex rounded-full bg-white px-4 py-2.5 text-[11px] font-bold uppercase tracking-[0.2em] text-[#000666] transition hover:-translate-y-0.5">
                        {{ auth()->check() ? 'VIEW MY PLAN' : 'UPGRADE TO PRO' }}
                    </a>
                </section>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            @if (session('success'))
                <div class="mb-6 rounded-[24px] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <section class="overflow-hidden rounded-[32px] border border-white/60 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] backdrop-blur sm:p-8 lg:p-10">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-indigo-700">
                            <span class="material-symbols-outlined text-sm">diamond</span>
                            Curated Learning Tracks
                        </div>
                        <h1 class="font-display mt-4 text-4xl font-extrabold tracking-[-0.06em] text-[#000666] sm:text-5xl lg:text-6xl">
                            Explore Courses
                        </h1>
                        <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                            {{ $resultLabel }}
                        </p>
                    </div>

                    <form method="GET" action="{{ route('courses.index') }}" class="flex flex-wrap items-center gap-3 rounded-full bg-slate-100 px-4 py-2">
                        <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500">Sort By</span>
                        <input type="hidden" name="search" value="{{ $search }}">
                        @foreach ($selectedCategoryIds as $selectedCategoryId)
                            <input type="hidden" name="category[]" value="{{ $selectedCategoryId }}">
                        @endforeach
                        @if ($selectedLevel !== '')
                            <input type="hidden" name="level" value="{{ $selectedLevel }}">
                        @endif
                        <input type="hidden" name="rating" value="{{ $selectedRating }}">
                        <div class="relative">
                            <select name="sort" class="appearance-none rounded-full bg-transparent py-2 pl-3 pr-10 text-sm font-semibold text-indigo-950 outline-none">
                                @foreach ($sortOptions as $value => $label)
                                    <option value="{{ $value }}" @selected($selectedSort === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-base text-slate-500">expand_more</span>
                        </div>
                        <button type="submit" class="rounded-full bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-indigo-950 shadow-sm">
                            Update
                        </button>
                    </form>
                </div>
            </section>

            @if ($courses->count() === 0)
                <section class="mt-8 overflow-hidden rounded-[32px] border border-dashed border-slate-300 bg-white/80 p-10 text-center shadow-[0_20px_60px_rgba(15,23,42,0.05)]">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-indigo-50 text-indigo-700">
                        <span class="material-symbols-outlined text-3xl">travel_explore</span>
                    </div>
                    <h2 class="font-display mt-5 text-3xl font-extrabold tracking-[-0.05em] text-indigo-950">No courses matched this search.</h2>
                    <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600 sm:text-base">
                        Try broadening your filters, choosing a different level, or resetting the search to browse the full LearnFlow catalog.
                    </p>
                    <a href="{{ route('courses.index') }}" class="mt-6 inline-flex rounded-full bg-gradient-to-r from-[#000666] to-[#4355b9] px-5 py-3 text-xs font-bold uppercase tracking-[0.22em] text-white">
                        Browse All Courses
                    </a>
                </section>
            @else
                <section class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ($topCourses as $course)
                        @php
                            $slug = \Illuminate\Support\Str::slug($course->category?->name ?? 'course');
                            $theme = $categoryTheme[$slug] ?? ['badge' => 'bg-indigo-50 text-indigo-900', 'panel' => 'from-indigo-950 via-indigo-700 to-sky-500'];
                            $thumbnail = $course->thumbnail
                                ? asset('storage/' . ltrim($course->thumbnail, '/'))
                                : null;
                            $rating = number_format((float) ($course->reviews_avg_rating ?? 0), 1);
                            $reviewCount = (int) ($course->reviews_count ?? 0);
                            $students = (int) ($course->accepted_enrollments_count ?? 0);
                            $instructor = $course->user?->name ?? 'LearnFlow Team';
                            $priceValue = (float) $course->price;
                            $priceLabel = $priceValue > 0 ? '$' . number_format($priceValue, 2) : 'FREE';
                        @endphp

                        <article class="group overflow-hidden rounded-[28px] border border-white/70 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.07)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_26px_80px_rgba(15,23,42,0.12)]">
                            <div class="relative aspect-[16/9] overflow-hidden">
                                @if ($thumbnail)
                                    <img src="{{ $thumbnail }}" alt="{{ $course->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div class="h-full w-full bg-gradient-to-br {{ $theme['panel'] }}"></div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent"></div>
                                <div class="absolute left-4 top-4 rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] {{ $theme['badge'] }}">
                                    {{ strtoupper($course->category?->name ?? 'Course') }}
                                </div>
                            </div>

                            <div class="flex h-[240px] flex-col justify-between p-6">
                                <div>
                                    <h2 class="font-display text-2xl font-bold leading-tight tracking-[-0.04em] text-[#000666]">
                                        <a href="{{ route('courses.show', $course) }}" class="transition hover:text-indigo-700">{{ $course->title }}</a>
                                    </h2>
                                    <p class="mt-4 text-sm leading-6 text-slate-600">
                                        {{ \Illuminate\Support\Str::limit($course->description ?: 'Dive into a structured learning track with practical lessons and clear outcomes.', 115) }}
                                    </p>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between text-xs font-medium text-slate-500">
                                        <span>{{ $instructor }}</span>
                                        <span>{{ $course->level }}</span>
                                    </div>

                                    <div class="mt-5 flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-lg text-amber-400" style="font-variation-settings: 'FILL' 1;">star</span>
                                            <span class="text-sm font-semibold text-slate-900">{{ $rating }}</span>
                                            <span class="text-xs text-slate-500">({{ number_format($reviewCount) }})</span>
                                            <span class="hidden text-xs text-slate-400 sm:inline">• {{ number_format($students) }} learners</span>
                                        </div>
                                        <span class="text-lg font-semibold text-[#000666] {{ $priceValue <= 0 ? 'rounded-md bg-emerald-100 px-2 py-0.5 text-sm text-emerald-950' : '' }}">
                                            {{ $priceLabel }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach

                    @if ($featuredCourse)
                        @php
                            $featuredThumbnail = $featuredCourse->thumbnail
                                ? asset('storage/' . ltrim($featuredCourse->thumbnail, '/'))
                                : null;
                            $featuredPrice = (float) $featuredCourse->price;
                            $featuredRating = number_format((float) ($featuredCourse->reviews_avg_rating ?? 0), 1);
                        @endphp

                        <article class="relative overflow-hidden rounded-[30px] border border-white/70 bg-slate-950 shadow-[0_22px_70px_rgba(15,23,42,0.16)] md:col-span-2">
                            <div class="absolute inset-0">
                                @if ($featuredThumbnail)
                                    <img src="{{ $featuredThumbnail }}" alt="{{ $featuredCourse->title }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full bg-gradient-to-br from-indigo-950 via-indigo-700 to-sky-400"></div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-r from-[#000666] via-[#000666]/80 to-[#000666]/20"></div>
                            </div>

                            <div class="relative flex h-full min-h-[320px] flex-col justify-between gap-8 p-8 sm:p-10">
                                <div>
                                    <div class="inline-flex rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-white backdrop-blur">
                                        Trending
                                    </div>
                                    <h2 class="font-display mt-5 max-w-2xl text-3xl font-extrabold tracking-[-0.05em] text-white sm:text-4xl">
                                        {{ $featuredCourse->title }}
                                    </h2>
                                    <p class="mt-4 max-w-2xl text-sm leading-7 text-indigo-100 sm:text-base">
                                        {{ \Illuminate\Support\Str::limit($featuredCourse->description ?: 'Our most popular editorial track for learners who want structure, momentum, and practical depth.', 180) }}
                                    </p>
                                </div>

                                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-white/85">
                                        <span>{{ $featuredCourse->user?->name ?? 'LearnFlow Team' }}</span>
                                        <span>• {{ $featuredCourse->level }}</span>
                                        <span>• {{ number_format((int) ($featuredCourse->accepted_enrollments_count ?? 0)) }} learners</span>
                                        <span class="inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-base text-amber-300" style="font-variation-settings: 'FILL' 1;">star</span>
                                            {{ $featuredRating }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <div class="text-3xl font-semibold text-white">
                                            {{ $featuredPrice > 0 ? '$' . number_format($featuredPrice, 2) : 'FREE' }}
                                        </div>
                                        <a href="{{ route('courses.show', $featuredCourse) }}" class="rounded-full bg-white px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-[#000666] transition hover:-translate-y-0.5">
                                            Enroll Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endif

                    @foreach ($bottomCourses as $course)
                        @php
                            $slug = \Illuminate\Support\Str::slug($course->category?->name ?? 'course');
                            $theme = $categoryTheme[$slug] ?? ['badge' => 'bg-indigo-50 text-indigo-900', 'panel' => 'from-indigo-950 via-indigo-700 to-sky-500'];
                            $thumbnail = $course->thumbnail
                                ? asset('storage/' . ltrim($course->thumbnail, '/'))
                                : null;
                            $rating = number_format((float) ($course->reviews_avg_rating ?? 0), 1);
                            $reviewCount = (int) ($course->reviews_count ?? 0);
                            $students = (int) ($course->accepted_enrollments_count ?? 0);
                            $priceValue = (float) $course->price;
                        @endphp

                        <article class="group overflow-hidden rounded-[28px] border border-white/70 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.07)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_26px_80px_rgba(15,23,42,0.12)]">
                            <div class="relative aspect-[16/9] overflow-hidden">
                                @if ($thumbnail)
                                    <img src="{{ $thumbnail }}" alt="{{ $course->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div class="h-full w-full bg-gradient-to-br {{ $theme['panel'] }}"></div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent"></div>
                                <div class="absolute left-4 top-4 rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] {{ $theme['badge'] }}">
                                    {{ strtoupper($course->category?->name ?? 'Course') }}
                                </div>
                            </div>

                            <div class="flex h-[240px] flex-col justify-between p-6">
                                <div>
                                    <h2 class="font-display text-2xl font-bold leading-tight tracking-[-0.04em] text-[#000666]">
                                        <a href="{{ route('courses.show', $course) }}" class="transition hover:text-indigo-700">{{ $course->title }}</a>
                                    </h2>
                                    <p class="mt-4 text-sm leading-6 text-slate-600">
                                        {{ \Illuminate\Support\Str::limit($course->description ?: 'Build a sharper learning rhythm with guided lessons, clear milestones, and hands-on practice.', 115) }}
                                    </p>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between text-xs font-medium text-slate-500">
                                        <span>{{ $course->user?->name ?? 'LearnFlow Team' }}</span>
                                        <span>{{ $course->level }}</span>
                                    </div>

                                    <div class="mt-5 flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-lg text-amber-400" style="font-variation-settings: 'FILL' 1;">star</span>
                                            <span class="text-sm font-semibold text-slate-900">{{ $rating }}</span>
                                            <span class="text-xs text-slate-500">({{ number_format($reviewCount) }})</span>
                                            <span class="hidden text-xs text-slate-400 sm:inline">• {{ number_format($students) }} learners</span>
                                        </div>
                                        <span class="text-lg font-semibold text-[#000666] {{ $priceValue <= 0 ? 'rounded-md bg-emerald-100 px-2 py-0.5 text-sm text-emerald-950' : '' }}">
                                            {{ $priceValue > 0 ? '$' . number_format($priceValue, 2) : 'FREE' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>

                @if ($remainingCourses->isNotEmpty())
                    <section class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($remainingCourses as $course)
                            @php
                                $slug = \Illuminate\Support\Str::slug($course->category?->name ?? 'course');
                                $theme = $categoryTheme[$slug] ?? ['badge' => 'bg-indigo-50 text-indigo-900', 'panel' => 'from-indigo-950 via-indigo-700 to-sky-500'];
                                $thumbnail = $course->thumbnail
                                    ? asset('storage/' . ltrim($course->thumbnail, '/'))
                                    : null;
                                $rating = number_format((float) ($course->reviews_avg_rating ?? 0), 1);
                                $reviewCount = (int) ($course->reviews_count ?? 0);
                                $students = (int) ($course->accepted_enrollments_count ?? 0);
                                $priceValue = (float) $course->price;
                            @endphp

                            <article class="group overflow-hidden rounded-[28px] border border-white/70 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.07)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_26px_80px_rgba(15,23,42,0.12)]">
                                <div class="relative aspect-[16/9] overflow-hidden">
                                    @if ($thumbnail)
                                        <img src="{{ $thumbnail }}" alt="{{ $course->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                    @else
                                        <div class="h-full w-full bg-gradient-to-br {{ $theme['panel'] }}"></div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent"></div>
                                    <div class="absolute left-4 top-4 rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] {{ $theme['badge'] }}">
                                        {{ strtoupper($course->category?->name ?? 'Course') }}
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h2 class="font-display text-2xl font-bold leading-tight tracking-[-0.04em] text-[#000666]">
                                                <a href="{{ route('courses.show', $course) }}" class="transition hover:text-indigo-700">{{ $course->title }}</a>
                                            </h2>
                                            <p class="mt-3 text-sm leading-6 text-slate-600">
                                                {{ \Illuminate\Support\Str::limit($course->description ?: 'A focused learning path with practical lessons and modern course structure.', 110) }}
                                            </p>
                                        </div>
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $course->level }}</span>
                                    </div>

                                    <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                            <span>{{ $course->user?->name ?? 'LearnFlow Team' }}</span>
                                            <span>• {{ number_format($students) }} learners</span>
                                            <span class="inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-base text-amber-400" style="font-variation-settings: 'FILL' 1;">star</span>
                                                {{ $rating }} ({{ number_format($reviewCount) }})
                                            </span>
                                        </div>
                                        <span class="text-lg font-semibold text-[#000666] {{ $priceValue <= 0 ? 'rounded-md bg-emerald-100 px-2 py-0.5 text-sm text-emerald-950' : '' }}">
                                            {{ $priceValue > 0 ? '$' . number_format($priceValue, 2) : 'FREE' }}
                                        </span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </section>
                @endif

                @if ($courses->lastPage() > 1)
                    <nav class="mt-10 flex flex-wrap items-center justify-center gap-3" aria-label="Pagination">
                        <a
                            href="{{ $courses->previousPageUrl() ?: '#' }}"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition {{ $courses->onFirstPage() ? 'pointer-events-none opacity-50' : 'hover:border-indigo-200 hover:text-indigo-700' }}"
                            aria-label="Previous page"
                        >
                            <span class="material-symbols-outlined text-base">chevron_left</span>
                        </a>

                        @if (! in_array(1, $paginationPages, true))
                            <a href="{{ $courses->url(1) }}" class="inline-flex h-11 min-w-11 items-center justify-center rounded-full px-4 text-sm font-semibold text-slate-500 transition hover:bg-white hover:text-indigo-700">1</a>
                            @if ($paginationWindowStart > 2)
                                <span class="px-2 text-slate-400">...</span>
                            @endif
                        @endif

                        @foreach ($paginationPages as $page)
                            <a
                                href="{{ $courses->url($page) }}"
                                class="inline-flex h-11 min-w-11 items-center justify-center rounded-full px-4 text-sm font-semibold transition {{ $page === $courses->currentPage() ? 'bg-[#000666] text-white shadow-lg shadow-indigo-950/20' : 'text-slate-500 hover:bg-white hover:text-indigo-700' }}"
                                @if ($page === $courses->currentPage()) aria-current="page" @endif
                            >
                                {{ $page }}
                            </a>
                        @endforeach

                        @if (! in_array($courses->lastPage(), $paginationPages, true))
                            @if ($paginationWindowEnd < $courses->lastPage() - 1)
                                <span class="px-2 text-slate-400">...</span>
                            @endif
                            <a href="{{ $courses->url($courses->lastPage()) }}" class="inline-flex h-11 min-w-11 items-center justify-center rounded-full px-4 text-sm font-semibold text-slate-500 transition hover:bg-white hover:text-indigo-700">{{ $courses->lastPage() }}</a>
                        @endif

                        <a
                            href="{{ $courses->nextPageUrl() ?: '#' }}"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition {{ $courses->hasMorePages() ? 'hover:border-indigo-200 hover:text-indigo-700' : 'pointer-events-none opacity-50' }}"
                            aria-label="Next page"
                        >
                            <span class="material-symbols-outlined text-base">chevron_right</span>
                        </a>
                    </nav>
                @endif
            @endif

            <footer class="mt-12 rounded-[30px] border border-white/60 bg-white/85 px-6 py-8 shadow-[0_18px_60px_rgba(15,23,42,0.06)] sm:px-8">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="font-display text-2xl font-extrabold tracking-[-0.05em] text-indigo-950">LearnFlow</p>
                        <p class="mt-3 max-w-xl text-sm leading-6 text-slate-500">
                            Editorial-quality learning experiences for curious builders, strategic thinkers, and ambitious teams.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-5 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                        <a href="{{ route('home') }}" class="transition hover:text-indigo-700">About Us</a>
                        <a href="{{ route('courses.index') }}" class="transition hover:text-indigo-700">Course Categories</a>
                        @auth
                            <a href="{{ route('enrollments.index') }}" class="transition hover:text-indigo-700">Academic Support</a>
                        @else
                            <a href="{{ route('register') }}" class="transition hover:text-indigo-700">Academic Support</a>
                        @endauth
                        <a href="{{ route('register') }}" class="transition hover:text-indigo-700">Privacy Policy</a>
                        <a href="{{ route('register') }}" class="transition hover:text-indigo-700">Terms of Service</a>
                    </div>
                </div>
            </footer>
        </main>
    </div>
</body>
</html>
