<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['client_id', 'lead_id', 'lead_proposal_id', 'scope', 'amount'])]
class Project extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo<Lead, $this>
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * @return BelongsTo<LeadProposal, $this>
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(LeadProposal::class, 'lead_proposal_id');
    }

    /**
     * @return HasMany<ProjectTask, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class)
            ->orderByRaw("case when status = 'open' then 0 else 1 end")
            ->orderByDesc('id');
    }

    /**
     * @return HasMany<ProjectDelivery, $this>
     */
    public function deliveries(): HasMany
    {
        return $this->hasMany(ProjectDelivery::class)->orderByDesc('delivered_at')->orderByDesc('id');
    }

    /**
     * @return HasMany<ProjectSupport, $this>
     */
    public function supports(): HasMany
    {
        return $this->hasMany(ProjectSupport::class)->orderByDesc('supported_at')->orderByDesc('id');
    }
}
