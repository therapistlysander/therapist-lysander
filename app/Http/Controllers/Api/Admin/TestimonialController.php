<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Testimonial::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_name'  => ['required', 'string', 'max:150'],
            'client_title' => ['nullable', 'string', 'max:255'],
            'quote'        => ['required', 'string', 'max:3000'],
            'rating'       => ['integer', 'min:1', 'max:5'],
            'is_featured'  => ['boolean'],
            'is_active'    => ['boolean'],
            'sort_order'   => ['integer'],
        ]);

        $testimonial = Testimonial::create($validated);

        return response()->json($testimonial, 201);
    }

    public function update(Request $request, Testimonial $testimonial): JsonResponse
    {
        $validated = $request->validate([
            'client_name'  => ['sometimes', 'string', 'max:150'],
            'client_title' => ['nullable', 'string', 'max:255'],
            'quote'        => ['sometimes', 'string', 'max:3000'],
            'rating'       => ['integer', 'min:1', 'max:5'],
            'is_featured'  => ['boolean'],
            'is_active'    => ['boolean'],
            'sort_order'   => ['integer'],
        ]);

        $testimonial->update($validated);

        return response()->json($testimonial);
    }

    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $testimonial->delete();

        return response()->json(['message' => 'Testimonial deleted.']);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'testimonials'              => ['required', 'array'],
            'testimonials.*.id'         => ['required', 'integer', 'exists:testimonials,id'],
            'testimonials.*.sort_order' => ['required', 'integer'],
        ]);

        foreach ($validated['testimonials'] as $item) {
            Testimonial::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'Order updated.']);
    }
}
