<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadIndexRequest;
use App\Models\Lead;
use App\Services\LeadWorkQueue;
use Illuminate\Contracts\View\View;

class LeadIndexController extends Controller
{
    public function __invoke(LeadIndexRequest $request, LeadWorkQueue $workQueue): View
    {
        $filters = $request->safe()->only(['status', 'service', 'intent']);

        return view('leads.index', [
            'leads' => Lead::query()
                ->with(['followUps', 'conversations', 'meetings', 'proposals.project.client', 'projects.tasks', 'projects.deliveries', 'projects.supports', 'projects.proposal'])
                ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
                ->when($filters['service'] ?? null, fn ($query, string $service) => $query->where('service', $service))
                ->when($filters['intent'] ?? null, fn ($query, string $intent) => $query->where('intent', $intent))
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->paginate(Lead::INBOX_PAGE_SIZE)
                ->withQueryString(),
            'workQueue' => $workQueue->collect(),
            'tally' => Lead::registrationTally(),
            'filters' => $filters,
        ]);
    }
}
