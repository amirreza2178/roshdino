@extends('layouts.staff')

@section('title', ($category->exists ? 'ویرایش دسته' : 'دستهٔ تازه').' | رشدینو')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> CATEGORY</p>
            <h1>{{ $category->exists ? 'ویرایش دسته' : 'دستهٔ تازه' }}</h1>
            @include('site.partials.nav')
            <form class="staff-form" method="POST" action="{{ $category->exists ? route('site.categories.update', $category) : route('site.categories.store') }}">
                @csrf
                @if ($category->exists) @method('PUT') @endif
                <div class="staff-field">
                    <label for="name">نام</label>
                    <input id="name" name="name" type="text" maxlength="120" value="{{ old('name', $category->name) }}" required>
                    @error('name') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="slug">نشانی</label>
                    <input id="slug" name="slug" type="text" maxlength="120" dir="ltr" value="{{ old('slug', $category->slug) }}" required>
                    @error('slug') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="staff-field">
                    <label for="type">نوع</label>
                    <select id="type" name="type" required>
                        @foreach (\App\Enums\CategoryType::cases() as $type)
                            <option value="{{ $type->value }}" @selected(old('type', $category->type?->value) === $type->value)>{{ $type->label() }}</option>
                        @endforeach
                    </select>
                    @error('type') <p class="form-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <button class="button button--lime" type="submit">ثبت دسته</button>
            </form>
        </div>
    </section>
@endsection
