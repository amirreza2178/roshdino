<header class="site-header" data-site-header>
    <div class="shell">
        <nav class="nav" aria-label="ناوبری اصلی">
            <a href="{{ route('home') }}" class="brand" aria-label="رشدینو، صفحه اصلی">
                <span class="brand__mark" aria-hidden="true">R</span>
                <span class="brand__name">رشدینو</span>
            </a>

            <button
                type="button"
                class="nav__toggle"
                data-menu-toggle
                aria-expanded="false"
                aria-controls="mobile-navigation"
                aria-label="باز کردن منو"
            >
                <span></span><span></span>
            </button>

            <div class="nav__desktop" aria-label="لینک‌های اصلی">
                <a href="#services" class="nav__link">راهکارها</a>
                <a href="#work" class="nav__link">نمونه‌کار</a>
                <a href="#process" class="nav__link">فرآیند</a>
                <a href="#about" class="nav__link">درباره</a>
            </div>

            <a href="#contact" class="button button--small button--lime nav__cta">
                شروع پروژه
                <span aria-hidden="true">←</span>
            </a>
        </nav>

        <div id="mobile-navigation" class="nav__mobile" data-mobile-menu hidden>
            <a href="#services" class="nav__mobile-link">راهکارها</a>
            <a href="#work" class="nav__mobile-link">نمونه‌کار</a>
            <a href="#process" class="nav__mobile-link">فرآیند</a>
            <a href="#about" class="nav__mobile-link">درباره</a>
            <a href="#contact" class="button button--lime nav__mobile-cta">شروع یک پروژه <span aria-hidden="true">←</span></a>
        </div>
    </div>
</header>
