@extends('admin.layouts.admin')
@section('title', $faq->exists ? 'Edit FAQ' : 'Add FAQ')
@section('page_title', $faq->exists ? 'Edit FAQ' : 'Add FAQ')

@section('content')
<form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
  @csrf
  @if($faq->exists) @method('PUT') @endif

  <div class="admin-page-header" style="position:sticky;top:56px;z-index:40;background:#f1f3f5;padding:16px 0;margin:-28px -28px 24px;padding:16px 28px;border-bottom:1px solid #e5e7eb;">
    <h1 style="font-size:18px;">{{ $faq->exists ? 'Edit FAQ' : 'Add FAQ' }}</h1>
    <div style="display:flex;gap:8px;">
      <a href="{{ route('admin.faqs.index') }}" class="btn-admin btn-admin--outline">&larr; Back</a>
      <button type="submit" class="btn-admin btn-admin--primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        {{ $faq->exists ? 'Save changes' : 'Create FAQ' }}
      </button>
    </div>
  </div>

  @if($errors->any())
    <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
  @endif

  <div style="display:grid;grid-template-columns:1fr 280px;gap:24px;align-items:start;">

    {{-- Left: Content --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
      <div class="admin-form">
        <div class="admin-form__section">
          <div class="admin-form__section-title">Question & Answer</div>
          <div class="admin-field">
            <label class="admin-label">Question <span style="color:#dc2626;">*</span></label>
            <input type="text" name="question" class="admin-input" value="{{ old('question', $faq->question) }}" required placeholder="What question does this answer?">
          </div>
          <div class="admin-field">
            <label class="admin-label">Answer <span style="color:#dc2626;">*</span></label>
            <input type="hidden" id="answer-input" name="answer" value="{{ old('answer', $faq->answer) }}">
            <div class="admin-editor-wrap" data-editor="answer-input" data-placeholder="Write the answer to this FAQ...">
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
            <label class="admin-label">Category <span style="color:#dc2626;">*</span></label>
            <select name="category" class="admin-select" required>
              @foreach(['general','booking','fees','sessions','approach'] as $cat)
                <option value="{{ $cat }}" {{ old('category', $faq->category) === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-field">
            <label class="admin-label">Sort order</label>
            <input type="number" name="sort_order" class="admin-input" value="{{ old('sort_order', $faq->sort_order ?? 0) }}">
          </div>
          <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;margin-top:12px;">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }}>
            Active (visible on site)
          </label>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection
