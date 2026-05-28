<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeoSettingController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => SeoSetting::all()]);
    }

    public function upsert(Request $request, string $pageKey): JsonResponse
    {
        $validated = $request->validate([
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'og_title'         => ['nullable', 'string', 'max:255'],
            'og_description'   => ['nullable', 'string', 'max:500'],
            'og_image'         => ['nullable', 'string', 'max:500'],
            'canonical_url'    => ['nullable', 'url', 'max:500'],
            'extra'            => ['nullable', 'array'],
        ]);

        $seo = SeoSetting::updateOrCreate(
            ['page_key' => $pageKey],
            $validated
        );

        return response()->json($seo);
    }
}

