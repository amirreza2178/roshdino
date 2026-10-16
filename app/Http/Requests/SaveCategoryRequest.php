<?php

namespace App\Http\Requests;

use App\Enums\CategoryType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => mb_strtolower(trim((string) $this->input('slug'))),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => [
                'required',
                'string',
                'max:120',
                'regex:/^[\p{L}\p{N}]+(?:-[\p{L}\p{N}]+)*$/u',
                Rule::unique('categories', 'slug')->where('type', (string) $this->input('type'))->ignore($this->route('category')),
            ],
            'type' => ['required', Rule::enum(CategoryType::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'نام دسته را بنویس.',
            'slug.required' => 'نشانی دسته را بنویس.',
            'slug.regex' => 'نشانی فقط حرف، عدد و خط تیره باشد.',
            'slug.unique' => 'این نشانی در همین نوع دسته استفاده شده.',
            'type.required' => 'نوع دسته را انتخاب کن.',
        ];
    }
}
