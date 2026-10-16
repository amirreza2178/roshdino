<?php

namespace App\Http\Requests;

use App\Enums\ProjectTaskStatus;
use App\Models\Lead;
use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateProjectTaskStatusRequest extends FormRequest
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
            'status' => ['required', Rule::enum(ProjectTaskStatus::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'وضعیت را انتخاب کن.',
            'status.enum' => 'این وضعیت برای وظیفه نیست.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $lead = $this->route('lead');
        $project = $this->route('project');
        $task = $this->route('task');

        $bag = 'default';
        if ($lead instanceof Lead && $project instanceof Project && $task instanceof ProjectTask) {
            $bag = 'project-task-status-'.$lead->id.'-'.$project->id.'-'.$task->id;
        }

        throw (new ValidationException($validator))
            ->errorBag($bag)
            ->redirectTo(route('leads.index'));
    }
}
