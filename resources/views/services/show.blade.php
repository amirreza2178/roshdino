@extends('layouts.app')

@section('title', $service->seo?->meta_title ?: $service->title.' | '.$site['brand'])
@section('meta_description', $service->seo?->meta_description ?: $service->description)
@section('og_title', $service->seo?->og_title ?: ($service->seo?->meta_title ?: $service->title))
@section('og_description', $service->seo?->og_description ?: ($service->seo?->meta_description ?: $service->description))
@section('canonical', $service->seo?->canonical ?: url()->current())
@section('robots', ($service->seo?->indexable ?? true) ? 'index, follow' : 'noindex, nofollow')
@if ($service->seo?->image)
    @section('og_image', $service->seo->image->url())
@endif

@section('content')
    @php
        $offer = match ($service->slug) {
            'web' => ['mark' => 'سایت', 'before' => 'از صفحهٔ اول تا', 'focus' => 'تماس یا خرید.', 'fit' => 'اگر سایت داری و کسی اقدام نمی‌کند،'],
            'software' => ['mark' => 'سیستم', 'before' => 'از کار پراکنده تا', 'focus' => 'یک سیستم روزانه.', 'fit' => 'اگر کار شرکت در چند جا پخش است،'],
            'growth' => ['mark' => 'رشد', 'before' => 'از توجه مخاطب تا', 'focus' => 'یک اقدام روشن.', 'fit' => 'اگر مخاطب هست و تماس نیست،'],
            default => ['mark' => $service->category?->name ?: 'خدمت', 'before' => 'این کار را', 'focus' => 'قدم‌به‌قدم جلو می‌بریم.', 'fit' => 'اگر این همان مسئلهٔ توست،'],
        };
        $webLanding = $service->slug === 'web';
        $landingIssues = match ($service->slug) {
            'software' => [
                'سفارش، مشتری، موجودی یا کار روزانه بین پیام، فایل و دفتر پخش است.',
                'برای فهمیدن یک وضعیت باید چند جا را بگردی و اگر یک نفر نباشد، کار می‌خوابد.',
                'نرم‌افزار بزرگ‌تر از مسئله ساخته می‌شود و کاربر روزانه را شلوغ‌تر می‌کند.',
            ],
            'growth' => [
                'بازدید یا دنبال‌کننده هست، ولی پیام اصلی برای مخاطب روشن نیست.',
                'صفحه دیده می‌شود، اما آدم نمی‌داند قدم بعدی تماس، درخواست یا خرید است.',
                'فرم و مسیر اقدام اصطکاک دارد و فرصت گفت‌وگو پیش از شروع از دست می‌رود.',
            ],
            default => [],
        };
        $landingQuestions = match ($service->slug) {
            'software' => [
                ['question' => 'هر فرایندی ارزش تبدیل شدن به نرم‌افزار دارد؟', 'answer' => 'نه. اول کار تکراری، تصمیم‌های روزانه و وضعیت‌هایی را پیدا می‌کنیم که بیشترین اصطکاک را دارند؛ بعد فقط همان بخش را سیستم می‌کنیم.'],
                ['question' => 'اگر هنوز فرایندمان روشن نیست چه؟', 'answer' => 'قبل از ساخت، مسیر واقعی کار را با آدم‌های درگیر مرور می‌کنیم. نرم‌افزار قرار نیست ابهام موجود را فقط سریع‌تر کند.'],
                ['question' => 'نرم‌افزار بعد از تحویل قابل گسترش است؟', 'answer' => 'اگر دامنه و مرزها از ابتدا روشن شده باشد، ساختار کار برای رشد بعدی آماده می‌ماند؛ بدون اینکه از روز اول همه‌چیز را بسازیم.'],
                ['question' => 'تیم برای استفاده از سیستم چه می‌کند؟', 'answer' => 'تحویل یعنی مسیر استفاده، نقش‌ها و کار روزانه روشن باشد؛ ادامهٔ آموزش یا پشتیبانی اگر لازم باشد، جدا و با مرز مشخص گفته می‌شود.'],
            ],
            'growth' => [
                ['question' => 'رشد دیجیتال یعنی فقط تولید محتوا؟', 'answer' => 'نه. پیام، تجربهٔ صفحه، جستجو، فرم و مسیر اقدام کنار هم بررسی می‌شوند؛ محتوا فقط بخشی از این مسیر است.'],
                ['question' => 'اگر هنوز مخاطب زیادی نداریم چه؟', 'answer' => 'اول باید مسئله و مخاطب درست روشن شود. رشد از رساندن آدم‌های نامرتبط به یک صفحهٔ مبهم شروع نمی‌شود.'],
                ['question' => 'نتیجه را با چه عددی می‌سنجید؟', 'answer' => 'عدد ساختگی وعده نمی‌دهیم. بعد از استفادهٔ واقعی، اقدام‌های مهم مثل شروع گفتگو و تکمیل تماس را با توجه به هدف کسب‌وکار بررسی می‌کنیم.'],
                ['question' => 'از کجا شروع می‌کنیم؟', 'answer' => 'از جایی که مخاطب متوقف می‌شود: پیام، صفحه یا اقدام. سپس یک اصلاح روشن و قابل بررسی را جلو می‌بریم، نه فهرستی بی‌پایان از کارها.'],
            ],
            default => [],
        };
        $hasLanding = in_array($service->slug, ['software', 'growth'], true);
        $softwareLanding = $service->slug === 'software';
        $webIssues = [
            'کار در پیام و شبکه پخش است و یک صفحهٔ مشخص برای معرفی و اقدام نیست.',
            'سایت هست، ولی بازدیدکننده نمی‌داند تماس بگیرد، خرید کند، یا فقط نگاه کند.',
            'اگر فروش جدی است، ویترین بدون سفارش و موجودی مسیر را نصفه می‌گذارد.',
        ];
        $webQuestions = [
            [
                'question' => 'سایت معرفی کافی است یا فروشگاه هم می‌سازید؟',
                'answer' => 'اگر کار با معرفی و تماس جمع می‌شود، همان را می‌سازیم. اگر کالا، سفارش و موجودی وسط کار است، فروشگاه را در همان مسیر می‌چینیم.',
            ],
            [
                'question' => 'اگر نیاز کسب‌وکار از یک ویترین بزرگ‌تر باشد چه؟',
                'answer' => 'دامنه را قبل از ساخت با اندازهٔ واقعی کار روشن می‌کنیم. کالا، قانون موجودی یا مرحلهٔ تازه‌ای در خرید بعداً به همین ساخت اضافه می‌شود، نه اینکه پروژه از اول شروع شود.',
            ],
            [
                'question' => 'از قالب آماده استفاده می‌کنید؟',
                'answer' => 'اگر قالب مسیر کسب‌وکار را خراب کند، مخصوص همان کار می‌سازیم. اگر یک قالب روشن کافی باشد، همان را می‌گوییم و چیز اضافه نمی‌سازیم.',
            ],
            [
                'question' => 'بعد از تحویل، سایت دست چه کسی است؟',
                'answer' => 'تحویل یعنی بتوانی با سایت کار کنی. ادامه، اگر لازم باشد، جدا و با مرز روشن گفته می‌شود.',
            ],
        ];
    @endphp
    <section class="offer-hero section">
        <div class="shell offer-hero__layout">
            <div class="offer-hero__copy">
                <p class="eyebrow eyebrow--fa"><span></span> {{ $offer['mark'] }}</p>
                <h1>{{ $service->title }}</h1>
                <p class="offer-hero__lead">{{ $service->description }}</p>
                <a class="button button--lime" href="{{ $service->ctaHref() }}">{{ $service->cta_label }} <span aria-hidden="true">←</span></a>
            </div>
            @if ($service->benefits !== [])
                <ul class="offer-points" aria-label="تحویل این کار">
                    @foreach ($service->benefits as $benefit)
                        <li>
                            <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <strong>{{ $benefit }}</strong>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    @if ($webLanding || $hasLanding)
        <section class="section" aria-labelledby="service-problem">
            <div class="shell">
                <div class="section-intro">
                    <p class="eyebrow eyebrow--fa"><span></span> مسئله</p>
                    <h2 id="service-problem">
                        @if ($webLanding)
                            سایت یا هنوز کار را یکجا نمی‌گوید، <em>یا هست و کسی اقدام نمی‌کند.</em>
                        @elseif ($service->slug === 'software')
                            کار روزانه بین چند جا پخش است، <em>اما سیستم نباید خودش مسئلهٔ تازه بسازد.</em>
                        @else
                            مخاطب هست، <em>اما مسیر روشن برای اقدام نیست.</em>
                        @endif
                    </h2>
                </div>
                <ul class="offer-issues">
                    @foreach ($webLanding ? $webIssues : $landingIssues as $issue)
                        <li>{{ $issue }}</li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @if ($service->process !== [])
        <section class="section section--process" aria-labelledby="service-process">
            <div class="shell">
                <div class="section-intro">
                    <p class="eyebrow eyebrow--fa"><span></span> مسیر کار</p>
                    <h2 id="service-process">{{ $offer['before'] }} <em>{{ $offer['focus'] }}</em></h2>
                </div>
                <ol class="offer-steps">
                    @foreach ($service->process as $step)
                        <li>
                            <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <strong>{{ $step['title'] ?? '' }}</strong>
                                @if (filled($step['body'] ?? null))
                                    <p>{{ $step['body'] }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    @if (($webLanding || $softwareLanding) && $proof)
        <section class="section" aria-labelledby="service-proof">
            <div class="shell">
                <div class="section-intro">
                    <p class="eyebrow eyebrow--fa"><span></span> نمونه‌کار</p>
                    <h2 id="service-proof">
                        @if ($webLanding)
                            یک فروشگاه، برای کاری که <em>ویترین ساده جوابش را نمی‌دهد.</em>
                        @else
                            یک سیستم واقعی، برای کاری که <em>فایل و پیام جوابش را نمی‌دهد.</em>
                        @endif
                    </h2>
                </div>
                <article class="offer-proof">
                    <h3>{{ $proof->title }}</h3>
                    @if (filled($proof->summary))
                        <p>{{ $proof->summary }}</p>
                    @endif
                    <a href="{{ route('works.show', $proof->slug) }}">
                        {{ $softwareLanding ? 'دیدن این سیستم' : 'دیدن این فروشگاه' }}
                        <span aria-hidden="true">←</span>
                    </a>
                </article>
            </div>
        </section>
    @endif

    @if ($webLanding || $hasLanding)
        <section class="section section--faq" id="service-faq" aria-labelledby="service-questions">
            <div class="shell faq-layout">
                <div class="section-intro">
                    <p class="eyebrow eyebrow--fa"><span></span> سؤال</p>
                    <h2 id="service-questions">
                        @if ($webLanding)
                            قبل از ساخت سایت، <em>این‌ها را روشن کنیم.</em>
                        @elseif ($service->slug === 'software')
                            قبل از ساخت سیستم، <em>این‌ها را روشن کنیم.</em>
                        @else
                            قبل از شروع رشد، <em>این‌ها را روشن کنیم.</em>
                        @endif
                </div>
                <div class="faq-list">
                    @foreach ($webLanding ? $webQuestions : $landingQuestions as $item)
                        <details class="faq-item">
                            <summary>{{ $item['question'] }}</summary>
                            <p>{{ $item['answer'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="offer-close section">
        <div class="shell offer-close__inner">
            <p class="eyebrow eyebrow--fa"><span></span> قدم بعدی</p>
            <h2>{{ $offer['fit'] }} <em>بیا همان را بگوییم.</em></h2>
            <a class="button button--lime" href="{{ $service->ctaHref() }}">{{ $service->cta_label }} <span aria-hidden="true">←</span></a>
        </div>
    </section>
@endsection

@push('head')
    <script type="application/ld+json">
        {!! json_encode(array_filter([
            '@' . 'context' => 'https://schema.org',
            '@' . 'type' => 'Service',
            'name' => $service->title,
            'description' => $service->description,
            'url' => url()->current(),
            'provider' => [
                '@' . 'type' => 'Organization',
                'name' => $site['brand'],
                'url' => url('/'),
            ],
        ]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @if ($webLanding || $hasLanding)
        <script type="application/ld+json">
            {!! json_encode([
                '@' . 'context' => 'https://schema.org',
                '@' . 'type' => 'FAQPage',
                'mainEntity' => array_map(fn (array $item): array => [
                    '@' . 'type' => 'Question',
                    'name' => $item['question'],
                    'acceptedAnswer' => [
                        '@' . 'type' => 'Answer',
                        'text' => $item['answer'],
                    ],
                ], $webLanding ? $webQuestions : $landingQuestions),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endif
@endpush
