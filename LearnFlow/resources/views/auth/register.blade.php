<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account | LearnFlow</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: "Inter", ui-sans-serif, system-ui, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(67, 85, 185, 0.08), transparent 28rem),
                linear-gradient(180deg, #f8f9fa 0%, #eef2ff 100%);
        }

        .font-display {
            font-family: "Manrope", ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen text-[#191c1d]">
    <div class="min-h-screen lg:grid lg:grid-cols-[minmax(0,1.05fr)_minmax(480px,600px)]">
        <section class="relative overflow-hidden bg-[#000666] px-6 py-10 sm:px-10 lg:px-16 lg:py-14">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_15%,rgba(186,195,255,0.24),transparent_0_22rem),radial-gradient(circle_at_78%_28%,rgba(67,85,185,0.38),transparent_0_24rem),linear-gradient(145deg,#000666_0%,#111d84_52%,#0f2e8d_100%)]"></div>
            <div class="absolute inset-0 opacity-25" style="background-image: linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 28px 28px;"></div>

            <div class="relative z-10 mx-auto flex min-h-full max-w-2xl flex-col justify-between">
                <div>
                    <a href="{{ route('home') }}" class="font-display inline-flex text-2xl font-extrabold tracking-[-0.06em] text-white">
                        LearnFlow
                    </a>

                    <div class="mt-14 max-w-xl lg:mt-20">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-indigo-200">
                            Focused Learning Starts Here
                        </p>
                        <h1 class="font-display mt-5 text-4xl font-extrabold leading-tight tracking-[-0.06em] text-white sm:text-5xl lg:text-6xl">
                            Design your next learning chapter.
                        </h1>
                        <p class="mt-6 max-w-lg text-base leading-8 text-indigo-100/90 sm:text-lg">
                            Create your LearnFlow profile to save progress, enroll faster, and build a personal catalog around your goals.
                        </p>
                    </div>

                    <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:mt-14">
                        <article class="rounded-[26px] border border-white/12 bg-white/10 p-6 backdrop-blur-md">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-white">
                                <svg viewBox="0 0 24 24" class="h-6 w-6 fill-none stroke-current" stroke-width="1.8" aria-hidden="true">
                                    <path d="M4 7.5 12 4l8 3.5-8 3.5L4 7.5Z"/>
                                    <path d="M7 10.5V15c0 1.7 2.2 3 5 3s5-1.3 5-3v-4.5"/>
                                </svg>
                            </div>
                            <p class="mt-6 text-3xl font-semibold text-white">450+</p>
                            <p class="mt-2 text-xs font-semibold uppercase tracking-[0.24em] text-white/60">Curated Courses</p>
                        </article>

                        <article class="rounded-[26px] border border-white/12 bg-white/10 p-6 backdrop-blur-md">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-white">
                                <svg viewBox="0 0 24 24" class="h-6 w-6 fill-none stroke-current" stroke-width="1.8" aria-hidden="true">
                                    <path d="M12 3 5 6v5c0 4.25 2.9 8.2 7 9 4.1-.8 7-4.75 7-9V6l-7-3Z"/>
                                </svg>
                            </div>
                            <p class="mt-6 text-3xl font-semibold text-white">Secure</p>
                            <p class="mt-2 text-xs font-semibold uppercase tracking-[0.24em] text-white/60">Personal Workspace</p>
                        </article>
                    </div>
                </div>

                <p class="mt-10 text-[11px] font-medium uppercase tracking-[0.22em] text-indigo-100/45">
                    © 2024 LearnFlow Editorial Intelligence
                </p>
            </div>
        </section>

        <main class="flex items-center justify-center px-6 py-10 sm:px-10 lg:px-12 lg:py-14">
            <section class="w-full max-w-lg rounded-[32px] border border-white/70 bg-white/90 p-6 shadow-[0_28px_90px_rgba(15,23,42,0.10)] backdrop-blur sm:p-8" aria-labelledby="signup-heading">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#4355b9]">Create Account</p>
                        <h2 id="signup-heading" class="font-display mt-3 text-3xl font-bold tracking-[-0.05em] text-[#191c1d]">
                            Build your profile
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-[#5a5d6d]">
                            One account gives you course discovery, enrollment, and progress tracking in one place.
                        </p>
                    </div>

                    <a href="{{ route('home') }}" class="rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 transition hover:border-indigo-200 hover:text-indigo-700">
                        Home
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="mt-8 space-y-6" method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <div class="space-y-2">
                        <label for="name" class="text-xs font-semibold uppercase tracking-[0.22em] text-[#767683]">
                            Full Name
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="1.8" aria-hidden="true">
                                    <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z"/>
                                    <path d="M4 20a8 8 0 0 1 16 0"/>
                                </svg>
                            </span>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                autocomplete="name"
                                required
                                class="w-full rounded-2xl border border-transparent bg-[#f3f4f5] py-4 pl-12 pr-4 text-sm text-[#191c1d] outline-none transition focus:border-indigo-200 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                                placeholder="Alex Rivers"
                            >
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-xs font-semibold uppercase tracking-[0.22em] text-[#767683]">
                            Email Address
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="1.8" aria-hidden="true">
                                    <rect x="3.5" y="5.5" width="17" height="13" rx="2"/>
                                    <path d="m5 7 7 6 7-6"/>
                                </svg>
                            </span>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                required
                                class="w-full rounded-2xl border border-transparent bg-[#f3f4f5] py-4 pl-12 pr-4 text-sm text-[#191c1d] outline-none transition focus:border-indigo-200 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                                placeholder="alex.rivers@learnflow.com"
                            >
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="phone" class="text-xs font-semibold uppercase tracking-[0.22em] text-[#767683]">
                            Phone Number
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="1.8" aria-hidden="true">
                                    <path d="M6.5 4.5h3l1.5 4-2 1.75a13 13 0 0 0 4.75 4.75l1.75-2 4 1.5v3a2 2 0 0 1-2.18 2A17 17 0 0 1 4.5 6.68 2 2 0 0 1 6.5 4.5Z"/>
                                </svg>
                            </span>
                            <input
                                id="phone"
                                name="phone"
                                type="text"
                                value="{{ old('phone') }}"
                                autocomplete="tel"
                                required
                                class="w-full rounded-2xl border border-transparent bg-[#f3f4f5] py-4 pl-12 pr-4 text-sm text-[#191c1d] outline-none transition focus:border-indigo-200 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                                placeholder="+1 555 010 2024"
                            >
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label for="password" class="text-xs font-semibold uppercase tracking-[0.22em] text-[#767683]">
                                Password
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="1.8" aria-hidden="true">
                                        <path d="M7 11V8a5 5 0 0 1 10 0v3"/>
                                        <rect x="5" y="11" width="14" height="10" rx="2"/>
                                    </svg>
                                </span>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    class="w-full rounded-2xl border border-transparent bg-[#f3f4f5] py-4 pl-12 pr-12 text-sm text-[#191c1d] outline-none transition focus:border-indigo-200 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                                    placeholder="8+ characters"
                                >
                                <button type="button" data-password-toggle="password" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-indigo-700" aria-label="Show password">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="1.8" aria-hidden="true">
                                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation" class="text-xs font-semibold uppercase tracking-[0.22em] text-[#767683]">
                                Confirm Password
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="1.8" aria-hidden="true">
                                        <path d="M7 11V8a5 5 0 0 1 10 0v3"/>
                                        <rect x="5" y="11" width="14" height="10" rx="2"/>
                                    </svg>
                                </span>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    class="w-full rounded-2xl border border-transparent bg-[#f3f4f5] py-4 pl-12 pr-12 text-sm text-[#191c1d] outline-none transition focus:border-indigo-200 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                                    placeholder="Repeat password"
                                >
                                <button type="button" data-password-toggle="password_confirmation" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-indigo-700" aria-label="Show password confirmation">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="1.8" aria-hidden="true">
                                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[24px] bg-slate-50 px-5 py-4 text-sm leading-6 text-slate-600">
                        Passwords should be at least 8 characters and include letters and numbers. Your account starts as a visitor profile and can grow with your learning path.
                    </div>

                    <button type="submit" class="w-full rounded-full bg-[linear-gradient(160deg,#000666_0%,#4355b9_100%)] px-6 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-white shadow-[0_18px_30px_rgba(0,6,102,0.18)] transition hover:-translate-y-0.5">
                        Create LearnFlow Account
                    </button>
                </form>

                <div class="mt-6 flex items-center justify-center gap-1 text-sm">
                    <p class="text-[#454652]">Already have an account?</p>
                    <a href="{{ route('login') }}" class="font-semibold text-[#000666] transition hover:text-[#4355b9]">
                        Sign In
                    </a>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const target = document.getElementById(button.getAttribute('data-password-toggle'));

                if (!target) {
                    return;
                }

                const nextType = target.type === 'password' ? 'text' : 'password';
                target.type = nextType;
                button.setAttribute('aria-label', nextType === 'password' ? 'Show password' : 'Hide password');
            });
        });
    </script>
</body>
</html>
