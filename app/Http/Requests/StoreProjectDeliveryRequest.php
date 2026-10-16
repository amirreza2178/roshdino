<?php

namespace App\Http\Requests;

use App\Models\Lead;
use App\Models\Project;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreProjectDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('note')) {
            $this->merge([
                'note' => trim((string) $this->input('note')),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'delivered_at' => ['required', 'date'],
            'note' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'delivered_at.required' => 'زمان تحویل را وارد کن.',
            'delivered_at.date' => 'زمان تحویل درست نیست.',
            'note.required' => 'یادداشت تحویل لازم است.',
            'note.max' => 'یادداشت تحویل را کوتاه‌تر بنویس.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $lead = $this->route('lead');
        $project = $this->route('project');

        $bag = 'default';
        if ($lead instanceof Lead && $project instanceof Project) {
            $bag = 'project-delivery-'.$lead->id.'-'.$project->id;
        }

        throw (new ValidationException($validator))
            ->errorBag($bag)
            ->redirectTo(route('leads.index'));
    }
}
