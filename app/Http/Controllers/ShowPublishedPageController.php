<?php

namespace App\Http\Controllers;

use App\Enums\LeadIntent;
use App\Enums\LeadServiceInterest;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ShowPublishedPageController extends Controller
{
    public function __invoke(Request $request, string $slug): View
    {
        $page = Page::query()->published()->where('slug', $slug)->firstOrFail();
        $page->load('seo.image');

        return view('pages.show', [
            'page' => $page,
            'intent' => LeadIntent::tryFrom((string) $request->query('intent', '')) ?? LeadIntent::Request,
            'prefillService' => LeadServiceInterest::tryFrom((string) $request->query('service', '')),
            'services' => $slug === 'about'
                ? Service::query()->published()->orderBy('position')->orderBy('id')->get()
                : collect(),
        ]);
    }
}
