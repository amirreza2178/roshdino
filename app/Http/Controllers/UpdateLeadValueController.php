<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateLeadValueRequest;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;

class UpdateLeadValueController extends Controller
{
    public function __invoke(UpdateLeadValueRequest $request, Lead $lead): RedirectResponse
    {
        $lead->update([
            'estimated_value' => $request->validated('estimated_value'),
        ]);

        return redirect()
            ->route('leads.index')
            ->with('value_saved', $lead->id);
    }
}
