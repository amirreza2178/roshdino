@extends('layouts.staff')

@section('title', 'متن صفحهٔ اصلی | رشدینو')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> HOME</p>
            <h1>متن صفحهٔ اصلی</h1>
            <p class="staff-lead">قهرمان، مسئله، فرآیند، اصول و سؤال‌ها از همین‌جا روی خانه می‌آیند. دکمه‌ها همچنان به درخواست، نمونه‌کار و درباره می‌روند. ردیف خالی ذخیره نمی‌شود.</p>
            @include('site.partials.nav')
            @if (session('saved'))
                <p class="lead-value-form__saved" role="status">{{ session('saved') }}</p>
            @endif

            <form class="staff-form staff-form--wide" method="POST" action="{{ route('site.home-copy.update') }}">
                @csrf
                @method('PUT')

                <fieldset class="staff-fieldset">
                    <legend>قهرمان</legend>
                    @include('site.home.fields', ['prefix' => 'hero', 'section' => $home['hero']])
                    <div class="staff-field">
                        <label for="hero-note">یادداشت</label>
                        <input id="hero-note" name="hero[note]" type="text" maxlength="200" value="{{ old('hero.note', $home['hero']['note']) }}">
                    </div>
                </fieldset>

                <fieldset class="staff-fieldset">
                    <legend>مسئله</legend>
                    @include('site.home.fields', ['prefix' => 'problem', 'section' => $home['problem']])
                    @foreach ($home['problem']['items'] as $item)
                        @include('site.home.line', ['prefix' => 'problem[items]['.$loop->index.']', 'id' => 'problem-'.$loop->index, 'title' => old('problem.items.'.$loop->index.'.title', $item['title']), 'body' => old('problem.items.'.$loop->index.'.body', $item['body']), 'titleLabel' => 'عنوان مسئله', 'bodyLabel' => 'توضیح'])
                    @endforeach
                </fieldset>

                <fieldset class="staff-fieldset">
                    <legend>فرآیند</legend>
                    @include('site.home.fields', ['prefix' => 'process', 'section' => $home['process']])
                    @foreach ($home['process']['steps'] as $item)
                        @include('site.home.line', ['prefix' => 'process[steps]['.$loop->index.']', 'id' => 'process-'.$loop->index, 'title' => old('process.steps.'.$loop->index.'.title', $item['title']), 'body' => old('process.steps.'.$loop->index.'.body', $item['body']), 'titleLabel' => 'عنوان قدم', 'bodyLabel' => 'توضیح'])
                    @endforeach
                </fieldset>

                <fieldset class="staff-fieldset">
                    <legend>اصول</legend>
                    @include('site.home.fields', ['prefix' => 'principle', 'section' => $home['principle']])
                    @foreach ($home['principle']['items'] as $item)
                        @include('site.home.line', ['prefix' => 'principle[items]['.$loop->index.']', 'id' => 'principle-'.$loop->index, 'title' => old('principle.items.'.$loop->index.'.title', $item['title']), 'body' => old('principle.items.'.$loop->index.'.body', $item['body']), 'titleLabel' => 'عنوان اصل', 'bodyLabel' => 'توضیح'])
                    @endforeach
                </fieldset>

                <fieldset class="staff-fieldset">
                    <legend>سؤال‌ها</legend>
                    @include('site.home.fields', ['prefix' => 'faq', 'section' => $home['faq'], 'lead' => false])
                    @foreach ($home['faq']['items'] as $item)
                        @include('site.home.line', ['prefix' => 'faq[items]['.$loop->index.']', 'id' => 'faq-'.$loop->index, 'titleName' => 'question', 'bodyName' => 'answer', 'title' => old('faq.items.'.$loop->index.'.question', $item['question']), 'body' => old('faq.items.'.$loop->index.'.answer', $item['answer']), 'titleLabel' => 'سؤال', 'bodyLabel' => 'جواب'])
                    @endforeach
                </fieldset>

                @if ($errors->any())
                    <p class="form-error" role="alert">{{ $errors->first() }}</p>
                @endif

                <button type="submit" class="button">ثبت متن</button>
            </form>
        </div>
    </section>
@endsection
