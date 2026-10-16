<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvertAcceptedProposalRequest;
use App\Models\Lead;
use App\Models\LeadProposal;
use App\Services\StartProjectService;
use Illuminate\Http\RedirectResponse;

class ConvertAcceptedProposalController extends Controller
{
    public function __invoke(ConvertAcceptedProposalRequest $request, Lead $lead, LeadProposal $proposal, StartProjectService $projects): RedirectResponse
    {
        $projects->fromAcceptedProposal($lead, $proposal);

        return redirect()
            ->route('leads.index')
            ->with('project_started', $proposal->id);
    }
}
