@extends('admin.layouts.admin')
@section('title', 'UI Translations')
@section('page_title', 'UI Translations')

@section('content')
<div class="admin-page-header">
  <h1>UI Translations</h1>
  <p style="color:#6b7280;font-size:14px;margin-top:4px;">Manage all navigation labels, buttons, page titles, and static text across the site.</p>
</div>

@if(session('success'))
  <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;max-width:1000px;">
  @foreach($groups as $item)
  <a href="{{ route('admin.ui-translations.edit', $item->group) }}" 
     style="display:block;padding:20px;background:#fff;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;color:inherit;transition:border-color 0.15s,box-shadow 0.15s;"
     onmouseover="this.style.borderColor='#6366f1';this.style.boxShadow='0 2px 8px rgba(99,102,241,0.1)'"
     onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
    <div style="font-weight:600;font-size:15px;color:#111827;">
      {{ $groupLabels[$item->group] ?? ucfirst(str_replace('_', ' ', $item->group)) }}
    </div>
    <div style="font-size:12px;color:#9ca3af;margin-top:4px;">
      <code style="background:#f3f4f6;padding:2px 6px;border-radius:3px;font-size:11px;">{{ $item->group }}</code>
      <span style="margin-left:8px;">{{ $item->key_count }} keys</span>
      <span style="margin-left:4px;">· {{ $item->locale_count }} locales</span>
    </div>
  </a>
  @endforeach
</div>

@if($groups->isEmpty())
  <div style="padding:40px;text-align:center;color:#9ca3af;background:#fff;border:1px solid #e5e7eb;border-radius:8px;max-width:600px;">
    <p style="font-size:15px;">No UI translations found in database.</p>
    <p style="font-size:13px;margin-top:8px;">Run <code>php artisan db:seed --class=UiTranslationSeeder</code> to populate from translation files.</p>
  </div>
@endif
@endsection
