@extends('layouts.staff')

@section('title', 'خدمات | رشدینو')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> SERVICES</p>
            <h1>خدمات</h1>
            @include('site.partials.nav')
            @if (session('saved'))
                <p class="lead-value-form__saved" role="status">{{ session('saved') }}</p>
            @endif
            <p class="staff-actions"><a class="button button--lime" href="{{ route('site.services.create') }}">خدمت تازه</a></p>
            <div class="staff-list">
                @forelse ($services as $service)
                    <article class="staff-card">
                        <h2>{{ $service->title }}</h2>
                        <p>{{ $service->status->label() }}@if ($service->category) · {{ $service->category->name }}@endif</p>
                        <div class="staff-actions">
                            <a class="button button--ghost" href="{{ route('site.services.edit', $service) }}">ویرایش</a>
                            @if ($service->status === \App\Enums\PublishStatus::Published)
                                <a class="button button--ghost" href="{{ route('services.show', $service->slug) }}">دیدن</a>
                            @endif
                            <form method="POST" action="{{ route('site.services.destroy', $service) }}">
                                @csrf
                                @method('DELETE')
                                <button class="button button--ghost" type="submit">حذف</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="staff-lead">هنوز خدمتی ثبت نشده. تا آن وقت، سه خدمت فعلی صفحهٔ اصلی سر جایشان می‌مانند.</p>
                @endforelse
            </div>
            @include('site.partials.pager', ['items' => $services])
        </div>
    </section>
@endsection
