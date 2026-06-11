@extends('layouts.app')

@section('title', 'FAQ | Therapist Lysander')
@section('meta_description', 'Frequently asked questions about therapy with Lysander Verschuur — fees, sessions, EMDR, online therapy, and how to get started.')
@section('canonical', 'https://www.therapistlysander.com/faq/')

@php
  $hero   = $sections['faq_hero'] ?? null;
  $ctaSec = $sections['faq_cta'] ?? null;
@endphp

@section('page_styles')
<style>
  .faq-page-grid {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: var(--space-12);
    align-items: start;
  }
  .faq-sidebar { position: sticky; top: 104px; }
  .faq-sidebar__title { font-size: var(--size-xs); letter-spacing: 0.12em; text-transform: uppercase; color: var(--color-text-light); margin-bottom: var(--space-4); }
  .faq-sidebar__link { display: block; font-size: var(--size-sm); color: var(--color-text-muted); padding: var(--space-2) 0; border-left: 2px solid transparent; padding-left: var(--space-3); transition: color 0.2s, border-color 0.2s; line-height: 1.4; }
  .faq-sidebar__link:hover, .faq-sidebar__link.active { color: var(--color-teal); border-left-color: var(--color-teal); }

  .faq-category { margin-bottom: var(--space-10); scroll-margin-top: 100px; }
  .faq-category:last-child { margin-bottom: 0; }
  .faq-category__header { display: flex; align-items: center; gap: var(--space-3); margin-bottom: var(--space-4); padding-bottom: var(--space-3); border-bottom: 2px solid var(--color-teal-light); }
  .faq-category__icon { width: 40px; height: 40px; background: var(--color-teal-light); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; color: var(--color-teal); flex-shrink: 0; }
  .faq-category__icon svg { width: 20px; height: 20px; }
  .faq-category__title { font-family: var(--font-heading); font-size: var(--size-xl); color: var(--color-text); margin: 0; }

  .faq-list { border-top: 1px solid var(--color-border); }
  .faq-item { border-bottom: 1px solid var(--color-border); }
  .faq-toggle { width: 100%; text-align: left; padding: var(--space-4) 0; font-family: var(--font-body); font-size: var(--size-base); font-weight: 500; color: var(--color-text); display: flex; justify-content: space-between; align-items: center; gap: var(--space-4); cursor: pointer; background: transparent; border: none; line-height: 1.4; transition: color 0.2s; }
  .faq-toggle:hover, .faq-item.open .faq-toggle { color: var(--color-teal); }
  .faq-icon { width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--color-accent); transition: color 0.2s; }
  .faq-icon svg { width: 14px; height: 14px; transition: transform 0.25s ease; }
  .faq-item.open .faq-icon { color: var(--color-teal); }
  .faq-item.open .faq-icon svg { transform: rotate(45deg); }
  .faq-body { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
  .faq-body.open { max-height: 600px; }
  .faq-body__inner { padding-bottom: var(--space-5); }
  .faq-body p { font-size: var(--size-sm); color: var(--color-text-muted); line-height: 1.8; max-width: none; margin-bottom: var(--space-3); }
  .faq-body p:last-child { margin-bottom: 0; }

  .faq-cta { background: var(--color-bg-dark); border-radius: var(--radius-md); padding: var(--space-6) var(--space-8); display: flex; align-items: center; justify-content: space-between; gap: var(--space-6); margin-top: var(--space-10); max-width: 100%; overflow: hidden; }
  .faq-cta h3 { color: var(--color-white); margin-bottom: var(--space-2); }
  .faq-cta p { color: rgba(255,255,255,0.65); font-size: var(--size-sm); margin: 0; }

  @media (max-width: 860px) {
    .faq-page-grid { grid-template-columns: minmax(0, 1fr); }
    .faq-sidebar { display: none; }
    .faq-cta { flex-direction: column; text-align: center; }
  }
</style>
@endsection

@section('content')
<div class="scroll-progress" id="scroll-progress"></div>
<main id="main-content">

  <div class="page-hero">
    <div class="container--narrow">
      <span class="page-hero__eyebrow">{{ $hero?->content['subheading'] ?? 'Questions & Answers' }}</span>
      <h1 class="page-hero__title">{{ $hero?->content['heading'] ?? 'Frequently Asked Questions' }}</h1>
      <div class="page-hero__text">{!! $hero?->content['body'] ?? "<p>Answers to common questions about therapy, trauma treatment, EMDR, practical matters, and what to expect when working together.</p>" !!}</div>
    </div>
  </div>

  <section class="section section--white">
    <div class="container">
      <div class="faq-page-grid">

        <!-- Sidebar -->
        <aside class="faq-sidebar">
          <p class="faq-sidebar__title">{{ __('ui.faq.categories') }}</p>
          @php
            $cmsCategories = $sections['faq_categories']?->content['categories'] ?? [];
            $categoryLabels = [];
            foreach ($cmsCategories as $cat) {
              $categoryLabels[$cat['key']] = $cat['label'];
            }
            if (empty($categoryLabels)) {
              $categoryLabels = [
                'therapy_emdr' => 'Therapy & EMDR',
                'starting_therapy' => 'Starting Therapy',
                'practical' => 'Practical Information',
                'sessions_progress' => 'Sessions & Progress',
              ];
            }
          @endphp
          @foreach($categoryLabels as $catKey => $catLabel)
            @if($faqs->has($catKey))
              <a href="#{{ $catKey }}" class="faq-sidebar__link">{{ $catLabel }}</a>
            @endif
          @endforeach
        </aside>

        <!-- FAQ content -->
        <div>
          @php
            $categoryIcons = [
              'therapy_emdr' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>',
              'starting_therapy' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>',
              'practical' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>',
              'sessions_progress' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>',
            ];
          @endphp

          @foreach($categoryLabels as $category => $label)
            @if(!$faqs->has($category)) @continue @endif
            @php $items = $faqs[$category]; @endphp
          <div class="faq-category" id="{{ $category }}">
            <div class="faq-category__header">
              <div class="faq-category__icon">
                {!! $categoryIcons[$category] ?? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>' !!}
              </div>
              <h2 class="faq-category__title">{{ $label }}</h2>
            </div>
            <div class="faq-list">
              @foreach($items as $faq)
              <div class="faq-item">
                <button class="faq-toggle" aria-expanded="false">
                  {{ $faq->question }}
                  <span class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
                </button>
                <div class="faq-body"><div class="faq-body__inner">
                  {!! $faq->answer !!}
                </div></div>
              </div>
              @endforeach
            </div>
          </div>
          @endforeach

          <!-- Bottom CTA -->
          <div class="faq-cta fade-in">
            <div>
              <h3>{{ __('ui.faq.still_questions') }}</h3>
              <p>{{ __('ui.faq.still_questions_desc') }}</p>
            </div>
            <a href="{{ route('contact') }}" class="btn btn--primary btn--lg" style="white-space:nowrap;">
              {{ __('ui.common.contact_me') }}
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
          </div>

        </div>
      </div>
    </div>
  </section>

</main>
@endsection

@section('page_scripts')
<script>
document.querySelectorAll('.faq-toggle').forEach(btn => {
  btn.addEventListener('click', () => {
    const item = btn.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    item.closest('.faq-list').querySelectorAll('.faq-item').forEach(i => {
      i.classList.remove('open');
      i.querySelector('.faq-toggle').setAttribute('aria-expanded', 'false');
      i.querySelector('.faq-body').classList.remove('open');
    });
    if (!isOpen) {
      item.classList.add('open');
      btn.setAttribute('aria-expanded', 'true');
      item.querySelector('.faq-body').classList.add('open');
    }
  });
});

const faqCats = document.querySelectorAll('.faq-category');
const sidebarLinks = document.querySelectorAll('.faq-sidebar__link');
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      sidebarLinks.forEach(l => l.classList.remove('active'));
      const link = document.querySelector('.faq-sidebar__link[href="#' + entry.target.id + '"]');
      if (link) link.classList.add('active');
    }
  });
}, { rootMargin: '-30% 0px -60% 0px' });
faqCats.forEach(c => observer.observe(c));
</script>
@endsection
