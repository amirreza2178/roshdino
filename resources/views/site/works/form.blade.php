@extends('layouts.staff')

@php
    $technologyText = old('technologies', implode("\n", $work->technologies ?? []));
@endphp

@section('title', ($work->exists ? 'ویرایش نمونه‌کار' : 'نمونه‌کار تازه').' | رشدینو')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> WORK</p>
            <h1>{{ $work->exists ? 'ویرایش نمونه‌کار' : 'نمونه‌کار تازه' }}</h1>
            <p class="staff-lead">چارچوب کیس‌استادی: مسئله → رویکرد → ساخت (تکنولوژی) → نتیجهٔ مستند → یادگیری. عدد و ادعای اثبات‌نشده ننویس.</p>
            @include('site.partials.nav')
            <form class="staff-form staff-form--wide" method="POST" enctype="multipart/form-data" action="{{ $work->exists ? route('site.works.update', $work) : route('site.works.store') }}">
                @csrf
                @if ($work->exists) @method('PUT') @endif
                <div class="staff-field">
                    <label for="title">پروژه</label>
                    <input id="title" name="title" type="text" maxlength="160" value="{{ old('title', $work->title) }}" required>
                    @error('title') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="summary">خلاصه</label>
                    <input id="summary" name="summary" type="text" maxlength="200" value="{{ old('summary', $work->summary) }}">
                    @error('summary') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="slug">نشانی</label>
                    <input id="slug" name="slug" type="text" maxlength="120" dir="ltr" value="{{ old('slug', $work->slug) }}" required>
                    @error('slug') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="category_id">دسته</label>
                    <select id="category_id" name="category_id">
                        <option value="">بدون دسته</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id', $work->category_id) === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="problem">مسئله</label>
                    <textarea id="problem" name="problem" rows="4" maxlength="5000" required>{{ old('problem', $work->problem) }}</textarea>
                    @error('problem') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="solution">رویکرد</label>
                    <textarea id="solution" name="solution" rows="4" maxlength="5000" required>{{ old('solution', $work->solution) }}</textarea>
                    <small>چرا این مسیر و چه چیزی ساخته شد؛ جزئیات ابزار در تکنولوژی‌ها.</small>
                    @error('solution') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="technologies">تکنولوژی‌ها</label>
                    <textarea id="technologies" name="technologies" rows="4" maxlength="2000">{{ $technologyText }}</textarea>
                    <small>هر تکنولوژی در یک خط.</small>
                    @error('technologies') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="result">نتیجهٔ مستند</label>
                    <textarea id="result" name="result" rows="4" maxlength="5000">{{ old('result', $work->result) }}</textarea>
                    @error('result') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="learning">یادگیری</label>
                    <textarea id="learning" name="learning" rows="3" maxlength="5000">{{ old('learning', $work->learning) }}</textarea>
                    <small>آنچه از پروژه برای کار بعدی برداشتی؛ بدون شعار.</small>
                    @error('learning') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="position">ترتیب</label>
                    <input id="position" name="position" type="number" min="0" max="9999" value="{{ old('position', $work->position ?? 0) }}" required>
                    @error('position') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="status">وضعیت انتشار</label>
                    <select id="status" name="status" required>
                        @foreach (\App\Enums\PublishStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(old('status', $work->status?->value) === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                @if ($work->exists && $work->images->isNotEmpty())
                    <fieldset class="staff-fieldset">
                        <legend>تصویرهای فعلی</legend>
                        @foreach ($work->images as $image)
                            <div class="staff-media">
                                <img src="{{ $image->media->url() }}" alt="" width="120" height="80">
                                <label for="alt-{{ $image->id }}">متن جایگزین</label>
                                <input id="alt-{{ $image->id }}" name="alts[{{ $image->id }}]" type="text" maxlength="160" value="{{ old('alts.'.$image->id, $image->alt) }}">
                                <label class="staff-check" for="remove-{{ $image->id }}">
                                    <input id="remove-{{ $image->id }}" name="remove_images[]" type="checkbox" value="{{ $image->id }}">
                                    <span>حذف از این نمونه‌کار</span>
                                </label>
                            </div>
                        @endforeach
                    </fieldset>
                @endif
                <div class="staff-field">
                    <label for="image">تصویر تازه</label>
                    <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                    @error('image') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="image_alt">متن جایگزین تصویر تازه</label>
                    <input id="image_alt" name="image_alt" type="text" maxlength="160" value="{{ old('image_alt') }}">
                    @error('image_alt') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                @include('site.partials.seo-fields')
                <button class="button button--lime" type="submit">ثبت نمونه‌کار</button>
            </form>
        </div>
    </section>
@endsection
