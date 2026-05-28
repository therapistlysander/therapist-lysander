<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class AdminSeoController extends Controller
{
    public function index()
    {
        $seoSettings = SeoSetting::orderBy('page_key')->get();
        return view('admin.pages.seo.index', compact('seoSettings'));
    }

    public function edit(string $pageKey)
    {
        $seo = SeoSetting::where('page_key', $pageKey)->firstOrNew(['page_key' => $pageKey]);
        return view('admin.pages.seo.edit', compact('seo', 'pageKey'));
    }

    public function update(Request $request, string $pageKey)
    {
        $request->validate([
            'title'            => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_title'         => 'nullable|string|max:255',
            'og_description'   => 'nullable|string|max:500',
            'canonical_url'    => 'nullable|url|max:500',
        ]);

        SeoSetting::updateOrCreate(
            ['page_key' => $pageKey],
            $request->only(['title', 'meta_description', 'og_title', 'og_description', 'canonical_url'])
        );

        return redirect()->route('admin.seo.index')->with('success', 'SEO settings saved.');
    }
}
