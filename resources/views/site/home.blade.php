@extends('layouts.staff')

@section('title', 'محتوا | رشدینو')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> SITE</p>
            <h1>محتوای سایت</h1>
            <p class="staff-lead">متن صفحات، خدمات و نمونه‌کارها از اینجا عوض می‌شود. نتیجهٔ نمونه‌کار فقط وقتی نوشته شود که مستند باشد.</p>
            @include('site.partials.nav')
        </div>
    </section>
@endsection
