<?php

namespace App\Http\Controllers\Site;

use App\Enums\CategoryType;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('site.categories.index', [
            'categories' => Category::query()->orderBy('type')->orderBy('name')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('site.categories.form', [
            'category' => new Category(['type' => CategoryType::Service]),
        ]);
    }

    public function store(SaveCategoryRequest $request): RedirectResponse
    {
        Category::query()->create($request->safe()->only(['name', 'slug', 'type']));

        return redirect()->route('site.categories.index')->with('saved', 'دسته ثبت شد.');
    }

    public function edit(Category $category): View
    {
        return view('site.categories.form', [
            'category' => $category,
        ]);
    }

    public function update(SaveCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->safe()->only(['name', 'slug', 'type']));

        return redirect()->route('site.categories.index')->with('saved', 'دسته ثبت شد.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->services()->exists() || $category->works()->exists()) {
            return redirect()->route('site.categories.index')->withErrors([
                'category' => 'این دسته هنوز به خدمت یا نمونه‌کار وصل است.',
            ]);
        }

        $category->delete();

        return redirect()->route('site.categories.index')->with('saved', 'دسته حذف شد.');
    }
}
