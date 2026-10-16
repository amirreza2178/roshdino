<?php

namespace App\Http\Controllers\Site;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\SavePageRequest;
use App\Models\Media;
use App\Models\Page;
use App\Models\SeoMeta;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PageController extends Controller
{
    public function index(): View
    {
        return view('site.pages.index', [
            'pages' => Page::query()->latest('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('site.pages.form', [
            'page' => new Page(['status' => PublishStatus::Draft]),
            'seo' => new SeoMeta(['indexable' => true]),
            'media' => Media::query()->latest('id')->get(),
        ]);
    }

    public function store(SavePageRequest $request): RedirectResponse
    {
        $page = Page::query()->create($request->safe()->only(['title', 'slug', 'status', 'body']));
        $page->saveSeo($request->seoAttributes());

        return redirect()->route('site.pages.index')->with('saved', 'صفحه ثبت شد.');
    }

    public function edit(Page $page): View
    {
        $page->load('seo');

        return view('site.pages.form', [
            'page' => $page,
            'seo' => $page->seo ?? new SeoMeta(['indexable' => true]),
            'media' => Media::query()->latest('id')->get(),
        ]);
    }

    public function update(SavePageRequest $request, Page $page): RedirectResponse
    {
        $page->update($request->safe()->only(['title', 'slug', 'status', 'body']));
        $page->saveSeo($request->seoAttributes());

        return redirect()->route('site.pages.index')->with('saved', 'صفحه ثبت شد.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('site.pages.index')->with('saved', 'صفحه حذف شد.');
    }
}
