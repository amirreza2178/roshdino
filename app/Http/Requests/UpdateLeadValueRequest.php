<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateLeadValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $amount = preg_replace('/[^\d]/', '', (string) $this->input('estimated_value')) ?? '';

        $this->merge([
            'estimated_value' => $amount === '' ? null : $amount,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'estimated_value' => ['required', 'integer', 'min:1', 'max:999999999999'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'estimated_value.required' => 'مبلغ را به تومان بنویس.',
            'estimated_value.integer' => 'مبلغ باید یک عدد صحیح به تومان باشد.',
            'estimated_value.min' => 'مبلغ را به تومان بنویس.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $lead = $this->route('lead');

        throw (new ValidationException($validator))
            ->errorBag($lead instanceof Lead ? 'value-'.$lead->id : 'default')
            ->redirectTo(route('leads.index'));
    }
}
