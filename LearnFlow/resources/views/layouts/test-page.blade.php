<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'LearnFlow test page')</title>
    <style>
        :root {
            --bg: #f4efe6;
            --panel: #fffaf2;
            --panel-alt: #f0e5d6;
            --text: #2f241c;
            --muted: #6f5c4f;
            --accent: #0f766e;
            --accent-soft: #d3f2ee;
            --border: #d7c4ae;
            --danger: #991b1b;
            --danger-soft: #fee2e2;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            background:
                radial-gradient(circle at top left, #fef3c7 0, transparent 24rem),
                linear-gradient(180deg, #f8f5ef 0%, var(--bg) 100%);
            color: var(--text);
        }

        a {
            color: inherit;
        }

        .shell {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1rem 4rem;
        }

        .hero {
            background: linear-gradient(135deg, #124e4a, #1f6f67 55%, #d7a857 55%, #f3d7a3 100%);
            color: #fff9f1;
            border-radius: 22px;
            padding: 1.5rem;
            box-shadow: 0 18px 45px rgba(47, 36, 28, 0.14);
        }

        .hero h1,
        .hero h2,
        .hero p {
            margin: 0;
        }

        .hero p {
            margin-top: 0.65rem;
            max-width: 42rem;
            color: #f7efe4;
        }

        .nav {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: 1rem;
        }

        .nav a,
        .nav button {
            border: 1px solid rgba(255, 249, 241, 0.35);
            background: rgba(255, 249, 241, 0.12);
            color: #fff9f1;
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
            border-radius: 18px;
            padding: 1.15rem;
            box-shadow: 0 10px 30px rgba(47, 36, 28, 0.07);
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
            border-radius: 12px;
            padding: 0.75rem 0.85rem;
            background: #fff;
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
            border-radius: 12px;
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
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('profile.show') }}">Profile</a>
                <a href="{{ route('password.show') }}">Change Password</a>
                <a href="{{ route('admin.users.index') }}">Admin Users</a>
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
