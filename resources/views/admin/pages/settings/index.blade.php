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

<form method="POST" action="{{ route('admin.settings.update') }}" style="max-width:820px;">
  @csrf @method('PATCH')

  @php
    $groupLabels = [
      'general'    => 'General',
      'contact'    => 'Contact & Location',
      'social'     => 'Social Media & Profiles',
      'analytics'  => 'Analytics & Tracking',
      'endorsement' => 'Professional Endorsement',
    ];
    $groupOrder = ['general', 'contact', 'social', 'analytics', 'endorsement'];
    // Groups managed on other pages (e.g. /email-settings)
    $skipGroups = ['email', 'notifications'];
  @endphp

  @foreach($groupOrder as $group)
  @if(isset($settings[$group]))
  <div class="admin-form" style="margin-bottom:24px;">
    <div class="admin-form__section">
      <div class="admin-form__section-title">{{ $groupLabels[$group] ?? ucfirst($group) }}</div>

      @foreach($settings[$group] as $setting)
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

        @elseif($setting->key === 'endorsement_full_text')
          {{-- Bilingual textarea for endorsement full text --}}
          @php $ftVal = json_decode($setting->getRawOriginal('value'), true) ?: []; @endphp
          <div style="display:flex;flex-direction:column;gap:8px;">
            <div>
              <label style="font-size:11px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">EN</label>
              <textarea name="settings[{{ $setting->key }}][en]"
                class="admin-input" rows="6">{{ old("settings.{$setting->key}.en", $ftVal['en'] ?? '') }}</textarea>
            </div>
            <div>
              <label style="font-size:11px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">NL</label>
              <textarea name="settings[{{ $setting->key }}][nl]"
                class="admin-input" rows="6">{{ old("settings.{$setting->key}.nl", $ftVal['nl'] ?? '') }}</textarea>
            </div>
          </div>

        @elseif($setting->type === 'text')
          <textarea name="settings[{{ $setting->key }}]"
            id="setting-{{ $setting->key }}"
            class="admin-input"
            rows="4">{{ old('settings.' . $setting->key, $setting->getRawOriginal('value')) }}</textarea>

        @elseif($setting->type === 'json')
          @php $jsonVal = json_decode($setting->getRawOriginal('value'), true) ?: []; @endphp
          <div style="display:flex;flex-direction:column;gap:8px;">
            <div>
              <label style="font-size:11px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">EN</label>
              <input type="text"
                name="settings[{{ $setting->key }}][en]"
                class="admin-input"
                value="{{ old("settings.{$setting->key}.en", $jsonVal['en'] ?? '') }}">
            </div>
            <div>
              <label style="font-size:11px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">NL</label>
              <input type="text"
                name="settings[{{ $setting->key }}][nl]"
                class="admin-input"
                value="{{ old("settings.{$setting->key}.nl", $jsonVal['nl'] ?? '') }}">
            </div>
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

<script>
function toggleLanguageSection(enabled) {
  document.getElementById('language-section').style.display = enabled ? '' : 'none';
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
