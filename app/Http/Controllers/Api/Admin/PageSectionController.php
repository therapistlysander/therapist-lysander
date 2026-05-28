<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageSectionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = PageSection::query();

        if ($request->has('page')) {
            $query->where('page', $request->page);
        }

        return response()->json([
            'data' => $query->orderBy('page')->orderBy('sort_order')->get(),
        ]);
    }

    public function show(PageSection $pageSection): JsonResponse
    {
        return response()->json($pageSection);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'page'        => ['required', 'string', 'max:50'],
            'section_key' => ['required', 'string', 'max:100', 'unique:page_sections'],
            'label'       => ['nullable', 'string', 'max:150'],
            'content'     => ['required', 'array'],
            'is_active'   => ['boolean'],
            'sort_order'  => ['integer'],
        ]);

        $section = PageSection::create($validated);

        return response()->json($section, 201);
    }

    public function update(Request $request, PageSection $pageSection): JsonResponse
    {
        $validated = $request->validate([
            'page'        => ['sometimes', 'string', 'max:50'],
            'section_key' => ['sometimes', 'string', 'max:100', 'unique:page_sections,section_key,' . $pageSection->id],
            'label'       => ['nullable', 'string', 'max:150'],
            'content'     => ['sometimes', 'array'],
            'is_active'   => ['boolean'],
            'sort_order'  => ['integer'],
        ]);

        $pageSection->update($validated);

        return response()->json($pageSection);
    }

    public function destroy(PageSection $pageSection): JsonResponse
    {
        $pageSection->delete();

        return response()->json(['message' => 'Section deleted.']);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sections'            => ['required', 'array'],
            'sections.*.id'       => ['required', 'integer', 'exists:page_sections,id'],
            'sections.*.sort_order' => ['required', 'integer'],
        ]);

        foreach ($validated['sections'] as $item) {
            PageSection::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'Order updated.']);
    }
}
