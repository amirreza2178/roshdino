@extends('layouts.staff')

@section('title', 'رسانه‌ها | رشدینو')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> MEDIA</p>
            <h1>رسانه‌ها</h1>
            <p class="staff-lead">تصویر دوباره ذخیره می‌شود، نام اصلی فایل در نشانی نمی‌آید، و ضلع بزرگ‌تر از ۱۶۰۰ پیکسل کوچک می‌شود.</p>
            @include('site.partials.nav')
            @if (session('saved'))
                <p class="lead-value-form__saved" role="status">{{ session('saved') }}</p>
            @endif
            @error('image') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            <form class="staff-form" method="POST" enctype="multipart/form-data" action="{{ route('site.media.store') }}">
                @csrf
                <div class="staff-field">
                    <label for="image">تصویر</label>
                    <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif" required>
                </div>
                <button class="button button--lime" type="submit">بارگذاری</button>
            </form>
            <div class="staff-list">
                @forelse ($media as $item)
                    <article class="staff-card staff-media">
                        <img src="{{ $item->url() }}" alt="" width="160" height="100">
                        <h2>{{ $item->original_name }}</h2>
                        <p dir="ltr">{{ $item->width }}×{{ $item->height }} · {{ $item->mime }}</p>
                        <form method="POST" action="{{ route('site.media.destroy', $item) }}">
                            @csrf
                            @method('DELETE')
                            <button class="button button--ghost" type="submit">حذف</button>
                        </form>
                    </article>
                @empty
                    <p class="staff-lead">هنوز تصویری بارگذاری نشده.</p>
                @endforelse
            </div>
            @include('site.partials.pager', ['items' => $media])
        </div>
    </section>
@endsection
