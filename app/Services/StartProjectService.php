<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Enums\ProposalStatus;
use App\Models\Client;
use App\Models\Lead;
use App\Models\LeadProposal;
use App\Models\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StartProjectService
{
    public function fromAcceptedProposal(Lead $lead, LeadProposal $proposal): Project
    {
        return DB::transaction(function () use ($lead, $proposal): Project {
            $lead = Lead::query()->whereKey($lead->id)->lockForUpdate()->firstOrFail();
            $proposal = LeadProposal::query()->whereKey($proposal->id)->lockForUpdate()->firstOrFail();

            if ($proposal->lead_id !== $lead->id) {
                throw (new ModelNotFoundException)->setModel(LeadProposal::class, [$proposal->id]);
            }

            if ($proposal->status !== ProposalStatus::Accepted) {
                throw $this->rejected($proposal, 'فقط پیشنهاد پذیرفته‌شده به پروژه تبدیل می‌شود.');
            }

            if ($proposal->project()->exists()) {
                throw $this->rejected($proposal, 'این پیشنهاد قبلاً به پروژه تبدیل شده است.');
            }

            $client = Client::query()->where('lead_id', $lead->id)->lockForUpdate()->first();

            if ($client === null) {
                $client = Client::query()->create([
                    'lead_id' => $lead->id,
                    'name' => $lead->name,
                    'contact' => $lead->contact,
                ]);
            }

            $project = Project::query()->create([
                'client_id' => $client->id,
                'lead_id' => $lead->id,
                'lead_proposal_id' => $proposal->id,
                'scope' => $proposal->scope,
                'amount' => $proposal->amount,
            ]);

            $lead->update([
                'status' => LeadStatus::Won,
            ]);

            return $project;
        });
    }

    private function rejected(LeadProposal $proposal, string $message): ValidationException
    {
        return ValidationException::withMessages([
            'proposal' => $message,
        ])->errorBag('convert-'.$proposal->id)->redirectTo(route('leads.index'));
    }
}
