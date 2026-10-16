<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Work;
use Illuminate\Contracts\View\View;

class ShowPublishedServiceController extends Controller
{
    public function __invoke(string $slug): View
    {
        $service = Service::query()->published()->where('slug', $slug)->firstOrFail();
        $service->load(['category', 'seo.image']);

        $proof = match ($service->slug) {
            'web' => Work::query()->published()->where('slug', 'laravel-shop')->first(),
            'software' => Work::query()->published()->where('slug', 'personal-system-faraja')->first(),
            default => null,
        };

        return view('services.show', [
            'service' => $service,
            'proof' => $proof,
        ]);
    }
}
