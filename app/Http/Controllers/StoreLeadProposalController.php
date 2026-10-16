<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadProposalRequest;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;

class StoreLeadProposalController extends Controller
{
    public function __invoke(StoreLeadProposalRequest $request, Lead $lead): RedirectResponse
    {
        $lead->proposals()->create($request->safe()->only(['scope', 'amount', 'status']));

        return redirect()
            ->route('leads.index')
            ->with('proposal_saved', $lead->id);
    }
}
