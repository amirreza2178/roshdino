<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveSiteSettingRequest;
use App\Models\Media;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('site.settings.edit', [
            'settings' => SiteSetting::query()->pluck('value', 'key'),
            'media' => Media::query()->latest('id')->get(),
        ]);
    }

    public function update(SaveSiteSettingRequest $request): RedirectResponse
    {
        SiteSetting::put($request->safe()->only(SiteSetting::keys()));

        return redirect()->route('site.settings.edit')->with('saved', 'تنظیمات ثبت شد.');
    }
}
