@extends('admin.layouts.admin')
@section('title', 'Site Settings')
@section('page_title', 'Site Settings')

@section('content')
<div class="admin-page-header">
  <h1>Site Settings</h1>
</div>

@if(session('success'))
  <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
@endif
@if($errors->any())
  <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" style="max-width:820px;">
  @csrf @method('PATCH')

  @php
    $groupLabels = [
      'general'    => 'General',
      'contact'    => 'Contact & Location',
      'booking'    => 'Booking & Sessions',
      'social'     => 'Social Media & Profiles',
      'analytics'  => 'Analytics & Tracking',
      'endorsement' => 'Professional Endorsement',
    ];
    $groupOrder = ['general', 'contact', 'booking', 'social', 'analytics', 'endorsement'];
    // Groups managed on other pages (e.g. /email-settings)
    $skipGroups = ['email', 'notifications'];
    // Hide Professional Endorsement from non-superadmin users
    if (! auth()->user()?->isSuperAdmin()) {
      $skipGroups[] = 'endorsement';
      $groupOrder = array_values(array_diff($groupOrder, ['endorsement']));
    }
  @endphp

  @foreach($groupOrder as $group)
  @if(isset($settings[$group]))
  <div class="admin-form" style="margin-bottom:24px;">
    <div class="admin-form__section">
      <div style="display:flex;align-items:center;justify-content:space-between;">
        <div class="admin-form__section-title" style="margin:0;">{{ $groupLabels[$group] ?? ucfirst($group) }}</div>
        @if($group === 'endorsement')
        <div class="settings-locale-tabs" style="display:flex;gap:0;border-bottom:2px solid #e5e7eb;">
          <button type="button" class="settings-locale-tab settings-locale-tab--active" data-target="endorsement" data-locale="en" onclick="switchSettingsLocale(this)">EN</button>
          <button type="button" class="settings-locale-tab" data-target="endorsement" data-locale="nl" onclick="switchSettingsLocale(this)">NL</button>
        </div>
        @endif
      </div>

      @foreach($settings[$group] as $setting)
      {{-- Default Social Share Image (Open Graph) upload disabled.
           The OG image is now set directly from the static file public/images/og-image.jpg.
           To re-enable admin uploads, remove this @continue block. --}}
      @if($setting->type === 'image')
        @continue
      @endif
      <div class="admin-field">
        <label class="admin-label" for="setting-{{ $setting->key }}">
          {{ $setting->label ?? $setting->key }}
        </label>

        @if($setting->type === 'boolean')
          <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;margin-top:4px;">
            <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
            <input type="checkbox"
              id="setting-{{ $setting->key }}"
              name="settings[{{ $setting->key }}]"
              value="1"
              {{ $setting->getRawOriginal('value') ? 'checked' : '' }}
              @if($setting->key === 'multilingual_enabled') onchange="toggleLanguageSection(this.checked)" @endif>
            <span>Enabled</span>
          </label>

        @elseif($setting->type === 'image')
          <div class="og-image-field">
            <input type="file"
              id="setting-{{ $setting->key }}"
              name="settings[{{ $setting->key }}]"
              class="admin-input"
              accept="image/jpeg,image/png,image/webp"
              onchange="previewOgImage(this, 'preview-{{ $setting->key }}')">
            @php
              $currentOgImage = $setting->getRawOriginal('value') ?: '/images/og-image.jpg';
              $currentOgImageUrl = \Illuminate\Support\Str::startsWith($currentOgImage, ['http://', 'https://']) ? $currentOgImage : url($currentOgImage);
            @endphp
            <div style="margin-top:10px;">
              <img id="preview-{{ $setting->key }}" src="{{ $currentOgImageUrl }}" alt="Current OG image preview" style="max-width:320px;max-height:168px;border-radius:6px;border:1px solid #e5e7eb;object-fit:cover;">
            </div>
            <p style="font-size:11px;color:#9ca3af;margin-top:6px;">Recommended size: 1200 × 630 px. Leave empty and save to keep the current image. Upload a new image to replace it.</p>
          </div>

        @elseif($setting->key === 'language')
          {{-- Language checkboxes — only shown when multilingual is enabled --}}
          <div id="language-section" style="{{ ($settings['general']->firstWhere('key','multilingual_enabled')?->getRawOriginal('value') === '0') ? 'display:none;' : '' }}">
            <div style="display:flex;gap:16px;flex-wrap:wrap;">
              @php $currentLangs = explode(',', $setting->getRawOriginal('value') ?? ''); @endphp
              @foreach(['nl' => 'Dutch', 'en' => 'English'] as $code => $name)
              <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                <input type="checkbox" name="settings[language][]" value="{{ $code }}" {{ in_array($code, $currentLangs) ? 'checked' : '' }}>
                <span>{{ $name }} ({{ strtoupper($code) }})</span>
              </label>
              @endforeach
            </div>
            <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Select one or more languages for the site.</p>
          </div>
          <input type="hidden" name="settings[language]" id="language-hidden" value="{{ old('settings.language', $setting->getRawOriginal('value')) }}">

        @elseif($setting->key === 'endorsement_full_body')
          {{-- Bilingual textarea for endorsement full text (controlled by section-level tab) --}}
          @php $ftVal = json_decode($setting->getRawOriginal('value'), true) ?: []; @endphp
          <div class="settings-locale-panel" data-target="endorsement" data-locale="en" style="display:flex;flex-direction:column;">
            <textarea name="settings[{{ $setting->key }}][en]" class="admin-input" rows="6">{{ old("settings.{$setting->key}.en", $ftVal['en'] ?? '') }}</textarea>
          </div>
          <div class="settings-locale-panel" data-target="endorsement" data-locale="nl" style="display:none;flex-direction:column;">
            <textarea name="settings[{{ $setting->key }}][nl]" class="admin-input" rows="6">{{ old("settings.{$setting->key}.nl", $ftVal['nl'] ?? '') }}</textarea>
          </div>

        @elseif($setting->key === 'default_meeting_platform')
          <select name="settings[{{ $setting->key }}]"
            id="setting-{{ $setting->key }}"
            class="admin-select"
            style="width:100%;">
            @foreach(['zoom'=>'Zoom','google_meet'=>'Google Meet','teams'=>'Microsoft Teams','whereby'=>'Whereby','other'=>'Other'] as $val => $lbl)
              <option value="{{ $val }}" {{ $setting->getRawOriginal('value') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
            @endforeach
          </select>

        @elseif($setting->type === 'text')
          <textarea name="settings[{{ $setting->key }}]"
            id="setting-{{ $setting->key }}"
            class="admin-input"
            rows="4">{{ old('settings.' . $setting->key, $setting->getRawOriginal('value')) }}</textarea>

        @elseif($setting->type === 'json')
          @php $jsonVal = json_decode($setting->getRawOriginal('value'), true) ?: []; @endphp
          <div class="settings-locale-panel" data-target="endorsement" data-locale="en" style="display:flex;flex-direction:column;">
            <input type="text" name="settings[{{ $setting->key }}][en]" class="admin-input" value="{{ old("settings.{$setting->key}.en", $jsonVal['en'] ?? '') }}">
          </div>
          <div class="settings-locale-panel" data-target="endorsement" data-locale="nl" style="display:none;flex-direction:column;">
            <input type="text" name="settings[{{ $setting->key }}][nl]" class="admin-input" value="{{ old("settings.{$setting->key}.nl", $jsonVal['nl'] ?? '') }}">
          </div>

        @else
          <input type="text"
            name="settings[{{ $setting->key }}]"
            id="setting-{{ $setting->key }}"
            class="admin-input"
            value="{{ old('settings.' . $setting->key, $setting->getRawOriginal('value')) }}"
            placeholder="{{ $setting->key }}">
        @endif

        {{-- Field-specific hints --}}
        @if($setting->key === 'contact_email')
          <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Used on contact forms and admin notifications.</p>
        @elseif($setting->key === 'google_analytics_id')
          <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Format: G-XXXXXXXXXX</p>
        @elseif($setting->key === 'gtm_id')
          <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Format: GTM-XXXXXXX</p>
        @elseif($setting->key === 'calendly_url')
          <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Full Calendly URL, e.g. https://calendly.com/yourname</p>
        @elseif($setting->key === 'default_meeting_link')
          <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Your online meeting room link (e.g. https://zoom.us/j/… or https://meet.google.com/…). This link is used automatically for every online session and included in approval emails and calendar invites.</p>
        @endif
      </div>
      @endforeach

    </div>
  </div>
  @endif
  @endforeach

  {{-- Any groups not in the ordered list --}}
  @foreach($settings as $group => $groupSettings)
  @if(!in_array($group, $groupOrder) && !in_array($group, $skipGroups))
  <div class="admin-form" style="margin-bottom:24px;">
    <div class="admin-form__section">
      <div class="admin-form__section-title">{{ ucfirst($group) }}</div>
      @foreach($groupSettings as $setting)
      <div class="admin-field">
        <label class="admin-label" for="setting-{{ $setting->key }}">{{ $setting->label ?? $setting->key }}</label>
        <input type="text" name="settings[{{ $setting->key }}]" id="setting-{{ $setting->key }}"
          class="admin-input" value="{{ old('settings.' . $setting->key, $setting->getRawOriginal('value')) }}">
      </div>
      @endforeach
    </div>
  </div>
  @endif
  @endforeach

  <div style="display:flex;justify-content:flex-end;gap:10px;">
    <button type="submit" class="btn-admin btn-admin--primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Save all settings
    </button>
  </div>
</form>

<style>
.settings-locale-tab {
  padding: 8px 20px; font-size: 13px; font-weight: 500; cursor: pointer;
  border: none; background: none; color: #6b7280; border-bottom: 2px solid transparent;
  margin-bottom: -2px; transition: all 0.15s;
}
.settings-locale-tab:hover { color: #374151; }
.settings-locale-tab--active { color: #5a9e97; border-bottom-color: #5a9e97; }
</style>

<script>
function switchSettingsLocale(btn) {
  var target = btn.dataset.target;
  var locale = btn.dataset.locale;
  // Update tabs within the same group
  btn.closest('.settings-locale-tabs').querySelectorAll('.settings-locale-tab').forEach(function(t) {
    t.classList.toggle('settings-locale-tab--active', t.dataset.locale === locale);
  });
  // Show/hide panels
  document.querySelectorAll('.settings-locale-panel[data-target="' + target + '"]').forEach(function(p) {
    p.style.display = p.dataset.locale === locale ? 'flex' : 'none';
  });
}

function toggleLanguageSection(enabled) {
  document.getElementById('language-section').style.display = enabled ? '' : 'none';
}

function previewOgImage(input, previewId) {
  var preview = document.getElementById(previewId);
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Sync language checkboxes to hidden field before submit
document.querySelector('form').addEventListener('submit', function() {
  var checks = document.querySelectorAll('input[name="settings[language][]"]');
  var vals = [];
  checks.forEach(function(c) { if (c.checked) vals.push(c.value); });
  document.getElementById('language-hidden').value = vals.join(',');
});
</script>
@endsection
