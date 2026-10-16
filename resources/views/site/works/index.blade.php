@extends('layouts.staff')

@section('title', 'نمونه‌کارها | رشدینو')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> WORK</p>
            <h1>نمونه‌کارها</h1>
            @include('site.partials.nav')
            @if (session('saved'))
                <p class="lead-value-form__saved" role="status">{{ session('saved') }}</p>
            @endif
            <p class="staff-actions"><a class="button button--lime" href="{{ route('site.works.create') }}">نمونه‌کار تازه</a></p>
            <div class="staff-list">
                @forelse ($works as $work)
                    <article class="staff-card">
                        <h2>{{ $work->title }}</h2>
                        <p>{{ $work->status->label() }}@if ($work->category) · {{ $work->category->name }}@endif</p>
                        <div class="staff-actions">
                            <a class="button button--ghost" href="{{ route('site.works.edit', $work) }}">ویرایش</a>
                            @if ($work->status === \App\Enums\PublishStatus::Published)
                                <a class="button button--ghost" href="{{ route('works.show', $work->slug) }}">دیدن</a>
                            @endif
                            <form method="POST" action="{{ route('site.works.destroy', $work) }}">
                                @csrf
                                @method('DELETE')
                                <button class="button button--ghost" type="submit">حذف</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="staff-lead">هنوز نمونه‌کاری ثبت نشده. تا آن وقت، Personal System Faraja روی صفحهٔ اصلی می‌ماند.</p>
                @endforelse
            </div>
            @include('site.partials.pager', ['items' => $works])
        </div>
    </section>
@endsection
