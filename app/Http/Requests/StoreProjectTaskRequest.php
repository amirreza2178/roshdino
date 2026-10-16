<?php

namespace App\Http\Requests;

use App\Models\Lead;
use App\Models\Project;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreProjectTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('title')) {
            $this->merge([
                'title' => trim((string) $this->input('title')),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'عنوان وظیفه لازم است.',
            'title.max' => 'عنوان وظیفه را کوتاه‌تر بنویس.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $lead = $this->route('lead');
        $project = $this->route('project');

        $bag = 'default';
        if ($lead instanceof Lead && $project instanceof Project) {
            $bag = 'project-task-'.$lead->id.'-'.$project->id;
        }

        throw (new ValidationException($validator))
            ->errorBag($bag)
            ->redirectTo(route('leads.index'));
    }
}
