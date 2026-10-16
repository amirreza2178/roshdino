<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectSupportRequest;
use App\Models\Lead;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class StoreProjectSupportController extends Controller
{
    public function __invoke(StoreProjectSupportRequest $request, Lead $lead, Project $project): RedirectResponse
    {
        $project->supports()->create([
            'supported_at' => Carbon::parse($request->validated('supported_at'), 'Asia/Tehran')->utc(),
            'note' => $request->validated('note'),
        ]);

        return redirect()
            ->route('leads.index')
            ->with('project_support_saved', $lead->id);
    }
}
