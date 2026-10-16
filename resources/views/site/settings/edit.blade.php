@extends('layouts.staff')

@section('title', 'تنظیمات سایت | رشدینو')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> SETTINGS</p>
            <h1>تنظیمات سایت</h1>
            <p class="staff-lead">نام برند، تماس و شبکه‌ها روی سایت عمومی دیده می‌شوند. خبر سرنخ همچنان به ایمیل env می‌رود. اگر فیلد تماس خالی بماند، مقدار env استفاده می‌شود.</p>
            @include('site.partials.nav')
            @if (session('saved'))
                <p class="lead-value-form__saved" role="status">{{ session('saved') }}</p>
            @endif
            <form class="staff-form staff-form--wide" method="POST" action="{{ route('site.settings.update') }}">
                @csrf
                @method('PUT')
                <div class="staff-field">
                    <label for="brand_name">نام برند</label>
                    <input id="brand_name" name="brand_name" type="text" maxlength="80" value="{{ old('brand_name', $settings['brand_name'] ?? '') }}">
                    @error('brand_name') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="brand_latin">نام انگلیسی</label>
                    <input id="brand_latin" name="brand_latin" type="text" maxlength="80" dir="ltr" value="{{ old('brand_latin', $settings['brand_latin'] ?? '') }}">
                    @error('brand_latin') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="contact_email">ایمیل تماس</label>
                    <input id="contact_email" name="contact_email" type="email" maxlength="190" dir="ltr" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}">
                    @error('contact_email') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="contact_phone">شماره تماس</label>
                    <input id="contact_phone" name="contact_phone" type="text" maxlength="30" dir="ltr" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
                    @error('contact_phone') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="contact_telegram">نام کاربری تلگرام</label>
                    <input id="contact_telegram" name="contact_telegram" type="text" maxlength="64" dir="ltr" value="{{ old('contact_telegram', $settings['contact_telegram'] ?? '') }}">
                    @error('contact_telegram') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="social_instagram">اینستاگرام</label>
                    <input id="social_instagram" name="social_instagram" type="url" maxlength="255" dir="ltr" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}">
                    @error('social_instagram') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="social_linkedin">لینکدین</label>
                    <input id="social_linkedin" name="social_linkedin" type="url" maxlength="255" dir="ltr" value="{{ old('social_linkedin', $settings['social_linkedin'] ?? '') }}">
                    @error('social_linkedin') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="social_x">X</label>
                    <input id="social_x" name="social_x" type="url" maxlength="255" dir="ltr" value="{{ old('social_x', $settings['social_x'] ?? '') }}">
                    @error('social_x') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="footer_text">متن پاصفحه</label>
                    <textarea id="footer_text" name="footer_text" rows="3" maxlength="500">{{ old('footer_text', $settings['footer_text'] ?? '') }}</textarea>
                    @error('footer_text') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <fieldset class="staff-fieldset">
                    <legend>سئوی صفحهٔ اصلی</legend>
                    <div class="staff-field">
                        <label for="seo_title">عنوان متا</label>
                        <input id="seo_title" name="seo_title" type="text" maxlength="70" value="{{ old('seo_title', $settings['seo_title'] ?? '') }}">
                        @error('seo_title') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                    </div>
                    <div class="staff-field">
                        <label for="seo_description">توضیح متا</label>
                        <textarea id="seo_description" name="seo_description" rows="3" maxlength="160">{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
                        @error('seo_description') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                    </div>
                    <div class="staff-field">
                        <label for="seo_canonical">Canonical</label>
                        <input id="seo_canonical" name="seo_canonical" type="url" maxlength="255" dir="ltr" value="{{ old('seo_canonical', $settings['seo_canonical'] ?? '') }}">
                        @error('seo_canonical') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                    </div>
                    <div class="staff-field">
                        <label for="seo_og_title">عنوان Open Graph</label>
                        <input id="seo_og_title" name="seo_og_title" type="text" maxlength="70" value="{{ old('seo_og_title', $settings['seo_og_title'] ?? '') }}">
                        @error('seo_og_title') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                    </div>
                    <div class="staff-field">
                        <label for="seo_og_description">توضیح Open Graph</label>
                        <textarea id="seo_og_description" name="seo_og_description" rows="3" maxlength="200">{{ old('seo_og_description', $settings['seo_og_description'] ?? '') }}</textarea>
                        @error('seo_og_description') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                    </div>
                    <div class="staff-field">
                        <label for="seo_og_image_id">تصویر Open Graph</label>
                        <select id="seo_og_image_id" name="seo_og_image_id">
                            <option value="">بدون تصویر</option>
                            @foreach ($media as $item)
                                <option value="{{ $item->id }}" @selected((string) old('seo_og_image_id', $settings['seo_og_image_id'] ?? '') === (string) $item->id)>{{ $item->original_name }}</option>
                            @endforeach
                        </select>
                        @error('seo_og_image_id') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                    </div>
                    <div class="staff-field">
                        <input type="hidden" name="seo_indexable" value="0">
                        <label class="staff-check" for="seo_indexable">
                            <input id="seo_indexable" name="seo_indexable" type="checkbox" value="1" @checked((string) old('seo_indexable', $settings['seo_indexable'] ?? '1') !== '0')>
                            <span>صفحهٔ اصلی ایندکس شود</span>
                        </label>
                    </div>
                </fieldset>
                <button class="button button--lime" type="submit">ثبت تنظیمات</button>
            </form>
        </div>
    </section>
@endsection
