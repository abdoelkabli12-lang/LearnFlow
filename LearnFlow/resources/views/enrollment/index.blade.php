@php
    use Illuminate\Support\Str;

    $user = auth()->user();
    $firstName = Str::of($user?->name ?? 'Learner')->explode(' ')->first();
    $userName = $user?->name ?? 'Alex Rivers';
    $userAvatar = $user?->avatar
        ? (Str::startsWith($user->avatar, ['http://', 'https://'])
            ? $user->avatar
            : asset('storage/' . ltrim($user->avatar, '/')))
        : null;
    $userInitials = collect(explode(' ', $userName))
        ->filter()
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->take(2)
        ->implode('');

    $courseInitials = function (string $title): string {
        return collect(explode(' ', $title))
            ->filter()
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->take(2)
            ->implode('');
    };

    $formatMinutes = function (int $minutes): string {
        if ($minutes <= 0) {
            return 'Self-paced';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($hours === 0) {
            return $remainingMinutes . ' min';
        }

        if ($remainingMinutes === 0) {
            return $hours . 'h';
        }

        return $hours . 'h ' . $remainingMinutes . 'm';
    };

    $libraryItems = $enrollments->map(function ($enrollment) {
        $course = $enrollment->course;
        $moduleCount = (int) ($course?->modules?->count() ?? 0);
        $lessonCount = (int) ($course?->modules?->sum(fn ($module) => $module->lessons->count()) ?? 0);
        $totalMinutes = (int) ($course?->modules?->sum('duration') ?? 0)
            + (int) ($course?->modules?->sum(fn ($module) => $module->lessons->sum('duration')) ?? 0);
        $completedLessons = $lessonCount > 0 ? (int) round(($enrollment->progress / 100) * $lessonCount) : 0;
        $thumbnailUrl = $course?->thumbnail
            ? (Str::startsWith($course->thumbnail, ['http://', 'https://'])
                ? $course->thumbnail
                : asset('storage/' . ltrim($course->thumbnail, '/')))
            : null;

        return [
            'enrollment' => $enrollment,
            'course' => $course,
            'moduleCount' => $moduleCount,
            'lessonCount' => $lessonCount,
            'completedLessons' => min($lessonCount, $completedLessons),
            'totalMinutes' => $totalMinutes,
            'thumbnailUrl' => $thumbnailUrl,
            'categoryName' => $course?->category?->name ?? 'Course',
            'isCompleted' => (int) $enrollment->progress >= 100,
            'isInProgress' => (int) $enrollment->progress > 0 && (int) $enrollment->progress < 100,
        ];
    })->values();

    $allCount = $libraryItems->count();
    $inProgressCount = $libraryItems->where('isInProgress', true)->count();
    $completedCount = $libraryItems->where('isCompleted', true)->count();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>My Learning Library</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {},
            },
            plugins: [],
        }
    </script>
    <style>
        .bg-cover-placeholder {
            background-image: url('https://placehold.co/600x400/1a237e/ffffff?text=Course+Image');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-[#f8f9fa] min-h-screen font-sans">
    <div class="flex flex-col items-start pl-64 pr-0 py-0 relative bg-[#f8f9fa] min-h-screen">
        <aside class="flex flex-col w-64 h-screen items-start gap-2 p-6 absolute top-0 left-0 bg-slate-50 border-r border-slate-200" aria-label="Sidebar navigation">
            <div class="pt-0 pb-10 px-0 flex-[0_0_auto] flex flex-col items-start relative self-stretch w-full">
                <div class="flex flex-col items-start px-2 py-0 relative self-stretch w-full flex-[0_0_auto]">
                    <a href="{{ route('home') }}" class="relative flex items-center self-stretch mt-[-1.00px] font-bold text-indigo-900 text-lg leading-7">LearnFlow</a>
                </div>
            </div>

            <nav class="flex flex-col items-start gap-2 relative flex-1 self-stretch w-full grow" aria-label="Primary">
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-4 py-3 relative self-stretch w-full flex-[0_0_auto] text-slate-500 hover:bg-slate-100 rounded-lg">
                    <span class="text-slate-500">📊</span>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>
                <a href="{{ route('enrollments.index') }}" aria-current="page" class="flex items-center gap-3 px-4 py-3 relative self-stretch w-full flex-[0_0_auto] bg-white rounded-lg shadow-sm text-indigo-700">
                    <span class="text-indigo-700">📚</span>
                    <span class="font-medium text-sm">My Courses</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 relative self-stretch w-full flex-[0_0_auto] text-slate-500 hover:bg-slate-100 rounded-lg">
                    <span class="text-slate-500">📝</span>
                    <span class="font-medium text-sm">Assignments</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 relative self-stretch w-full flex-[0_0_auto] text-slate-500 hover:bg-slate-100 rounded-lg">
                    <span class="text-slate-500">💬</span>
                    <span class="font-medium text-sm">Messages</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 relative self-stretch w-full flex-[0_0_auto] text-slate-500 hover:bg-slate-100 rounded-lg">
                    <span class="text-slate-500">📈</span>
                    <span class="font-medium text-sm">Analytics</span>
                </a>
                <a href="{{ route('password.show') }}" class="flex items-center gap-3 px-4 py-3 relative self-stretch w-full flex-[0_0_auto] text-slate-500 hover:bg-slate-100 rounded-lg">
                    <span class="text-slate-500">⚙️</span>
                    <span class="font-medium text-sm">Settings</span>
                </a>
            </nav>

            <div class="flex flex-col items-start gap-4 pt-6 pb-0 px-0 relative self-stretch w-full flex-[0_0_auto] border-t border-slate-200">
                <div class="flex items-center gap-3 px-2 py-0 relative self-stretch w-full flex-[0_0_auto]">
                    @if ($userAvatar)
                        <img class="flex flex-col w-10 h-10 items-start relative rounded-full overflow-hidden object-cover" src="{{ $userAvatar }}" alt="{{ $userName }}">
                    @else
                        <div class="flex flex-col w-10 h-10 items-start relative bg-[#1a237e] rounded-full overflow-hidden text-white flex items-center justify-center font-bold">
                            {{ $userInitials ?: 'AR' }}
                        </div>
                    @endif
                    <div class="inline-flex flex-col items-start gap-1 relative flex-[0_0_auto]">
                        <div class="font-bold text-indigo-900 text-sm leading-[14px]">{{ $userName }}</div>
                        <div class="font-semibold text-slate-500 text-xs leading-4">PREMIUM LEARNER</div>
                    </div>
                </div>
                <a href="{{ route('courses.index') }}" class="box-border flex items-center justify-center px-4 py-3 relative self-stretch w-full flex-[0_0_auto] rounded-full bg-gradient-to-r from-[#000666] to-[#4355b9]">
                    <span class="font-semibold text-white text-xs text-center tracking-[1.20px]">UPGRADE TO PRO</span>
                </a>
            </div>
        </aside>

        <div class="relative self-stretch w-full min-h-screen bg-[#f8f9fa]">
            <header class="flex w-full items-center justify-between px-8 py-6 absolute top-0 left-0 bg-[#ffffffcc] backdrop-blur z-10">
                <div class="inline-flex flex-col items-start gap-1 relative flex-[0_0_auto]">
                    <h1 class="font-extrabold text-[#000666] text-3xl">My Learning Library</h1>
                    <p class="text-[#454652] text-sm">Pick up where you left off, {{ $firstName }}.</p>
                </div>
                <div class="inline-flex items-center gap-4 relative flex-[0_0_auto]">
                    <form class="inline-flex flex-col items-start relative flex-[0_0_auto]" role="search">
                        <label for="course-search" class="sr-only">Search your courses</label>
                        <div class="flex w-64 items-start justify-center pl-10 pr-4 pt-[9px] pb-2.5 relative flex-[0_0_auto] bg-[#f3f4f5] rounded-lg overflow-hidden">
                            <input id="course-search" class="relative grow border-[none] bg-none self-stretch mt-[-1.00px] font-normal text-gray-500 text-sm p-0 outline-none" placeholder="Search your courses..." type="search" />
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                        </div>
                    </form>
                    <button type="button" class="box-border inline-flex items-center gap-2 px-4 py-2 relative flex-[0_0_auto] bg-[#e7e8e9] rounded-lg text-sm font-medium text-[#191c1d]">
                        <span>Sort</span>
                    </button>
                </div>
            </header>

            <main class="flex flex-col w-full items-start gap-10 pt-0 pb-20 px-8 absolute top-[108px] left-0">
                <section class="flex items-start gap-8 relative self-stretch w-full flex-[0_0_auto] border-b border-[#e1e3e4]" aria-label="Course filters">
                    <button type="button" class="border-b-2 border-[#000666] pb-4 px-0 font-semibold text-[#000666] text-sm">All Courses ({{ $allCount }})</button>
                    <button type="button" class="pb-4 px-0 font-medium text-[#454652] text-sm hover:text-[#000666]">In Progress ({{ $inProgressCount }})</button>
                    <button type="button" class="pb-4 px-0 font-medium text-[#454652] text-sm hover:text-[#000666]">Completed ({{ $completedCount }})</button>
                    <button type="button" class="pb-4 px-0 font-medium text-[#454652] text-sm hover:text-[#000666]">Favorites</button>
                </section>

                @if (session('success'))
                    <div class="w-full rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                <section class="grid grid-cols-1 xl:grid-cols-3 gap-8 h-fit w-full" aria-label="Course library">
                    @forelse ($libraryItems as $item)
                        @php
                            $course = $item['course'];
                        @endphp
                        <article class="relative w-full h-fit flex flex-col items-start bg-white rounded-xl overflow-hidden shadow-lg">
                            <div class="flex flex-col h-48 items-start justify-center relative self-stretch w-full {{ $item['thumbnailUrl'] ? 'bg-cover bg-center' : 'bg-cover-placeholder' }}" @if($item['thumbnailUrl']) style="background-image: url('{{ $item['thumbnailUrl'] }}')" @endif>
                                <div class="inline-flex items-start gap-2 absolute top-4 left-4">
                                    <div class="inline-flex flex-col items-start px-3 py-[2.5px] relative self-stretch flex-[0_0_auto] bg-[#ffffffe6] rounded-full backdrop-blur-sm">
                                        <span class="font-semibold text-[#000666] text-[10px] tracking-[1.00px]">{{ Str::upper($item['categoryName']) }}</span>
                                    </div>
                                    @if ($item['isCompleted'])
                                        <div class="inline-flex items-center gap-[3.99px] pt-[3.5px] pb-[4.5px] px-3 relative self-stretch flex-[0_0_auto] bg-[#85f6e5] rounded-full">
                                            <span class="font-semibold text-[#00201c] text-[10px] tracking-[1.00px]">DONE</span>
                                        </div>
                                    @endif
                                </div>
                                @if (! $item['thumbnailUrl'])
                                    <div class="absolute bottom-4 left-4 text-2xl font-bold text-white/90">{{ $course ? $courseInitials($course->title) : 'LF' }}</div>
                                @endif
                            </div>
                            <div class="flex flex-col items-start justify-between p-6 relative self-stretch w-full flex-[0_0_auto] z-0">
                                <div class="pt-0 pb-2 px-0 flex-[0_0_auto] flex flex-col items-start relative self-stretch w-full">
                                    <div class="flex items-start justify-between relative self-stretch w-full flex-[0_0_auto]">
                                        <h2 class="font-bold text-[#000666] text-lg leading-[22.5px]">{{ $course?->title ?? 'Course unavailable' }}</h2>
                                        <button type="button" class="text-gray-400 hover:text-red-500">♡</button>
                                    </div>
                                </div>
                                <div class="pt-0 pb-6 px-0 flex-[0_0_auto] flex flex-col items-start relative self-stretch w-full">
                                    <p class="self-stretch mt-[-1.00px] font-normal text-[#454652] text-sm leading-5">{{ Str::limit($course?->description ?: 'This enrollment is still in your library, but the course content is currently unavailable.', 82) }}</p>
                                </div>
                                <div class="h-24 justify-end pt-0.5 pb-0 px-0 flex flex-col items-start relative self-stretch w-full">
                                    <div class="relative self-stretch w-full h-[94px]">
                                        <div class="flex w-full items-start justify-between absolute top-0 left-0">
                                            <div class="font-semibold text-[#454652] text-xs leading-4">{{ $item['isCompleted'] ? '100% Complete' : $item['enrollment']->progress . '% Complete' }}</div>
                                            <div class="font-semibold text-[#454652] text-xs leading-4">{{ $item['completedLessons'] }}/{{ $item['lessonCount'] }} Lessons</div>
                                        </div>
                                        <div class="absolute w-full top-6 left-0 h-1.5 flex bg-[#e1e3e4] rounded-full overflow-hidden">
                                            <div class="rounded-full bg-gradient-to-r from-[#4355b9] to-[#67d9c9]" style="width: {{ $item['enrollment']->progress }}%"></div>
                                        </div>
                                        <a href="{{ route('enrollments.show', $item['enrollment']) }}" class="box-border flex w-full items-center justify-center px-0 py-3 absolute top-[54px] left-0 bg-[#000666] rounded-full">
                                            <span class="font-semibold text-white text-xs text-center tracking-[1.20px]">{{ $item['isCompleted'] ? 'VIEW COURSE' : 'RESUME LEARNING' }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <article class="col-span-full relative w-full h-fit flex flex-col items-start bg-white rounded-xl overflow-hidden shadow-lg p-8">
                            <div class="font-bold text-[#000666] text-lg leading-[22.5px]">No courses in your library yet</div>
                            <p class="mt-3 text-sm leading-5 text-[#454652]">Once you enroll in a course, it will appear here with progress, lesson totals, and resume actions.</p>
                            <a href="{{ route('courses.index') }}" class="mt-6 box-border flex items-center justify-center px-6 py-3 rounded-full bg-[#000666]">
                                <span class="font-semibold text-white text-xs text-center tracking-[1.20px]">BROWSE COURSES</span>
                            </a>
                        </article>
                    @endforelse
                </section>
            </main>
        </div>
    </div>
</body>
</html>
