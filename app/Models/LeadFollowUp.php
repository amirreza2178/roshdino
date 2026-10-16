<?php

namespace App\Models;

use App\Enums\FollowUpChannel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['lead_id', 'followed_up_at', 'channel', 'note'])]
class LeadFollowUp extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'followed_up_at' => 'datetime',
            'channel' => FollowUpChannel::class,
        ];
    }

    /**
     * @return BelongsTo<Lead, $this>
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
