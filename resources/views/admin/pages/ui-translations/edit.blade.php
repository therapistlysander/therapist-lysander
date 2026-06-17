@extends('admin.layouts.admin')
@section('title', 'Edit: ' . $groupLabel)
@section('page_title', $groupLabel)

@section('content')
<div class="admin-page-header" style="display:flex;align-items:center;gap:12px;">
  <a href="{{ route('admin.ui-translations.index') }}" style="color:#6366f1;text-decoration:none;font-size:14px;">&larr; All Sections</a>
  <h1 style="margin:0;">{{ $groupLabel }}</h1>
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
            <th style="text-align:left;padding:8px 12px;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;width:25%;">Content</th>
            <th style="text-align:left;padding:8px 12px;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;width:35%;">English</th>
            <th style="text-align:left;padding:8px 12px;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;width:35%;">Dutch</th>
            <th style="width:5%;"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($translations as $key => $localeRows)
          @php
            $enRow = $localeRows->firstWhere('locale', 'en');
            $nlRow = $localeRows->firstWhere('locale', 'nl');
            $enValue = $enRow?->value ?? '';
            $nlValue = $nlRow?->value ?? '';
            $label = $localeRows->first()?->label ?? ucwords(str_replace('_', ' ', $key));
            $isLong = strlen($enValue) > 80 || strlen($nlValue) > 80 || str_contains($enValue, "\n");
            $hasEn = $enRow !== null;
            $hasNl = $nlRow !== null;
          @endphp
          <tr style="border-bottom:1px solid #f3f4f6;vertical-align:top;">
            <td style="padding:10px 12px;">
              <div style="font-weight:500;font-size:13px;color:#111827;">{{ $label }}</div>
              <input type="hidden" name="labels[{{ $key }}]" value="{{ $label }}">
            </td>
            <td style="padding:10px 12px;">
              @if($isLong)
                <textarea name="translations[{{ $key }}][en]" rows="3" style="width:100%;padding:6px 8px;border:1px solid #d1d5db;border-radius:4px;font-size:13px;color:#111827;resize:vertical;font-family:inherit;">{{ $enValue }}</textarea>
              @else
                <input type="text" name="translations[{{ $key }}][en]" value="{{ $enValue }}" style="width:100%;padding:6px 8px;border:1px solid #d1d5db;border-radius:4px;font-size:13px;color:#111827;">
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
              @if($hasEn && $hasNl)
                <span style="color:#10b981;font-size:16px;" title="Both languages filled">&#10003;</span>
              @elseif($hasEn || $hasNl)
                <span style="color:#f59e0b;font-size:14px;" title="Missing a language">&#9888;</span>
              @else
                <span style="color:#ef4444;font-size:14px;" title="No translations">&#10007;</span>
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
