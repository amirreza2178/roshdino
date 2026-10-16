<div class="staff-field">
    <label for="{{ $prefix }}-eyebrow">برچسب</label>
    <input id="{{ $prefix }}-eyebrow" name="{{ $prefix }}[eyebrow]" type="text" maxlength="80" value="{{ old($prefix.'.eyebrow', $section['eyebrow']) }}">
</div>
<div class="staff-field">
    <label for="{{ $prefix }}-title">عنوان</label>
    <input id="{{ $prefix }}-title" name="{{ $prefix }}[title]" type="text" maxlength="180" value="{{ old($prefix.'.title', $section['title']) }}" required>
</div>
<div class="staff-field">
    <label for="{{ $prefix }}-accent">عبارت تأکید</label>
    <input id="{{ $prefix }}-accent" name="{{ $prefix }}[accent]" type="text" maxlength="120" value="{{ old($prefix.'.accent', $section['accent']) }}">
</div>
@if ($lead ?? true)
    <div class="staff-field">
        <label for="{{ $prefix }}-lead">توضیح</label>
        <textarea id="{{ $prefix }}-lead" name="{{ $prefix }}[lead]" rows="3" maxlength="500" @if ($prefix === 'hero') required @endif>{{ old($prefix.'.lead', $section['lead'] ?? '') }}</textarea>
    </div>
@endif
