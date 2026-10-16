<?php

namespace App\Http\Requests;

use App\Enums\ProposalStatus;
use App\Models\Lead;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreLeadProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $amount = preg_replace('/[^\d]/', '', (string) $this->input('amount')) ?? '';

        $this->merge([
            'scope' => trim((string) $this->input('scope')),
            'amount' => $amount === '' ? null : $amount,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'scope' => ['required', 'string', 'max:2000'],
            'amount' => ['required', 'integer', 'min:1', 'max:999999999999'],
            'status' => ['required', Rule::enum(ProposalStatus::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'scope.required' => 'دامنه پیشنهاد را بنویس.',
            'scope.max' => 'دامنه پیشنهاد را کوتاه‌تر بنویس.',
            'amount.required' => 'مبلغ را به تومان بنویس.',
            'amount.integer' => 'مبلغ باید یک عدد صحیح به تومان باشد.',
            'amount.min' => 'مبلغ را به تومان بنویس.',
            'status.required' => 'وضعیت پیشنهاد را انتخاب کن.',
            'status.enum' => 'این وضعیت برای پیشنهاد نیست.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $lead = $this->route('lead');

        throw (new ValidationException($validator))
            ->errorBag($lead instanceof Lead ? 'proposal-'.$lead->id : 'default')
            ->redirectTo(route('leads.index'));
    }
}
