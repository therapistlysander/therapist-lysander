@extends('admin.layouts.admin')
@section('title', 'Edit Section: ' . $section->section_key)
@section('page_title', 'Edit Section')

@section('content')
<form method="POST" action="{{ route('admin.sections.update', $section) }}" enctype="multipart/form-data">
  @csrf @method('PATCH')

  {{-- Sticky page header with save button --}}
  <div class="admin-page-header" style="position:sticky;top:56px;z-index:40;background:#f1f3f5;padding:16px 0;margin:-28px -28px 24px;padding:16px 28px;border-bottom:1px solid #e5e7eb;">
    <div>
      <h1 style="font-size:18px;">{{ $section->label ?? $section->section_key }}</h1>
      <p style="font-size:12px;color:#9ca3af;margin:2px 0 0;">
        Page: <strong>{{ $section->page }}</strong> &middot;
        Key: <code style="font-size:11px;background:#e5e7eb;padding:1px 5px;border-radius:3px;">{{ $section->section_key }}</code>
      </p>
    </div>
    <div style="display:flex;gap:8px;align-items:center;">
      <a href="{{ route('admin.sections.index', $section->page) }}" class="btn-admin btn-admin--outline">&larr; {{ ucfirst($section->page) }} Sections</a>
      <button type="submit" class="btn-admin btn-admin--primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        Save changes
      </button>
    </div>
  </div>

  @if($errors->any())
    <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
  @endif

  <div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start;">

    {{-- ── Left Column: Main content ── --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

      {{-- Locale Tabs --}}
      <div class="locale-tabs" style="display:flex;gap:0;border-bottom:2px solid #e5e7eb;">
        @foreach($locales as $locale)
        <button type="button" class="locale-tab {{ $loop->first ? 'locale-tab--active' : '' }}" data-locale="{{ $locale }}" onclick="switchLocale('{{ $locale }}')">
          {{ strtoupper($locale) }}
        </button>
        @endforeach
      </div>

      @foreach($locales as $li => $locale)
      @php
        $lc = $localeContent[$locale] ?? [];
        // For repeater fields: if this locale doesn't have them but another does, inherit structure
        foreach (['items', 'stats', 'steps', 'cards', 'groups'] as $repField) {
            $anyLocaleData = collect($localeContent)->first(fn($c) => !empty($c[$repField]));
            if (empty($lc[$repField]) && $anyLocaleData) {
                $subFields = ['items' => ['title','description','key','label','value','tab_label','heading'], 'stats' => ['value','label'], 'steps' => ['title','description','duration','badge'], 'cards' => ['title','subtitle','description'], 'groups' => ['title']];
                $lc[$repField] = array_map(function($ref) use ($subFields, $repField) {
                    $row = [];
                    foreach ($subFields[$repField] as $sf) { $row[$sf] = ''; }
                    // Preserve non-editable keys
                    foreach (['key','label','value'] as $k) { if (isset($ref[$k])) $row[$k] = $ref[$k]; }
                    return $row;
                }, $anyLocaleData);
            }
        }
      @endphp
      <div class="locale-panel" data-locale="{{ $locale }}" style="display:{{ $li === 0 ? 'flex' : 'none' }};flex-direction:column;gap:20px;">

        {{-- Text Content --}}
        <div class="admin-form">
          <div class="admin-form__section">
            <div class="admin-form__section-title">Text Content — {{ strtoupper($locale) }}</div>

            <div class="admin-field">
              <label class="admin-label">Heading / Title</label>
              <input type="text" name="translations[{{ $locale }}][title]" class="admin-input" value="{{ old("translations.$locale.title", $lc['title'] ?? $lc['heading'] ?? '') }}" placeholder="Main heading text">
            </div>

            <div class="admin-field">
              <label class="admin-label">Subheading / Eyebrow</label>
              <input type="text" name="translations[{{ $locale }}][subtitle]" class="admin-input" value="{{ old("translations.$locale.subtitle", $lc['subtitle'] ?? $lc['subheading'] ?? '') }}" placeholder="Smaller text above or below the heading">
            </div>

            <div class="admin-field">
              <label class="admin-label">Body</label>
              <input type="hidden" id="body-{{ $locale }}-input" name="translations[{{ $locale }}][body]" value="{{ old("translations.$locale.body", $lc['body'] ?? '') }}">
              <div class="admin-editor-wrap" data-editor="body-{{ $locale }}-input" data-placeholder="Write the body text for this section...">
                <div class="ql-editor-area"></div>
              </div>
            </div>
          </div>
        </div>

        {{-- CTA Section --}}
        <div class="admin-form">
          <div class="admin-form__section">
            <div class="admin-form__section-title">Call-to-Action — {{ strtoupper($locale) }}</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
              <div class="admin-field">
                <label class="admin-label">Primary button label</label>
                <input type="text" name="translations[{{ $locale }}][cta_text]" class="admin-input" value="{{ old("translations.$locale.cta_text", $lc['cta_text'] ?? $lc['cta_label'] ?? $lc['cta_primary_label'] ?? '') }}" placeholder="e.g. Book a Free Call">
              </div>
              <div class="admin-field">
                <label class="admin-label">Primary button URL</label>
                <input type="text" name="translations[{{ $locale }}][cta_url]" class="admin-input" value="{{ old("translations.$locale.cta_url", $lc['cta_url'] ?? $lc['cta_primary_url'] ?? '') }}" placeholder="/booking or https://...">
              </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:12px;">
              <div class="admin-field">
                <label class="admin-label">Secondary label <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
                <input type="text" name="translations[{{ $locale }}][cta_secondary_text]" class="admin-input" value="{{ old("translations.$locale.cta_secondary_text", $lc['cta_secondary_text'] ?? $lc['cta_secondary_label'] ?? '') }}" placeholder="e.g. Learn More">
              </div>
              <div class="admin-field">
                <label class="admin-label">Secondary URL</label>
                <input type="text" name="translations[{{ $locale }}][cta_secondary_url]" class="admin-input" value="{{ old("translations.$locale.cta_secondary_url", $lc['cta_secondary_url'] ?? '') }}" placeholder="/about">
              </div>
            </div>
          </div>
        </div>

        {{-- Extra scalar fields --}}
        @php
          $extraFields = [
            'quote' => 'Quote text',
            'attribution' => 'Quote attribution',
            'fee_amount' => 'Fee amount (e.g. €110)',
            'fee_duration' => 'Fee duration label',
            'whatsapp_number' => 'WhatsApp number',
            'whatsapp_text' => 'WhatsApp CTA text',
            'email' => 'Email address',
          ];
          $hasExtras = false;
          foreach ($extraFields as $k => $v) { if (isset($lc[$k])) { $hasExtras = true; break; } }
        @endphp
        @if($hasExtras)
        <div class="admin-form">
          <div class="admin-form__section">
            <div class="admin-form__section-title">Additional Fields — {{ strtoupper($locale) }}</div>
            @foreach($extraFields as $fieldKey => $fieldLabel)
              @if(isset($lc[$fieldKey]))
              <div class="admin-field">
                <label class="admin-label">{{ $fieldLabel }}</label>
                <input type="text" name="translations[{{ $locale }}][{{ $fieldKey }}]" class="admin-input" value="{{ old("translations.$locale.$fieldKey", $lc[$fieldKey] ?? '') }}">
              </div>
              @endif
            @endforeach
          </div>
        </div>
        @endif

        {{-- Repeater: Stats --}}
        @if(isset($lc['stats']))
        <div class="admin-form">
          <div class="admin-form__section">
            <div class="admin-form__section-title">Stats — {{ strtoupper($locale) }}</div>
            <div class="repeater" data-field="stats" data-locale="{{ $locale }}">
              @foreach($lc['stats'] ?? [] as $i => $stat)
              <div class="repeater-row" style="display:grid;grid-template-columns:100px 1fr 32px;gap:8px;align-items:center;margin-bottom:8px;">
                <input type="text" name="translations[{{ $locale }}][stats][{{ $i }}][value]" class="admin-input" value="{{ $stat['value'] ?? '' }}" placeholder="Value">
                <input type="text" name="translations[{{ $locale }}][stats][{{ $i }}][label]" class="admin-input" value="{{ $stat['label'] ?? '' }}" placeholder="Label">
                <button type="button" class="repeater-remove" title="Remove">&times;</button>
              </div>
              @endforeach
              <button type="button" class="repeater-add btn-admin btn-admin--outline" data-field="stats" data-fields="value,label" data-cols="100px 1fr 32px" data-locale="{{ $locale }}" style="font-size:12px;margin-top:4px;">+ Add stat</button>
            </div>
          </div>
        </div>
        @endif

        {{-- Repeater: Items --}}
        @if(isset($lc['items']))
        <div class="admin-form">
          <div class="admin-form__section">
            <div class="admin-form__section-title">Items — {{ strtoupper($locale) }}</div>
            <div class="repeater" data-field="items" data-locale="{{ $locale }}">
              @php
                $itemHasDesc = collect($lc['items'] ?? [])->contains(fn($i) => !empty($i['description'] ?? ''));
                // Show tab fields when items have descriptions (approach tabs pattern)
                $showTabFields = $itemHasDesc;
              @endphp
              @foreach($lc['items'] ?? [] as $i => $item)
              <div class="repeater-row item-card" style="border:1px solid #d1d5db;border-radius:10px;padding:16px;margin-bottom:12px;background:#fafbfc;position:relative;">
                @if($showTabFields)
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                  <div>
                    <label style="display:block;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Tab Title</label>
                    <input type="text" name="translations[{{ $locale }}][items][{{ $i }}][title]" class="admin-input" value="{{ $item['title'] ?? '' }}" placeholder="e.g. CGT, ACT">
                  </div>
                  <div>
                    <label style="display:block;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Tab Heading</label>
                    <input type="text" name="translations[{{ $locale }}][items][{{ $i }}][heading]" class="admin-input" value="{{ $item['heading'] ?? '' }}" placeholder="e.g. Schematherapie">
                  </div>
                </div>
                <div style="margin-bottom:4px;">
                  <label style="display:block;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Tab Content</label>
                  <textarea name="translations[{{ $locale }}][items][{{ $i }}][description]" class="admin-input" rows="3" placeholder="Tab description text..." style="resize:vertical;">{{ $item['description'] ?? '' }}</textarea>
                </div>
                @else
                <div style="display:grid;grid-template-columns:1fr 32px;gap:8px;align-items:center;">
                  <input type="text" name="translations[{{ $locale }}][items][{{ $i }}][title]" class="admin-input" value="{{ $item['title'] ?? '' }}" placeholder="Title">
                </div>
                @endif
                @if(!empty($item['key'] ?? ''))
                <input type="hidden" name="translations[{{ $locale }}][items][{{ $i }}][key]" value="{{ $item['key'] }}">
                @endif
                @if(!empty($item['label'] ?? ''))
                <input type="hidden" name="translations[{{ $locale }}][items][{{ $i }}][label]" value="{{ $item['label'] }}">
                @endif
                @if(!empty($item['value'] ?? ''))
                <input type="hidden" name="translations[{{ $locale }}][items][{{ $i }}][value]" value="{{ $item['value'] }}">
                @endif
                @if(!empty($item['tab_label'] ?? ''))
                <input type="hidden" name="translations[{{ $locale }}][items][{{ $i }}][tab_label]" value="{{ $item['tab_label'] }}">
                @endif
                <button type="button" class="repeater-remove" style="position:absolute;top:10px;right:10px;" title="Remove">&times;</button>
              </div>
              @endforeach
              <button type="button" class="repeater-add btn-admin btn-admin--outline" data-field="items" data-fields="title{{ $itemHasDesc ? ',description' : '' }}" data-cols="{{ $itemHasDesc ? '1fr 1fr' : '1fr' }} 32px" data-locale="{{ $locale }}" style="font-size:12px;margin-top:4px;">+ Add item</button>
            </div>
          </div>
        </div>
        @endif

        {{-- Repeater: Steps --}}
        @if(isset($lc['steps']))
        <div class="admin-form">
          <div class="admin-form__section">
            <div class="admin-form__section-title">Steps — {{ strtoupper($locale) }}</div>
            <div class="repeater" data-field="steps" data-locale="{{ $locale }}">
              @foreach($lc['steps'] ?? [] as $i => $step)
              <div class="repeater-row" style="border:1px solid #e5e7eb;border-radius:8px;padding:12px;margin-bottom:10px;position:relative;">
                <div style="display:grid;grid-template-columns:1fr 1fr 120px;gap:8px;margin-bottom:8px;">
                  <input type="text" name="translations[{{ $locale }}][steps][{{ $i }}][title]" class="admin-input" value="{{ $step['title'] ?? '' }}" placeholder="Step title">
                  <input type="text" name="translations[{{ $locale }}][steps][{{ $i }}][duration]" class="admin-input" value="{{ $step['duration'] ?? '' }}" placeholder="Duration (optional)">
                  <input type="text" name="translations[{{ $locale }}][steps][{{ $i }}][badge]" class="admin-input" value="{{ $step['badge'] ?? '' }}" placeholder="Badge (optional)">
                </div>
                <textarea name="translations[{{ $locale }}][steps][{{ $i }}][description]" class="admin-input" rows="2" placeholder="Step description" style="resize:vertical;">{{ $step['description'] ?? '' }}</textarea>
                <button type="button" class="repeater-remove" style="position:absolute;top:8px;right:8px;" title="Remove">&times;</button>
              </div>
              @endforeach
              <button type="button" class="repeater-add-step btn-admin btn-admin--outline" data-field="steps" data-locale="{{ $locale }}" style="font-size:12px;margin-top:4px;">+ Add step</button>
            </div>
          </div>
        </div>
        @endif

        {{-- Repeater: Cards --}}
        @if(isset($lc['cards']))
        <div class="admin-form">
          <div class="admin-form__section">
            <div class="admin-form__section-title">Cards — {{ strtoupper($locale) }}</div>
            <div class="repeater" data-field="cards" data-locale="{{ $locale }}">
              @foreach($lc['cards'] ?? [] as $i => $card)
              <div class="repeater-row" style="border:1px solid #e5e7eb;border-radius:8px;padding:12px;margin-bottom:10px;position:relative;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px;">
                  <input type="text" name="translations[{{ $locale }}][cards][{{ $i }}][title]" class="admin-input" value="{{ $card['title'] ?? '' }}" placeholder="Card title">
                  <input type="text" name="translations[{{ $locale }}][cards][{{ $i }}][subtitle]" class="admin-input" value="{{ $card['subtitle'] ?? '' }}" placeholder="Subtitle (optional)">
                </div>
                <textarea name="translations[{{ $locale }}][cards][{{ $i }}][description]" class="admin-input" rows="2" placeholder="Card description" style="resize:vertical;">{{ $card['description'] ?? '' }}</textarea>
                <button type="button" class="repeater-remove" style="position:absolute;top:8px;right:8px;" title="Remove">&times;</button>
              </div>
              @endforeach
              <button type="button" class="repeater-add-card btn-admin btn-admin--outline" data-field="cards" data-locale="{{ $locale }}" style="font-size:12px;margin-top:4px;">+ Add card</button>
            </div>
          </div>
        </div>
        @endif

        {{-- Repeater: Groups (nested) --}}
        @if(isset($lc['groups']))
        <div class="admin-form">
          <div class="admin-form__section">
            <div class="admin-form__section-title">Groups — {{ strtoupper($locale) }}</div>
            <div class="repeater-groups" data-field="groups" data-locale="{{ $locale }}">
              @foreach($lc['groups'] ?? [] as $gi => $group)
              <div class="repeater-group" style="border:1px solid #d1d5db;border-radius:8px;padding:14px;margin-bottom:12px;background:#fafafa;position:relative;">
                <input type="text" name="translations[{{ $locale }}][groups][{{ $gi }}][title]" class="admin-input" value="{{ $group['title'] ?? '' }}" placeholder="Group title" style="font-weight:600;margin-bottom:8px;">
                <div class="group-items">
                  @foreach($group['items'] ?? [] as $ii => $item)
                  <div class="repeater-row" style="display:grid;grid-template-columns:1fr 32px;gap:8px;align-items:center;margin-bottom:6px;">
                    <input type="text" name="translations[{{ $locale }}][groups][{{ $gi }}][items][{{ $ii }}][title]" class="admin-input" value="{{ $item['title'] ?? '' }}" placeholder="Item title">
                    <button type="button" class="repeater-remove" title="Remove">&times;</button>
                  </div>
                  @endforeach
                </div>
                <button type="button" class="repeater-add-group-item btn-admin btn-admin--outline" data-group="{{ $gi }}" data-locale="{{ $locale }}" style="font-size:11px;margin-top:4px;">+ Add item</button>
                <button type="button" class="repeater-remove" style="position:absolute;top:8px;right:8px;" title="Remove group">&times;</button>
              </div>
              @endforeach
              <button type="button" class="repeater-add-group btn-admin btn-admin--outline" data-locale="{{ $locale }}" style="font-size:12px;margin-top:4px;">+ Add group</button>
            </div>
          </div>
        </div>
        @endif

        {{-- Categories (FAQ categories special field) --}}
        @if(isset($lc['categories']))
        <div class="admin-form">
          <div class="admin-form__section">
            <div class="admin-form__section-title">Categories — {{ strtoupper($locale) }}</div>
            <div class="repeater" data-field="categories" data-locale="{{ $locale }}">
              @foreach($lc['categories'] ?? [] as $i => $cat)
              <div class="repeater-row" style="display:grid;grid-template-columns:120px 1fr 32px;gap:8px;align-items:center;margin-bottom:8px;">
                <input type="text" name="translations[{{ $locale }}][categories][{{ $i }}][key]" class="admin-input" value="{{ $cat['key'] ?? '' }}" placeholder="Key">
                <input type="text" name="translations[{{ $locale }}][categories][{{ $i }}][label]" class="admin-input" value="{{ $cat['label'] ?? '' }}" placeholder="Label">
                <button type="button" class="repeater-remove" title="Remove">&times;</button>
              </div>
              @endforeach
            </div>
          </div>
        </div>
        @endif

      </div>{{-- end locale-panel --}}
      @endforeach

      {{-- Raw JSON (advanced) --}}
      <details>
        <summary style="font-size:12px;font-weight:500;color:#6b7280;cursor:pointer;padding:8px 0;">
          Advanced: view raw content JSON
        </summary>
        <div style="margin-top:8px;background:#1e293b;border-radius:8px;padding:14px;font-size:11px;font-family:monospace;color:#e2e8f0;white-space:pre-wrap;overflow-x:auto;max-height:280px;overflow-y:auto;">{{ json_encode($section->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</div>
      </details>
    </div>

    {{-- ── Right Column: Image & Settings ── --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

      {{-- Image --}}
      <div class="admin-form">
        <div class="admin-form__section">
          <div class="admin-form__section-title">Image</div>

          @php
            $currentImg = $localeContent['en']['image'] ?? $section->content['image'] ?? null;
            // Normalize absolute URLs to relative paths
            if ($currentImg && preg_match('#https?://[^/]+(/storage/.+)#', $currentImg, $m)) {
                $currentImg = $m[1];
            }
          @endphp

          @if($currentImg)
          <div style="margin-bottom:14px;">
            <img src="{{ $currentImg }}" alt="" style="width:100%;max-height:180px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;display:block;" onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
            <p style="display:none;font-size:11px;color:#ef4444;margin:4px 0;">Image could not be loaded: {{ basename($currentImg) }}</p>
            <p style="font-size:11px;color:#6b7280;margin:4px 0 0;">Current: {{ basename($currentImg) }}</p>
            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;color:#ef4444;margin-top:6px;">
              <input type="checkbox" name="remove_image" value="1"> Remove current image
            </label>
          </div>
          @else
          <p style="font-size:12px;color:#9ca3af;margin:0 0 12px;">No image set for this section.</p>
          @endif

          <div class="admin-field">
            <label class="admin-label">Upload new</label>
            <input type="file" name="image" class="admin-input" accept=".jpg,.jpeg,.png,.webp,.gif,.svg" id="image-upload-input" onchange="previewNewImage(this)">
            <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Max 5 MB</p>
          </div>
          <div id="new-image-preview" style="display:none;margin-top:8px;">
            <img id="new-image-thumb" src="" alt="" style="width:100%;max-height:140px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;display:block;">
          </div>

          @if($mediaFiles->isNotEmpty())
          <div style="margin-top:16px;border-top:1px solid #e5e7eb;padding-top:14px;">
            <p style="font-size:12px;font-weight:500;color:#374151;margin:0 0 8px;">Or pick from Media:</p>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(70px,1fr));gap:6px;max-height:200px;overflow-y:auto;">
              @foreach($mediaFiles as $mf)
              @php
                $ext = strtolower(pathinfo($mf['filename'], PATHINFO_EXTENSION));
                $isImg = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
              @endphp
              @if($isImg)
              <button type="button" onclick="pickMediaImage('{{ $mf['url'] }}', this)"
                class="media-thumb-btn {{ ($currentImg && basename($currentImg) === $mf['filename']) ? 'media-thumb-btn--selected' : '' }}"
                title="{{ $mf['filename'] }}">
                <img src="{{ $mf['url'] }}" alt="{{ $mf['filename'] }}" style="width:100%;height:56px;object-fit:cover;border-radius:4px;display:block;">
              </button>
              @endif
              @endforeach
            </div>
            <input type="hidden" name="media_image_url" id="media-image-url" value="{{ old('media_image_url', '') }}">
            <p id="media-picked-label" style="font-size:11px;color:#5a9e97;margin-top:6px;{{ ($currentImg && str_contains($currentImg, '/storage/media/')) ? '' : 'display:none;' }}">
              {{ ($currentImg && str_contains($currentImg, '/storage/media/')) ? 'Current image from media: ' . basename($currentImg) : '' }}
            </p>
          </div>
          @endif
        </div>
      </div>

      {{-- Visibility --}}
      <div class="admin-form">
        <div class="admin-form__section">
          <div class="admin-form__section-title">Visibility</div>
          <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
            <span>Active &mdash; visible on public site</span>
          </label>
        </div>
      </div>

    </div>{{-- end right column --}}
  </div>{{-- end grid --}}
</form>
@endsection

@section('page_styles')
<style>
  .locale-tab {
    padding: 8px 20px; font-size: 13px; font-weight: 500; cursor: pointer;
    border: none; background: none; color: #6b7280; border-bottom: 2px solid transparent;
    margin-bottom: -2px; transition: all 0.15s;
  }
  .locale-tab:hover { color: #374151; }
  .locale-tab--active { color: #5a9e97; border-bottom-color: #5a9e97; }
  .media-thumb-btn {
    background: none; border: 2px solid #e5e7eb; border-radius: 6px;
    padding: 0; cursor: pointer; transition: border-color 0.15s; overflow: hidden;
  }
  .media-thumb-btn:hover { border-color: #5a9e97; }
  .media-thumb-btn--selected { border-color: #5a9e97; box-shadow: 0 0 0 2px rgba(90,158,151,0.2); }
  .repeater-remove {
    width:28px;height:28px;border:1px solid #e5e7eb;border-radius:6px;background:#fff;
    color:#ef4444;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;
    transition:background 0.15s,border-color 0.15s;line-height:1;
  }
  .repeater-remove:hover { background:#fef2f2;border-color:#fca5a5; }
</style>
@endsection

@section('page_scripts')
<script>
function switchLocale(locale) {
  document.querySelectorAll('.locale-tab').forEach(t => t.classList.toggle('locale-tab--active', t.dataset.locale === locale));
  document.querySelectorAll('.locale-panel').forEach(p => p.style.display = p.dataset.locale === locale ? 'flex' : 'none');
}

function previewNewImage(input) {
  const wrap = document.getElementById('new-image-preview');
  const thumb = document.getElementById('new-image-thumb');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => { thumb.src = e.target.result; wrap.style.display = 'block'; };
    reader.readAsDataURL(input.files[0]);
    document.getElementById('media-image-url').value = '';
    document.querySelectorAll('.media-thumb-btn').forEach(b => b.classList.remove('media-thumb-btn--selected'));
    const lbl = document.getElementById('media-picked-label');
    if (lbl) lbl.style.display = 'none';
  }
}

function pickMediaImage(url, btn) {
  document.getElementById('media-image-url').value = url;
  document.getElementById('image-upload-input').value = '';
  document.getElementById('new-image-preview').style.display = 'none';
  document.querySelectorAll('.media-thumb-btn').forEach(b => b.classList.remove('media-thumb-btn--selected'));
  if (btn) btn.classList.add('media-thumb-btn--selected');
  const lbl = document.getElementById('media-picked-label');
  if (lbl) { lbl.textContent = 'Selected: ' + url.split('/').pop(); lbl.style.display = 'block'; }
}

// Repeater: remove row
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('repeater-remove')) {
    const row = e.target.closest('.repeater-row, .repeater-group');
    if (row) row.remove();
  }
});

// Helper: build locale-prefixed name
function locName(locale, field, idx, sub) {
  return 'translations[' + locale + '][' + field + '][' + idx + '][' + sub + ']';
}

// Repeater: add simple row (items, stats)
document.querySelectorAll('.repeater-add').forEach(btn => {
  btn.addEventListener('click', function() {
    const field = this.dataset.field;
    const fields = this.dataset.fields.split(',');
    const cols = this.dataset.cols;
    const locale = this.dataset.locale;
    const container = this.closest('.repeater');
    const idx = container.querySelectorAll('.repeater-row').length;
    const row = document.createElement('div');
    row.className = 'repeater-row';
    row.style.cssText = 'display:grid;grid-template-columns:' + cols + ';gap:8px;align-items:center;margin-bottom:8px;';
    fields.forEach(f => {
      row.innerHTML += '<input type="text" name="' + locName(locale, field, idx, f) + '" class="admin-input" placeholder="' + f.charAt(0).toUpperCase() + f.slice(1) + '">';
    });
    row.innerHTML += '<button type="button" class="repeater-remove" title="Remove">&times;</button>';
    container.insertBefore(row, this);
  });
});

// Repeater: add step
document.querySelectorAll('.repeater-add-step').forEach(btn => {
  btn.addEventListener('click', function() {
    const container = this.closest('.repeater');
    const locale = this.dataset.locale;
    const idx = container.querySelectorAll('.repeater-row').length;
    const row = document.createElement('div');
    row.className = 'repeater-row';
    row.style.cssText = 'border:1px solid #e5e7eb;border-radius:8px;padding:12px;margin-bottom:10px;position:relative;';
    row.innerHTML = '<div style="display:grid;grid-template-columns:1fr 1fr 120px;gap:8px;margin-bottom:8px;">' +
      '<input type="text" name="' + locName(locale, 'steps', idx, 'title') + '" class="admin-input" placeholder="Step title">' +
      '<input type="text" name="' + locName(locale, 'steps', idx, 'duration') + '" class="admin-input" placeholder="Duration (optional)">' +
      '<input type="text" name="' + locName(locale, 'steps', idx, 'badge') + '" class="admin-input" placeholder="Badge (optional)">' +
      '</div>' +
      '<textarea name="' + locName(locale, 'steps', idx, 'description') + '" class="admin-input" rows="2" placeholder="Step description" style="resize:vertical;"></textarea>' +
      '<button type="button" class="repeater-remove" style="position:absolute;top:8px;right:8px;" title="Remove">&times;</button>';
    container.insertBefore(row, this);
  });
});

// Repeater: add card
document.querySelectorAll('.repeater-add-card').forEach(btn => {
  btn.addEventListener('click', function() {
    const container = this.closest('.repeater');
    const locale = this.dataset.locale;
    const idx = container.querySelectorAll('.repeater-row').length;
    const row = document.createElement('div');
    row.className = 'repeater-row';
    row.style.cssText = 'border:1px solid #e5e7eb;border-radius:8px;padding:12px;margin-bottom:10px;position:relative;';
    row.innerHTML = '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px;">' +
      '<input type="text" name="' + locName(locale, 'cards', idx, 'title') + '" class="admin-input" placeholder="Card title">' +
      '<input type="text" name="' + locName(locale, 'cards', idx, 'subtitle') + '" class="admin-input" placeholder="Subtitle (optional)">' +
      '</div>' +
      '<textarea name="' + locName(locale, 'cards', idx, 'description') + '" class="admin-input" rows="2" placeholder="Card description" style="resize:vertical;"></textarea>' +
      '<button type="button" class="repeater-remove" style="position:absolute;top:8px;right:8px;" title="Remove">&times;</button>';
    container.insertBefore(row, this);
  });
});

// Repeater: add group item
document.querySelectorAll('.repeater-add-group-item').forEach(btn => {
  btn.addEventListener('click', function() {
    const gi = this.dataset.group;
    const locale = this.dataset.locale;
    const container = this.previousElementSibling;
    const idx = container.querySelectorAll('.repeater-row').length;
    const row = document.createElement('div');
    row.className = 'repeater-row';
    row.style.cssText = 'display:grid;grid-template-columns:1fr 32px;gap:8px;align-items:center;margin-bottom:6px;';
    row.innerHTML = '<input type="text" name="translations[' + locale + '][groups][' + gi + '][items][' + idx + '][title]" class="admin-input" placeholder="Item title">' +
      '<button type="button" class="repeater-remove" title="Remove">&times;</button>';
    container.appendChild(row);
  });
});

// Repeater: add group
document.querySelectorAll('.repeater-add-group').forEach(btn => {
  btn.addEventListener('click', function() {
    const container = this.closest('.repeater-groups');
    const locale = this.dataset.locale;
    const gi = container.querySelectorAll('.repeater-group').length;
    const group = document.createElement('div');
    group.className = 'repeater-group';
    group.style.cssText = 'border:1px solid #d1d5db;border-radius:8px;padding:14px;margin-bottom:12px;background:#fafafa;position:relative;';
    group.innerHTML = '<input type="text" name="translations[' + locale + '][groups][' + gi + '][title]" class="admin-input" value="" placeholder="Group title" style="font-weight:600;margin-bottom:8px;">' +
      '<div class="group-items"></div>' +
      '<button type="button" class="repeater-add-group-item btn-admin btn-admin--outline" data-group="' + gi + '" data-locale="' + locale + '" style="font-size:11px;margin-top:4px;">+ Add item</button>' +
      '<button type="button" class="repeater-remove" style="position:absolute;top:8px;right:8px;" title="Remove group">&times;</button>';
    container.insertBefore(group, this);
    // Rebind the add-group-item button
    group.querySelector('.repeater-add-group-item').addEventListener('click', function() {
      const gIdx = this.dataset.group;
      const loc = this.dataset.locale;
      const itemsDiv = this.previousElementSibling;
      const iIdx = itemsDiv.querySelectorAll('.repeater-row').length;
      const row = document.createElement('div');
      row.className = 'repeater-row';
      row.style.cssText = 'display:grid;grid-template-columns:1fr 32px;gap:8px;align-items:center;margin-bottom:6px;';
      row.innerHTML = '<input type="text" name="translations[' + loc + '][groups][' + gIdx + '][items][' + iIdx + '][title]" class="admin-input" placeholder="Item title">' +
        '<button type="button" class="repeater-remove" title="Remove">&times;</button>';
      itemsDiv.appendChild(row);
    });
  });
});
</script>
@endsection
