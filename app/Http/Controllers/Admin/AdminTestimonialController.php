<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\DataTableTrait;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class AdminTestimonialController extends Controller
{
    use DataTableTrait;

    public function index(Request $request)
    {
        try {
            $query = Testimonial::query();

            $this->applySearch($query, $request->get('search'), ['client_name', 'headline', 'body', 'tag']);
            $this->applyFilter($query, 'type', $request->get('type'));
            $this->applyFilter($query, 'is_active', $request->get('is_active'));
            $this->applyFilter($query, 'is_featured', $request->get('is_featured'));
            $this->applySort($query, 'sort_order', ['sort_order', 'client_name', 'created_at', 'is_active']);

            $testimonials = $this->paginateResults($query);

            // Extract string values from translatable fields for views
            foreach ($testimonials as $t) {
                $t->headline_str = $this->getTranslatableString($t->getRawOriginal('headline'));
                $t->body_str = $this->getTranslatableString($t->getRawOriginal('body'));
            }

            return view('admin.pages.testimonials.index', compact('testimonials'));
        } catch (\Throwable $e) {
            \Log::error('Testimonials index error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Testimonials error: ' . $e->getMessage());
        }
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
            'type'         => 'nullable|string|in:client,endorsement',
            'tag'          => 'nullable|string|max:100',
            'rating'       => 'nullable|integer|min:1|max:5',
            'sort_order'   => 'nullable|integer',
            'is_featured'  => 'boolean',
            'is_active'    => 'boolean',
            'translations' => 'nullable|array',
        ]);

        $t = new Testimonial();
        $t->client_name = $request->input('client_name');
        $t->type        = $request->input('type', 'client');
        $t->tag         = $request->input('tag');
        $t->rating      = $request->input('rating', 5);
        $t->sort_order  = $request->input('sort_order', 0);
        $t->is_featured = $request->boolean('is_featured');
        $t->is_active   = $request->boolean('is_active');

        $translations = $request->input('translations', []);
        foreach ($translations as $locale => $data) {
            $t->setTranslation('headline', $locale, $data['headline'] ?? '');
            $t->setTranslation('short_description', $locale, $data['short_description'] ?? '');
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
            'type'         => 'nullable|string|in:client,endorsement',
            'tag'          => 'nullable|string|max:100',
            'rating'       => 'nullable|integer|min:1|max:5',
            'sort_order'   => 'nullable|integer',
            'is_featured'  => 'boolean',
            'is_active'    => 'boolean',
            'translations' => 'nullable|array',
        ]);

        $testimonial->client_name = $request->input('client_name');
        $testimonial->type        = $request->input('type', 'client');
        $testimonial->tag         = $request->input('tag');
        $testimonial->rating      = $request->input('rating', 5);
        $testimonial->sort_order  = $request->input('sort_order', 0);
        $testimonial->is_featured = $request->boolean('is_featured');
        $testimonial->is_active   = $request->boolean('is_active');

        $translations = $request->input('translations', []);
        foreach ($translations as $locale => $data) {
            $testimonial->setTranslation('headline', $locale, $data['headline'] ?? '');
            $testimonial->setTranslation('short_description', $locale, $data['short_description'] ?? '');
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

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:testimonials,id']);
        Testimonial::whereIn('id', $request->ids)->delete();
        return redirect()->route('admin.testimonials.index')->with('success', count($request->ids) . ' testimonial(s) deleted.');
    }
}
