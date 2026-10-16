<?php

use App\Models\HomePage;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $this->replace([
            'DIGITAL GROWTH STUDIO' => 'استودیو رشد دیجیتال',
            'THE REAL PROBLEM' => 'مسئله',
            'HOW WE WORK' => 'روش کار',
            'OUR PRINCIPLE' => 'اصل کار',
            'FAQ' => 'سؤال',
            'DISCOVER' => 'شناخت',
            'DESIGN' => 'طراحی',
            'BUILD' => 'ساخت',
            'GROW' => 'رشد',
        ]);
    }

    public function down(): void
    {
        $this->replace([
            'استودیو رشد دیجیتال' => 'DIGITAL GROWTH STUDIO',
            'مسئله' => 'THE REAL PROBLEM',
            'روش کار' => 'HOW WE WORK',
            'اصل کار' => 'OUR PRINCIPLE',
            'سؤال' => 'FAQ',
            'شناخت' => 'DISCOVER',
            'طراحی' => 'DESIGN',
            'ساخت' => 'BUILD',
            'رشد' => 'GROW',
        ]);
    }

    /**
     * @param  array<string, string>  $labels
     */
    private function replace(array $labels): void
    {
        $page = HomePage::query()->first();

        if ($page === null || ! is_array($page->story)) {
            return;
        }

        $story = $page->story;
        $changed = false;

        foreach (['hero', 'problem', 'process', 'principle', 'faq'] as $section) {
            $eyebrow = $story[$section]['eyebrow'] ?? null;

            if (is_string($eyebrow) && isset($labels[$eyebrow])) {
                $story[$section]['eyebrow'] = $labels[$eyebrow];
                $changed = true;
            }
        }

        foreach ($story['process']['steps'] ?? [] as $index => $step) {
            $title = $step['title'] ?? null;

            if (is_string($title) && isset($labels[$title])) {
                $story['process']['steps'][$index]['title'] = $labels[$title];
                $changed = true;
            }
        }

        if ($changed) {
            $page->story = $story;
            $page->save();
        }
    }
};
