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

      {{-- Locale Tabs --}}
      <div class="locale-tabs" style="display:flex;gap:0;border-bottom:2px solid #e5e7eb;">
        @foreach($locales as $locale)
        <button type="button" class="locale-tab {{ $loop->first ? 'locale-tab--active' : '' }}" data-locale="{{ $locale }}" onclick="switchLocale('{{ $locale }}')">
          {{ strtoupper($locale) }}
        </button>
        @endforeach
      </div>

      @foreach($locales as $locale)
      <div class="locale-panel" data-locale="{{ $locale }}" style="{{ !$loop->first ? 'display:none;' : 'display:flex;' }}flex-direction:column;gap:20px;">
        <div class="admin-form">
          <div class="admin-form__section">
            <div class="admin-form__section-title">Question & Answer — {{ strtoupper($locale) }}</div>
            <div class="admin-field">
              <label class="admin-label">Question <span style="color:#dc2626;">*</span></label>
              <input type="text" name="translations[{{ $locale }}][question]" class="admin-input" value="{{ old("translations.$locale.question", $faq->exists ? ($faq->getTranslation('question', $locale) ?? '') : '') }}" required placeholder="What question does this answer?">
            </div>
            <div class="admin-field">
              <label class="admin-label">Answer <span style="color:#dc2626;">*</span></label>
              <input type="hidden" id="answer-{{ $locale }}-input" name="translations[{{ $locale }}][answer]" value="{{ old("translations.$locale.answer", $faq->exists ? ($faq->getTranslation('answer', $locale) ?? '') : '') }}">
              <div class="admin-editor-wrap" data-editor="answer-{{ $locale }}-input" data-placeholder="Write the answer to this FAQ...">
                <div class="ql-editor-area"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    {{-- Right: Settings --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
      <div class="admin-form">
        <div class="admin-form__section">
          <div class="admin-form__section-title">Settings</div>
          <div class="admin-field">
            <label class="admin-label">Category <span style="color:#dc2626;">*</span></label>
            <select name="category" class="admin-select" required>
              @foreach($categories ?? ['therapy_emdr'=>'Therapy & EMDR','starting_therapy'=>'Starting Therapy','practical'=>'Practical Information','sessions_progress'=>'Sessions & Progress'] as $cat => $label)
                <option value="{{ $cat }}" {{ old('category', $faq->category) === $cat ? 'selected' : '' }}>{{ $label }}</option>
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

@section('page_styles')
<style>
  .locale-tab {
    padding: 8px 20px; font-size: 13px; font-weight: 500; cursor: pointer;
    border: none; background: none; color: #6b7280; border-bottom: 2px solid transparent;
    margin-bottom: -2px; transition: all 0.15s;
  }
  .locale-tab:hover { color: #374151; }
  .locale-tab--active { color: #5a9e97; border-bottom-color: #5a9e97; }
</style>
@endsection

@section('page_scripts')
<script>
function switchLocale(locale) {
  document.querySelectorAll('.locale-tab').forEach(t => t.classList.toggle('locale-tab--active', t.dataset.locale === locale));
  document.querySelectorAll('.locale-panel').forEach(p => p.style.display = p.dataset.locale === locale ? '' : 'none');
}
</script>
@endsection
