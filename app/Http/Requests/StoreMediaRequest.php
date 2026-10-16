<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.required' => 'یک تصویر انتخاب کن.',
            'image.mimes' => 'فقط تصویر JPG، PNG، WEBP یا GIF پذیرفته می‌شود.',
            'image.max' => 'حجم تصویر حداکثر ۵ مگابایت است.',
        ];
    }
}
