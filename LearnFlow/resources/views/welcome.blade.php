{{-- resources/views/home.blade.php --}}
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'LearnFlow') }} | Editorial Intelligence Learning</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }

        .glass-nav {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .editorial-gradient {
            background: linear-gradient(135deg, #000666 0%, #4355b9 100%);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans selection:bg-indigo-200 selection:text-indigo-950">
    @php
        $isAuthenticated = auth()->check();
        $isHostOrAdmin = $isAuthenticated && in_array(auth()->user()->role, ['host', 'admin'], true);

        $navLinks = [
            ['label' => 'Courses', 'url' => route('courses.index')],
            ['label' => 'Dashboard', 'url' => $isAuthenticated ? route('dashboard') : route('login')],
            ['label' => 'Profile', 'url' => $isAuthenticated ? route('profile.show') : route('register')],
            ['label' => 'Manage', 'url' => $isHostOrAdmin ? route('host.courses.index') : route('courses.index')],
        ];

        $statsText = 'Joined by 12,000+ learners this month';

        $features = [
            [
                'title' => 'Learn at Your Own Pace',
                'description' => 'Tailor your education to your schedule. Our modular content allows for deep dives or quick refreshes whenever inspiration strikes.',
                'icon' => 'schedule',
                'large' => true,
            ],
            [
                'title' => 'Expert Mentorship',
                'description' => 'Direct access to industry veterans who have shaped the digital landscape.',
                'icon' => 'psychology',
                'dark' => true,
            ],
            [
                'title' => 'Career-Ready Skills',
                'description' => 'Projects that mirror real-world challenges, building a portfolio that commands attention.',
                'icon' => 'work',
                'primary' => true,
            ],
            [
                'title' => 'Advanced Analytics',
                'description' => 'Visualize your progress with high-fidelity tracking of your skills development over time.',
                'icon' => 'monitoring',
                'wide' => true,
            ],
        ];

        $courses = $courses ?? [
            [
                'title' => 'Editorial UI Systems',
                'category' => 'Design',
                'rating' => '4.9',
                'price' => '$199',
                'description' => 'Master the art of high-end digital layouts using editorial principles and grid systems.',
                'instructor' => 'Prof. Julian Vane',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDcjK5svWy6V7UPwVpEWcji78mNC3O7oXhT9Y3dLM10mm7TajO1G5Vlxtya5GIAlPOczzubscPzk0XkaBRoMtqWCxJq6-61Ghnnf6GxwkkZzyJCF38LTboa4GRF4TqbfaQ9m6wyaPOXRantXwTSiZzCGmUL6Htz7R7ySVVSFmf0BdTzhdlU750YXUB7R99wlInZoPpiIIyGtXJJ7mnb9F2Hz_3pBTUJq04PaJ3wCAd9AzLlsU052lc3Sh2BCIrfShEOmtxihmCrjFU',
                'instructor_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDXRw56L2wt0SmsPuEa1nRhYbr3tskvtAauun4awnfqM81_cHAekeBGaAqaCyp9Q6kjRo9UdF4NIZ8GYMMF-V4lYnJupvUQOyP83Th7x6Euhg96QF8cAkjYQ7hXUjrpfDkgptSGhX1HoLujMgLZGHbd-lhi80geSRtixjtZyps5Be0hFehahzfL1KSH-0pPDLR15Ad8ydLFvf5ppf3Lwd_MV7tCgw87tIDYBiPtSguFcv_c_J3uoyK6cr2y9WUskJO3Tn9BwGGbejA',
            ],
            [
                'title' => 'Full-Stack Intelligence',
                'category' => 'Development',
                'rating' => '4.8',
                'price' => '$249',
                'description' => 'Building modern web applications with a focus on architecture and scalable performance.',
                'instructor' => 'Sarah Sterling',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuALqc_gjWrNvvsziCeN7VfGkM290hlWWtmd8c7EWdYlZ16OFEM7zigE5Hq8U2vtukZBgiEq7zAU8ZcPT9BfHexaNzAGBxJlPK68hpgpJ-vB-5Qccb9N9yEdO69RQifygzQ5iPCQ4O6fRV0OxDVOosGT815rSr2Dl4Zzaoo6CDXFyVEgKlVJ7_iB6oSiSlgBMlaY9Fi6FXQ2WJi5AJL5JQ2TL6m2_AWfqLGYpXGXRtnBwxkkTG3CsZwiaD-fLXRAhCcz2R00ThLNCcY',
                'instructor_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCIhkH61BTWEzKErG1uTrs_OhEduVioMPP0x1IpIh-sAY2YqoAVmuN-xQR25cVBheH0dobOFcIOwNowqKcc0v6q8lKuUN9hnodkqhorVGxxDen2V9uft7Wzr8R0kzNoMC-wrDsGfoVfOwGDn-mYCEsrDxdfsdym8-41Jj6xr7vZ_-q3oX7tL_xgPtBOsdacORQM3nGJW3B0bzKgb2Kmt4EVuSO8DtHawaU2q9Tr_kjAqR4obXzTb5AkfDH3tmnAjw6457Y0UgqH1mE',
            ],
            [
                'title' => 'Digital Strategy Lab',
                'category' => 'Business',
                'rating' => '5.0',
                'price' => '$175',
                'description' => 'Transforming traditional business models into digital-first powerhouses through analytics.',
                'instructor' => 'Marc Hudson',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBVkqZhwHo_OOLT3R48YPf4LhpFzbUaE1UuRFDHVU9Vxo_kAiFSCkciymRAoCf3owTQyW3kEMD5jiJJkKiDWgFreMAWf0aSA6Au_SXy7Ca2g9G6MDQbCHERmD5cOwxpgBDE8nRVfuKgyEQkmvlB7-nfr4r3Ats8bicIHpq4ry22PYdqkJsUYW4Zg-97dzlyrm-gTVf8-EYAvsJ6ZsJyaDm3wQQMzcsycFVh-OcFSNA2qWmrNwq33Vwn4qlIXrLK1a3cGsaHvfWQknw',
                'instructor_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCaSSNmqxnztsUZoqDCAPIlbUh7V7DS4Ff1XEjrbKXBvjpjEuhdNcKN0ZXRTweLZ6hSIpONBQcjI35UY7r8EudGdyi-2ALkSbRL2u8Y7cBZ1QPsxwGQbVGUn7660qx9-6UtP7Fcp-Y6nNpOkqtMXEB9ALp0gFb6Og3YH3_z-8OdUMEfQR-gH461pSFzjqBAqzQUhwZc92J-hjU72eqIkVBKe1RlmFD2vh2BjSAtcD8FRFMWixFVqmuwbDabqUYuFbK1lIW5UWAQMig',
            ],
        ];

        $miniTestimonials = [
            ['text' => 'The structured approach to learning complex development concepts is unparalleled.', 'name' => 'Liam T.'],
            ['text' => 'I found my first internship within weeks of completing the Strategy Lab.', 'name' => 'Sofia G.'],
            ['text' => 'Mentorship sessions are pure gold. Worth every penny of the subscription.', 'name' => 'David K.'],
            ['text' => 'Finally, a learning platform that treats users like professionals.', 'name' => 'Chloe M.'],
        ];
    @endphp

    <nav class="fixed top-0 z-50 w-full border-b border-slate-200/60 bg-white/80 backdrop-blur-lg">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-8 py-4">
            <div class="flex items-center gap-12">
                <span class="font-['Manrope'] text-xl font-extrabold tracking-tighter text-indigo-900">LearnFlow</span>

                <div class="hidden items-center gap-8 md:flex">
                    @foreach ($navLinks as $link)
                        <a href="{{ $link['url'] }}" class="text-sm tracking-tight text-slate-600 transition-colors hover:text-indigo-600">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-lg px-4 py-2 text-sm text-indigo-900 transition-colors hover:bg-slate-100">Dashboard</a>
                    <a href="{{ $isHostOrAdmin ? route('host.courses.index') : route('courses.index') }}" class="editorial-gradient rounded-full px-6 py-2.5 text-sm font-semibold uppercase tracking-wider text-white transition-transform active:scale-95">
                        {{ $isHostOrAdmin ? 'Manage Courses' : 'Browse Courses' }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg px-4 py-2 text-sm text-indigo-900 transition-colors hover:bg-slate-100">Sign In</a>
                    <a href="{{ route('register') }}" class="editorial-gradient rounded-full px-6 py-2.5 text-sm font-semibold uppercase tracking-wider text-white transition-transform active:scale-95">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-20">
        <section class="relative overflow-hidden bg-slate-50 px-8 py-20">
            <div class="mx-auto grid min-h-[870px] max-w-7xl items-center gap-16 md:grid-cols-2">
                <div class="space-y-8">
                    <div class="inline-flex items-center gap-2 rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-indigo-900">
                        <span class="material-symbols-outlined text-sm">auto_awesome</span>
                        Revolutionizing Digital Education
                    </div>

                    <h1 class="font-['Manrope'] text-6xl font-extrabold leading-[1.1] tracking-tighter text-indigo-950 md:text-7xl">
                        Master Your Skills, <br>
                        <span class="text-indigo-600">Shape Your Future.</span>
                    </h1>

                    <p class="max-w-lg text-lg leading-relaxed text-slate-600">
                        Immerse yourself in a curated learning environment designed for deep focus. Access premium content, world-class mentors, and a community of high-achievers.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="#courses" class="editorial-gradient rounded-full px-8 py-4 text-sm font-bold uppercase tracking-widest text-white shadow-xl shadow-indigo-900/20 transition-transform hover:-translate-y-1">
                            Explore Courses
                        </a>
                        <a href="{{ route('courses.index') }}" class="rounded-full border border-slate-300 px-8 py-4 text-sm font-bold uppercase tracking-widest text-indigo-950 transition-colors hover:bg-slate-100">
                            Browse Catalog
                        </a>
                    </div>

                    <div class="flex items-center gap-6 pt-8">
                        <div class="flex -space-x-3">
                            <img class="h-10 w-10 rounded-full border-2 border-white object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCcOYPbKfB6F9By3miCzx38arXwCKSdtGhlXdP0rBH_NPJC5dYsLuK0G5Oe7pdneOYqMeyu79TdQti9YjpyFzb5GIUtdVuruKj2O5MqaNs_PhgXYiK1tEhfRYYtMrW-C6zHEm_6P2o4HIO9tu8UfoM4AQiq8qIAi1qOjGvwJJOdKItSXGqpvSb2u4vC9NSE1weqKKq3NS3pGxkR_3IWkPdW1cHeo7Jz4xNbcXqhfc9RtdJQlzSBaFVBHx7zbHZczGKj2uXDfdMXKDY" alt="Learner avatar">
                            <img class="h-10 w-10 rounded-full border-2 border-white object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAYiiTN_-BQD9mdg9BhC7_Wa-5IL_AXnoR3gTNjLcXC1c7q-6JSs6YA6LUuNd1-52M8YFcXJ9N68Lllfi2glWq7Ipn6nnKr4WdecZGFSX2Sv1DTtjOYdbt3UGa4lKHv4Uo4lcZxyMJb5FSJ17-zB54qC7WRF-AV4dQHrTMUhF6t69OBNHhZNy12AKWiLEz8hCU9RjS0T35CYUIfyz20IxF9-hXCXZwW1cIMZBAzPbvrzAz8Pb_OiuRhM-ZPa7OfDCliqn1JZjjA72o" alt="Learner avatar">
                            <img class="h-10 w-10 rounded-full border-2 border-white object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBzMvf2z_0T5aHN4TRPzP0yU7gTzkfpaYmHspwLFgIIUXC_dEYNijC0tOhuy6VIJaGIJ51qRpnjOu4piG98Vqhe7KNsjZ_W_CNca76ZNZdZC2kCm_XgLUiTdCL4GybnuAKZRDP-bGfQoHIxbDX_dmcwrSAVmr3v-zxVRv0id1Pd5zSP2bgsVOGVwlhUkis_ArZ72KYh909aNxETH5pfOB1HSqf4gU4Pe3D-Mn07gVWrLqBtkfA-0J0E36fy4s_YBlw2CTNUe7YiM64" alt="Learner avatar">
                        </div>

                        <p class="text-sm font-medium text-slate-600">{{ $statsText }}</p>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full bg-emerald-200/30 blur-[100px]"></div>
                    <div class="absolute -bottom-20 -left-20 h-80 w-80 rounded-full bg-indigo-200/30 blur-[100px]"></div>

                    <div class="relative aspect-[4/5] overflow-hidden rounded-2xl bg-white shadow-2xl">
                        <img class="h-full w-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfm0z3zHB4t1-10Z5jFoV1Vv5w9s0YT37Kdfj6piclIVtbUcVAqcO_7mTc5VdpQ0sXGsdPs107-InEQSQx6FSjUeiWC-cJLwCTFep-f6Xi9PQNW8kmsLDVtsvtVp_4lTEWdyiTxO94dm0qt5JtSUrEeCczna9jK5q5vcvRkThZ2A3i4b9WFOdww9M8d-yGrYF-R0s_PP8h9CAPAzCTCBpp_DcA_OO1qLMmA0PfO5X11sk7yMID5xM9khbImFdk5LfUATI7lXUbmd0" alt="Students learning">

                        <div class="glass-nav absolute bottom-6 left-6 right-6 rounded-xl border border-white/20 p-6">
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="mb-1 text-xs font-bold uppercase tracking-widest text-white/80">Live Now</p>
                                    <h3 class="font-['Manrope'] text-xl font-bold text-white">UI/UX Editorial Masterclass</h3>
                                </div>
                                <div class="flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs text-white backdrop-blur-md">
                                    <span class="h-2 w-2 animate-pulse rounded-full bg-red-500"></span>
                                    428 Watching
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-slate-100 px-8 py-24">
            <div class="mx-auto max-w-7xl">
                <div class="mb-20 space-y-4 text-center">
                    <h2 class="font-['Manrope'] text-4xl font-extrabold tracking-tight text-indigo-950">The Digital Atelier Experience</h2>
                    <p class="mx-auto max-w-2xl text-slate-600">We've reimagined the learning process from the ground up, focusing on deep integration and editorial quality.</p>
                </div>

                <div class="grid gap-6 md:h-[600px] md:grid-cols-12">
                    <div class="relative overflow-hidden rounded-2xl bg-white p-10 md:col-span-8">
                        <div class="relative z-10 max-w-md">
                            <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100">
                                <span class="material-symbols-outlined text-indigo-700">pace</span>
                            </div>
                            <h3 class="mb-4 font-['Manrope'] text-3xl font-bold text-indigo-950">Learn at Your Own Pace</h3>
                            <p class="text-lg leading-relaxed text-slate-600">Tailor your education to your schedule. Our modular content allows for deep dives or quick refreshes whenever inspiration strikes.</p>
                        </div>
                        <div class="absolute bottom-0 right-0 h-full w-1/2 opacity-10">
                            <span class="material-symbols-outlined text-[300px]">schedule</span>
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-2xl bg-emerald-950 p-10 text-emerald-100 md:col-span-4">
                        <div class="absolute right-0 top-0 p-8">
                            <span class="material-symbols-outlined text-5xl opacity-40">psychology</span>
                        </div>
                        <div class="mt-20">
                            <h3 class="mb-3 font-['Manrope'] text-2xl font-bold">Expert Mentorship</h3>
                            <p class="text-emerald-200">Direct access to industry veterans who have shaped the digital landscape.</p>
                        </div>
                    </div>

                    <div class="flex flex-col justify-between rounded-2xl bg-indigo-600 p-10 text-white md:col-span-4">
                        <span class="material-symbols-outlined text-4xl">work</span>
                        <div>
                            <h3 class="mb-3 font-['Manrope'] text-2xl font-bold">Career-Ready Skills</h3>
                            <p class="text-indigo-100">Projects that mirror real-world challenges, building a portfolio that commands attention.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-10 rounded-2xl bg-slate-200 p-10 md:col-span-8">
                        <div class="hidden h-32 w-48 flex-shrink-0 overflow-hidden rounded-lg sm:block">
                            <img class="h-full w-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA1eq_vjKS9mf9rudSBx8IH1okTmg5X83KtpZmU10vEu2pDlEDQxp3pNmllR4FMv4Yecm10hgkrmsrpQkDVq69-efNevcdA0DkAFkWFIR7Wzk7mt4jcZPz1iHc9nf5xP4yyEilJU1mwQ26ijs214q_cWACvtmSrKBwrx7aQD4Q6ukbj5KQoMy5BN8cntF7i7_hNPG_KNl43kjesk2qwrZln-Rtof-q8-Ym88L1G3ww34fUIGAQ_a_Dd_BVJO-4qsO8qdCiiUN7IVhQ" alt="Analytics screen">
                        </div>
                        <div>
                            <h3 class="mb-2 font-['Manrope'] text-2xl font-bold text-indigo-950">Advanced Analytics</h3>
                            <p class="text-slate-600">Visualize your progress with high-fidelity tracking of your skills development over time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="courses" class="bg-slate-50 px-8 py-24">
            <div class="mx-auto max-w-7xl">
                <div class="mb-12 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="mb-4 font-['Manrope'] text-4xl font-extrabold tracking-tight text-indigo-950">Trending Curriculums</h2>
                        <p class="text-slate-600">Most enrolled programs this season.</p>
                    </div>
                    <a href="{{ route('courses.index') }}" class="flex items-center gap-2 font-semibold text-indigo-600 transition-all hover:gap-4">
                        View All Courses
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>

                <div class="grid gap-8 md:grid-cols-3">
                    @foreach ($courses as $course)
                        <div class="group overflow-hidden rounded-2xl bg-white transition-all hover:-translate-y-2 hover:shadow-2xl">
                            <div class="relative aspect-video overflow-hidden">
                                <img src="{{ $course['image'] }}" alt="{{ $course['title'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-indigo-950">
                                    {{ $course['category'] }}
                                </div>
                            </div>

                            <div class="space-y-4 p-8">
                                <div class="flex items-start justify-between gap-4">
                                    <h3 class="font-['Manrope'] text-xl font-bold text-indigo-950 transition-colors group-hover:text-indigo-600">
                                        {{ $course['title'] }}
                                    </h3>
                                    <div class="flex items-center gap-1 text-sm font-bold text-emerald-700">
                                        <span class="material-symbols-outlined text-sm">star</span>
                                        {{ $course['rating'] }}
                                    </div>
                                </div>

                                <p class="line-clamp-2 text-sm text-slate-600">{{ $course['description'] }}</p>

                                <div class="flex items-center gap-3 border-t border-slate-200 pt-4">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ $course['instructor_image'] }}" alt="{{ $course['instructor'] }}">
                                    <span class="text-xs font-medium text-slate-600">{{ $course['instructor'] }}</span>
                                </div>

                                <div class="flex items-center justify-between pt-2">
                                    <span class="font-['Manrope'] text-2xl font-extrabold text-indigo-950">{{ $course['price'] }}</span>
                                    <a href="{{ route('courses.index') }}" class="rounded-full p-2 text-indigo-600 transition-colors hover:bg-slate-100">
                                        <span class="material-symbols-outlined">bookmark_add</span>
                                    </a>
                                </div>

                                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition-all hover:gap-3">
                                    Open course catalog
                                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="relative overflow-hidden bg-indigo-950 px-8 py-24 text-white">
            <div class="absolute right-0 top-0 h-full w-1/2 translate-x-1/4 skew-x-12 bg-indigo-500/10"></div>

            <div class="relative z-10 mx-auto max-w-7xl">
                <div class="grid items-center gap-20 md:grid-cols-2">
                    <div class="space-y-8">
                        <h2 class="font-['Manrope'] text-5xl font-extrabold leading-tight">
                            Hear it from the <br>
                            <span class="text-indigo-200">Next Generation.</span>
                        </h2>

                        <div class="rounded-2xl border border-white/10 bg-white/5 p-8 backdrop-blur-lg">
                            <p class="mb-6 text-xl italic leading-relaxed">
                                "LearnFlow isn't just a platform; it's an intellectual sanctuary. The quality of content and the direct access to mentors completely shifted my career trajectory in months."
                            </p>

                            <div class="flex items-center gap-4">
                                <img class="h-12 w-12 rounded-full object-cover ring-2 ring-indigo-300" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuVTfw_ROFVnyuAXn0__DIFAIX159Qk8sWkw1ZL1hNXAzNCSrs3bbvhzLWEj8-z9zT9JPD6gOxoQDHN05flb-eXmQhTubC5Q_z6IpMo8VBMkHaiVBo9ioqhWFnVhQsuE6dqfHm1MVqfxppdf4ZTBrujEc7rEfNbbiq9lTWMo9XcX_4EQc8rzg1hTLlTMpwc-Cr6TSYKe2N1sQ2b52N126Ep6KkZGtYbeAqyM412IguQomCDWzJbmDdoG8f-ndZZaVjb0w2-3MQvoQ" alt="Testimonial author">
                                <div>
                                    <p class="font-bold">Elena Rodriguez</p>
                                    <p class="text-xs text-white/60">Senior UI Designer at Lumina</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        @foreach ($miniTestimonials as $index => $testimonial)
                            <div class="{{ $index < 2 ? 'translate-y-8' : '' }} space-y-4">
                                @if (($index % 2) === 0)
                                    <div class="rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                                        <p class="mb-4 text-sm opacity-80">"{{ $testimonial['text'] }}"</p>
                                        <p class="text-xs font-bold">— {{ $testimonial['name'] }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div class="space-y-4">
                            <div class="rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                                <p class="mb-4 text-sm opacity-80">"Mentorship sessions are pure gold. Worth every penny of the subscription."</p>
                                <p class="text-xs font-bold">— David K.</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                                <p class="mb-4 text-sm opacity-80">"Finally, a learning platform that treats users like professionals."</p>
                                <p class="text-xs font-bold">— Chloe M.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-slate-50 px-8 py-24 text-center">
            <div class="mx-auto max-w-3xl space-y-10">
                <h2 class="font-['Manrope'] text-5xl font-extrabold tracking-tight text-indigo-950">Start Your Learning Journey Today</h2>
                <p class="text-xl text-slate-600">Join thousands of global learners who are shaping their futures with LearnFlow’s premier curriculum.</p>

                <div class="flex flex-col justify-center gap-4 sm:flex-row">
                    <a href="{{ auth()->check() ? route('courses.index') : route('register') }}" class="editorial-gradient rounded-full px-10 py-5 text-sm font-bold uppercase tracking-widest text-white shadow-2xl transition-all hover:scale-105 active:scale-95">
                        {{ auth()->check() ? 'Browse Courses' : 'Enroll Now' }}
                    </a>
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="rounded-full border border-slate-300 px-10 py-5 text-sm font-bold uppercase tracking-widest text-indigo-950 transition-all hover:bg-slate-100">
                        {{ auth()->check() ? 'Open Dashboard' : 'Sign In to Continue' }}
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="w-full border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col justify-between gap-12 px-8 py-12 md:flex-row">
            <div class="max-w-xs space-y-6">
                <span class="font-['Manrope'] text-2xl font-black text-indigo-900">LearnFlow</span>
                <p class="text-xs uppercase tracking-widest text-slate-400">Editorial Intelligence for the Modern Learner. Curating excellence in digital education since 2024.</p>
                <div class="flex gap-4">
                    <span class="material-symbols-outlined cursor-pointer text-slate-400 hover:text-indigo-600">social_leaderboard</span>
                    <span class="material-symbols-outlined cursor-pointer text-slate-400 hover:text-indigo-600">terminal</span>
                    <span class="material-symbols-outlined cursor-pointer text-slate-400 hover:text-indigo-600">public</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-12 sm:grid-cols-3">
                <div class="space-y-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-900">Platform</p>
                    <nav class="flex flex-col gap-2">
                        <a class="text-xs uppercase tracking-widest text-slate-400 underline underline-offset-4 hover:text-indigo-500" href="{{ route('home') }}">About Us</a>
                        <a class="text-xs uppercase tracking-widest text-slate-400 underline underline-offset-4 hover:text-indigo-500" href="{{ route('courses.index') }}">Course Categories</a>
                        <a class="text-xs uppercase tracking-widest text-slate-400 underline underline-offset-4 hover:text-indigo-500" href="{{ auth()->check() ? route('dashboard') : route('login') }}">Academic Support</a>
                    </nav>
                </div>

                <div class="space-y-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-900">Legal</p>
                    <nav class="flex flex-col gap-2">
                        <a class="text-xs uppercase tracking-widest text-slate-400 underline underline-offset-4 hover:text-indigo-500" href="{{ route('register') }}">Privacy Policy</a>
                        <a class="text-xs uppercase tracking-widest text-slate-400 underline underline-offset-4 hover:text-indigo-500" href="{{ route('login') }}">Terms of Service</a>
                    </nav>
                </div>

                <div class="space-y-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-900">Contact</p>
                    <p class="text-xs uppercase tracking-widest text-slate-400">support@learnflow.ai</p>
                    <p class="text-xs uppercase tracking-widest text-slate-400">+1 (555) 000-0000</p>
                </div>
            </div>
        </div>

        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 border-t border-slate-100 px-8 py-8 md:flex-row">
            <p class="text-[10px] uppercase tracking-widest text-slate-400">© 2024 LearnFlow Editorial Intelligence. All rights reserved.</p>
            <div class="flex gap-6">
                <div class="h-6 w-10 rounded bg-slate-100 opacity-50"></div>
                <div class="h-6 w-10 rounded bg-slate-100 opacity-50"></div>
                <div class="h-6 w-10 rounded bg-slate-100 opacity-50"></div>
            </div>
        </div>
    </footer>
</body>
</html>
