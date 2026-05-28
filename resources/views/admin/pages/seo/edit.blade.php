@extends('admin.layouts.admin')
@section('title', 'Edit SEO: ' . $pageKey)
@section('page_title', 'Edit SEO Settings')

@section('content')
<form method="POST" action="{{ route('admin.seo.update', $pageKey) }}">
  @csrf @method('PATCH')

  <div class="admin-page-header" style="position:sticky;top:56px;z-index:40;background:#f1f3f5;padding:16px 0;margin:-28px -28px 24px;padding:16px 28px;border-bottom:1px solid #e5e7eb;">
    <div>
      <h1 style="font-size:18px;">SEO Settings</h1>
      <p style="font-size:12px;color:#9ca3af;margin:2px 0 0;">Page: <code style="font-size:11px;background:#e5e7eb;padding:1px 5px;border-radius:3px;">{{ $pageKey }}</code></p>
    </div>
    <div style="display:flex;gap:8px;">
      <a href="{{ route('admin.seo.index') }}" class="btn-admin btn-admin--outline">&larr; Back</a>
      <button type="submit" class="btn-admin btn-admin--primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        Save SEO settings
      </button>
    </div>
  </div>

  @if($errors->any())
    <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
  @endif

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

    {{-- Left: Page Title & Description --}}
    <div class="admin-form">
      <div class="admin-form__section">
        <div class="admin-form__section-title">Page Title & Description</div>
        <div class="admin-field">
          <label class="admin-label">Page title</label>
          <input type="text" name="title" class="admin-input" value="{{ old('title', $seo->title) }}" placeholder="e.g. Trauma Therapy | Therapist Lysander">
          <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Recommended: 50-60 characters</p>
        </div>
        <div class="admin-field">
          <label class="admin-label">Meta description</label>
          <textarea name="meta_description" class="admin-input" rows="4" style="resize:vertical;">{{ old('meta_description', $seo->meta_description) }}</textarea>
          <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Recommended: 150-160 characters</p>
        </div>
        <div class="admin-field">
          <label class="admin-label">Canonical URL</label>
          <input type="url" name="canonical_url" class="admin-input" value="{{ old('canonical_url', $seo->canonical_url) }}" placeholder="https://www.therapistlysander.com/...">
        </div>
      </div>
    </div>

    {{-- Right: Open Graph --}}
    <div class="admin-form">
      <div class="admin-form__section">
        <div class="admin-form__section-title">Open Graph (Social Sharing)</div>
        <div class="admin-field">
          <label class="admin-label">OG title</label>
          <input type="text" name="og_title" class="admin-input" value="{{ old('og_title', $seo->og_title) }}" placeholder="Title shown when shared on social media">
        </div>
        <div class="admin-field">
          <label class="admin-label">OG description</label>
          <textarea name="og_description" class="admin-input" rows="4" style="resize:vertical;">{{ old('og_description', $seo->og_description) }}</textarea>
          <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Description shown when shared on Facebook, LinkedIn, etc.</p>
        </div>
      </div>
    </div>

  </div>
</form>
@endsection
