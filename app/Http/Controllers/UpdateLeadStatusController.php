<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateLeadStatusRequest;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;

class UpdateLeadStatusController extends Controller
{
    public function __invoke(UpdateLeadStatusRequest $request, Lead $lead): RedirectResponse
    {
        $lead->update([
            'status' => $request->validated('status'),
        ]);

        return redirect()
            ->route('leads.index')
            ->with('status_updated', $lead->id);
    }
}
