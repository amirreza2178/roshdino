<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Contracts\View\View;

class ShowPublishedWorkController extends Controller
{
    public function __invoke(string $slug): View
    {
        $work = Work::query()->published()->where('slug', $slug)->firstOrFail();
        $work->load(['category', 'images.media', 'seo.image']);

        return view('works.show', [
            'work' => $work,
        ]);
    }
}
