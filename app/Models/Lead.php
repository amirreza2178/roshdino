<?php

namespace App\Models;

use App\Enums\LeadIntent;
use App\Enums\LeadServiceInterest;
use App\Enums\LeadStatus;
use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['name', 'contact', 'service', 'intent', 'problem', 'status', 'estimated_value', 'source', 'ip_address'])]
class Lead extends Model
{
    public const INBOX_PAGE_SIZE = 20;

    /** @use HasFactory<LeadFactory> */
    use HasFactory;

    /**
     * @param  Builder<Lead>  $query
     * @return Builder<Lead>
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereNotIn('status', [LeadStatus::Won, LeadStatus::Lost]);
    }

    /**
     * @return array{total: int, intents: list<array{value: string, label: string, count: int}>, services: list<array{value: string, label: string, count: int}>}
     */
    public static function registrationTally(): array
    {
        $intents = self::countsBy('intent');
        $services = self::countsBy('service');

        return [
            'total' => array_sum($intents),
            'intents' => array_map(
                fn (LeadIntent $intent) => [
                    'value' => $intent->value,
                    'label' => $intent->label(),
                    'count' => $intents[$intent->value] ?? 0,
                ],
                LeadIntent::cases(),
            ),
            'services' => array_map(
                fn (LeadServiceInterest $service) => [
                    'value' => $service->value,
                    'label' => $service->label(),
                    'count' => $services[$service->value] ?? 0,
                ],
                LeadServiceInterest::cases(),
            ),
        ];
    }

    /**
     * @return array<string, int>
     */
    private static function countsBy(string $column): array
    {
        $counts = [];

        foreach (self::query()->select($column)->selectRaw('count(*) as aggregate')->groupBy($column)->get() as $row) {
            $value = $row->getAttribute($column);
            $key = $value instanceof \BackedEnum ? $value->value : (string) $value;
            $counts[$key] = (int) $row->aggregate;
        }

        return $counts;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'service' => LeadServiceInterest::class,
            'intent' => LeadIntent::class,
            'status' => LeadStatus::class,
            'estimated_value' => 'integer',
        ];
    }

    /**
     * @return HasMany<LeadFollowUp, $this>
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(LeadFollowUp::class)->orderByDesc('followed_up_at')->orderByDesc('id');
    }

    /**
     * @return HasMany<LeadConversation, $this>
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(LeadConversation::class)->orderByDesc('conversed_at')->orderByDesc('id');
    }

    /**
     * @return HasMany<LeadMeeting, $this>
     */
    public function meetings(): HasMany
    {
        return $this->hasMany(LeadMeeting::class)->orderByDesc('meets_at')->orderByDesc('id');
    }

    /**
     * @return HasMany<LeadProposal, $this>
     */
    public function proposals(): HasMany
    {
        return $this->hasMany(LeadProposal::class)->latest()->orderByDesc('id');
    }

    /**
     * @return HasOne<Client, $this>
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    /**
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class)->latest()->orderByDesc('id');
    }
}
