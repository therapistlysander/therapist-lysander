<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\AdminTableTrait;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminPageSectionController extends Controller
{
    use AdminTableTrait;
    private array $pageLabels = [
        'home'         => 'Home',
        'about'        => 'About',
        'approach'     => 'Trauma & Approach',
        'training'     => 'Clinical Training',
        'testimonials' => 'Testimonials',
        'fees'         => 'Fees & Process',
        'faq'          => 'FAQ',
        'contact'      => 'Contact',
        'booking'      => 'Booking',
        'privacy'      => 'Privacy Policy',
        'terms'        => 'Terms & Conditions',
    ];

    private array $pageRoutes = [
        'home'         => '/',
        'about'        => '/about',
        'approach'     => '/trauma-approach',
        'training'     => '/clinical-training',
        'testimonials' => '/testimonials',
        'fees'         => '/fees-process',
        'faq'          => '/faq',
        'contact'      => '/contact',
        'booking'      => '/booking',
        'privacy'      => '/privacy',
        'terms'        => '/terms',
    ];

    public function pages()
    {
        $pageOrder = array_keys($this->pageLabels);

        $pages = PageSection::select('page')
            ->selectRaw('COUNT(*) as sections_count')
            ->selectRaw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count')
            ->groupBy('page')
            ->get()
            ->map(fn($p) => (object) [
                'key'            => $p->page,
                'label'          => $this->pageLabels[$p->page] ?? ucfirst($p->page),
                'route'          => $this->pageRoutes[$p->page] ?? '/' . $p->page,
                'sections_count' => $p->sections_count,
                'active_count'   => $p->active_count,
                'sort'           => array_search($p->page, $pageOrder),
            ])
            ->sortBy('sort')
            ->values();

        return view('admin.pages.sections.pages', compact('pages'));
    }

    public function index(string $page, Request $request)
    {
        $query = PageSection::where('page', $page);

        // Search
        $this->applySearch($query, ['label', 'section_key'], $request->input('search'));

        // Filters
        $this->applyFilters($query, ['status' => 'is_active']);

        // Sorting
        $this->applySort($query, ['sort_order', 'label', 'is_active', 'section_key'], 'sort_order', 'asc');

        $sections = $this->safePaginate($query->paginate($this->getPerPage($request, 50)));

        if ($sections->isEmpty() && !$request->hasAny(['search', 'status', 'sort'])) {
            abort(404);
        }

        $pageLabel = $this->pageLabels[$page] ?? ucfirst($page);
        $pageRoute = $this->pageRoutes[$page] ?? '/' . $page;

        return view('admin.pages.sections.index', compact('sections', 'page', 'pageLabel', 'pageRoute'));
    }

    public function edit(PageSection $section)
    {
        $mediaFiles = collect(Storage::disk('public')->files('media'))
            ->map(fn($path) => [
                'path'     => $path,
                'filename' => basename($path),
                'url'      => '/storage/' . $path,
                'size'     => Storage::disk('public')->size($path),
            ])
            ->sortBy('filename')
            ->values();

        $locales = config('app.supported_locales', ['en', 'nl']);

        // Get per-locale content for the form
        $localeContent = [];
        foreach ($locales as $locale) {
            $localeContent[$locale] = $section->getTranslation('content', $locale) ?? [];
        }

        return view('admin.pages.sections.edit', compact('section', 'mediaFiles', 'locales', 'localeContent'));
    }

    public function update(Request $request, PageSection $section)
    {
        $request->validate([
            'is_active'       => 'boolean',
            'translations'    => 'nullable|array',
            'image'           => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,svg|max:5120',
            'media_image_url' => 'nullable|string|max:500',
            'remove_image'    => 'nullable|boolean',
        ]);

        $locales = config('app.supported_locales', ['en', 'nl']);
        $translations = $request->input('translations', []);

        // Text field mapping: form input key => possible content keys
        $textFields = [
            'title'              => ['title', 'heading'],
            'subtitle'           => ['subtitle', 'subheading'],
            'body'               => ['body'],
            'cta_text'           => ['cta_text', 'cta_label', 'cta_primary_label'],
            'cta_url'            => ['cta_url', 'cta_primary_url'],
            'cta_secondary_text' => ['cta_secondary_text', 'cta_secondary_label'],
            'cta_secondary_url'  => ['cta_secondary_url'],
        ];

        // Extra scalar fields
        $extraFields = ['fee_amount', 'fee_duration', 'quote', 'attribution', 'whatsapp_number', 'whatsapp_text', 'email'];

        // Array/repeater schemas
        $arraySchemas = [
            'steps'  => ['title', 'description', 'duration', 'badge'],
            'items'  => ['title', 'description', 'key', 'label', 'value', 'tab_label', 'heading'],
            'cards'  => ['title', 'subtitle', 'description'],
            'stats'  => ['value', 'label'],
            'groups' => ['title'],
        ];

        foreach ($locales as $locale) {
            $locData = $translations[$locale] ?? [];
            // Start with existing content for this locale
            $content = $section->getTranslation('content', $locale) ?? [];

            // ── Text fields ──
            foreach ($textFields as $input => $contentKeys) {
                if (isset($locData[$input])) {
                    $written = false;
                    foreach ($contentKeys as $key) {
                        if (array_key_exists($key, $content)) {
                            $content[$key] = $locData[$input] ?: null;
                            $written = true;
                            break;
                        }
                    }
                    if (!$written) {
                        $content[$contentKeys[0]] = $locData[$input] ?: null;
                    }
                }
            }

            // ── Array/repeater fields ──
            foreach ($arraySchemas as $field => $subFields) {
                if (isset($locData[$field])) {
                    $raw = $locData[$field];
                    $cleaned = [];

                    foreach ($raw as $row) {
                        if (!is_array($row)) continue;

                        if ($field === 'groups') {
                            $title = trim($row['title'] ?? '');
                            if ($title === '') continue;
                            $nestedItems = [];
                            foreach (($row['items'] ?? []) as $item) {
                                if (is_array($item) && trim($item['title'] ?? '') !== '') {
                                    $nestedItems[] = ['title' => trim($item['title'])];
                                }
                            }
                            $cleaned[] = ['title' => $title, 'items' => $nestedItems];
                            continue;
                        }

                        $hasContent = false;
                        $cleanedRow = [];
                        foreach ($subFields as $sf) {
                            $val = trim($row[$sf] ?? '');
                            if ($val !== '') $hasContent = true;
                            $cleanedRow[$sf] = $val ?: null;
                        }
                        if ($hasContent) {
                            $cleaned[] = array_filter($cleanedRow, fn($v) => $v !== null);
                        }
                    }

                    $content[$field] = $cleaned;
                }
            }

            // ── Extra scalar fields ──
            foreach ($extraFields as $ef) {
                if (isset($locData[$ef])) {
                    $content[$ef] = $locData[$ef] ?: null;
                }
            }

            $section->setTranslation('content', $locale, $content);
        }

        // ── Image handling (shared across locales — stored in English content) ──
        $enContent = $section->getTranslation('content', 'en') ?? [];

        if ($request->boolean('remove_image')) {
            $oldImg = $enContent['image'] ?? null;
            if ($oldImg && Str::contains($oldImg, '/storage/media/')) {
                $relativePath = 'media/' . basename($oldImg);
                Storage::disk('public')->delete($relativePath);
            }
            $enContent['image'] = null;
        } elseif ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                  . '-' . time()
                  . '.' . $file->getClientOriginalExtension();
            $file->storeAs('media', $name, 'public');
            $enContent['image'] = '/storage/media/' . $name;
        } elseif ($request->filled('media_image_url')) {
            $enContent['image'] = $request->input('media_image_url');
        }

        $section->setTranslation('content', 'en', $enContent);

        // Also update image in NL content if it exists
        $nlContent = $section->getTranslation('content', 'nl') ?? [];
        if (!empty($nlContent) && isset($enContent['image'])) {
            $nlContent['image'] = $enContent['image'];
            $section->setTranslation('content', 'nl', $nlContent);
        }

        // Note: categories are NOT synced EN→NL. Each locale manages its own labels
        // via the admin form. The public FAQ page uses UI translations for display.

        $section->is_active = $request->boolean('is_active');
        $section->save();

        return redirect()->route('admin.sections.edit', $section)
            ->with('success', 'Section "' . $section->label . '" saved successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'action' => 'required|in:activate,deactivate']);
        $ids = $request->ids;
        if (empty($ids)) return back()->with('error', 'No items selected.');

        $items = PageSection::whereIn('id', $ids);
        match ($request->action) {
            'activate'   => $items->update(['is_active' => true]),
            'deactivate' => $items->update(['is_active' => false]),
        };

        return back()->with('success', count($ids) . ' section(s) updated.');
    }
}
