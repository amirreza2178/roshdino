<?php

namespace App\Http\Controllers\Site;

use App\Enums\CategoryType;
use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveServiceRequest;
use App\Models\Category;
use App\Models\Media;
use App\Models\SeoMeta;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('site.services.index', [
            'services' => Service::query()->with('category')->orderBy('position')->orderBy('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('site.services.form', [
            'service' => new Service([
                'status' => PublishStatus::Draft,
                'position' => 0,
                'cta_label' => 'گفتگو',
            ]),
            'seo' => new SeoMeta(['indexable' => true]),
            'categories' => $this->categories(),
            'media' => Media::query()->latest('id')->get(),
        ]);
    }

    public function store(SaveServiceRequest $request): RedirectResponse
    {
        $service = Service::query()->create($this->attributes($request));
        $service->saveSeo($request->seoAttributes());

        return redirect()->route('site.services.index')->with('saved', 'خدمت ثبت شد.');
    }

    public function edit(Service $service): View
    {
        $service->load('seo');

        return view('site.services.form', [
            'service' => $service,
            'seo' => $service->seo ?? new SeoMeta(['indexable' => true]),
            'categories' => $this->categories(),
            'media' => Media::query()->latest('id')->get(),
        ]);
    }

    public function update(SaveServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($this->attributes($request));
        $service->saveSeo($request->seoAttributes());

        return redirect()->route('site.services.index')->with('saved', 'خدمت ثبت شد.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('site.services.index')->with('saved', 'خدمت حذف شد.');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Category>
     */
    private function categories()
    {
        return Category::query()->where('type', CategoryType::Service)->orderBy('name')->get();
    }

    /**
     * @return array<string, mixed>
     */
    private function attributes(SaveServiceRequest $request): array
    {
        return [
            'title' => $request->string('title')->toString(),
            'slug' => $request->string('slug')->toString(),
            'category_id' => $request->filled('category_id') ? $request->integer('category_id') : null,
            'description' => $request->string('description')->toString(),
            'benefits' => $request->lines('benefits'),
            'process' => $request->steps(),
            'cta_label' => $request->string('cta_label')->toString(),
            'cta_url' => $request->filled('cta_url') ? $request->string('cta_url')->toString() : null,
            'interest' => $request->filled('interest') ? $request->string('interest')->toString() : null,
            'status' => $request->string('status')->toString(),
            'position' => $request->integer('position'),
        ];
    }
}
