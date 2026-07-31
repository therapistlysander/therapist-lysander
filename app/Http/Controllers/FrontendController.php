<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\PageSection;
use App\Models\SeoSetting;
use App\Models\SiteSetting;

class FrontendController extends Controller
{
    /**
     * Load active page sections for a given page, keyed by section_key.
     */
    private function sections(string $page): \Illuminate\Support\Collection
    {
        return PageSection::where('page', $page)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->keyBy('section_key');
    }

    /**
     * Load SEO settings for a page key, returns an array with null defaults.
     */
    private function seo(string $pageKey): ?SeoSetting
    {
        return SeoSetting::where('page_key', $pageKey)->first();
    }

    /**
     * Load endorsement settings as [key => [en => ..., nl => ...]].
     */
    private function endorsementSettings(): array
    {
        return SiteSetting::where('group', 'endorsement')
            ->pluck('value', 'key')
            ->map(fn($v) => json_decode($v, true) ?: [])
            ->toArray();
    }

    public function home(string $locale)
    {
        $testimonials = Testimonial::client()
            ->featured()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $endorsement = Testimonial::endorsement()
            ->active()
            ->orderBy('sort_order')
            ->first();

        $sections = $this->sections('home');
        $seo      = $this->seo('home');
        $endorsementSettings = $this->endorsementSettings();

        return view('pages.home', compact('testimonials', 'endorsement', 'sections', 'seo', 'endorsementSettings', 'locale'));
    }

    public function about(string $locale)
    {
        $sections = $this->sections('about');
        $seo      = $this->seo('about');
        return view('pages.about', compact('sections', 'seo'));
    }

    public function approach(string $locale)
    {
        $sections = $this->sections('approach');
        $seo      = $this->seo('approach');
        return view('pages.approach', compact('sections', 'seo'));
    }

    public function training(string $locale)
    {
        $sections = $this->sections('training');
        $seo      = $this->seo('training');
        return view('pages.training', compact('sections', 'seo'));
    }

    public function testimonials(string $locale)
    {
        $testimonials = Testimonial::client()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $endorsements = Testimonial::endorsement()
            ->active()
            ->orderBy('sort_order')
            ->get();

        $sections = $this->sections('testimonials');
        $seo      = $this->seo('testimonials');
        $endorsementSettings = $this->endorsementSettings();

        return view('pages.testimonials', compact('testimonials', 'endorsements', 'sections', 'seo', 'endorsementSettings', 'locale'));
    }

    public function fees(string $locale)
    {
        $sections = $this->sections('fees');
        $seo      = $this->seo('fees');
        return view('pages.fees', compact('sections', 'seo'));
    }

    public function faq(string $locale)
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        $sections = $this->sections('faq');
        $seo      = $this->seo('faq');

        return view('pages.faq', compact('faqs', 'sections', 'seo'));
    }

    public function contact(string $locale)
    {
        $sections = $this->sections('contact');
        $seo      = $this->seo('contact');
        return view('pages.contact', compact('sections', 'seo'));
    }

    public function booking(string $locale)
    {
        $sections = $this->sections('booking');
        $seo      = $this->seo('booking');
        return view('pages.booking', compact('sections', 'seo'));
    }

    public function privacy(string $locale)
    {
        $sections = $this->sections('privacy');
        $seo      = $this->seo('privacy');
        return view('pages.privacy', compact('sections', 'seo'));
    }

    public function terms(string $locale)
    {
        $sections = $this->sections('terms');
        $seo      = $this->seo('terms');
        return view('pages.terms', compact('sections', 'seo'));
    }
}
