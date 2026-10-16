@extends('layouts.app')

@section('title', $work->seo?->meta_title ?: $work->title.' | '.$site['brand'])
@section('meta_description', $work->seo?->meta_description ?: $work->problem)
@section('og_title', $work->seo?->og_title ?: ($work->seo?->meta_title ?: $work->title))
@section('og_description', $work->seo?->og_description ?: ($work->seo?->meta_description ?: $work->problem))
@section('canonical', $work->seo?->canonical ?: url()->current())
@section('robots', ($work->seo?->indexable ?? true) ? 'index, follow' : 'noindex, nofollow')
@if ($work->seo?->image)
    @section('og_image', $work->seo->image->url())
@endif

@section('content')
    <section class="offer-hero section">
        <div class="shell offer-hero__layout">
            <div class="offer-hero__copy">
                <p class="eyebrow eyebrow--fa"><span></span> {{ $work->category?->name ?: 'کیس‌استادی' }}</p>
                <h1>{{ $work->title }}</h1>
                @if (filled($work->summary))
                    <p class="offer-hero__lead">{{ $work->summary }}</p>
                @endif
                <a class="button button--lime" href="{{ route('pages.show', ['slug' => 'contact', 'intent' => 'request']) }}">برای کار مشابه حرف بزنیم <span aria-hidden="true">←</span></a>
            </div>
            <ul class="offer-points" aria-label="چارچوب کیس‌استادی">
                <li>
                    <span>01</span>
                    <div>
                        <strong>مسئله</strong>
                        <p>{{ $work->problem }}</p>
                    </div>
                </li>
                <li>
                    <span>02</span>
                    <div>
                        <strong>رویکرد</strong>
                        <p>{{ $work->solution }}</p>
                    </div>
                </li>
                @if (filled($work->result))
                    <li>
                        <span>03</span>
                        <div>
                            <strong>نتیجه</strong>
                            <p>{{ $work->result }}</p>
                        </div>
                    </li>
                @endif
                @if (filled($work->learning))
                    <li>
                        <span>{{ filled($work->result) ? '04' : '03' }}</span>
                        <div>
                            <strong>یادگیری</strong>
                            <p>{{ $work->learning }}</p>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
        @if ($work->images->isNotEmpty())
            <div class="shell offer-gallery">
                @foreach ($work->images as $image)
                    <img src="{{ $image->media->url() }}" alt="{{ $image->alt ?: $work->title }}" width="{{ $image->media->width }}" height="{{ $image->media->height }}" loading="lazy">
                @endforeach
            </div>
        @endif
    </section>

    @if ($work->technologies !== [])
        <section class="section section--process" aria-labelledby="work-build">
            <div class="shell">
                <div class="section-intro">
                    <p class="eyebrow eyebrow--fa"><span></span> ساخت</p>
                    <h2 id="work-build">با ابزار واقعی <em>ساخته شد.</em></h2>
                </div>
                <ul class="offer-tools">
                    @foreach ($work->technologies as $technology)
                        <li>{{ $technology }}</li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <section class="offer-close section">
        <div class="shell offer-close__inner">
            <p class="eyebrow eyebrow--fa"><span></span> قدم بعدی</p>
            <h2>اگر مسیر کارت هم پراکنده است، <em>همان را روشن می‌کنیم.</em></h2>
            <a class="button button--lime" href="{{ route('pages.show', ['slug' => 'contact', 'intent' => 'request']) }}">برای کار مشابه حرف بزنیم <span aria-hidden="true">←</span></a>
        </div>
    </section>
@endsection

@push('head')
    <script type="application/ld+json">
        {!! json_encode(array_filter([
            '@' . 'context' => 'https://schema.org',
            '@' . 'type' => 'CreativeWork',
            'name' => $work->title,
            'description' => $work->summary ?: $work->problem,
            'url' => url()->current(),
            'creator' => [
                '@' . 'type' => 'Organization',
                'name' => $site['brand'],
                'url' => url('/'),
            ],
        ]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush
