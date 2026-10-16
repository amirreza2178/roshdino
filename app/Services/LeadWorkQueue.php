<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Enums\ProposalStatus;
use App\Models\Lead;
use App\Models\LeadMeeting;
use App\Models\LeadProposal;
use Illuminate\Database\Eloquent\Collection;

class LeadWorkQueue
{
    public const MEETING_DAYS = 7;

    /** @var Collection<int, Lead> */
    public Collection $firstContacts;

    /** @var Collection<int, LeadMeeting> */
    public Collection $meetings;

    /** @var Collection<int, LeadProposal> */
    public Collection $waitingProposals;

    public function __construct()
    {
        $this->firstContacts = new Collection;
        $this->meetings = new Collection;
        $this->waitingProposals = new Collection;
    }

    public function collect(): static
    {
        $this->firstContacts = Lead::query()
            ->open()
            ->where('status', LeadStatus::New)
            ->whereDoesntHave('followUps')
            ->oldest()
            ->orderBy('id')
            ->get();

        $start = now()->timezone('Asia/Tehran')->startOfDay();
        $end = $start->copy()->addDays(self::MEETING_DAYS - 1)->endOfDay();

        $this->meetings = LeadMeeting::query()
            ->whereBetween('meets_at', [$start->utc(), $end->utc()])
            ->whereHas('lead', fn ($query) => $query->open())
            ->with('lead')
            ->orderBy('meets_at')
            ->orderBy('id')
            ->get();

        $this->waitingProposals = LeadProposal::query()
            ->where('status', ProposalStatus::Sent)
            ->whereHas('lead', fn ($query) => $query->open())
            ->with('lead')
            ->oldest()
            ->orderBy('id')
            ->get();

        return $this;
    }

    public function isEmpty(): bool
    {
        return $this->firstContacts->isEmpty()
            && $this->meetings->isEmpty()
            && $this->waitingProposals->isEmpty();
    }

    public function inboxUrl(Lead $lead): string
    {
        $newer = Lead::query()
            ->where(function ($query) use ($lead): void {
                $query->where('created_at', '>', $lead->created_at)
                    ->orWhere(function ($query) use ($lead): void {
                        $query->where('created_at', $lead->created_at)
                            ->where('id', '>', $lead->id);
                    });
            })
            ->count();

        $page = intdiv($newer, Lead::INBOX_PAGE_SIZE) + 1;

        return route('leads.index', $page > 1 ? ['page' => $page] : []).'#lead-'.$lead->id;
    }
}
