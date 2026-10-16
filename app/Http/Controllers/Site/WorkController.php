<?php

namespace App\Http\Controllers\Site;

use App\Enums\CategoryType;
use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveWorkRequest;
use App\Models\Category;
use App\Models\Media;
use App\Models\SeoMeta;
use App\Models\Work;
use App\Services\ImageStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class WorkController extends Controller
{
    public function __construct(private ImageStorage $images) {}

    public function index(): View
    {
        return view('site.works.index', [
            'works' => Work::query()->with('category')->orderBy('position')->orderBy('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('site.works.form', [
            'work' => new Work([
                'status' => PublishStatus::Draft,
                'position' => 0,
            ]),
            'seo' => new SeoMeta(['indexable' => true]),
            'categories' => $this->categories(),
            'media' => Media::query()->latest('id')->get(),
        ]);
    }

    public function store(SaveWorkRequest $request): RedirectResponse
    {
        $work = Work::query()->create($this->attributes($request));
        $work->saveSeo($request->seoAttributes());
        $this->syncImages($request, $work);

        return redirect()->route('site.works.index')->with('saved', 'نمونه‌کار ثبت شد.');
    }

    public function edit(Work $work): View
    {
        $work->load(['seo', 'images.media']);

        return view('site.works.form', [
            'work' => $work,
            'seo' => $work->seo ?? new SeoMeta(['indexable' => true]),
            'categories' => $this->categories(),
            'media' => Media::query()->latest('id')->get(),
        ]);
    }

    public function update(SaveWorkRequest $request, Work $work): RedirectResponse
    {
        $work->update($this->attributes($request));
        $work->saveSeo($request->seoAttributes());
        $this->syncImages($request, $work);

        return redirect()->route('site.works.index')->with('saved', 'نمونه‌کار ثبت شد.');
    }

    public function destroy(Work $work): RedirectResponse
    {
        $work->delete();

        return redirect()->route('site.works.index')->with('saved', 'نمونه‌کار حذف شد.');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Category>
     */
    private function categories()
    {
        return Category::query()->where('type', CategoryType::Work)->orderBy('name')->get();
    }

    /**
     * @return array<string, mixed>
     */
    private function attributes(SaveWorkRequest $request): array
    {
        return [
            'title' => $request->string('title')->toString(),
            'summary' => $request->filled('summary') ? $request->string('summary')->toString() : null,
            'slug' => $request->string('slug')->toString(),
            'category_id' => $request->filled('category_id') ? $request->integer('category_id') : null,
            'problem' => $request->string('problem')->toString(),
            'solution' => $request->string('solution')->toString(),
            'technologies' => $request->lines('technologies'),
            'result' => $request->filled('result') ? $request->string('result')->toString() : null,
            'learning' => $request->filled('learning') ? $request->string('learning')->toString() : null,
            'status' => $request->string('status')->toString(),
            'position' => $request->integer('position'),
        ];
    }

    private function syncImages(SaveWorkRequest $request, Work $work): void
    {
        $remove = collect($request->input('remove_images', []))
            ->map(fn ($id): int => (int) $id)
            ->filter()
            ->all();

        if ($remove !== []) {
            $work->images()->whereIn('id', $remove)->delete();
        }

        foreach ($request->input('alts', []) as $id => $alt) {
            $work->images()->whereKey((int) $id)->update([
                'alt' => filled($alt) ? mb_substr(trim((string) $alt), 0, 160) : null,
            ]);
        }

        if ($request->hasFile('image')) {
            $media = $this->images->store($request->file('image'));
            $next = ((int) $work->images()->max('position')) + 1;
            $work->images()->create([
                'media_id' => $media->id,
                'alt' => $request->filled('image_alt') ? $request->string('image_alt')->toString() : null,
                'position' => $next,
            ]);
        }
    }
}
