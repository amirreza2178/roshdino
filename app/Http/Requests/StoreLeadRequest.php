<?php

namespace App\Http\Requests;

use App\Enums\LeadIntent;
use App\Enums\LeadServiceInterest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'contact' => trim((string) $this->input('contact')),
            'problem' => trim((string) $this->input('problem')),
            'service' => $this->filled('service') ? $this->input('service') : LeadServiceInterest::Unsure->value,
            'intent' => $this->filled('intent') ? $this->input('intent') : LeadIntent::Request->value,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'contact' => ['required', 'string', 'max:190', $this->emailOrMobileRule()],
            'service' => ['required', Rule::enum(LeadServiceInterest::class)],
            'intent' => ['required', Rule::enum(LeadIntent::class)],
            'problem' => [
                Rule::excludeIf(fn (): bool => $this->input('intent') !== LeadIntent::Request->value),
                'required',
                'string',
                'min:10',
                'max:2000',
            ],
            'meets_at' => [
                Rule::excludeIf(fn (): bool => $this->input('intent') !== LeadIntent::Meeting->value),
                'required',
                'date',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'نام را بنویس.',
            'contact.required' => 'ایمیل یا شماره موبایل را بنویس.',
            'problem.required' => 'مسئله را کوتاه توضیح بده.',
            'problem.min' => 'مسئله را کمی واضح‌تر بنویس.',
            'service.enum' => 'یکی از مسیرهای موجود را انتخاب کن.',
            'intent.required' => 'یکی از سه اقدام را انتخاب کن.',
            'intent.enum' => 'این اقدام در مسیر شروع نیست.',
            'meets_at.required' => 'زمان جلسه را انتخاب کن.',
            'meets_at.date' => 'زمان جلسه درست نیست.',
        ];
    }

    public function isSpam(): bool
    {
        return filled($this->input('company_website'));
    }

    public function contactUrl(): string
    {
        return route('home', [
            'intent' => $this->input('intent', LeadIntent::Request->value),
        ]).'#contact';
    }

    protected function getRedirectUrl(): string
    {
        return $this->contactUrl();
    }

    private function emailOrMobileRule(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            if (! is_string($value) || (! $this->isEmail($value) && ! $this->isMobile($value))) {
                $fail('یک ایمیل یا شماره موبایل معتبر وارد کن.');
            }
        };
    }

    private function isEmail(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function isMobile(string $value): bool
    {
        $digits = preg_replace('/\D+/', '', $value) ?? '';

        return preg_match('/^09\d{9}$/', $digits) === 1
            || preg_match('/^989\d{9}$/', $digits) === 1;
    }
}
