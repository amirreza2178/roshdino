<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadConversationRequest;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class StoreLeadConversationController extends Controller
{
    public function __invoke(StoreLeadConversationRequest $request, Lead $lead): RedirectResponse
    {
        $lead->conversations()->create([
            'conversed_at' => Carbon::parse($request->validated('conversed_at'), 'Asia/Tehran')->utc(),
            'body' => $request->validated('body'),
        ]);

        return redirect()
            ->route('leads.index')
            ->with('conversation_saved', $lead->id);
    }
}
