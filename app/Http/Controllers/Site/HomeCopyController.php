<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveHomePageRequest;
use App\Models\HomePage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class HomeCopyController extends Controller
{
    public function edit(): View
    {
        return view('site.home.edit', [
            'home' => HomePage::withBlankSlots(HomePage::present()),
        ]);
    }

    public function update(SaveHomePageRequest $request): RedirectResponse
    {
        $page = HomePage::query()->first() ?? new HomePage;
        $page->story = $request->validated();
        $page->save();

        return redirect()
            ->route('site.home-copy.edit')
            ->with('saved', 'متن صفحهٔ اصلی ثبت شد.');
    }
}
