@php($selectedIntent = \App\Enums\LeadIntent::tryFrom((string) old('intent', $intent->value)) ?? $intent)
@php($selectedService = old('service', $prefillService?->value ?? \App\Enums\LeadServiceInterest::Unsure->value))
@php($onContactPage = ($intentPage ?? 'home') === 'contact')

<div class="intent-choices" role="group" aria-label="اقدام بعدی">
    @foreach (\App\Enums\LeadIntent::cases() as $choice)
        <a
            href="{{ $onContactPage ? route('pages.show', ['slug' => 'contact', 'intent' => $choice->value]) : route('home', ['intent' => $choice->value]).'#contact' }}"
            @class(['intent-choice', 'is-selected' => $selectedIntent === $choice])
            @if ($selectedIntent === $choice) aria-current="true" @endif
        >{{ $choice->label() }}</a>
    @endforeach
</div>
<p class="intent-note">
    @if ($selectedIntent === \App\Enums\LeadIntent::Contact)
        نام و راه تماس کافی است. برای ادامه با همین راه برمی‌گردیم.
    @elseif ($selectedIntent === \App\Enums\LeadIntent::Meeting)
        زمان جلسه را با ساعت تهران انتخاب کن.
    @else
        مسئله را کوتاه بنویس تا مسیر مناسب روشن شود.
    @endif
</p>

@if (session('lead_received'))
    <p class="form-success" role="status">ثبت شد. با همین راه ارتباطی برای ادامهٔ گفتگو برمی‌گردیم.</p>
@endif

<form class="lead-form" method="POST" action="{{ route('leads.store') }}">
    @csrf
    <input type="hidden" name="intent" value="{{ $selectedIntent->value }}">
    <div class="lead-form__trap" aria-hidden="true">
        <label for="company_website">وب‌سایت شرکت</label>
        <input id="company_website" name="company_website" type="text" tabindex="-1" autocomplete="off">
    </div>

    <label for="lead-name">
        <span>نام</span>
        <input id="lead-name" name="name" type="text" value="{{ old('name') }}" maxlength="120" autocomplete="name" required @if ($errors->has('name')) aria-invalid="true" aria-describedby="lead-name-error" @endif>
    </label>
    @error('name') <p id="lead-name-error" class="form-error" role="alert">{{ $message }}</p> @enderror

    <label for="lead-contact">
        <span>ایمیل یا موبایل</span>
        <input id="lead-contact" name="contact" type="text" value="{{ old('contact') }}" maxlength="190" inputmode="text" autocomplete="on" required @if ($errors->has('contact')) aria-invalid="true" aria-describedby="lead-contact-error" @endif>
    </label>
    @error('contact') <p id="lead-contact-error" class="form-error" role="alert">{{ $message }}</p> @enderror

    <label for="lead-service">
        <span>مسیر احتمالی</span>
        <select id="lead-service" name="service" @if ($errors->has('service')) aria-invalid="true" aria-describedby="lead-service-error" @endif>
            @foreach (\App\Enums\LeadServiceInterest::cases() as $serviceChoice)
                <option value="{{ $serviceChoice->value }}" @selected($selectedService === $serviceChoice->value)>{{ $serviceChoice->label() }}</option>
            @endforeach
        </select>
    </label>
    @error('service') <p id="lead-service-error" class="form-error" role="alert">{{ $message }}</p> @enderror

    @if ($selectedIntent === \App\Enums\LeadIntent::Request)
        <label for="lead-problem">
            <span>مسئله</span>
            <textarea id="lead-problem" name="problem" rows="4" maxlength="2000" required @if ($errors->has('problem')) aria-invalid="true" aria-describedby="lead-problem-error" @endif>{{ old('problem') }}</textarea>
        </label>
        @error('problem') <p id="lead-problem-error" class="form-error" role="alert">{{ $message }}</p> @enderror
    @endif

    @if ($selectedIntent === \App\Enums\LeadIntent::Meeting)
        <label for="lead-meets">
            <span>زمان جلسه</span>
            <input id="lead-meets" name="meets_at" type="datetime-local" value="{{ old('meets_at') }}" required @if ($errors->has('meets_at')) aria-invalid="true" aria-describedby="lead-meets-error" @endif>
        </label>
        @error('meets_at') <p id="lead-meets-error" class="form-error" role="alert">{{ $message }}</p> @enderror
    @endif

    <button class="button button--lime button--large" type="submit">
        @if ($selectedIntent === \App\Enums\LeadIntent::Meeting)
            رزرو جلسه
        @elseif ($selectedIntent === \App\Enums\LeadIntent::Contact)
            ثبت راه تماس
        @else
            شروع گفتگو
        @endif
        <span aria-hidden="true">←</span>
    </button>
</form>
<small>بعد از ثبت، برای ادامه با همین راه ارتباطی برمی‌گردیم.</small>

@php($telegramUser = $site['telegram'])
@php($teamPhone = $site['phone'])
@if ($telegramUser !== '' || $teamPhone !== '')
    <div class="direct-contact">
        @if ($telegramUser !== '')
            <a class="direct-contact__link" href="https://t.me/{{ $telegramUser }}" target="_blank" rel="noopener noreferrer">پیام در تلگرام</a>
        @endif
        @if ($teamPhone !== '')
            <a class="direct-contact__link" href="tel:{{ $teamPhone }}">{{ $teamPhone }}</a>
        @endif
    </div>
@endif
