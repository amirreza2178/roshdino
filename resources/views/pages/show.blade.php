@extends('layouts.app')

@section('title', $page->seo?->meta_title ?: $page->title.' | '.$site['brand'])
@section('meta_description', $page->seo?->meta_description ?: $page->title)
@section('og_title', $page->seo?->og_title ?: ($page->seo?->meta_title ?: $page->title))
@section('og_description', $page->seo?->og_description ?: ($page->seo?->meta_description ?: $page->title))
@section('canonical', $page->seo?->canonical ?: url()->current())
@section('robots', ($page->seo?->indexable ?? true) ? 'index, follow' : 'noindex, nofollow')
@if ($page->seo?->image)
    @section('og_image', $page->seo->image->url())
@endif

@section('content')
    @if ($page->slug === 'about')
        <section class="offer-hero section">
            <div class="shell offer-hero__layout">
                <div class="offer-hero__copy">
                    <p class="eyebrow eyebrow--fa"><span></span> درباره</p>
                    <h1>{{ $page->title }}</h1>
                    <p class="offer-hero__lead">{!! nl2br(e($page->body)) !!}</p>
                    <a class="button button--lime" href="{{ route('pages.show', ['slug' => 'contact', 'intent' => 'request']) }}">حرف بزنیم <span aria-hidden="true">←</span></a>
                </div>
                @if ($services->isNotEmpty())
                    <ul class="offer-points" aria-label="سه مسیر">
                        @foreach ($services as $service)
                            <li>
                                <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <div>
                                    <strong><a href="{{ route('services.show', $service->slug) }}">{{ $service->title }}</a></strong>
                                    <p>{{ $service->description }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>
        <section class="section" aria-labelledby="about-standards">
            <div class="shell">
                <div class="section-intro">
                    <p class="eyebrow eyebrow--fa"><span></span> استاندارد</p>
                    <h2 id="about-standards">کیفیت ساخت <em>جزء خود کار است.</em></h2>
                </div>
                <ul class="offer-points">
                    <li>
                        <span>01</span>
                        <div>
                            <strong>موبایل جزء کار است</strong>
                            <p>صفحه باید روی گوشی روشن و قابل استفاده باشد، نه فقط روی مانیتور.</p>
                        </div>
                    </li>
                    <li>
                        <span>02</span>
                        <div>
                            <strong>برای جستجو قابل فهم است</strong>
                            <p>عنوان و توضیح صفحه معنی کار را می‌گویند، نه یک شعار.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
        <section class="offer-close section">
            <div class="shell offer-close__inner">
                <p class="eyebrow eyebrow--fa"><span></span> قدم بعدی</p>
                <h2>اگر گیر کار را دیدی، <em>بیا همان را بگوییم.</em></h2>
                <a class="button button--lime" href="{{ route('pages.show', ['slug' => 'contact', 'intent' => 'request']) }}">حرف بزنیم <span aria-hidden="true">←</span></a>
            </div>
        </section>
    @elseif ($page->slug === 'contact')
        <section class="offer-hero section">
            <div class="shell offer-hero__layout">
                <div class="offer-hero__copy">
                    <p class="eyebrow eyebrow--fa"><span></span> تماس</p>
                    <h1>{{ $page->title }}</h1>
                    <p class="offer-hero__lead">{!! nl2br(e($page->body)) !!}</p>
                </div>
                <ul class="offer-points" aria-label="سه راه شروع">
                    <li><span>01</span><strong>مسئله‌ات را بنویس</strong></li>
                    <li><span>02</span><strong>فقط راه تماس را بگذار</strong></li>
                    <li><span>03</span><strong>زمان جلسه را با ساعت تهران انتخاب کن</strong></li>
                </ul>
            </div>
        </section>
        <section class="section offer-form" id="contact">
            <div class="shell offer-close__inner">
                <p class="eyebrow eyebrow--fa"><span></span> قدم بعدی</p>
                <h2>لازم نیست همه‌چیز روشن باشد. <em>از همین‌جا بنویس.</em></h2>
                @include('partials.lead-form', ['intentPage' => 'contact'])
            </div>
        </section>
    @else
        <section class="section section--ivory">
            <div class="shell content-page">
                <h1>{{ $page->title }}</h1>
                <p class="content-page__body">{!! nl2br(e($page->body)) !!}</p>
            </div>
        </section>
    @endif
@endsection

@if ($page->slug === 'about' || $page->slug === 'contact')
    @push('head')
        <script type="application/ld+json">
            {!! json_encode([
                '@' . 'context' => 'https://schema.org',
                '@' . 'type' => $page->slug === 'about' ? 'AboutPage' : 'ContactPage',
                'name' => $page->title,
                'description' => $page->seo?->meta_description ?: $page->title,
                'url' => url()->current(),
                'isPartOf' => [
                    '@' . 'type' => 'WebSite',
                    'name' => $site['brand'],
                    'url' => url('/'),
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush
@endif
