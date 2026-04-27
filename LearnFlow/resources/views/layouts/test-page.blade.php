<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'LearnFlow') | LearnFlow</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg: #f8fafc;
            --surface: #ffffff;
            --surface-soft: #f1f5f9;
            --surface-tint: #eef2ff;
            --text: #172554;
            --text-strong: #0f172a;
            --muted: #64748b;
            --accent: #4f46e5;
            --accent-dark: #000666;
            --accent-mid: #4355b9;
            --accent-soft: #e0e7ff;
            --emerald: #047857;
            --emerald-dark: #022c22;
            --border: #e2e8f0;
            --danger: #b91c1c;
            --danger-soft: #fee2e2;
            --success: #0f766e;
            --shadow: 0 24px 60px rgba(15, 23, 42, 0.10);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Inter", ui-sans-serif, system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(circle at 8% 12%, rgba(79, 70, 229, 0.12) 0, transparent 22rem),
                radial-gradient(circle at 85% 8%, rgba(16, 185, 129, 0.11) 0, transparent 20rem),
                linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        }

        a {
            color: inherit;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }

        .editorial-gradient {
            background: linear-gradient(135deg, var(--accent-dark) 0%, var(--accent-mid) 100%);
        }

        .topbar {
            position: fixed;
            top: 0;
            z-index: 50;
            width: 100%;
            border-bottom: 1px solid rgba(226, 232, 240, 0.72);
            background: rgba(255, 255, 255, 0.84);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .topbar-inner {
            display: flex;
            max-width: 1240px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        .brand {
            font-family: "Manrope", sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: #312e81;
            text-decoration: none;
        }

        .topbar-links,
        .topbar-actions,
        .nav {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-links {
            gap: 1.5rem;
        }

        .topbar-links a {
            color: #475569;
            font-size: 0.9rem;
            text-decoration: none;
            transition: color 160ms ease;
        }

        .topbar-links a:hover {
            color: var(--accent);
        }

        .shell {
            max-width: 1240px;
            margin: 0 auto;
            padding: 6.2rem 1.5rem 4rem;
        }

        .hero {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 24px;
            padding: 2.2rem;
            color: #ffffff;
            box-shadow: var(--shadow);
        }

        .hero::after {
            content: "";
            position: absolute;
            top: -30%;
            right: -12%;
            width: 34rem;
            height: 34rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 58rem;
        }

        .hero h1,
        .hero h2,
        .hero p,
        .card h2,
        .card h3,
        .metric-card strong {
            margin: 0;
        }

        .hero h1,
        .hero h2,
        .card h2,
        .card h3,
        .metric-card strong,
        .brand {
            font-family: "Manrope", sans-serif;
        }

        .hero h1 {
            margin-top: 0.85rem;
            max-width: 44rem;
            font-size: clamp(2.6rem, 7vw, 5.4rem);
            line-height: 1.02;
            font-weight: 800;
            letter-spacing: -0.055em;
        }

        .hero p {
            margin-top: 1rem;
            max-width: 46rem;
            color: rgba(255, 255, 255, 0.78);
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .hero .pill {
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.10);
            color: #ffffff;
        }

        .nav {
            position: relative;
            z-index: 1;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        .nav a,
        .nav button,
        .nav-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.09);
            color: #ffffff;
            padding: 0.58rem 0.95rem;
            font: inherit;
            font-size: 0.86rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: transform 160ms ease, background 160ms ease, border-color 160ms ease;
        }

        .nav a:hover,
        .nav button:hover {
            transform: translateY(-1px);
            border-color: rgba(255, 255, 255, 0.38);
            background: rgba(255, 255, 255, 0.15);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.92);
            padding: 1.35rem;
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
        }

        .card h2,
        .card h3 {
            color: var(--text);
            font-weight: 800;
            letter-spacing: -0.035em;
        }

        .card h2 {
            font-size: clamp(1.55rem, 3vw, 2.15rem);
        }

        .card h3 {
            font-size: 1.18rem;
        }

        .card p {
            margin: 0;
            line-height: 1.65;
        }

        .card p + p,
        .card h3 + p,
        .card p + ul,
        .card h2 + p {
            margin-top: 0.7rem;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 0.38rem;
            width: fit-content;
            border-radius: 999px;
            background: var(--accent-soft);
            color: #3730a3;
            padding: 0.35rem 0.72rem;
            font-size: 0.76rem;
            font-weight: 800;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .stack {
            display: grid;
            gap: 0.95rem;
        }

        .field {
            display: grid;
            gap: 0.42rem;
        }

        label {
            color: var(--text);
            font-size: 0.9rem;
            font-weight: 800;
        }

        input,
        textarea,
        select {
            width: 100%;
            min-height: 3rem;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.96);
            color: var(--text-strong);
            padding: 0.78rem 0.9rem;
            font: inherit;
            transition: border-color 160ms ease, box-shadow 160ms ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: rgba(79, 70, 229, 0.58);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
            outline: 0;
        }

        input[type="checkbox"] {
            min-height: auto;
            accent-color: var(--accent);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
        }

        .button,
        button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2.75rem;
            border: 0;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent-dark) 0%, var(--accent-mid) 100%);
            color: #ffffff;
            padding: 0.76rem 1.12rem;
            font: inherit;
            font-size: 0.86rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-decoration: none;
            text-transform: uppercase;
            cursor: pointer;
            transition: transform 160ms ease, box-shadow 160ms ease, background 160ms ease;
        }

        .button:hover,
        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(49, 46, 129, 0.16);
        }

        .button.secondary,
        .ghost-button {
            border: 1px solid var(--border);
            background: #ffffff;
            color: var(--text);
        }

        .button.danger,
        button.danger,
        .danger {
            background: var(--danger);
            color: #ffffff;
        }

        .notice,
        .error-box {
            margin-top: 1rem;
            border-radius: 18px;
            padding: 1rem 1.1rem;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
        }

        .notice {
            border: 1px solid #a7f3d0;
            background: #ecfdf5;
            color: #047857;
        }

        .error-box {
            border: 1px solid #fecaca;
            background: var(--danger-soft);
            color: var(--danger);
        }

        .list {
            margin: 0;
            padding-left: 1.2rem;
        }

        .list li + li {
            margin-top: 0.35rem;
        }

        .meta {
            color: var(--muted);
            font-size: 0.94rem;
        }

        .meta strong {
            color: var(--text);
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 0.95rem;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 18px;
        }

        .table th,
        .table td {
            padding: 0.85rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
            vertical-align: top;
        }

        .table th {
            background: var(--surface-soft);
            color: var(--text);
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .table tr:last-child td {
            border-bottom: 0;
        }

        .split {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.85rem;
            align-items: center;
        }

        .eyebrow {
            color: var(--muted);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .metric-grid,
        .course-card-grid {
            display: grid;
            gap: 1rem;
        }

        .metric-grid {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }

        .course-card-grid {
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            margin-top: 1rem;
        }

        .metric-card,
        .soft-panel,
        .lesson-card,
        .module-card {
            border: 1px solid var(--border);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.86);
            padding: 1rem;
        }

        .metric-card strong {
            display: block;
            margin-top: 0.35rem;
            color: var(--text);
            font-size: 1.85rem;
            line-height: 1;
        }

        .soft-panel {
            background: linear-gradient(180deg, rgba(238, 242, 255, 0.96), rgba(255, 255, 255, 0.9));
        }

        .module-stack,
        .lesson-stack {
            display: grid;
            gap: 1rem;
        }

        .module-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(241, 245, 249, 0.92));
        }

        .lesson-card {
            background: #ffffff;
        }

        .reorder-list {
            display: grid;
            gap: 0.85rem;
            margin-top: 1rem;
        }

        .order-item {
            display: flex;
            flex-wrap: wrap;
            gap: 0.85rem;
            align-items: center;
            justify-content: space-between;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.9);
            padding: 0.9rem 1rem;
        }

        .order-handle {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .ghost-button {
            padding: 0.55rem 0.8rem;
        }

        .mono {
            font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
        }

        .helper {
            color: var(--muted);
            font-size: 0.88rem;
        }

        .content-panel {
            display: none;
        }

        .content-panel.is-active {
            display: block;
        }

        .content-box {
            border: 1px solid var(--border);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.88);
            padding: 1rem;
            white-space: pre-wrap;
        }

        .status-dot {
            display: inline-block;
            width: 0.65rem;
            height: 0.65rem;
            border-radius: 999px;
            background: var(--success);
        }

        .status-dot.draft {
            background: #d97706;
        }

        .hidden {
            display: none !important;
        }

        @media (max-width: 860px) {
            .topbar-inner {
                align-items: flex-start;
                flex-direction: column;
            }

            .topbar-links {
                display: flex;
            }

            .topbar-links a:not(.brand) {
                display: none;
            }

            .shell {
                padding-top: 8.25rem;
            }
        }

        @media (max-width: 720px) {
            .topbar-inner,
            .shell {
                padding-left: 0.9rem;
                padding-right: 0.9rem;
            }

            .topbar-actions {
                width: 100%;
                flex-wrap: wrap;
            }

            .hero,
            .card,
            .metric-card,
            .soft-panel,
            .module-card,
            .lesson-card {
                border-radius: 18px;
            }

            .hero {
                padding: 1.35rem;
            }

            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    @php
        $isAuthenticated = auth()->check();
        $isHostOrAdmin = $isAuthenticated && in_array(auth()->user()->role, ['host', 'admin'], true);
    @endphp

    <nav class="topbar">
        <div class="topbar-inner">
            <div class="topbar-links">
                <a class="brand" href="{{ route('home') }}">LearnFlow</a>
                <a href="{{ route('courses.index') }}">Courses</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('profile.show') }}">Profile</a>
                    <a href="{{ route('enrollments.index') }}">Enrollments</a>
                    @if ($isHostOrAdmin)
                        <a href="{{ route('host.courses.index') }}">Manage</a>
                    @endif
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.users.index') }}">Admin</a>
                    @endif
                @else
                    <a href="{{ route('login') }}">Dashboard</a>
                @endauth
            </div>

            <div class="topbar-actions">
                @auth
                    <a class="button secondary" href="{{ route('dashboard') }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a class="button secondary" href="{{ route('login') }}">Sign In</a>
                    <a class="button" href="{{ route('register') }}">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="shell">
        <section class="hero editorial-gradient">
            <div class="hero-content">
                <div class="pill">
                    <span class="material-symbols-outlined" style="font-size: 1rem;">auto_awesome</span>
                    LearnFlow Workspace
                </div>
                <h1>@yield('title', 'LearnFlow')</h1>
                <p>@yield('lead', 'Curated learning tools, course management, and progress views for the modern learner.')</p>
            </div>

            <nav class="nav">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('courses.index') }}">Courses</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('profile.show') }}">Profile</a>
                    <a href="{{ route('enrollments.index') }}">Enrollments</a>
                    <a href="{{ route('password.show') }}">Password</a>
                    @if ($isHostOrAdmin)
                        <a href="{{ route('host.courses.index') }}">Manage Courses</a>
                    @endif
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.users.index') }}">Users</a>
                        <a href="{{ route('admin.categories.index') }}">Categories</a>
                    @endif
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </nav>
        </section>

        @if (session('success'))
            <div class="notice">{{ session('success') }}</div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="error-box">
                <strong>Request errors</strong>
                <ul class="list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <main style="margin-top: 1.5rem;">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
