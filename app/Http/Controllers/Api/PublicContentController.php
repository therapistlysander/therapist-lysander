<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use App\Models\SeoSetting;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;

class PublicContentController extends Controller
{
    /**
     * Full homepage payload: sections + SEO + featured testimonials.
     */
    public function homepage(): JsonResponse
    {
        $sections = PageSection::where('page', 'home')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->keyBy('section_key');

        $seo = SeoSetting::where('page_key', 'home')->first();

        $testimonials = Testimonial::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->get(['id', 'client_name', 'client_title', 'quote', 'rating']);

        return response()->json([
            'sections'     => $sections,
            'seo'          => $seo,
            'testimonials' => $testimonials,
        ]);
    }

    /**
     * SEO data for a specific page.
     */
    public function pageSeo(string $pageKey): JsonResponse
    {
        $seo = SeoSetting::where('page_key', $pageKey)->first();

        if (! $seo) {
            return response()->json(['message' => 'SEO settings not found for this page.'], 404);
        }

        return response()->json($seo);
    }

    /**
     * All active testimonials for public display.
     */
    public function testimonials(): JsonResponse
    {
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'client_name', 'client_title', 'quote', 'rating', 'is_featured']);

        return response()->json(['data' => $testimonials]);
    }

    /**
     * All active FAQs, grouped by category.
     */
    public function faqs(): JsonResponse
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get(['id', 'category', 'question', 'answer'])
            ->groupBy('category');

        return response()->json(['data' => $faqs]);
    }

    /**
     * Public site settings (contact info, social links etc.) — non-sensitive only.
     */
    public function settings(): JsonResponse
    {
        $settings = SiteSetting::whereIn('group', ['general', 'social', 'contact'])
            ->get(['group', 'key', 'value', 'type', 'label'])
            ->groupBy('group');

        return response()->json(['data' => $settings]);
    }
}
