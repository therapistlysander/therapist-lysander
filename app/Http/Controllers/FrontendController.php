<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\PageSection;
use App\Models\SeoSetting;

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

    public function home()
    {
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $sections = $this->sections('home');
        $seo      = $this->seo('home');

        return view('pages.home', compact('testimonials', 'sections', 'seo'));
    }

    public function about()
    {
        $sections = $this->sections('about');
        $seo      = $this->seo('about');
        return view('pages.about', compact('sections', 'seo'));
    }

    public function approach()
    {
        $sections = $this->sections('approach');
        $seo      = $this->seo('approach');
        return view('pages.approach', compact('sections', 'seo'));
    }

    public function training()
    {
        $sections = $this->sections('training');
        $seo      = $this->seo('training');
        return view('pages.training', compact('sections', 'seo'));
    }

    public function testimonials()
    {
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $featuredTestimonials = $testimonials->where('is_featured', true)->take(3);
        $sections = $this->sections('testimonials');
        $seo      = $this->seo('testimonials');

        return view('pages.testimonials', compact('testimonials', 'featuredTestimonials', 'sections', 'seo'));
    }

    public function fees()
    {
        $sections = $this->sections('fees');
        $seo      = $this->seo('fees');
        return view('pages.fees', compact('sections', 'seo'));
    }

    public function faq()
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

    public function contact()
    {
        $sections = $this->sections('contact');
        $seo      = $this->seo('contact');
        return view('pages.contact', compact('sections', 'seo'));
    }

    public function booking()
    {
        $sections = $this->sections('booking');
        $seo      = $this->seo('booking');
        return view('pages.booking', compact('sections', 'seo'));
    }
}
