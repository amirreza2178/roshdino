@extends('layouts.staff')

@section('title', 'صفحات | رشدینو')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> PAGES</p>
            <h1>صفحات</h1>
            @include('site.partials.nav')
            @if (session('saved'))
                <p class="lead-value-form__saved" role="status">{{ session('saved') }}</p>
            @endif
            <p class="staff-actions"><a class="button button--lime" href="{{ route('site.pages.create') }}">صفحهٔ تازه</a></p>
            <div class="staff-list">
                @forelse ($pages as $page)
                    <article class="staff-card">
                        <h2>{{ $page->title }}</h2>
                        <p dir="ltr">/{{ $page->slug }}</p>
                        <p>{{ $page->status->label() }}</p>
                        <div class="staff-actions">
                            <a class="button button--ghost" href="{{ route('site.pages.edit', $page) }}">ویرایش</a>
                            @if ($page->status === \App\Enums\PublishStatus::Published)
                                <a class="button button--ghost" href="{{ route('pages.show', $page->slug) }}">دیدن</a>
                            @endif
                            <form method="POST" action="{{ route('site.pages.destroy', $page) }}">
                                @csrf
                                @method('DELETE')
                                <button class="button button--ghost" type="submit">حذف</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="staff-lead">هنوز صفحه‌ای ثبت نشده.</p>
                @endforelse
            </div>
            @include('site.partials.pager', ['items' => $pages])
        </div>
    </section>
@endsection
