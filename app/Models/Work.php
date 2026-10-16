<?php

namespace App\Models;

use App\Enums\PublishStatus;
use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['category_id', 'title', 'summary', 'slug', 'problem', 'solution', 'technologies', 'result', 'learning', 'status', 'position'])]
class Work extends Model
{
    use HasSeo;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'technologies' => '[]',
        'position' => 0,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'status' => PublishStatus::class,
            'position' => 'integer',
        ];
    }

    /**
     * @param  Builder<Work>  $query
     * @return Builder<Work>
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

    /**
     * @return HasMany<WorkImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(WorkImage::class)->orderBy('position')->orderBy('id');
    }
}
