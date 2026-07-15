<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\DataTableTrait;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    use DataTableTrait;

    public function index(Request $request)
    {
        $query = Faq::query();

        $this->applySearch($query, $request->get('search'), ['question']);
        $this->applyFilter($query, 'category', $request->get('category'));
        $this->applyFilter($query, 'is_active', $request->get('is_active'));
        $this->applySort($query, 'sort_order', ['sort_order', 'category', 'is_active', 'created_at']);

        $faqs = $this->paginateResults($query);

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
            'translations.en.question' => 'required|string',
            'translations.en.answer'   => 'required|string',
            'translations.nl.question' => 'nullable|string',
            'translations.nl.answer'   => 'nullable|string',
        ]);

        $faq = new Faq();
        $faq->category   = $request->input('category');
        $faq->sort_order = $request->input('sort_order', 0);
        $faq->is_active  = $request->boolean('is_active');

        $translations = $request->input('translations', []);

        // English is always required
        $faq->setTranslation('question', 'en', $translations['en']['question'] ?? '');
        $faq->setTranslation('answer', 'en', $translations['en']['answer'] ?? '');

        // Dutch is optional — pre-fill with English if not provided
        $nlQuestion = $translations['nl']['question'] ?? $translations['en']['question'];
        $nlAnswer   = $translations['nl']['answer']   ?? $translations['en']['answer'];

        if (!empty($nlQuestion)) {
            $faq->setTranslation('question', 'nl', $nlQuestion);
        }
        if (!empty($nlAnswer)) {
            $faq->setTranslation('answer', 'nl', $nlAnswer);
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
            'translations.en.question' => 'required|string',
            'translations.en.answer'   => 'required|string',
            'translations.nl.question' => 'nullable|string',
            'translations.nl.answer'   => 'nullable|string',
        ]);

        $faq->category   = $request->input('category');
        $faq->sort_order = $request->input('sort_order', 0);
        $faq->is_active  = $request->boolean('is_active');

        $translations = $request->input('translations', []);

        // English is always required
        $faq->setTranslation('question', 'en', $translations['en']['question'] ?? '');
        $faq->setTranslation('answer', 'en', $translations['en']['answer'] ?? '');

        // Dutch is optional — only update if provided
        if (!empty($translations['nl']['question'])) {
            $faq->setTranslation('question', 'nl', $translations['nl']['question']);
        }
        if (!empty($translations['nl']['answer'])) {
            $faq->setTranslation('answer', 'nl', $translations['nl']['answer']);
        }

        $faq->save();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:faqs,id']);
        Faq::whereIn('id', $request->ids)->delete();
        return redirect()->route('admin.faqs.index')->with('success', count($request->ids) . ' FAQ(s) deleted.');
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
