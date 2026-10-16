@extends('layouts.staff')

@section('title', 'دسته‌ها | رشدینو')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> CATEGORIES</p>
            <h1>دسته‌ها</h1>
            @include('site.partials.nav')
            @if (session('saved'))
                <p class="lead-value-form__saved" role="status">{{ session('saved') }}</p>
            @endif
            @error('category') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            <p class="staff-actions"><a class="button button--lime" href="{{ route('site.categories.create') }}">دستهٔ تازه</a></p>
            <div class="staff-list">
                @forelse ($categories as $category)
                    <article class="staff-card">
                        <h2>{{ $category->name }}</h2>
                        <p>{{ $category->type->label() }} · <span dir="ltr">{{ $category->slug }}</span></p>
                        <div class="staff-actions">
                            <a class="button button--ghost" href="{{ route('site.categories.edit', $category) }}">ویرایش</a>
                            <form method="POST" action="{{ route('site.categories.destroy', $category) }}">
                                @csrf
                                @method('DELETE')
                                <button class="button button--ghost" type="submit">حذف</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="staff-lead">هنوز دسته‌ای ثبت نشده.</p>
                @endforelse
            </div>
            @include('site.partials.pager', ['items' => $categories])
        </div>
    </section>
@endsection
