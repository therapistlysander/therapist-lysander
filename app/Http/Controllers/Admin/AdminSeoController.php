<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\AdminTableTrait;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class AdminSeoController extends Controller
{
    use AdminTableTrait;

    public function index(Request $request)
    {
        $query = SeoSetting::query();

        // Search
        $this->applySearch($query, ['page_key'], $request->input('search'));

        // Sorting
        $this->applySort($query, ['page_key', 'updated_at'], 'page_key', 'asc');

        $seoSettings = $this->safePaginate($query->paginate($this->getPerPage($request)));

        return view('admin.pages.seo.index', compact('seoSettings'));
    }

    public function edit(string $pageKey)
    {
        $seo = SeoSetting::where('page_key', $pageKey)->firstOrNew(['page_key' => $pageKey]);
        $locales = config('app.supported_locales', ['en', 'nl']);
        return view('admin.pages.seo.edit', compact('seo', 'pageKey', 'locales'));
    }

    public function update(Request $request, string $pageKey)
    {
        $request->validate([
            'canonical_url' => 'nullable|url|max:500',
            'og_image'      => 'nullable|url|max:500',
            'translations'  => 'nullable|array',
        ]);

        $seo = SeoSetting::where('page_key', $pageKey)->firstOrNew(['page_key' => $pageKey]);
        $seo->canonical_url = $request->input('canonical_url');
        $seo->og_image = $request->input('og_image');

        $translations = $request->input('translations', []);
        foreach ($translations as $locale => $data) {
            foreach (['meta_title', 'meta_description', 'og_title', 'og_description'] as $col) {
                if (isset($data[$col])) {
                    $seo->setTranslation($col, $locale, $data[$col] ?: '');
                }
            }
        }

        $seo->save();

        return redirect()->route('admin.seo.index')->with('success', 'SEO settings saved.');
    }
}
