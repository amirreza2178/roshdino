@extends('layouts.app')

@section('title', $site['seo_title'] !== '' ? $site['seo_title'] : 'رشدینو | طراحی سایت، نرم‌افزار اختصاصی و رشد دیجیتال')
@section('meta_description', $site['seo_description'] !== '' ? $site['seo_description'] : 'رشدینو یک استودیو رشد دیجیتال است؛ طراحی و توسعه وب، نرم‌افزار اختصاصی و راهکارهای رشد برای کسب‌وکارهایی که می‌خواهند تجربه دیجیتال قابل‌استفاده و قابل‌توسعه داشته باشند.')
@section('og_title', $site['seo_og_title'] !== '' ? $site['seo_og_title'] : 'رشدینو | استودیو رشد دیجیتال')
@section('og_description', $site['seo_og_description'] !== '' ? $site['seo_og_description'] : 'وب، نرم‌افزار و راهکارهای رشد دیجیتال برای حل مسئله واقعی کسب‌وکار.')
@section('canonical', $site['seo_canonical'] !== '' ? $site['seo_canonical'] : url()->current())
@section('robots', $site['indexable'] ? 'index, follow' : 'noindex, nofollow')
@if ($site['og_image'] !== '')
    @section('og_image', $site['og_image'])
@endif

@section('content')
    <section class="hero section">
        <div class="shell hero__layout">
            <div class="hero__content reveal">
                <p class="eyebrow eyebrow--fa"><span></span> {{ $home['hero']['eyebrow'] }}</p>
                <h1>{!! \App\Models\HomePage::emphasized($home['hero']['title'], $home['hero']['accent']) !!}</h1>
                <p class="hero__lead">{{ $home['hero']['lead'] }}</p>

                <div class="hero__actions">
                    <a href="{{ route('home', ['intent' => 'request']) }}#contact" class="button button--lime button--large">شروع یک پروژه <span aria-hidden="true">←</span></a>
                    <a href="#work" class="button button--ghost">دیدن نمونه‌کار <span aria-hidden="true">↓</span></a>
                </div>

                <a href="{{ route('home', ['intent' => 'request']) }}#contact" class="micro-link">
                    هنوز نمی‌دانی دقیقاً به چه چیزی نیاز داری؟ <strong>مسئله‌ات را بفرست.</strong>
                </a>

                <p class="hero__note">{{ $home['hero']['note'] }}</p>
            </div>

            <aside class="hero__paths" aria-label="سه مسیر">
                <p class="eyebrow eyebrow--fa"><span></span> سه مسیر</p>
                @forelse ($services->take(3) as $service)
                    <a class="hero__path" href="{{ route('services.show', $service->slug) }}">
                        <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <strong>{{ $service->title }}</strong>
                        <p>{{ $service->description }}</p>
                    </a>
                @empty
                    <a class="hero__path" href="{{ route('home', ['intent' => 'request', 'service' => 'web']) }}#contact">
                        <span>01</span>
                        <strong>طراحی و توسعه وب</strong>
                        <p>سایت‌هایی سریع، حرفه‌ای و قابل توسعه که فقط زیبا نیستند؛ برای کسب‌وکار کار می‌کنند.</p>
                    </a>
                    <a class="hero__path" href="{{ route('home', ['intent' => 'request', 'service' => 'software']) }}#contact">
                        <span>02</span>
                        <strong>نرم‌افزار اختصاصی</strong>
                        <p>فرآیندهای پیچیده کسب‌وکارت را به یک سیستم ساده، قابل کنترل و قابل توسعه تبدیل می‌کنیم.</p>
                    </a>
                    <a class="hero__path" href="{{ route('home', ['intent' => 'request', 'service' => 'growth']) }}#contact">
                        <span>03</span>
                        <strong>راهکارهای رشد دیجیتال</strong>
                        <p>از ساختار سایت و محتوا تا تجربه کاربر و مسیر تبدیل؛ نقاط اصطکاک را پیدا می‌کنیم و بهبود می‌دهیم.</p>
                    </a>
                @endforelse
            </aside>
        </div>
    </section>

    <section class="section section--problem" id="problem">
        <div class="shell">
            <div class="section-intro reveal">
                <p class="eyebrow eyebrow--fa"><span></span> {{ $home['problem']['eyebrow'] }}</p>
                <h2>{!! \App\Models\HomePage::emphasized($home['problem']['title'], $home['problem']['accent']) !!}</h2>
                <p>{{ $home['problem']['lead'] }}</p>
            </div>

            <div class="problem-list">
                @foreach ($home['problem']['items'] as $item)
                    <article class="problem-item reveal">
                        <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['body'] }}</p>
                        </div>
                        <a href="{{ route('home', ['intent' => 'request']) }}#contact" class="problem-arrow" aria-label="درباره {{ $item['title'] }} گفتگو کنیم">حل مشکل</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--services" id="services">
        <div class="shell">
            <div class="section-intro section-intro--services reveal">
                <p class="eyebrow eyebrow--fa"><span></span> سه مسیر</p>
                <h2>راهکاری بساز که <em>برای کسب‌وکار کار کند.</em></h2>
                <p>سه مسیر اصلی داریم؛ اما نقطه شروع همیشه مسئله توست، نه تکنولوژی.</p>
            </div>

            <div class="services-grid">
                @forelse ($services as $service)
                    <article class="service-card reveal">
                        <header class="service-card__header">
                            <span class="service-card__index" aria-hidden="true">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="service-card__heading">
                                <h3><a href="{{ route('services.show', $service->slug) }}">{{ $service->title }}</a></h3>
                            </div>
                            <a href="{{ $service->ctaHref() }}" class="service-card__action" aria-label="درخواست درباره {{ $service->title }}"><span class="service-card__action-label">{{ $service->cta_label }}</span><span aria-hidden="true">↗</span></a>
                        </header>
                        <div class="service-card__content">
                            <p class="service-card__description">{{ $service->description }}</p>
                            @if ($service->benefits !== [])
                                <ul class="service-card__tags">
                                    @foreach ($service->benefits as $benefit)
                                        <li>{{ $benefit }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <footer class="service-card__footer">
                            <a href="{{ route('services.show', $service->slug) }}" class="service-card__more">مسیر این کار را ببین</a>
                        </footer>
                    </article>
                @empty
                    <article class="service-card reveal">
                        <header class="service-card__header">
                            <span class="service-card__index" aria-hidden="true">01</span>
                            <div class="service-card__heading">
                                <h3>طراحی و توسعه وب</h3>
                            </div>
                            <a href="{{ route('home', ['intent' => 'request', 'service' => 'web']) }}#contact" class="service-card__action" aria-label="درخواست درباره طراحی و توسعه وب"><span class="service-card__action-label">گفتگو</span><span aria-hidden="true">↗</span></a>
                        </header>
                        <div class="service-card__content">
                            <p class="service-card__description">سایت‌هایی سریع، حرفه‌ای و قابل توسعه که فقط زیبا نیستند؛ برای کسب‌وکار کار می‌کنند.</p>
                            <ul class="service-card__tags"><li>تجربه کاربر</li><li>توسعه اختصاصی</li><li>جستجو و سرعت</li></ul>
                        </div>
                    </article>

                    <article class="service-card reveal">
                        <header class="service-card__header">
                            <span class="service-card__index" aria-hidden="true">02</span>
                            <div class="service-card__heading">
                                <h3>نرم‌افزار اختصاصی</h3>
                            </div>
                            <a href="{{ route('home', ['intent' => 'request', 'service' => 'software']) }}#contact" class="service-card__action" aria-label="درخواست درباره نرم‌افزار اختصاصی"><span class="service-card__action-label">گفتگو</span><span aria-hidden="true">↗</span></a>
                        </header>
                        <div class="service-card__content">
                            <p class="service-card__description">فرآیندهای پیچیده کسب‌وکارت را به یک سیستم ساده، قابل کنترل و قابل توسعه تبدیل می‌کنیم.</p>
                            <ul class="service-card__tags"><li>سیستم‌های اختصاصی</li><li>اتوماسیون</li><li>داشبورد و گردش کار</li></ul>
                        </div>
                    </article>

                    <article class="service-card reveal">
                        <header class="service-card__header">
                            <span class="service-card__index" aria-hidden="true">03</span>
                            <div class="service-card__heading">
                                <h3>راهکارهای رشد دیجیتال</h3>
                            </div>
                            <a href="{{ route('home', ['intent' => 'request', 'service' => 'growth']) }}#contact" class="service-card__action" aria-label="درخواست درباره راهکارهای رشد دیجیتال"><span class="service-card__action-label">گفتگو</span><span aria-hidden="true">↗</span></a>
                        </header>
                        <div class="service-card__content">
                            <p class="service-card__description">از ساختار سایت و محتوا تا تجربه کاربر و مسیر تبدیل؛ نقاط اصطکاک را پیدا می‌کنیم و بهبود می‌دهیم.</p>
                            <ul class="service-card__tags"><li>تبدیل</li><li>محتوا</li><li>جستجو و سنجش</li></ul>
                        </div>
                    </article>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section section--process" id="process">
        <div class="shell">
            <div class="section-intro reveal">
                <p class="eyebrow eyebrow--fa"><span></span> {{ $home['process']['eyebrow'] }}</p>
                <h2>{!! \App\Models\HomePage::emphasized($home['process']['title'], $home['process']['accent']) !!}</h2>
                <p>{{ $home['process']['lead'] }}</p>
            </div>

            <div class="process-line">
                @foreach ($home['process']['steps'] as $step)
                    <article class="process-step reveal">
                        <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--case" id="work">
        <div class="shell">
            <div class="section-intro reveal">
                <p class="eyebrow eyebrow--fa"><span></span> کیس‌استادی</p>
                <h2>از مسئله تا <em>سیستم قابل اتکا.</em></h2>
                <p>دو کار واقعی با همان استاندارد رشدینو — روشن، بدون عدد و ادعای توخالی.</p>
            </div>

            @forelse ($works as $work)
                <article class="case-study reveal{{ $work->images->isNotEmpty() ? '' : ' case-study--text' }}">
                    @if ($work->images->isNotEmpty())
                        <div class="case-study__visual">
                            <img class="case-study__photo" src="{{ $work->images->first()->media->url() }}" alt="{{ $work->images->first()->alt ?: $work->title }}" width="{{ $work->images->first()->media->width }}" height="{{ $work->images->first()->media->height }}">
                        </div>
                    @endif
                    <div class="case-study__content">
                        <p class="eyebrow eyebrow--fa"><span></span> {{ $work->title }}</p>
                        <h3>{{ $work->summary ?: $work->title }}</h3>
                        <div class="case-grid">
                            <div><small>مسئله</small><p>{{ $work->problem }}</p></div>
                            <div><small>رویکرد</small><p>{{ $work->solution }}</p></div>
                            @if (filled($work->result))
                                <div><small>نتیجه</small><p>{{ $work->result }}</p></div>
                            @endif
                            @if (filled($work->learning))
                                <div><small>یادگیری</small><p>{{ $work->learning }}</p></div>
                            @endif
                        </div>
                        @if ($work->technologies !== [])
                            <div class="case-tech">
                                @foreach ($work->technologies as $technology)
                                    <span>{{ $technology }}</span>
                                @endforeach
                            </div>
                        @endif
                        <a href="{{ route('works.show', $work->slug) }}" class="text-link">خواندن کیس‌استادی <span aria-hidden="true">←</span></a>
                    </div>
                </article>
            @empty
                <article class="case-study case-study--text reveal">
                    <div class="case-study__content">
                        <p class="eyebrow eyebrow--fa"><span></span> Personal System Faraja</p>
                        <h3>یک سیستم واقعی برای مدیریت فرآیندهای پرسنلی.</h3>
                        <div class="case-grid">
                            <div><small>مسئله</small><p>مدیریت اطلاعات پرسنل و فرآیندهای مرتبط نیازمند یک سیستم یکپارچه بود.</p></div>
                            <div><small>راهکار</small><p>یک سیستم با ساختار قابل توسعه و گردش کار مشخص.</p></div>
                        </div>
                        <div class="case-tech"><span>Laravel</span><span>PHP</span><span>MariaDB</span><span>Blade</span></div>
                        <a href="{{ route('home', ['intent' => 'request']) }}#contact" class="text-link">درباره یک پروژه مشابه صحبت کنیم <span aria-hidden="true">←</span></a>
                    </div>
                </article>
            @endforelse
        </div>
    </section>

    <section class="section section--trust" id="about">
        <div class="shell trust">
            <div class="trust__statement reveal">
                <p class="eyebrow eyebrow--fa"><span></span> {{ $home['principle']['eyebrow'] }}</p>
                <h2>{!! \App\Models\HomePage::emphasized($home['principle']['title'], $home['principle']['accent']) !!}</h2>
            </div>
            <div class="trust__copy reveal">
                <p>{{ $home['principle']['lead'] }}</p>
                <a href="{{ route('pages.show', 'about') }}" class="text-link">روش کار را بخوان <span aria-hidden="true">←</span></a>
            </div>
            <div class="principle-grid">
                @foreach ($home['principle']['items'] as $item)
                    <article class="principle reveal">
                        <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--faq" id="faq">
        <div class="shell faq-layout">
            <div class="section-intro reveal">
                <p class="eyebrow eyebrow--fa"><span></span> {{ $home['faq']['eyebrow'] }}</p>
                <h2>{!! \App\Models\HomePage::emphasized($home['faq']['title'], $home['faq']['accent']) !!}</h2>
            </div>
            <div class="faq-list">
                @foreach ($home['faq']['items'] as $item)
                    <details class="faq-item reveal"><summary>{{ $item['question'] }}</summary><p>{{ $item['answer'] }}</p></details>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--contact" id="contact">
        <div class="shell contact-cta reveal">
            <p class="eyebrow eyebrow--fa"><span></span> قدم بعدی</p>
            <h2>یک مسئله دیجیتال داری؟ <em>از همین‌جا شروع کنیم.</em></h2>
            <p>لازم نیست از قبل دقیقاً بدانی چه چیزی باید ساخته شود. مسئله را توضیح بده؛ مسیر مناسب را با هم پیدا می‌کنیم.</p>

            @include('partials.lead-form')
        </div>
    </section>
@endsection

@push('head')
    <script type="application/ld+json">
        {!! json_encode(array_filter([
            '@' . 'context' => 'https://schema.org',
            '@' . 'type' => 'Organization',
            'name' => $site['brand'],
            'alternateName' => $site['brand_latin'],
            'url' => url('/'),
            'description' => 'استودیو رشد دیجیتال برای طراحی و توسعه وب، نرم‌افزار اختصاصی و راهکارهای رشد دیجیتال.',
            'sameAs' => ($sameAs = array_values(array_filter([
                $site['telegram'] !== '' ? 'https://t.me/'.$site['telegram'] : null,
                $site['instagram'] !== '' ? $site['instagram'] : null,
                $site['linkedin'] !== '' ? $site['linkedin'] : null,
                $site['x'] !== '' ? $site['x'] : null,
            ]))) !== [] ? $sameAs : null,
            'telephone' => $site['phone'] !== '' ? $site['phone'] : null,
        ]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush
