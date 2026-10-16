<?php

namespace App\Models;

use App\Enums\LeadServiceInterest;
use App\Enums\PublishStatus;
use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['category_id', 'title', 'slug', 'description', 'benefits', 'process', 'cta_label', 'cta_url', 'interest', 'status', 'position'])]
class Service extends Model
{
    use HasSeo;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'benefits' => '[]',
        'process' => '[]',
        'position' => 0,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'benefits' => 'array',
            'process' => 'array',
            'interest' => LeadServiceInterest::class,
            'status' => PublishStatus::class,
            'position' => 'integer',
        ];
    }

    /**
     * @param  Builder<Service>  $query
     * @return Builder<Service>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublishStatus::Published);
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ctaHref(): string
    {
        if (filled($this->cta_url)) {
            return $this->cta_url;
        }

        $parameters = ['intent' => 'request'];

        if ($this->interest instanceof LeadServiceInterest && $this->interest !== LeadServiceInterest::Unsure) {
            $parameters['service'] = $this->interest->value;
        }

        return route('home', $parameters).'#contact';
    }
}
