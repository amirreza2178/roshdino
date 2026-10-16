@extends('layouts.staff')

@section('title', 'ورود مسئول | رشدینو')
@section('meta_description', 'ورود مسئول رشدینو برای دیدن مسئله‌های ثبت‌شده.')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> ROSHDINO</p>
            <h1>ورود مسئول</h1>
            <p class="staff-lead">برای دیدن مسئله‌های ثبت‌شده وارد شو.</p>

            <form class="staff-form" method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="staff-field">
                    <label for="email">ایمیل</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        maxlength="255"
                        inputmode="email"
                        autocomplete="username"
                        autocapitalize="none"
                        spellcheck="false"
                        dir="ltr"
                        required
                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                    >
                    @error('email')
                        <p id="email-error" class="form-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="staff-field">
                    <label for="password">رمز عبور</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        maxlength="255"
                        autocomplete="current-password"
                        dir="ltr"
                        required
                        @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                    >
                    @error('password')
                        <p id="password-error" class="form-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="button button--lime">ورود</button>
            </form>
        </div>
    </section>
@endsection
