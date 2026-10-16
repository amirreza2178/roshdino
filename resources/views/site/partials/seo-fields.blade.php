<fieldset class="staff-fieldset">
    <legend>سئو</legend>
    <div class="staff-field">
        <label for="meta_title">عنوان متا</label>
        <input id="meta_title" name="meta_title" type="text" maxlength="70" value="{{ old('meta_title', $seo->meta_title) }}">
        @error('meta_title') <p class="form-error" role="alert">{{ $message }}</p> @enderror
    </div>
    <div class="staff-field">
        <label for="meta_description">توضیح متا</label>
        <textarea id="meta_description" name="meta_description" maxlength="160" rows="3">{{ old('meta_description', $seo->meta_description) }}</textarea>
        @error('meta_description') <p class="form-error" role="alert">{{ $message }}</p> @enderror
    </div>
    <div class="staff-field">
        <label for="canonical">Canonical</label>
        <input id="canonical" name="canonical" type="url" inputmode="url" maxlength="255" dir="ltr" value="{{ old('canonical', $seo->canonical) }}" placeholder="https://">
        @error('canonical') <p class="form-error" role="alert">{{ $message }}</p> @enderror
    </div>
    <div class="staff-field">
        <label for="og_title">عنوان Open Graph</label>
        <input id="og_title" name="og_title" type="text" maxlength="70" value="{{ old('og_title', $seo->og_title) }}">
        @error('og_title') <p class="form-error" role="alert">{{ $message }}</p> @enderror
    </div>
    <div class="staff-field">
        <label for="og_description">توضیح Open Graph</label>
        <textarea id="og_description" name="og_description" maxlength="200" rows="3">{{ old('og_description', $seo->og_description) }}</textarea>
        @error('og_description') <p class="form-error" role="alert">{{ $message }}</p> @enderror
    </div>
    <div class="staff-field">
        <label for="og_image_id">تصویر Open Graph</label>
        <select id="og_image_id" name="og_image_id">
            <option value="">بدون تصویر</option>
            @foreach ($media as $item)
                <option value="{{ $item->id }}" @selected((string) old('og_image_id', $seo->og_image_id) === (string) $item->id)>{{ $item->original_name }}</option>
            @endforeach
        </select>
        @error('og_image_id') <p class="form-error" role="alert">{{ $message }}</p> @enderror
    </div>
    <div class="staff-field">
        <input type="hidden" name="indexable" value="0">
        <label class="staff-check" for="indexable">
            <input id="indexable" name="indexable" type="checkbox" value="1" @checked(old('indexable', $seo->indexable))>
            <span>این صفحه در موتورهای جست‌وجو ایندکس شود</span>
        </label>
        @error('indexable') <p class="form-error" role="alert">{{ $message }}</p> @enderror
    </div>
</fieldset>
