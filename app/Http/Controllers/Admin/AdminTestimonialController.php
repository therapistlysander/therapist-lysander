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
        return view('admin.pages.testimonials.form', ['testimonial' => new Testimonial()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'headline'    => 'nullable|string|max:255',
            'body'        => 'required|string',
            'tag'         => 'nullable|string|max:100',
            'rating'      => 'nullable|integer|min:1|max:5',
            'sort_order'  => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_active'   => 'boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active');

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.pages.testimonials.form', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'headline'    => 'nullable|string|max:255',
            'body'        => 'required|string',
            'tag'         => 'nullable|string|max:100',
            'rating'      => 'nullable|integer|min:1|max:5',
            'sort_order'  => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_active'   => 'boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active');

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
