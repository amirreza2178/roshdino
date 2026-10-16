<div class="staff-field">
    <label for="{{ $id }}-title">{{ $titleLabel }}</label>
    <input id="{{ $id }}-title" name="{{ $prefix }}[{{ $titleName ?? 'title' }}]" type="text" maxlength="180" value="{{ $title }}">
</div>
<div class="staff-field">
    <label for="{{ $id }}-body">{{ $bodyLabel }}</label>
    <textarea id="{{ $id }}-body" name="{{ $prefix }}[{{ $bodyName ?? 'body' }}]" rows="2" maxlength="500">{{ $body }}</textarea>
</div>
