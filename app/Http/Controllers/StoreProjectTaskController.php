<?php

namespace App\Http\Controllers;

use App\Enums\ProjectTaskStatus;
use App\Http\Requests\StoreProjectTaskRequest;
use App\Models\Lead;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;

class StoreProjectTaskController extends Controller
{
    public function __invoke(StoreProjectTaskRequest $request, Lead $lead, Project $project): RedirectResponse
    {
        $project->tasks()->create([
            'title' => $request->validated('title'),
            'status' => ProjectTaskStatus::Open,
        ]);

        return redirect()
            ->route('leads.index')
            ->with('project_task_saved', $lead->id);
    }
}
