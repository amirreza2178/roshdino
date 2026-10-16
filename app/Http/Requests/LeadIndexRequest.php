<?php

namespace App\Http\Requests;

use App\Enums\LeadIntent;
use App\Enums\LeadServiceInterest;
use App\Enums\LeadStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $filters = [];

        foreach (['status', 'service', 'intent'] as $field) {
            if ($this->exists($field)) {
                $filters[$field] = $this->input($field) === '' ? null : $this->input($field);
            }
        }

        $this->merge($filters);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::enum(LeadStatus::class)],
            'service' => ['nullable', Rule::enum(LeadServiceInterest::class)],
            'intent' => ['nullable', Rule::enum(LeadIntent::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.enum' => 'این وضعیت در مسیر فروش نیست.',
            'service.enum' => 'این خدمت در مسیر فروش نیست.',
            'intent.enum' => 'این اقدام در مسیر فروش نیست.',
        ];
    }
}
