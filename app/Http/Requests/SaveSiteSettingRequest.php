<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $telegram = ltrim(trim((string) $this->input('contact_telegram')), '@');

        $this->merge([
            'contact_telegram' => $telegram,
            'seo_indexable' => $this->boolean('seo_indexable') ? '1' : '0',
            'seo_og_image_id' => $this->filled('seo_og_image_id') ? (string) $this->integer('seo_og_image_id') : null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'brand_name' => ['nullable', 'string', 'max:80'],
            'brand_latin' => ['nullable', 'string', 'max:80'],
            'contact_email' => ['nullable', 'email', 'max:190'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'contact_telegram' => ['nullable', 'string', 'max:64', 'regex:/^[A-Za-z0-9_]*$/'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'social_x' => ['nullable', 'url', 'max:255'],
            'footer_text' => ['nullable', 'string', 'max:500'],
            'seo_title' => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:160'],
            'seo_canonical' => ['nullable', 'url', 'max:255'],
            'seo_og_title' => ['nullable', 'string', 'max:70'],
            'seo_og_description' => ['nullable', 'string', 'max:200'],
            'seo_og_image_id' => ['nullable', 'integer', 'exists:media,id'],
            'seo_indexable' => ['required', 'in:0,1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contact_email.email' => 'ایمیل تماس درست نیست.',
            'contact_telegram.regex' => 'نام کاربری تلگرام فقط حرف انگلیسی، عدد و _ باشد.',
            'social_instagram.url' => 'نشانی اینستاگرام باید کامل باشد.',
            'social_linkedin.url' => 'نشانی لینکدین باید کامل باشد.',
            'social_x.url' => 'نشانی شبکهٔ اجتماعی باید کامل باشد.',
            'seo_title.max' => 'عنوان متا حداکثر ۷۰ نویسه است.',
            'seo_description.max' => 'توضیح متا حداکثر ۱۶۰ نویسه است.',
            'seo_canonical.url' => 'Canonical باید یک نشانی کامل باشد.',
            'seo_og_image_id.exists' => 'تصویر Open Graph پیدا نشد.',
        ];
    }
}
