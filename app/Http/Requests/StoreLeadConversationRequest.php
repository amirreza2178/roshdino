<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreLeadConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('body')) {
            $this->merge([
                'body' => trim((string) $this->input('body')),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'conversed_at' => ['required', 'date'],
            'body' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'conversed_at.required' => 'زمان گفتگو را وارد کن.',
            'conversed_at.date' => 'زمان گفتگو درست نیست.',
            'body.required' => 'متن گفتگو لازم است.',
            'body.max' => 'متن گفتگو را کوتاه‌تر بنویس.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $lead = $this->route('lead');

        throw (new ValidationException($validator))
            ->errorBag($lead instanceof Lead ? 'conversation-'.$lead->id : 'default')
            ->redirectTo(route('leads.index'));
    }
}
