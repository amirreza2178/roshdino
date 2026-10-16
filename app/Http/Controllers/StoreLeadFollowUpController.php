<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadFollowUpRequest;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class StoreLeadFollowUpController extends Controller
{
    public function __invoke(StoreLeadFollowUpRequest $request, Lead $lead): RedirectResponse
    {
        $lead->followUps()->create([
            'followed_up_at' => Carbon::parse($request->validated('followed_up_at'), 'Asia/Tehran')->utc(),
            'channel' => $request->validated('channel'),
            'note' => $request->validated('note'),
        ]);

        return redirect()
            ->route('leads.index')
            ->with('follow_up_saved', $lead->id);
    }
}
