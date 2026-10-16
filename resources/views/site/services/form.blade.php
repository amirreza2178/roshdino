@extends('layouts.staff')

@php
    $benefitText = old('benefits', implode("\n", $service->benefits ?? []));
    $processText = old('process', collect($service->process ?? [])->map(function (array $step): string {
        $body = trim((string) ($step['body'] ?? ''));

        return $body !== '' ? $step['title'].' | '.$body : (string) $step['title'];
    })->implode("\n"));
@endphp

@section('title', ($service->exists ? 'ویرایش خدمت' : 'خدمت تازه').' | رشدینو')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> SERVICE</p>
            <h1>{{ $service->exists ? 'ویرایش خدمت' : 'خدمت تازه' }}</h1>
            @include('site.partials.nav')
            <form class="staff-form staff-form--wide" method="POST" action="{{ $service->exists ? route('site.services.update', $service) : route('site.services.store') }}">
                @csrf
                @if ($service->exists) @method('PUT') @endif
                <div class="staff-field">
                    <label for="title">عنوان</label>
                    <input id="title" name="title" type="text" maxlength="160" value="{{ old('title', $service->title) }}" required>
                    @error('title') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="slug">نشانی</label>
                    <input id="slug" name="slug" type="text" maxlength="120" dir="ltr" value="{{ old('slug', $service->slug) }}" required>
                    @error('slug') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="category_id">دسته</label>
                    <select id="category_id" name="category_id">
                        <option value="">بدون دسته</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id', $service->category_id) === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="description">توضیح</label>
                    <textarea id="description" name="description" rows="5" maxlength="5000" required>{{ old('description', $service->description) }}</textarea>
                    @error('description') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="benefits">مزایا</label>
                    <textarea id="benefits" name="benefits" rows="4" maxlength="4000">{{ $benefitText }}</textarea>
                    <small>هر مزیت در یک خط.</small>
                    @error('benefits') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="process">فرایند اجرا</label>
                    <textarea id="process" name="process" rows="5" maxlength="8000">{{ $processText }}</textarea>
                    <small>هر قدم در یک خط: عنوان | توضیح</small>
                    @error('process') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="cta_label">متن فراخوان</label>
                    <input id="cta_label" name="cta_label" type="text" maxlength="80" value="{{ old('cta_label', $service->cta_label) }}" required>
                    @error('cta_label') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="cta_url">پیوند فراخوان</label>
                    <input id="cta_url" name="cta_url" type="text" maxlength="255" dir="ltr" value="{{ old('cta_url', $service->cta_url) }}" placeholder="/?intent=request#contact">
                    @error('cta_url') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="interest">مسیر فرم سرنخ</label>
                    <select id="interest" name="interest">
                        <option value="">بدون پیش‌فرض</option>
                        @foreach (['web' => 'وب', 'software' => 'نرم‌افزار', 'growth' => 'رشد'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('interest', $service->interest?->value) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('interest') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="position">ترتیب</label>
                    <input id="position" name="position" type="number" min="0" max="9999" value="{{ old('position', $service->position ?? 0) }}" required>
                    @error('position') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="status">وضعیت انتشار</label>
                    <select id="status" name="status" required>
                        @foreach (\App\Enums\PublishStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(old('status', $service->status?->value) === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                @include('site.partials.seo-fields')
                <button class="button button--lime" type="submit">ثبت خدمت</button>
            </form>
        </div>
    </section>
@endsection
