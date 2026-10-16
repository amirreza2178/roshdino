<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable(['disk', 'path', 'original_name', 'mime', 'size', 'width', 'height'])]
class Media extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function deleteFile(): void
    {
        Storage::disk($this->disk)->delete($this->path);
    }

    /**
     * @return HasMany<WorkImage, $this>
     */
    public function workImages(): HasMany
    {
        return $this->hasMany(WorkImage::class);
    }

    /**
     * @return HasMany<SeoMeta, $this>
     */
    public function seoMetas(): HasMany
    {
        return $this->hasMany(SeoMeta::class, 'og_image_id');
    }

    public function isInUse(): bool
    {
        return $this->workImages()->exists()
            || $this->seoMetas()->exists()
            || SiteSetting::query()->where('key', 'seo_og_image_id')->where('value', (string) $this->id)->exists();
    }
}
