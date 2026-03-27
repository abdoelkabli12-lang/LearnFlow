<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'LearnFlow test page')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f4f7fb;
            --panel: rgba(255, 255, 255, 0.9);
            --panel-alt: #edf2ff;
            --text: #172554;
            --muted: #5b6b91;
            --accent: #1d4ed8;
            --accent-soft: #dbeafe;
            --border: #d8e1f3;
            --danger: #991b1b;
            --danger-soft: #fee2e2;
            --success: #0f766e;
            --success-soft: #ccfbf1;
            --shadow: 0 24px 60px rgba(37, 65, 143, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Inter", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(96, 165, 250, 0.22) 0, transparent 22rem),
                radial-gradient(circle at top right, rgba(251, 191, 36, 0.22) 0, transparent 18rem),
                linear-gradient(180deg, #eff4ff 0%, var(--bg) 100%);
            color: var(--text);
        }

        a {
            color: inherit;
        }

        .shell {
            max-width: 1240px;
            margin: 0 auto;
            padding: 2rem 1rem 4rem;
        }

        .hero {
            background:
                radial-gradient(circle at top right, rgba(251, 191, 36, 0.35) 0, transparent 18rem),
                linear-gradient(135deg, #0f172a 0%, #1e3a8a 52%, #2563eb 100%);
            color: #eff6ff;
            border-radius: 28px;
            padding: 1.7rem;
            box-shadow: var(--shadow);
        }

        .hero h1,
        .hero h2,
        .hero p {
            margin: 0;
        }

        .hero h1,
        .hero h2,
        .card h2,
        .card h3,
        .metric-card strong {
            font-family: "Manrope", sans-serif;
        }

        .hero p {
            margin-top: 0.65rem;
            max-width: 48rem;
            color: #dbeafe;
        }

        .nav {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: 1rem;
        }

        .nav a,
        .nav button {
            border: 1px solid rgba(219, 234, 254, 0.35);
            background: rgba(219, 234, 254, 0.1);
            color: #eff6ff;
            border-radius: 999px;
            padding: 0.55rem 0.9rem;
            text-decoration: none;
            font: inherit;
            cursor: pointer;
        }

        .grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            margin-top: 1.5rem;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            backdrop-filter: blur(14px);
            border-radius: 24px;
            padding: 1.2rem;
            box-shadow: 0 14px 36px rgba(37, 65, 143, 0.08);
        }

        .card h2,
        .card h3,
        .card p {
            margin: 0;
        }

        .card p + p,
        .card h3 + p,
        .card p + ul,
        .card h2 + p {
            margin-top: 0.65rem;
        }

        .pill {
            display: inline-block;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 0.92rem;
            font-weight: 700;
        }

        .stack {
            display: grid;
            gap: 0.85rem;
        }

        .field {
            display: grid;
            gap: 0.35rem;
        }

        label {
            font-weight: 700;
        }

        input,
        textarea,
        select {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 0.75rem 0.85rem;
            background: rgba(255, 255, 255, 0.96);
            color: var(--text);
            font: inherit;
        }

        textarea {
            min-height: 110px;
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
            border: 0;
            border-radius: 14px;
            background: var(--accent);
            color: #fff;
            padding: 0.75rem 1rem;
            text-decoration: none;
            font: inherit;
            cursor: pointer;
        }

        .button.secondary {
            background: var(--panel-alt);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .button.danger,
        .danger {
            background: var(--danger);
            color: #fff;
        }

        .notice,
        .error-box {
            margin-top: 1rem;
            border-radius: 16px;
            padding: 0.95rem 1rem;
        }

        .notice {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .error-box {
            background: var(--danger-soft);
            color: var(--danger);
        }

        .list {
            margin: 0;
            padding-left: 1.2rem;
        }

        .meta {
            color: var(--muted);
            font-size: 0.95rem;
        }

        .meta strong {
            color: var(--text);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.75rem;
        }

        .table th,
        .table td {
            padding: 0.7rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
            vertical-align: top;
        }

        .split {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.75rem;
            align-items: center;
        }

        .eyebrow {
            text-transform: uppercase;
            letter-spacing: 0.14em;
            font-size: 0.8rem;
            color: var(--muted);
            font-weight: 700;
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
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.82);
            padding: 1rem;
        }

        .metric-card strong {
            display: block;
            font-size: 1.8rem;
            line-height: 1;
            margin-top: 0.35rem;
        }

        .soft-panel {
            background: linear-gradient(180deg, rgba(237, 242, 255, 0.96), rgba(255, 255, 255, 0.86));
        }

        .module-stack,
        .lesson-stack {
            display: grid;
            gap: 1rem;
        }

        .module-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(239, 244, 255, 0.9));
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
            gap: 0.75rem;
            align-items: center;
            justify-content: space-between;
            padding: 0.85rem 1rem;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.78);
        }

        .order-handle {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .ghost-button {
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text);
            padding: 0.55rem 0.8rem;
        }

        .mono {
            font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
        }

        .helper {
            font-size: 0.88rem;
            color: var(--muted);
        }

        .content-panel {
            display: none;
        }

        .content-panel.is-active {
            display: block;
        }

        .content-box {
            border-radius: 18px;
            border: 1px solid var(--border);
            padding: 1rem;
            background: rgba(255, 255, 255, 0.86);
            white-space: pre-wrap;
        }

        .status-dot {
            width: 0.65rem;
            height: 0.65rem;
            border-radius: 999px;
            display: inline-block;
            background: var(--success);
        }

        .status-dot.draft {
            background: #d97706;
        }

        .hidden {
            display: none !important;
        }

        @media (max-width: 720px) {
            .shell {
                padding: 1rem 0.75rem 3rem;
            }

            .hero,
            .card,
            .metric-card,
            .soft-panel,
            .module-card,
            .lesson-card {
                border-radius: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <section class="hero">
            <div class="pill">LearnFlow route tester</div>
            <h1 style="margin-top: 0.85rem;">@yield('title', 'LearnFlow test page')</h1>
            <p>@yield('lead', 'Minimal views to make the current routes easy to test while we sort out controller logic.')</p>

            <nav class="nav">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('courses.index') }}">Courses</a>
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('profile.show') }}">Profile</a>
                <a href="{{ route('password.show') }}">Change Password</a>
                <a href="{{ route('admin.users.index') }}">Admin Users</a>
                @auth
                    @if (auth()->user()->role === 'host' || auth()->user()->role === 'admin')
                        <a href="{{ route('host.courses.index') }}">Manage Courses</a>
                    @endif
                @endauth
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
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
</body>
</html>
