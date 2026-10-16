<?php

namespace App\Services;

use App\Models\Media;
use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ImageStorage
{
    private const MAX_EDGE = 1600;

    private const MAX_PIXELS = 16_000_000;

    public function store(UploadedFile $file): Media
    {
        if (! function_exists('imagecreatetruecolor')) {
            throw ValidationException::withMessages([
                'image' => 'بهینه‌سازی تصویر روی این سرور فعال نیست.',
            ]);
        }

        $sourcePath = $file->getRealPath();
        $info = $sourcePath !== false ? @getimagesize($sourcePath) : false;

        if ($info === false) {
            throw ValidationException::withMessages([
                'image' => 'فایل باید یک تصویر واقعی باشد.',
            ]);
        }

        [$width, $height, $type] = $info;

        if (! in_array($type, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF], true)) {
            throw ValidationException::withMessages([
                'image' => 'فقط تصویر JPG، PNG، WEBP یا GIF پذیرفته می‌شود.',
            ]);
        }

        if ($type === IMAGETYPE_WEBP && ! function_exists('imagecreatefromwebp')) {
            throw ValidationException::withMessages([
                'image' => 'این سرور تصویر WEBP را نمی‌خواند.',
            ]);
        }

        if ($width < 1 || $height < 1 || $width > 8000 || $height > 8000 || ($width * $height) > self::MAX_PIXELS) {
            throw ValidationException::withMessages([
                'image' => 'اندازه تصویر مجاز نیست.',
            ]);
        }

        $source = $this->open($sourcePath, $type);

        if ($source === false) {
            throw ValidationException::withMessages([
                'image' => 'فایل باید یک تصویر واقعی باشد.',
            ]);
        }

        $image = $this->constrain($source, $width, $height, $type);
        $encoded = $this->encode($image, $type);
        imagedestroy($image);

        if ($encoded['binary'] === '') {
            throw ValidationException::withMessages([
                'image' => 'تصویر ذخیره نشد.',
            ]);
        }

        $storedPath = 'media/'.Str::lower(Str::random(40)).'.'.$encoded['extension'];
        Storage::disk('public')->put($storedPath, $encoded['binary']);
        $size = @getimagesizefromstring($encoded['binary']);

        return Media::query()->create([
            'disk' => 'public',
            'path' => $storedPath,
            'original_name' => $this->originalName($file),
            'mime' => $encoded['mime'],
            'size' => strlen($encoded['binary']),
            'width' => (int) ($size[0] ?? 0),
            'height' => (int) ($size[1] ?? 0),
        ]);
    }

    private function open(string $path, int $type): GdImage|false
    {
        return match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            IMAGETYPE_GIF => @imagecreatefromgif($path),
            default => false,
        };
    }

    private function constrain(GdImage $source, int $width, int $height, int $type): GdImage
    {
        $longest = max($width, $height);

        if ($longest <= self::MAX_EDGE) {
            return $source;
        }

        $scale = self::MAX_EDGE / $longest;
        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));
        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF], true)) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($source);

        return $canvas;
    }

    /**
     * @return array{binary: string, mime: string, extension: string}
     */
    private function encode(GdImage $image, int $type): array
    {
        ob_start();

        if ($type === IMAGETYPE_JPEG) {
            imagejpeg($image, null, 82);

            return [
                'binary' => (string) ob_get_clean(),
                'mime' => 'image/jpeg',
                'extension' => 'jpg',
            ];
        }

        if ($type === IMAGETYPE_WEBP && function_exists('imagewebp')) {
            imagewebp($image, null, 82);

            return [
                'binary' => (string) ob_get_clean(),
                'mime' => 'image/webp',
                'extension' => 'webp',
            ];
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);
        imagepng($image, null, 6);

        return [
            'binary' => (string) ob_get_clean(),
            'mime' => 'image/png',
            'extension' => 'png',
        ];
    }

    private function originalName(UploadedFile $file): string
    {
        $name = basename(str_replace('\\', '/', $file->getClientOriginalName()));
        $name = preg_replace('/[^\p{L}\p{N}.\-_ ]/u', '', $name) ?? '';
        $name = trim($name);

        if ($name === '' || $name === '.' || str_contains($name, '..')) {
            return 'image';
        }

        return mb_substr($name, 0, 120);
    }
}
