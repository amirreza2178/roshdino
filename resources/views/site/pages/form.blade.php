@extends('layouts.staff')

@section('title', ($page->exists ? 'ویرایش صفحه' : 'صفحهٔ تازه').' | رشدینو')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> PAGE</p>
            <h1>{{ $page->exists ? 'ویرایش صفحه' : 'صفحهٔ تازه' }}</h1>
            @include('site.partials.nav')
            <form class="staff-form staff-form--wide" method="POST" action="{{ $page->exists ? route('site.pages.update', $page) : route('site.pages.store') }}">
                @csrf
                @if ($page->exists) @method('PUT') @endif
                <div class="staff-field">
                    <label for="title">عنوان</label>
                    <input id="title" name="title" type="text" maxlength="160" value="{{ old('title', $page->title) }}" required>
                    @error('title') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="slug">نشانی</label>
                    <input id="slug" name="slug" type="text" maxlength="120" dir="ltr" value="{{ old('slug', $page->slug) }}" required>
                    @error('slug') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="status">وضعیت انتشار</label>
                    <select id="status" name="status" required>
                        @foreach (\App\Enums\PublishStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(old('status', $page->status?->value) === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="body">متن</label>
                    <textarea id="body" name="body" rows="10" maxlength="20000" required>{{ old('body', $page->body) }}</textarea>
                    @error('body') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                @include('site.partials.seo-fields')
                <button class="button button--lime" type="submit">ثبت صفحه</button>
            </form>
        </div>
    </section>
@endsection
