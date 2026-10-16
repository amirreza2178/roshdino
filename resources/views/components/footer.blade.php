<footer class="site-footer">
    <div class="shell">
        <div class="footer__top">
            <div class="footer__brand">
                <a href="{{ route('home') }}" class="brand" aria-label="رشدینو، صفحه اصلی">
                    <span class="brand__mark" aria-hidden="true">R</span>
                    <span class="brand__name">{{ $site['brand'] ?? 'رشدینو' }}</span>
                </a>
                <p>{{ $site['footer'] ?? 'استودیو رشد دیجیتال؛ وب، نرم‌افزار و راهکارهایی برای مسئله واقعی کسب‌وکار.' }}</p>
            </div>

            <div class="footer__links" aria-label="لینک‌های پایانی">
                <div>
                    <span class="footer__label">برو به</span>
                    <a href="{{ route('home') }}#services">راهکارها</a>
                    <a href="{{ route('home') }}#work">نمونه‌کار</a>
                    <a href="{{ route('home') }}#process">فرآیند</a>
                    <a href="{{ route('pages.show', 'contact') }}">تماس</a>
                </div>
                <div>
                    <span class="footer__label">رشدینو</span>
                    <a href="{{ route('pages.show', 'about') }}">درباره ما</a>
                    <a href="{{ route('home') }}#faq">سؤالات متداول</a>
                    @if (($site['telegram'] ?? '') !== '')
                        <a href="https://t.me/{{ $site['telegram'] }}" target="_blank" rel="noopener noreferrer">تلگرام</a>
                    @endif
                    @if (($site['instagram'] ?? '') !== '')
                        <a href="{{ $site['instagram'] }}" target="_blank" rel="noopener noreferrer">اینستاگرام</a>
                    @endif
                    @if (($site['linkedin'] ?? '') !== '')
                        <a href="{{ $site['linkedin'] }}" target="_blank" rel="noopener noreferrer">لینکدین</a>
                    @endif
                    @if (($site['x'] ?? '') !== '')
                        <a href="{{ $site['x'] }}" target="_blank" rel="noopener noreferrer">X</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer__bottom">
            <span>© {{ date('Y') }} {{ $site['brand'] ?? 'رشدینو' }}. همه حقوق محفوظ است.</span>
        </div>
    </div>
</footer>
