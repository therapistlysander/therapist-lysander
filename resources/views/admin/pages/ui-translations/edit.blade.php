@extends('admin.layouts.admin')
@section('title', 'Edit: ' . $groupLabel)
@section('page_title', $groupLabel)

@section('content')
<div class="admin-page-header" style="display:flex;align-items:center;gap:12px;">
  <a href="{{ route('admin.ui-translations.index') }}" style="color:#6366f1;text-decoration:none;font-size:14px;">&larr; All Groups</a>
  <h1 style="margin:0;">{{ $groupLabel }}</h1>
  <code style="background:#f3f4f6;padding:4px 8px;border-radius:4px;font-size:12px;color:#6b7280;">{{ $group }}</code>
</div>

@if(session('success'))
  <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
@endif
@if($errors->any())
  <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('admin.ui-translations.update', $group) }}">
  @csrf @method('PATCH')

  <div class="admin-form">
    <div class="admin-form__section">
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="border-bottom:2px solid #e5e7eb;">
            <th style="text-align:left;padding:8px 12px;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;width:20%;">Key</th>
            <th style="text-align:left;padding:8px 12px;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;width:38%;">English (EN)</th>
            <th style="text-align:left;padding:8px 12px;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;width:38%;">Dutch (NL)</th>
            <th style="width:4%;"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($translations as $key => $localeRows)
          @php
            $enValue = $localeRows->firstWhere('locale', 'en')?->value ?? '';
            $nlValue = $localeRows->firstWhere('locale', 'nl')?->value ?? '';
            $label = $localeRows->first()?->label;
            $isLong = strlen($enValue) > 80 || strlen($nlValue) > 80 || str_contains($enValue, "\n");
          @endphp
          <tr style="border-bottom:1px solid #f3f4f6;vertical-align:top;">
            <td style="padding:10px 12px;">
              <code style="font-size:11px;color:#6366f1;background:#eef2ff;padding:2px 6px;border-radius:3px;word-break:break-all;">{{ $key }}</code>
              @if($label)
                <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ $label }}</div>
              @endif
            </td>
            <td style="padding:10px 12px;">
              @if($isLong)
                <textarea readonly rows="3" style="width:100%;padding:6px 8px;border:1px solid #e5e7eb;border-radius:4px;font-size:13px;color:#6b7280;background:#f9fafb;resize:vertical;font-family:inherit;">{{ $enValue }}</textarea>
              @else
                <input type="text" readonly value="{{ $enValue }}" style="width:100%;padding:6px 8px;border:1px solid #e5e7eb;border-radius:4px;font-size:13px;color:#6b7280;background:#f9fafb;">
              @endif
            </td>
            <td style="padding:10px 12px;">
              @if($isLong)
                <textarea name="translations[{{ $key }}][nl]" rows="3" style="width:100%;padding:6px 8px;border:1px solid #d1d5db;border-radius:4px;font-size:13px;color:#111827;resize:vertical;font-family:inherit;">{{ $nlValue }}</textarea>
              @else
                <input type="text" name="translations[{{ $key }}][nl]" value="{{ $nlValue }}" style="width:100%;padding:6px 8px;border:1px solid #d1d5db;border-radius:4px;font-size:13px;color:#111827;">
              @endif
            </td>
            <td style="padding:10px 4px;text-align:center;">
              @if($localeRows->count() >= 2)
                <span style="color:#10b981;font-size:16px;" title="Both locales present">&#10003;</span>
              @else
                <span style="color:#f59e0b;font-size:14px;" title="Missing a locale">&#9888;</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
    <a href="{{ route('admin.ui-translations.index') }}" class="btn-admin" style="background:#f3f4f6;color:#374151;text-decoration:none;padding:8px 16px;border-radius:6px;font-size:13px;">Cancel</a>
    <button type="submit" class="btn-admin btn-admin--primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Save Translations
    </button>
  </div>
</form>
@endsection
