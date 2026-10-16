<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Services\LeadService;
use Illuminate\Http\RedirectResponse;

class StoreLeadController extends Controller
{
    public function __invoke(StoreLeadRequest $request, LeadService $leads): RedirectResponse
    {
        $leads->capture($request);

        return redirect()
            ->to($request->contactUrl())
            ->with('lead_received', true);
    }
}
