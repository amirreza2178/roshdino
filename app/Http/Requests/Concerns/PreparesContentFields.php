<?php

namespace App\Http\Requests\Concerns;

trait PreparesContentFields
{
    protected function prepareForValidation(): void
    {
        if ($this->exists('slug')) {
            $this->merge([
                'slug' => mb_strtolower(trim((string) $this->input('slug'))),
            ]);
        }

        if ($this->exists('indexable')) {
            $this->merge([
                'indexable' => $this->boolean('indexable'),
            ]);
        }
    }

    /**
     * @return list<string>
     */
    public function lines(string $key): array
    {
        $value = (string) $this->input($key, '');

        return collect(preg_split('/\r\n|\r|\n/', $value) ?: [])
            ->map(fn (string $line): string => trim($line))
            ->filter(fn (string $line): bool => $line !== '')
            ->values()
            ->all();
    }

    /**
     * @return list<array{title: string, body: string}>
     */
    public function steps(): array
    {
        return collect($this->lines('process'))
            ->map(function (string $line): array {
                [$title, $body] = array_pad(explode('|', $line, 2), 2, '');

                return [
                    'title' => trim($title),
                    'body' => trim((string) $body),
                ];
            })
            ->filter(fn (array $step): bool => $step['title'] !== '')
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function seoAttributes(): array
    {
        return [
            'meta_title' => $this->filled('meta_title') ? $this->string('meta_title')->toString() : null,
            'meta_description' => $this->filled('meta_description') ? $this->string('meta_description')->toString() : null,
            'canonical' => $this->filled('canonical') ? $this->string('canonical')->toString() : null,
            'og_title' => $this->filled('og_title') ? $this->string('og_title')->toString() : null,
            'og_description' => $this->filled('og_description') ? $this->string('og_description')->toString() : null,
            'og_image_id' => $this->filled('og_image_id') ? $this->integer('og_image_id') : null,
            'indexable' => $this->boolean('indexable'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function seoRules(): array
    {
        return [
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'canonical' => ['nullable', 'url', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:70'],
            'og_description' => ['nullable', 'string', 'max:200'],
            'og_image_id' => ['nullable', 'integer', 'exists:media,id'],
            'indexable' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function seoMessages(): array
    {
        return [
            'meta_title.max' => 'عنوان متا حداکثر ۷۰ نویسه است.',
            'meta_description.max' => 'توضیح متا حداکثر ۱۶۰ نویسه است.',
            'canonical.url' => 'Canonical باید یک نشانی کامل باشد.',
            'og_title.max' => 'عنوان Open Graph حداکثر ۷۰ نویسه است.',
            'og_description.max' => 'توضیح Open Graph حداکثر ۲۰۰ نویسه است.',
            'og_image_id.exists' => 'تصویر Open Graph پیدا نشد.',
            'indexable.required' => 'وضعیت ایندکس را مشخص کن.',
        ];
    }
}
