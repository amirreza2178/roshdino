<nav class="staff-subnav" aria-label="بخش‌های محتوا">
    <a href="{{ route('site.home-copy.edit') }}" @if (request()->routeIs('site.home-copy.*')) aria-current="page" @endif>صفحهٔ اصلی</a>
    <a href="{{ route('site.pages.index') }}" @if (request()->routeIs('site.pages.*')) aria-current="page" @endif>صفحات</a>
    <a href="{{ route('site.services.index') }}" @if (request()->routeIs('site.services.*')) aria-current="page" @endif>خدمات</a>
    <a href="{{ route('site.works.index') }}" @if (request()->routeIs('site.works.*')) aria-current="page" @endif>نمونه‌کارها</a>
    <a href="{{ route('site.categories.index') }}" @if (request()->routeIs('site.categories.*')) aria-current="page" @endif>دسته‌ها</a>
    <a href="{{ route('site.settings.edit') }}" @if (request()->routeIs('site.settings.*')) aria-current="page" @endif>تنظیمات</a>
    <a href="{{ route('site.media.index') }}" @if (request()->routeIs('site.media.*')) aria-current="page" @endif>رسانه‌ها</a>
</nav>
