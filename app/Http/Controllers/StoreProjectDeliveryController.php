<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectDeliveryRequest;
use App\Models\Lead;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class StoreProjectDeliveryController extends Controller
{
    public function __invoke(StoreProjectDeliveryRequest $request, Lead $lead, Project $project): RedirectResponse
    {
        $project->deliveries()->create([
            'delivered_at' => Carbon::parse($request->validated('delivered_at'), 'Asia/Tehran')->utc(),
            'note' => $request->validated('note'),
        ]);

        return redirect()
            ->route('leads.index')
            ->with('project_delivery_saved', $lead->id);
    }
}
