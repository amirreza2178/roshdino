<?php

namespace App\Models\Concerns;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    protected static function bootHasSeo(): void
    {
        static::deleting(function ($model): void {
            $model->seo()->delete();
        });
    }

    /**
     * @return MorphOne<SeoMeta, $this>
     */
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function saveSeo(array $attributes): void
    {
        $this->seo()->updateOrCreate([], $attributes);
    }
}
