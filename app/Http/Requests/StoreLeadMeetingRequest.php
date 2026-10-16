<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreLeadMeetingRequest extends FormRequest
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
            'meets_at' => ['required', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'meets_at.required' => 'زمان جلسه را وارد کن.',
            'meets_at.date' => 'زمان جلسه درست نیست.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $lead = $this->route('lead');

        throw (new ValidationException($validator))
            ->errorBag($lead instanceof Lead ? 'meeting-'.$lead->id : 'default')
            ->redirectTo(route('leads.index'));
    }
}
