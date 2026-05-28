@extends('admin.layouts.admin')
@section('title', $testimonial->exists ? 'Edit Testimonial' : 'Add Testimonial')
@section('page_title', $testimonial->exists ? 'Edit Testimonial' : 'Add Testimonial')

@section('content')
<form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}">
  @csrf
  @if($testimonial->exists) @method('PUT') @endif

  <div class="admin-page-header" style="position:sticky;top:56px;z-index:40;background:#f1f3f5;padding:16px 0;margin:-28px -28px 24px;padding:16px 28px;border-bottom:1px solid #e5e7eb;">
    <h1 style="font-size:18px;">{{ $testimonial->exists ? 'Edit Testimonial' : 'Add Testimonial' }}</h1>
    <div style="display:flex;gap:8px;">
      <a href="{{ route('admin.testimonials.index') }}" class="btn-admin btn-admin--outline">&larr; Back</a>
      <button type="submit" class="btn-admin btn-admin--primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        {{ $testimonial->exists ? 'Save changes' : 'Create testimonial' }}
      </button>
    </div>
  </div>

  @if($errors->any())
    <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
  @endif

  <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">

    {{-- Left: Content --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
      <div class="admin-form">
        <div class="admin-form__section">
          <div class="admin-form__section-title">Client Information</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="admin-field">
              <label class="admin-label">Client name <span style="color:#dc2626;">*</span></label>
              <input type="text" name="client_name" class="admin-input" value="{{ old('client_name', $testimonial->client_name) }}" required>
            </div>
            <div class="admin-field">
              <label class="admin-label">Tag / therapy type</label>
              <input type="text" name="tag" class="admin-input" value="{{ old('tag', $testimonial->tag) }}" placeholder="e.g. EMDR Therapy">
            </div>
          </div>
        </div>
      </div>

      <div class="admin-form">
        <div class="admin-form__section">
          <div class="admin-form__section-title">Content</div>
          <div class="admin-field">
            <label class="admin-label">Headline (short pull quote)</label>
            <input type="text" name="headline" class="admin-input" value="{{ old('headline', $testimonial->headline) }}" placeholder="A short memorable line">
          </div>
          <div class="admin-field">
            <label class="admin-label">Full testimonial body <span style="color:#dc2626;">*</span></label>
            <input type="hidden" id="body-input" name="body" value="{{ old('body', $testimonial->body) }}">
            <div class="admin-editor-wrap" data-editor="body-input" data-placeholder="Write the full testimonial text...">
              <div class="ql-editor-area"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Right: Settings --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
      <div class="admin-form">
        <div class="admin-form__section">
          <div class="admin-form__section-title">Settings</div>
          <div class="admin-field">
            <label class="admin-label">Rating (1-5)</label>
            <input type="number" name="rating" class="admin-input" min="1" max="5" value="{{ old('rating', $testimonial->rating ?? 5) }}">
          </div>
          <div class="admin-field">
            <label class="admin-label">Sort order</label>
            <input type="number" name="sort_order" class="admin-input" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}">
          </div>
          <div style="display:flex;flex-direction:column;gap:10px;margin-top:12px;">
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
              <input type="hidden" name="is_featured" value="0">
              <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }}>
              Featured (show on homepage)
            </label>
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
              <input type="hidden" name="is_active" value="0">
              <input type="checkbox" name="is_active" value="1" {{ old('is_active', $testimonial->is_active ?? true) ? 'checked' : '' }}>
              Active (visible on site)
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection
