<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaRequest;
use App\Models\Media;
use App\Services\ImageStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MediaController extends Controller
{
    public function __construct(private ImageStorage $images) {}

    public function index(): View
    {
        return view('site.media.index', [
            'media' => Media::query()->latest('id')->paginate(20),
        ]);
    }

    public function store(StoreMediaRequest $request): RedirectResponse
    {
        $this->images->store($request->file('image'));

        return redirect()->route('site.media.index')->with('saved', 'تصویر ثبت شد.');
    }

    public function destroy(Media $medium): RedirectResponse
    {
        if ($medium->isInUse()) {
            return redirect()->route('site.media.index')->withErrors([
                'image' => 'این تصویر هنوز در نمونه‌کار یا سئو استفاده می‌شود.',
            ]);
        }

        $medium->deleteFile();
        $medium->delete();

        return redirect()->route('site.media.index')->with('saved', 'تصویر حذف شد.');
    }
}
