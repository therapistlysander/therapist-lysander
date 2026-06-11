<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('category')->orderBy('sort_order')->get();
        return view('admin.pages.faqs.index', compact('faqs'));
    }

    public function create()
    {
        $categories = $this->getCategoriesFromCms();
        $locales = config('app.supported_locales', ['en', 'nl']);
        return view('admin.pages.faqs.form', ['faq' => new Faq(), 'categories' => $categories, 'locales' => $locales]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'   => 'required|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'boolean',
            'translations' => 'nullable|array',
        ]);

        $faq = new Faq();
        $faq->category   = $request->input('category');
        $faq->sort_order = $request->input('sort_order', 0);
        $faq->is_active  = $request->boolean('is_active');

        $translations = $request->input('translations', []);
        foreach ($translations as $locale => $data) {
            if (!empty($data['question'])) {
                $faq->setTranslation('question', $locale, $data['question']);
            }
            if (!empty($data['answer'])) {
                $faq->setTranslation('answer', $locale, $data['answer']);
            }
        }

        $faq->save();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created.');
    }

    public function edit(Faq $faq)
    {
        $categories = $this->getCategoriesFromCms();
        $locales = config('app.supported_locales', ['en', 'nl']);
        return view('admin.pages.faqs.form', compact('faq', 'categories', 'locales'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'category'   => 'required|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'boolean',
            'translations' => 'nullable|array',
        ]);

        $faq->category   = $request->input('category');
        $faq->sort_order = $request->input('sort_order', 0);
        $faq->is_active  = $request->boolean('is_active');

        $translations = $request->input('translations', []);
        foreach ($translations as $locale => $data) {
            if (!empty($data['question'])) {
                $faq->setTranslation('question', $locale, $data['question']);
            }
            if (!empty($data['answer'])) {
                $faq->setTranslation('answer', $locale, $data['answer']);
            }
        }

        $faq->save();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }

    private function getCategoriesFromCms(): array
    {
        $section = \App\Models\PageSection::where('page', 'faq')
            ->where('section_key', 'faq_categories')
            ->first();

        $cmsCategories = $section?->content['categories'] ?? [];

        if (!empty($cmsCategories)) {
            return collect($cmsCategories)->mapWithKeys(function ($cat) {
                return [$cat['key'] => $cat['label']];
            })->toArray();
        }

        // Fallback if CMS section doesn't exist yet
        return [
            'therapy_emdr' => 'Therapy & EMDR',
            'starting_therapy' => 'Starting Therapy',
            'practical' => 'Practical Information',
            'sessions_progress' => 'Sessions & Progress',
        ];
    }
}
