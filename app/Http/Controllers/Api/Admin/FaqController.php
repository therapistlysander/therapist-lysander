<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Faq::orderBy('category')->orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category'   => ['required', 'string', 'max:100'],
            'question'   => ['required', 'string', 'max:500'],
            'answer'     => ['required', 'string'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer'],
        ]);

        $faq = Faq::create($validated);

        return response()->json($faq, 201);
    }

    public function update(Request $request, Faq $faq): JsonResponse
    {
        $validated = $request->validate([
            'category'   => ['sometimes', 'string', 'max:100'],
            'question'   => ['sometimes', 'string', 'max:500'],
            'answer'     => ['sometimes', 'string'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer'],
        ]);

        $faq->update($validated);

        return response()->json($faq);
    }

    public function destroy(Faq $faq): JsonResponse
    {
        $faq->delete();

        return response()->json(['message' => 'FAQ deleted.']);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'faqs'              => ['required', 'array'],
            'faqs.*.id'         => ['required', 'integer', 'exists:faqs,id'],
            'faqs.*.sort_order' => ['required', 'integer'],
        ]);

        foreach ($validated['faqs'] as $item) {
            Faq::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'Order updated.']);
    }
}
