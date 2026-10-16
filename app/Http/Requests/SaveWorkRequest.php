<?php

namespace App\Http\Requests;

use App\Enums\CategoryType;
use App\Enums\PublishStatus;
use App\Http\Requests\Concerns\PreparesContentFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SaveWorkRequest extends FormRequest
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
            'summary' => ['nullable', 'string', 'max:200'],
            'slug' => [
                'required',
                'string',
                'max:120',
                'regex:/^[\p{L}\p{N}]+(?:-[\p{L}\p{N}]+)*$/u',
                Rule::unique('works', 'slug')->ignore($this->route('work')),
            ],
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where('type', CategoryType::Work->value),
            ],
            'problem' => ['required', 'string', 'max:5000'],
            'solution' => ['required', 'string', 'max:5000'],
            'technologies' => ['nullable', 'string', 'max:2000'],
            'result' => ['nullable', 'string', 'max:5000'],
            'learning' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::enum(PublishStatus::class)],
            'position' => ['required', 'integer', 'min:0', 'max:9999'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'image_alt' => ['nullable', 'string', 'max:160'],
            'remove_images' => ['array'],
            'remove_images.*' => ['integer'],
            'alts' => ['array'],
            'alts.*' => ['nullable', 'string', 'max:160'],
            ...$this->seoRules(),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (count($this->lines('technologies')) > 12) {
                $validator->errors()->add('technologies', 'تکنولوژی‌ها حداکثر ۱۲ مورد است.');
            }

            foreach ($this->lines('technologies') as $line) {
                if (mb_strlen($line) > 40) {
                    $validator->errors()->add('technologies', 'هر تکنولوژی حداکثر ۴۰ نویسه است.');
                    break;
                }
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'نام پروژه را بنویس.',
            'slug.required' => 'نشانی نمونه‌کار را بنویس.',
            'slug.regex' => 'نشانی فقط حرف، عدد و خط تیره باشد.',
            'slug.unique' => 'این نشانی قبلاً استفاده شده.',
            'category_id.exists' => 'دسته باید از دسته‌های نمونه‌کار باشد.',
            'problem.required' => 'مسئله را بنویس.',
            'solution.required' => 'راه‌حل را بنویس.',
            'status.required' => 'وضعیت انتشار را انتخاب کن.',
            'position.required' => 'ترتیب را بنویس.',
            'image.mimes' => 'فقط تصویر JPG، PNG، WEBP یا GIF پذیرفته می‌شود.',
            'image.max' => 'حجم تصویر حداکثر ۵ مگابایت است.',
            ...$this->seoMessages(),
        ];
    }
}
