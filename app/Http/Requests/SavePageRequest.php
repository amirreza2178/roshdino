<?php

namespace App\Http\Requests;

use App\Enums\PublishStatus;
use App\Http\Requests\Concerns\PreparesContentFields;
use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePageRequest extends FormRequest
{
    use PreparesContentFields;

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
            'title' => ['required', 'string', 'max:160'],
            'slug' => [
                'required',
                'string',
                'max:120',
                'regex:/^[\p{L}\p{N}]+(?:-[\p{L}\p{N}]+)*$/u',
                Rule::notIn(Page::reservedSlugs()),
                Rule::unique('pages', 'slug')->ignore($this->route('page')),
            ],
            'status' => ['required', Rule::enum(PublishStatus::class)],
            'body' => ['required', 'string', 'max:20000'],
            ...$this->seoRules(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'عنوان را بنویس.',
            'slug.required' => 'نشانی صفحه را بنویس.',
            'slug.regex' => 'نشانی فقط حرف، عدد و خط تیره باشد.',
            'slug.not_in' => 'این نشانی برای صفحهٔ سایت آزاد نیست.',
            'slug.unique' => 'این نشانی قبلاً استفاده شده.',
            'status.required' => 'وضعیت انتشار را انتخاب کن.',
            'body.required' => 'متن صفحه را بنویس.',
            ...$this->seoMessages(),
        ];
    }
}
