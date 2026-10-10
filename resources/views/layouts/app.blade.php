<!DOCTYPE html>
<html lang="fa" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#080C0A">
    <meta name="color-scheme" content="dark">
    <meta name="description" content="@yield('meta_description', 'رشدینو یک استودیو رشد دیجیتال است؛ از طراحی و توسعه وب و نرم‌افزار اختصاصی تا راهکارهای رشد برای ساخت تجربه‌های دیجیتال قابل‌استفاده و قابل‌توسعه.')">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="fa_IR">
    <meta property="og:site_name" content="رشدینو">
    <meta property="og:title" content="@yield('og_title', 'رشدینو | استودیو رشد دیجیتال')">
    <meta property="og:description" content="@yield('og_description', 'وب، نرم‌افزار و راهکارهای رشد دیجیتال برای حل مسئله واقعی کسب‌وکار.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <title>@yield('title', 'رشدینو | استودیو رشد دیجیتال')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="bg-ink text-ivory antialiased">
    <a class="skip-link" href="#main-content">پرش به محتوای اصلی</a>

    @include('components.navbar')

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    @include('components.footer')

    <div class="mobile-cta" aria-label="اقدام سریع">
        <a href="#contact" class="mobile-cta__link">
            <span>شروع یک پروژه</span>
            <span aria-hidden="true">←</span>
        </a>
    </div>

    @stack('scripts')
</body>
</html>
