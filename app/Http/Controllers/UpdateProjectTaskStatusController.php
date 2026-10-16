<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectTaskStatusRequest;
use App\Models\Lead;
use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Http\RedirectResponse;

class UpdateProjectTaskStatusController extends Controller
{
    public function __invoke(
        UpdateProjectTaskStatusRequest $request,
        Lead $lead,
        Project $project,
        ProjectTask $task,
    ): RedirectResponse {
        $task->update([
            'status' => $request->validated('status'),
        ]);

        return redirect()
            ->route('leads.index')
            ->with('project_task_status_saved', $lead->id);
    }
}
