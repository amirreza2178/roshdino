<?php

namespace App\Http\Requests;

use App\Enums\CategoryType;
use App\Enums\PublishStatus;
use App\Http\Requests\Concerns\PreparesContentFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SaveServiceRequest extends FormRequest
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
                Rule::unique('services', 'slug')->ignore($this->route('service')),
            ],
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where('type', CategoryType::Service->value),
            ],
            'description' => ['required', 'string', 'max:5000'],
            'benefits' => ['nullable', 'string', 'max:4000'],
            'process' => ['nullable', 'string', 'max:8000'],
            'cta_label' => ['required', 'string', 'max:80'],
            'cta_url' => ['nullable', 'string', 'max:255', 'regex:/^(\/|https:\/\/)/'],
            'interest' => ['nullable', Rule::in(['web', 'software', 'growth'])],
            'status' => ['required', Rule::enum(PublishStatus::class)],
            'position' => ['required', 'integer', 'min:0', 'max:9999'],
            ...$this->seoRules(),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (count($this->lines('benefits')) > 12) {
                $validator->errors()->add('benefits', 'مزایا حداکثر ۱۲ مورد است.');
            }

            foreach ($this->lines('benefits') as $line) {
                if (mb_strlen($line) > 120) {
                    $validator->errors()->add('benefits', 'هر مزیت حداکثر ۱۲۰ نویسه است.');
                    break;
                }
            }

            if (count($this->steps()) > 8) {
                $validator->errors()->add('process', 'فرایند حداکثر ۸ قدم است.');
            }

            foreach ($this->steps() as $step) {
                if (mb_strlen($step['title']) > 80 || mb_strlen($step['body']) > 300) {
                    $validator->errors()->add('process', 'عنوان هر قدم حداکثر ۸۰ و توضیح آن حداکثر ۳۰۰ نویسه است.');
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
            'title.required' => 'عنوان خدمت را بنویس.',
            'slug.required' => 'نشانی خدمت را بنویس.',
            'slug.regex' => 'نشانی فقط حرف، عدد و خط تیره باشد.',
            'slug.unique' => 'این نشانی قبلاً استفاده شده.',
            'category_id.exists' => 'دسته باید از دسته‌های خدمت باشد.',
            'description.required' => 'توضیح خدمت را بنویس.',
            'cta_label.required' => 'متن فراخوان را بنویس.',
            'cta_url.regex' => 'پیوند فراخوان باید با / یا https:// شروع شود.',
            'interest.in' => 'مسیر فرم فقط وب، نرم‌افزار یا رشد است.',
            'status.required' => 'وضعیت انتشار را انتخاب کن.',
            'position.required' => 'ترتیب را بنویس.',
            ...$this->seoMessages(),
        ];
    }
}
