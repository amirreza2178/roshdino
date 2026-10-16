<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#080C0A">
    <meta name="color-scheme" content="dark">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="@yield('meta_description', 'ورود مسئول رشدینو برای پیگیری مسیر فروش.')">
    <title>@yield('title', 'مسئول رشدینو')</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ink text-ivory antialiased">
    <a class="skip-link" href="#main-content">پرش به محتوای اصلی</a>

    <header class="site-header">
        <div class="shell staff-bar">
            <a href="{{ route('home') }}" class="brand" aria-label="رشدینو، صفحه اصلی">
                <span class="brand__mark" aria-hidden="true">R</span>
                <span class="brand__name">رشدینو</span>
            </a>

            @auth
                <div class="staff-bar__actions">
                    <nav class="staff-nav" aria-label="پنل مسئول">
                        <a href="{{ route('leads.index') }}">سرنخ‌ها</a>
                        <a href="{{ route('site.home') }}">محتوا</a>
                        <a href="{{ route('password.edit') }}">رمز عبور</a>
                    </nav>
                    <span class="staff-bar__name">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="button button--ghost">خروج</button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>
</body>
</html>
