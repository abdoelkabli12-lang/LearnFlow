@php
    use Illuminate\Support\Str;

    $firstName = Str::of($user->name)->explode(' ')->first();
    $avatarUrl = $user->avatar
        ? (Str::startsWith($user->avatar, ['http://', 'https://'])
            ? $user->avatar
            : asset('storage/' . ltrim($user->avatar, '/')))
        : null;

    $userInitials = collect(explode(' ', $user->name))
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

    $continueItems = $continueLearning->take(2)->values();
    $recommendedItems = $recommendations->take(2)->values();
    $nextItems = $upcomingLessons->take(3)->values();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>LearnFlow Dashboard</title>
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
</head>
<body>
    <div class="flex min-h-screen flex-col items-start pl-64 pr-0 py-0 relative bg-[#f8f9fa]">

        <aside class="flex flex-col w-64 min-h-screen items-start gap-2 p-6 fixed top-0 left-0 bg-slate-50" aria-label="Sidebar">
            <div class="flex flex-col items-start pt-0 pb-8 px-0 relative self-stretch w-full flex-[0_0_auto]">
                <div class="flex self-stretch w-full flex-col items-start relative flex-[0_0_auto]">
                    <a href="{{ route('home') }}" class="relative flex items-center self-stretch mt-[-1.00px] [font-family:'Manrope',Helvetica] font-bold text-indigo-900 text-lg tracking-[-0.90px] leading-7">LearnFlow</a>
                </div>
            </div>

            <nav class="flex flex-col items-start gap-2 relative flex-1 self-stretch w-full grow" aria-label="Primary">
                <a href="{{ route('student.dashboard') }}" aria-current="page" class="flex items-center gap-3 px-4 py-3 relative self-stretch w-full flex-[0_0_auto] bg-white rounded-lg shadow-[0px_1px_2px_#0000000d]">
                    <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <svg class="relative w-[18px] h-[18px] text-indigo-700" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M4 4h7v7H4V4Zm9 0h7v4h-7V4ZM13 10h7v10h-7V10ZM4 13h7v7H4v-7Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <span class="relative flex items-center w-[83.45px] h-6 mt-[-1.00px] [font-family:'Inter',Helvetica] font-medium text-indigo-700 text-base leading-6 whitespace-nowrap">Dashboard</span>
                    </span>
                </a>
                <a href="{{ route('enrollments.index') }}" class="flex items-center gap-3 px-4 py-3 self-stretch w-full relative flex-[0_0_auto]">
                    <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <svg class="relative w-[22px] h-[18px] text-slate-500" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 5.5A2.5 2.5 0 0 1 5.5 3H20v15.5A2.5 2.5 0 0 0 17.5 16H3V5.5ZM5 18h12.5a1.5 1.5 0 1 1 0 3H5v-3Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <span class="relative flex items-center w-[92.03px] h-6 mt-[-1.00px] [font-family:'Inter',Helvetica] font-medium text-slate-500 text-base leading-6 whitespace-nowrap">My Courses</span>
                    </span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 self-stretch w-full relative flex-[0_0_auto]">
                    <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <svg class="relative w-[18px] h-5 text-slate-500" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M7 3h10v3H7V3Zm11 5H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2Zm-7 3h5v2h-5v-2Zm0 4h5v2h-5v-2Zm-3-4h2v2H8v-2Zm0 4h2v2H8v-2Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <span class="relative flex items-center w-[99.25px] h-6 mt-[-1.00px] [font-family:'Inter',Helvetica] font-medium text-slate-500 text-base leading-6 whitespace-nowrap">Assignments</span>
                    </span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 self-stretch w-full relative flex-[0_0_auto]">
                    <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <svg class="relative w-5 h-4 text-slate-500" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M4 5h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H9l-5 4V7a2 2 0 0 1 2-2Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <span class="relative flex items-center w-[78.25px] h-6 mt-[-1.00px] [font-family:'Inter',Helvetica] font-medium text-slate-500 text-base leading-6 whitespace-nowrap">Messages</span>
                    </span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 self-stretch w-full relative flex-[0_0_auto]">
                    <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <svg class="relative w-[18px] h-[18px] text-slate-500" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M4 19h16v2H4v-2Zm1-3 3.5-4.5 2.5 3L15 9l4 7H5Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <span class="relative flex items-center w-[70.98px] h-6 mt-[-1.00px] [font-family:'Inter',Helvetica] font-medium text-slate-500 text-base leading-6 whitespace-nowrap">Analytics</span>
                    </span>
                </a>
                <a href="{{ route('password.show') }}" class="flex items-center gap-3 px-4 py-3 self-stretch w-full relative flex-[0_0_auto]">
                    <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <svg class="relative w-[20.1px] h-5 text-slate-500" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 8.5A3.5 3.5 0 1 0 12 1.5a3.5 3.5 0 0 0 0 7Zm8.94 2.06-1.63-.94.16-1.87-1.92-1.11-1.47 1.17-1.77-.74-.3-1.86h-2.22l-.3 1.86-1.77.74-1.47-1.17-1.92 1.1.16 1.88-1.63.94.76 2.08 1.88.2.85 1.72-1.08 1.55 1.57 1.57 1.55-1.08 1.72.85.2 1.88h2.08l.2-1.88 1.72-.85 1.55 1.08 1.57-1.57-1.08-1.55.85-1.72 1.88-.2.76-2.08ZM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <span class="relative flex items-center w-[62.77px] h-6 mt-[-1.00px] [font-family:'Inter',Helvetica] font-medium text-slate-500 text-base leading-6 whitespace-nowrap">Settings</span>
                    </span>
                </a>
            </nav>

            <div class="flex flex-col items-start gap-6 pt-6 pb-0 px-0 relative self-stretch w-full flex-[0_0_auto] border-t [border-top-style:solid] border-slate-100">
                <section class="flex flex-col items-start gap-1 p-4 relative self-stretch w-full flex-[0_0_auto] bg-[#1a237e] rounded-xl" aria-label="Pro plan">
                    <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto] opacity-80">
                        <div class="relative flex items-center self-stretch mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-white text-xs tracking-[1.20px] leading-4">PRO PLAN</div>
                    </div>
                    <div class="flex flex-col items-start pt-0 pb-2 px-0 relative self-stretch w-full flex-[0_0_auto]">
                        <div class="relative flex items-center self-stretch mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-white text-sm leading-5">Unlock AI Tutoring</div>
                    </div>
                    <a href="{{ route('courses.index') }}" class="box-border flex px-0 py-2 self-stretch w-full bg-white rounded-full items-center justify-center relative flex-[0_0_auto] cursor-pointer">
                        <span class="relative flex items-center justify-center w-[122.47px] h-4 mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-[#000666] text-xs text-center tracking-[1.20px] leading-4 whitespace-nowrap">UPGRADE TO PRO</span>
                    </a>
                </section>

                <section class="flex items-center gap-3 relative self-stretch w-full flex-[0_0_auto]" aria-label="User profile">
                    @if ($avatarUrl)
                        <img class="relative max-w-52 w-10 h-10 rounded-full object-cover" src="{{ $avatarUrl }}" alt="{{ $user->name }}">
                    @else
                        <div class="relative max-w-52 w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-[11px] font-semibold text-indigo-900" aria-hidden="true">{{ $userInitials ?: 'LF' }}</div>
                    @endif
                    <div class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                        <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                            <div class="relative flex items-center h-[18px] mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-indigo-900 text-sm leading-[17.5px] whitespace-nowrap">{{ Str::limit($user->name, 16) }}</div>
                        </div>
                        <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                            <div class="relative flex items-center h-4 mt-[-1.00px] [font-family:'Inter',Helvetica] font-normal text-slate-500 text-xs leading-4 whitespace-nowrap">Premium Learner</div>
                        </div>
                    </div>
                </section>
            </div>
        </aside>

        <div class="relative self-stretch w-full min-h-screen">
            <header class="flex w-full items-center justify-between px-8 py-4 fixed top-0 left-64 right-0 z-20 bg-[#ffffffcc] backdrop-blur backdrop-brightness-[100%] [-webkit-backdrop-filter:blur(8px)_brightness(100%)]">
                <div class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                    <div class="flex self-stretch w-full flex-col items-start relative flex-[0_0_auto]">
                        <h1 class="relative flex items-center w-[219.75px] h-8 mt-[-1.00px] [font-family:'Manrope',Helvetica] font-extrabold text-indigo-900 text-2xl tracking-[-1.20px] leading-8 whitespace-nowrap">Welcome back, {{ $firstName }}!</h1>
                    </div>
                    <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                        <p class="relative flex items-center w-[315.69px] h-5 mt-[-1.00px] [font-family:'Inter',Helvetica] font-medium text-[#454652] text-sm leading-5 whitespace-nowrap">Ready to continue your learning journey today?</p>
                    </div>
                </div>

                <div class="inline-flex items-center gap-6 relative flex-[0_0_auto]">
                    <form class="flex w-64 items-center px-4 py-2 relative bg-[#f3f4f5] rounded-full" role="search" action="{{ route('courses.index') }}" method="GET">
                        <label for="course-search" class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                            <svg class="relative w-[10.5px] h-[10.5px] text-slate-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M10.5 4a6.5 6.5 0 1 0 4.03 11.6l4.44 4.44 1.41-1.41-4.44-4.44A6.5 6.5 0 0 0 10.5 4Zm0 2a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9Z" fill="currentColor"/>
                            </svg>
                        </label>
                        <div class="flex flex-col items-start pt-[9px] pb-2.5 px-3 relative flex-1 grow ml-[-1.78e-14px]">
                            <input id="course-search" aria-label="Search courses" class="relative self-stretch w-full border-[none] [background:none] mt-[-1.00px] [font-family:'Inter',Helvetica] font-normal text-[#767683] text-sm leading-[normal] p-0 outline-none" placeholder="Search courses..." type="search" name="search" />
                        </div>
                    </form>

                    <button type="button" aria-label="Notifications" class="inline-flex flex-col p-2 rounded-full items-center justify-center relative flex-[0_0_auto] cursor-pointer">
                        <span class="inline-flex items-start justify-center relative flex-[0_0_auto]">
                            <svg class="relative w-4 h-5 text-slate-600" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 2a5 5 0 0 0-5 5v2.76c0 .53-.21 1.04-.59 1.41L5 12.59V15h14v-2.41l-1.41-1.42A2 2 0 0 1 17 9.76V7a5 5 0 0 0-5-5Zm0 20a3 3 0 0 0 2.83-2H9.17A3 3 0 0 0 12 22Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-[#ba1a1a] rounded-full" aria-hidden="true"></span>
                    </button>
                </div>
            </header>

            <main class="flex flex-col max-w-screen-xl w-full items-start gap-12 p-8 absolute top-[84px] left-0">
                <section class="grid grid-cols-1 md:grid-cols-3 grid-rows-[104px] md:grid-rows-[104px] h-fit gap-6 w-full" aria-label="Overview statistics">
                    <article class="relative row-[1_/_2] col-[1_/_2] w-full h-[104px] flex items-center gap-5 p-6 bg-white rounded-xl">
                        <div class="flex w-12 h-12 items-center justify-center relative bg-[#0006661a] rounded-lg" aria-hidden="true">
                            <svg class="relative w-[27.5px] h-[24.38px] text-indigo-900" viewBox="0 0 24 24" fill="none">
                                <path d="M5 19h14v2H5v-2Zm0-8h4v6H5v-6Zm5-4h4v10h-4V7Zm5 2h4v8h-4V9Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                            <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                                <div class="relative flex items-center h-5 mt-[-1.00px] [font-family:'Inter',Helvetica] font-medium text-[#767683] text-sm leading-5 whitespace-nowrap">Courses in Progress</div>
                            </div>
                            <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                                <div class="relative flex items-center h-9 mt-[-1.00px] [font-family:'Manrope',Helvetica] font-extrabold text-indigo-900 text-3xl leading-9 whitespace-nowrap">{{ $stats['activeCourses'] }}</div>
                            </div>
                        </div>
                    </article>

                    <article class="relative row-[1_/_2] col-[2_/_3] w-full h-[104px] flex items-center gap-5 p-6 bg-white rounded-xl">
                        <div class="flex w-12 h-12 items-center justify-center relative bg-[#4355b91a] rounded-lg" aria-hidden="true">
                            <svg class="relative w-[27.5px] h-[26.25px] text-indigo-900" viewBox="0 0 24 24" fill="none">
                                <path d="m9.55 18.54-4.24-4.24 1.41-1.41 2.83 2.82 7.73-7.72 1.41 1.41-9.14 9.14Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                            <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                                <div class="relative flex items-center h-5 mt-[-1.00px] [font-family:'Inter',Helvetica] font-medium text-[#767683] text-sm leading-5 whitespace-nowrap">Completed</div>
                            </div>
                            <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                                <div class="relative flex items-center h-9 mt-[-1.00px] [font-family:'Manrope',Helvetica] font-extrabold text-indigo-900 text-3xl leading-9 whitespace-nowrap">{{ $stats['completedCourses'] }}</div>
                            </div>
                        </div>
                    </article>

                    <article class="relative row-[1_/_2] col-[3_/_4] w-full h-[104px] flex items-center gap-5 p-6 bg-white rounded-xl">
                        <div class="flex w-12 h-12 items-center justify-center relative bg-[#85f6e54c] rounded-lg" aria-hidden="true">
                            <svg class="relative w-5 h-[23.75px] text-indigo-900" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2c1.38 0 2.5 1.12 2.5 2.5 0 .81-.39 1.53-1 1.99V8h1a3 3 0 0 1 3 3c0 2.21-1.79 4-4 4h-1v1.5h2v2h-2V22h-2v-3.5H8v-2h2V15H9a4 4 0 0 1-4-4 3 3 0 0 1 3-3h1V6.49a2.5 2.5 0 0 1-1-1.99C8 3.12 9.12 2 10.5 2H12Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                            <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                                <div class="relative flex items-center h-5 mt-[-1.00px] [font-family:'Inter',Helvetica] font-medium text-[#767683] text-sm leading-5 whitespace-nowrap">Average Progress</div>
                            </div>
                            <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                                <div class="relative flex items-center h-9 mt-[-1.00px] [font-family:'Manrope',Helvetica] font-extrabold text-indigo-900 text-3xl leading-9 whitespace-nowrap">{{ $stats['averageProgress'] }}%</div>
                            </div>
                        </div>
                    </article>
                </section>

                <section class="flex flex-col items-start gap-6 relative self-stretch w-full flex-[0_0_auto]" aria-labelledby="continue-learning-heading">
                    <div class="flex items-center justify-between relative self-stretch w-full flex-[0_0_auto]">
                        <div class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                            <h2 id="continue-learning-heading" class="relative flex items-center h-7 mt-[-1.00px] [font-family:'Manrope',Helvetica] font-extrabold text-indigo-900 text-xl tracking-[-0.50px] leading-7 whitespace-nowrap">Continue Learning</h2>
                        </div>
                        <a href="{{ route('enrollments.index') }}" class="inline-flex items-center gap-[3.99px] relative flex-[0_0_auto]">
                            <span class="relative flex items-center h-5 mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-[#4355b9] text-sm leading-5 whitespace-nowrap">View All</span>
                            <span class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                                <svg class="relative w-[9.33px] h-[9.33px] text-[#4355b9]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 w-full">
                        @forelse ($continueItems as $index => $item)
                            @php
                                $course = $item['course'];
                                $buttonUrl = $item['nextLesson']
                                    ? route('lessons.show', $item['nextLesson'])
                                    : route('enrollments.show', $item['enrollment']);
                                $badgeColor = $index === 0 ? 'text-[#4355b9]' : 'text-[#67d9c9]';
                                $progressGradient = $index === 0
                                    ? 'linear-gradient(90deg,rgba(0,6,102,1)_0%,rgba(67,85,185,1)_100%)'
                                    : 'linear-gradient(90deg,rgba(67,85,185,1)_0%,rgba(103,217,201,1)_100%)';
                                $buttonClass = $index === 0 ? 'bg-[#000666] text-white' : 'bg-[#e7e8e9] text-indigo-900';
                            @endphp
                            <article class="relative w-full h-[260px] flex items-start bg-white rounded-2xl overflow-hidden">
                                <div class="flex flex-col w-[185.59px] items-start justify-center relative self-stretch z-[1]">
                                    @if ($item['thumbnailUrl'])
                                        <div class="relative flex-1 self-stretch w-full grow bg-cover bg-center" style="background-image: url('{{ $item['thumbnailUrl'] }}')" role="img" aria-label="{{ $course->title }} course artwork"></div>
                                    @else
                                        <div class="relative flex-1 self-stretch w-full grow bg-[linear-gradient(135deg,rgba(0,6,102,1)_0%,rgba(67,85,185,1)_100%)] flex items-end p-5" role="img" aria-label="{{ $course->title }} course artwork">
                                            <span class="font-bold text-4xl text-white/85">{{ $courseInitials($course->title) ?: 'LF' }}</span>
                                        </div>
                                    @endif
                                    <div class="absolute w-full h-full top-0 left-0 {{ $index === 0 ? 'bg-[#00066633]' : 'bg-[#4355b933]' }}" aria-hidden="true"></div>
                                </div>
                                <div class="flex flex-col flex-1 items-start justify-between p-6 relative self-stretch z-0">
                                    <div class="flex flex-col items-start gap-[7.3px] pt-0 pb-4 px-0 relative self-stretch w-full flex-[0_0_auto]">
                                        <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                                            <div class="relative flex items-center self-stretch mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold {{ $badgeColor }} text-[10px] tracking-[1.00px] leading-[15px]">{{ Str::upper($item['categoryName']) }}</div>
                                        </div>
                                        <div class="flex pt-0 pb-[0.75px] px-0 self-stretch w-full flex-col items-start relative flex-[0_0_auto]">
                                            <h3 class="relative self-stretch mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-indigo-900 text-lg tracking-[0] leading-[22.5px]">{{ Str::limit($course->title, 52) }}</h3>
                                        </div>
                                        <div class="flex flex-col items-start pt-[0.7px] pb-0 px-0 relative self-stretch w-full flex-[0_0_auto]">
                                            <p class="relative self-stretch mt-[-1.00px] [font-family:'Inter',Helvetica] font-normal text-[#454652] text-xs tracking-[0] leading-4">{{ Str::limit($course->description ?: 'Continue from your latest lesson and keep building your learning momentum.', 92) }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                                        <div class="flex flex-col items-start gap-1 relative self-stretch w-full flex-[0_0_auto]">
                                            <div class="flex items-end justify-between relative self-stretch w-full flex-[0_0_auto]">
                                                <div class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                                                    <div class="relative flex items-center h-4 mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-[#767683] text-xs leading-4 whitespace-nowrap">Progress</div>
                                                </div>
                                                <div class="inline-flex flex-col items-start relative flex-[0_0_auto]">
                                                    <div class="relative flex items-center h-4 mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-indigo-900 text-xs leading-4 whitespace-nowrap">{{ $item['progress'] }}%</div>
                                                </div>
                                            </div>
                                            <div class="relative self-stretch w-full h-2 bg-[#e1e3e4] rounded-full overflow-hidden" role="progressbar" aria-valuenow="{{ $item['progress'] }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $course->title }} progress">
                                                <div class="h-full rounded-full" style="width: {{ $item['progress'] }}%; background: {{ $progressGradient }};"></div>
                                            </div>
                                        </div>
                                        <a href="{{ $buttonUrl }}" class="box-border flex gap-[7.99px] px-0 py-3 self-stretch w-full rounded-full items-center justify-center relative flex-[0_0_auto] cursor-pointer {{ $buttonClass }}">
                                            <span class="relative flex items-center justify-center h-4 mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-xs text-center tracking-[1.20px] leading-4 whitespace-nowrap">{{ $item['nextLesson'] ? 'RESUME LESSON' : 'OPEN COURSE' }}</span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <article class="col-span-full flex items-start rounded-2xl bg-white p-8">
                                <div>
                                    <h3 class="[font-family:'Manrope',Helvetica] font-extrabold text-indigo-900 text-xl">No active courses yet</h3>
                                    <p class="mt-3 text-sm text-slate-500 max-w-2xl">Enroll in your first course and this section will populate automatically with your live lesson progress.</p>
                                    <a href="{{ route('courses.index') }}" class="mt-5 inline-flex rounded-full bg-[#000666] px-5 py-3 text-xs font-semibold tracking-[1.20px] text-white">EXPLORE COURSES</a>
                                </div>
                            </article>
                        @endforelse
                    </div>
                </section>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 pt-0 pb-12 px-0 w-full">
                    <section class="xl:col-span-2 w-full h-fit flex flex-col items-start gap-6 pt-0 pb-[58px] px-0" aria-labelledby="recommended-heading">
                        <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                            <h2 id="recommended-heading" class="relative flex items-center self-stretch mt-[-1.00px] [font-family:'Manrope',Helvetica] font-extrabold text-indigo-900 text-xl tracking-[-0.50px] leading-7">Recommended for You</h2>
                        </div>
                        <div class="flex flex-col items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                            @forelse ($recommendedItems as $item)
                                @php $course = $item['course']; @endphp
                                <article class="flex items-center gap-6 p-5 relative self-stretch w-full flex-[0_0_auto] bg-[#f3f4f5] rounded-xl">
                                    <div class="relative w-20 h-20 rounded-lg overflow-hidden shrink-0" role="img" aria-label="{{ $course->title }} artwork">
                                        @if ($item['thumbnailUrl'])
                                            <img src="{{ $item['thumbnailUrl'] }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-[linear-gradient(135deg,rgba(0,6,102,1)_0%,rgba(67,85,185,1)_100%)] flex items-end p-3">
                                                <span class="font-bold text-2xl text-white/85">{{ $courseInitials($course->title) ?: 'LF' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-start gap-1 relative flex-1 grow">
                                        <div class="flex items-start justify-between relative self-stretch w-full flex-[0_0_auto]">
                                            <h3 class="relative flex items-center mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-indigo-900 text-base tracking-[0] leading-6">{{ $course->title }}</h3>
                                            <div class="inline-flex flex-col items-start px-2 py-1 relative flex-[0_0_auto] bg-[#003731] rounded">
                                                <div class="relative flex items-center mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-[#67d9c9] text-[10px] tracking-[0] leading-[15px] whitespace-nowrap">{{ Str::upper(Str::limit($item['categoryName'], 8, '')) }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                                            <p class="relative flex items-center self-stretch mt-[-1.00px] [font-family:'Inter',Helvetica] font-normal text-slate-500 text-xs tracking-[0] leading-4">Based on your interest in {{ $item['categoryName'] }}</p>
                                        </div>
                                        <div class="flex items-center gap-4 pt-2 pb-0 px-0 relative self-stretch w-full flex-[0_0_auto]">
                                            <div class="inline-flex items-center gap-1 relative flex-[0_0_auto]">
                                                <div class="relative flex items-center h-[15px] mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-[#767683] text-[10px] tracking-[0] leading-[15px] whitespace-nowrap">{{ $formatMinutes($item['totalMinutes']) }}</div>
                                            </div>
                                            <div class="inline-flex items-center gap-[3.99px] relative flex-[0_0_auto]">
                                                <div class="relative flex items-center h-[15px] mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-[#767683] text-[10px] tracking-[0] leading-[15px] whitespace-nowrap">{{ $item['averageRating'] ? $item['averageRating'] . ' / 5' : $item['totalLessons'] . ' lessons' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('courses.show', $course) }}" class="inline-flex flex-col p-2 rounded-full items-center justify-center relative flex-[0_0_auto] cursor-pointer bg-white" aria-label="Open {{ $course->title }}">
                                        <span class="text-[10px] font-semibold text-indigo-900">GO</span>
                                    </a>
                                </article>
                            @empty
                                <article class="flex items-center gap-6 p-5 relative self-stretch w-full flex-[0_0_auto] bg-[#f3f4f5] rounded-xl">
                                    <p class="text-sm text-slate-500">Recommendations will appear here as published courses become available.</p>
                                </article>
                            @endforelse
                        </div>
                    </section>

                    <section class="w-full h-fit flex flex-col items-start gap-6" aria-labelledby="deadlines-heading">
                        <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                            <h2 id="deadlines-heading" class="relative flex items-center self-stretch mt-[-1.00px] [font-family:'Manrope',Helvetica] font-extrabold text-indigo-900 text-xl tracking-[-0.50px] leading-7">Next Up</h2>
                        </div>
                        <div class="flex flex-col items-start gap-8 p-6 relative self-stretch w-full flex-[0_0_auto] bg-[#e1e3e4] rounded-2xl">
                            <div class="flex flex-col items-start gap-6 relative self-stretch w-full flex-[0_0_auto]">
                                @forelse ($nextItems as $item)
                                    @php
                                        $shortLabel = Str::upper(Str::limit($item['categoryName'], 3, ''));
                                        $progressLabel = str_pad((string) min(99, $item['progress']), 2, '0', STR_PAD_LEFT);
                                    @endphp
                                    <article class="flex items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                                        <div class="inline-flex flex-col min-w-12 h-12 items-center justify-center py-0 relative flex-[0_0_auto] bg-white rounded-lg shadow-[0px_1px_2px_#0000000d]" aria-hidden="true">
                                            <div class="relative flex items-center h-[15px] mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-indigo-400 text-[10px] leading-[15px] whitespace-nowrap">{{ $shortLabel }}</div>
                                            <div class="relative flex items-center h-7 mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-indigo-900 text-lg leading-7 whitespace-nowrap">{{ $progressLabel }}</div>
                                        </div>
                                        <div class="inline-flex flex-col items-start gap-1 relative self-stretch flex-[0_0_auto]">
                                            <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                                                <h3 class="relative flex items-center mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-indigo-900 text-sm leading-[17.5px]">{{ $item['nextLesson']?->title ?? $item['course']->title }}</h3>
                                            </div>
                                            <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                                                <p class="relative flex items-center mt-[-1.00px] [font-family:'Inter',Helvetica] font-normal text-slate-500 text-xs leading-4">{{ Str::limit($item['course']->title, 24) }}</p>
                                            </div>
                                        </div>
                                    </article>
                                @empty
                                    <article class="flex items-start gap-4 relative self-stretch w-full flex-[0_0_auto]">
                                        <div class="inline-flex flex-col min-w-12 h-12 items-center justify-center py-0 relative flex-[0_0_auto] bg-white rounded-lg shadow-[0px_1px_2px_#0000000d]" aria-hidden="true">
                                            <div class="relative flex items-center h-[15px] mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-indigo-400 text-[10px] leading-[15px] whitespace-nowrap">NXT</div>
                                            <div class="relative flex items-center h-7 mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-indigo-900 text-lg leading-7 whitespace-nowrap">00</div>
                                        </div>
                                        <div class="inline-flex flex-col items-start gap-1 relative self-stretch flex-[0_0_auto]">
                                            <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                                                <h3 class="relative flex items-center mt-[-1.00px] [font-family:'Inter',Helvetica] font-semibold text-indigo-900 text-sm leading-[17.5px]">No upcoming lessons yet</h3>
                                            </div>
                                            <div class="flex flex-col items-start relative self-stretch w-full flex-[0_0_auto]">
                                                <p class="relative flex items-center mt-[-1.00px] [font-family:'Inter',Helvetica] font-normal text-slate-500 text-xs leading-4">Enroll in a course to populate this panel</p>
                                            </div>
                                        </div>
                                    </article>
                                @endforelse
                            </div>
                            <a href="{{ $nextItems->isNotEmpty() ? route('lessons.show', $nextItems->first()['nextLesson']) : route('courses.index') }}" class="box-border flex px-0 py-3 self-stretch w-full bg-white rounded-xl border border-solid border-indigo-100 items-center justify-center relative flex-[0_0_auto] cursor-pointer">
                                <span class="relative flex items-center justify-center h-4 [font-family:'Inter',Helvetica] font-semibold text-indigo-900 text-xs text-center tracking-[1.20px] leading-4 whitespace-nowrap">{{ $nextItems->isNotEmpty() ? 'OPEN NEXT LESSON' : 'VIEW CATALOG' }}</span>
                            </a>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
