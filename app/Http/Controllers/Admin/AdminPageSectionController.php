<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminPageSectionController extends Controller
{
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
    ];

    public function pages()
    {
        $pages = PageSection::select('page')
            ->selectRaw('COUNT(*) as sections_count')
            ->selectRaw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count')
            ->groupBy('page')
            ->orderByRaw("FIELD(page, 'home','about','approach','training','testimonials','fees','faq','contact','booking')")
            ->get()
            ->map(fn($p) => (object) [
                'key'            => $p->page,
                'label'          => $this->pageLabels[$p->page] ?? ucfirst($p->page),
                'route'          => $this->pageRoutes[$p->page] ?? '/' . $p->page,
                'sections_count' => $p->sections_count,
                'active_count'   => $p->active_count,
            ]);

        return view('admin.pages.sections.pages', compact('pages'));
    }

    public function index(string $page)
    {
        $sections = PageSection::where('page', $page)->orderBy('sort_order')->get();

        if ($sections->isEmpty()) {
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

        return view('admin.pages.sections.edit', compact('section', 'mediaFiles'));
    }

    public function update(Request $request, PageSection $section)
    {
        $request->validate([
            'title'              => 'nullable|string|max:500',
            'subtitle'           => 'nullable|string|max:500',
            'body'               => 'nullable|string',
            'cta_text'           => 'nullable|string|max:255',
            'cta_url'            => 'nullable|string|max:500',
            'cta_secondary_text' => 'nullable|string|max:255',
            'cta_secondary_url'  => 'nullable|string|max:500',
            'is_active'          => 'boolean',
            'image'              => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,svg|max:5120',
            'media_image_url'    => 'nullable|string|max:500',
            'remove_image'       => 'nullable|boolean',
        ]);

        $content = $section->content ?? [];

        // ── Text fields ──
        $textFields = [
            'title'              => ['title', 'heading'],
            'subtitle'           => ['subtitle', 'subheading'],
            'body'               => ['body'],
            'cta_text'           => ['cta_text', 'cta_label', 'cta_primary_label'],
            'cta_url'            => ['cta_url', 'cta_primary_url'],
            'cta_secondary_text' => ['cta_secondary_text', 'cta_secondary_label'],
            'cta_secondary_url'  => ['cta_secondary_url'],
        ];

        foreach ($textFields as $input => $contentKeys) {
            if ($request->has($input)) {
                // Write to the first key that already exists in content, else the canonical name
                $written = false;
                foreach ($contentKeys as $key) {
                    if (array_key_exists($key, $content)) {
                        $content[$key] = $request->input($input) ?: null;
                        $written = true;
                        break;
                    }
                }
                if (!$written) {
                    $content[$contentKeys[0]] = $request->input($input) ?: null;
                }
            }
        }

        // ── Image handling ──
        if ($request->boolean('remove_image')) {
            // Delete stored upload if it was one
            $oldImg = $content['image'] ?? null;
            if ($oldImg && Str::contains($oldImg, '/storage/media/')) {
                $relativePath = 'media/' . basename($oldImg);
                Storage::disk('public')->delete($relativePath);
            }
            $content['image'] = null;

        } elseif ($request->hasFile('image')) {
            // Upload new file
            $file = $request->file('image');
            $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                  . '-' . time()
                  . '.' . $file->getClientOriginalExtension();

            $file->storeAs('media', $name, 'public');
            $content['image'] = '/storage/media/' . $name;

        } elseif ($request->filled('media_image_url')) {
            // Use URL picked from Media Library
            $content['image'] = $request->input('media_image_url');
        }

        // ── Array/repeater fields ──
        $arraySchemas = [
            'steps'  => ['title', 'description', 'duration', 'badge'],
            'items'  => ['title', 'description', 'key', 'label', 'value'],
            'cards'  => ['title', 'subtitle', 'description'],
            'stats'  => ['value', 'label'],
            'groups' => ['title'], // groups also contain nested 'items'
        ];

        foreach ($arraySchemas as $field => $subFields) {
            if ($request->has($field)) {
                $raw = $request->input($field, []);
                $cleaned = [];

                foreach ($raw as $row) {
                    if (!is_array($row)) continue;

                    // For groups with nested items
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

                    // Filter out completely empty rows
                    $hasContent = false;
                    $cleanedRow = [];
                    foreach ($subFields as $sf) {
                        $val = trim($row[$sf] ?? '');
                        if ($val !== '') $hasContent = true;
                        $cleanedRow[$sf] = $val ?: null;
                    }

                    if ($hasContent) {
                        // Remove null-only fields to keep JSON clean
                        $cleaned[] = array_filter($cleanedRow, fn($v) => $v !== null);
                    }
                }

                $content[$field] = $cleaned;
            }
        }

        // ── Extra scalar fields (fee_amount, fee_duration, quote, etc.) ──
        $extraFields = ['fee_amount', 'fee_duration', 'quote', 'attribution', 'whatsapp_number', 'whatsapp_text', 'email'];
        foreach ($extraFields as $ef) {
            if ($request->has($ef)) {
                $content[$ef] = $request->input($ef) ?: null;
            }
        }

        $section->update([
            'content'   => $content,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.sections.edit', $section)
            ->with('success', 'Section "' . $section->label . '" saved successfully.');
    }
}
