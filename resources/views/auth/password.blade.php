@extends('layouts.staff')

@section('title', 'رمز عبور | رشدینو')
@section('meta_description', 'عوض کردن رمز ورود مسئول رشدینو.')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> ROSHDINO</p>
            <h1>رمز عبور</h1>
            <p class="staff-lead">رمز تازه حداقل ۱۲ نویسه است. رمز داخل env با این صفحه عوض نمی‌شود.</p>

            <form class="staff-form" method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="staff-field">
                    <label for="current_password">رمز فعلی</label>
                    <input id="current_password" name="current_password" type="password" maxlength="255" autocomplete="current-password" dir="ltr" required @error('current_password') aria-invalid="true" aria-describedby="current-password-error" @enderror>
                    @error('current_password')
                        <p id="current-password-error" class="form-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="staff-field">
                    <label for="password">رمز تازه</label>
                    <input id="password" name="password" type="password" minlength="12" maxlength="255" autocomplete="new-password" dir="ltr" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    @error('password')
                        <p id="password-error" class="form-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="staff-field">
                    <label for="password_confirmation">تکرار رمز تازه</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" minlength="12" maxlength="255" autocomplete="new-password" dir="ltr" required>
                </div>

                <button type="submit" class="button button--lime">ثبت رمز</button>
                @if (session('password_saved'))
                    <p class="lead-value-form__saved" role="status">رمز عبور عوض شد.</p>
                @endif
            </form>
        </div>
    </section>
@endsection
