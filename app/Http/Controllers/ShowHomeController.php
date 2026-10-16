<?php

namespace App\Http\Controllers;

use App\Enums\LeadIntent;
use App\Enums\LeadServiceInterest;
use App\Models\HomePage;
use App\Models\Service;
use App\Models\Work;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ShowHomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('home', [
            'intent' => LeadIntent::tryFrom((string) $request->query('intent', '')) ?? LeadIntent::Request,
            'prefillService' => LeadServiceInterest::tryFrom((string) $request->query('service', '')),
            'home' => HomePage::present(),
            'services' => Service::query()->published()->orderBy('position')->orderBy('id')->get(),
            'works' => Work::query()->published()->with(['images.media'])->orderBy('position')->orderBy('id')->get(),
        ]);
    }
}
