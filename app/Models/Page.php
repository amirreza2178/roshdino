<?php

namespace App\Models;

use App\Enums\PublishStatus;
use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'slug', 'status', 'body'])]
class Page extends Model
{
    use HasSeo;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PublishStatus::class,
        ];
    }

    /**
     * @param  Builder<Page>  $query
     * @return Builder<Page>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublishStatus::Published);
    }

    /**
     * @return list<string>
     */
    public static function reservedSlugs(): array
    {
        return [
            'login',
            'leads',
            'password',
            'site',
            'services',
            'work',
            'storage',
            'up',
            'build',
            'media',
        ];
    }
}
