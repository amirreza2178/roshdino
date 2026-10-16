<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadMeetingRequest;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class StoreLeadMeetingController extends Controller
{
    public function __invoke(StoreLeadMeetingRequest $request, Lead $lead): RedirectResponse
    {
        $lead->meetings()->create([
            'meets_at' => Carbon::parse($request->validated('meets_at'), 'Asia/Tehran')->utc(),
        ]);

        return redirect()
            ->route('leads.index')
            ->with('meeting_saved', $lead->id);
    }
}
