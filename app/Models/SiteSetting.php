<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value'])]
class SiteSetting extends Model
{
    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return [
            'brand_name',
            'brand_latin',
            'contact_email',
            'contact_phone',
            'contact_telegram',
            'social_instagram',
            'social_linkedin',
            'social_x',
            'footer_text',
            'seo_title',
            'seo_description',
            'seo_canonical',
            'seo_og_title',
            'seo_og_description',
            'seo_og_image_id',
            'seo_indexable',
        ];
    }

    /**
     * @param  array<string, string|null>  $values
     */
    public static function put(array $values): void
    {
        foreach (self::keys() as $key) {
            if (! array_key_exists($key, $values)) {
                continue;
            }

            $value = $values[$key];

            self::query()->updateOrCreate(
                ['key' => $key],
                ['value' => filled($value) ? $value : null],
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    public static function publicProfile(): array
    {
        $rows = self::query()->pluck('value', 'key');
        $telegram = filled($rows['contact_telegram'] ?? null)
            ? ltrim((string) $rows['contact_telegram'], '@')
            : ltrim((string) config('roshdino.contact.telegram'), '@');
        $phone = filled($rows['contact_phone'] ?? null)
            ? (string) $rows['contact_phone']
            : trim((string) config('roshdino.contact.phone'));
        $imageId = $rows['seo_og_image_id'] ?? null;
        $image = filled($imageId) ? Media::query()->find($imageId) : null;

        return [
            'brand' => filled($rows['brand_name'] ?? null) ? (string) $rows['brand_name'] : 'رشدینو',
            'brand_latin' => filled($rows['brand_latin'] ?? null) ? (string) $rows['brand_latin'] : 'ROSHDINO',
            'email' => filled($rows['contact_email'] ?? null) ? (string) $rows['contact_email'] : '',
            'phone' => $phone,
            'telegram' => $telegram,
            'instagram' => (string) ($rows['social_instagram'] ?? ''),
            'linkedin' => (string) ($rows['social_linkedin'] ?? ''),
            'x' => (string) ($rows['social_x'] ?? ''),
            'footer' => filled($rows['footer_text'] ?? null)
                ? (string) $rows['footer_text']
                : 'استودیو رشد دیجیتال؛ وب، نرم‌افزار و راهکارهایی برای مسئله واقعی کسب‌وکار.',
            'seo_title' => (string) ($rows['seo_title'] ?? ''),
            'seo_description' => (string) ($rows['seo_description'] ?? ''),
            'seo_canonical' => (string) ($rows['seo_canonical'] ?? ''),
            'seo_og_title' => (string) ($rows['seo_og_title'] ?? ''),
            'seo_og_description' => (string) ($rows['seo_og_description'] ?? ''),
            'og_image' => $image?->url() ?? '',
            'indexable' => ($rows['seo_indexable'] ?? '1') !== '0',
        ];
    }
}
