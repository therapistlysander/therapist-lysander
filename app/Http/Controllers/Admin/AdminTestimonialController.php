<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class AdminTestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('admin.pages.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        $locales = config('app.supported_locales', ['en', 'nl']);
        return view('admin.pages.testimonials.form', ['testimonial' => new Testimonial(), 'locales' => $locales]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name'  => 'required|string|max:255',
            'tag'          => 'nullable|string|max:100',
            'rating'       => 'nullable|integer|min:1|max:5',
            'sort_order'   => 'nullable|integer',
            'is_featured'  => 'boolean',
            'is_active'    => 'boolean',
            'translations' => 'nullable|array',
        ]);

        $t = new Testimonial();
        $t->client_name = $request->input('client_name');
        $t->tag         = $request->input('tag');
        $t->rating      = $request->input('rating', 5);
        $t->sort_order  = $request->input('sort_order', 0);
        $t->is_featured = $request->boolean('is_featured');
        $t->is_active   = $request->boolean('is_active');

        $translations = $request->input('translations', []);
        foreach ($translations as $locale => $data) {
            $t->setTranslation('headline', $locale, $data['headline'] ?? '');
            $t->setTranslation('body', $locale, $data['body'] ?? '');
            $t->setTranslation('quote', $locale, $data['quote'] ?? '');
        }

        $t->save();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial)
    {
        $locales = config('app.supported_locales', ['en', 'nl']);
        return view('admin.pages.testimonials.form', compact('testimonial', 'locales'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'client_name'  => 'required|string|max:255',
            'tag'          => 'nullable|string|max:100',
            'rating'       => 'nullable|integer|min:1|max:5',
            'sort_order'   => 'nullable|integer',
            'is_featured'  => 'boolean',
            'is_active'    => 'boolean',
            'translations' => 'nullable|array',
        ]);

        $testimonial->client_name = $request->input('client_name');
        $testimonial->tag         = $request->input('tag');
        $testimonial->rating      = $request->input('rating', 5);
        $testimonial->sort_order  = $request->input('sort_order', 0);
        $testimonial->is_featured = $request->boolean('is_featured');
        $testimonial->is_active   = $request->boolean('is_active');

        $translations = $request->input('translations', []);
        foreach ($translations as $locale => $data) {
            $testimonial->setTranslation('headline', $locale, $data['headline'] ?? '');
            $testimonial->setTranslation('body', $locale, $data['body'] ?? '');
            $testimonial->setTranslation('quote', $locale, $data['quote'] ?? '');
        }

        $testimonial->save();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
