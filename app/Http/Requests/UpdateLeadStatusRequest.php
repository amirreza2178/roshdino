<?php

namespace App\Http\Requests;

use App\Enums\LeadStatus;
use App\Models\Lead;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateLeadStatusRequest extends FormRequest
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
            'status' => ['required', Rule::enum(LeadStatus::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'وضعیت را انتخاب کن.',
            'status.enum' => 'این وضعیت در مسیر فروش نیست.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $lead = $this->route('lead');

        throw (new ValidationException($validator))
            ->errorBag($lead instanceof Lead ? 'lead-'.$lead->id : 'default')
            ->redirectTo(route('leads.index'));
    }
}
