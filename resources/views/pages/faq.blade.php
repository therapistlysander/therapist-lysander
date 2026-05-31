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
    gap: var(--space-16);
    align-items: start;
  }
  .faq-sidebar { position: sticky; top: 88px; }
  .faq-sidebar__title { font-size: var(--size-xs); letter-spacing: 0.12em; text-transform: uppercase; color: var(--color-text-light); margin-bottom: var(--space-4); }
  .faq-sidebar__link { display: block; font-size: var(--size-sm); color: var(--color-text-muted); padding: var(--space-2) 0; border-left: 2px solid transparent; padding-left: var(--space-3); transition: color 0.2s, border-color 0.2s; line-height: 1.4; }
  .faq-sidebar__link:hover, .faq-sidebar__link.active { color: var(--color-teal); border-left-color: var(--color-teal); }

  .faq-category { margin-bottom: var(--space-16); }
  .faq-category:last-child { margin-bottom: 0; }
  .faq-category__header { display: flex; align-items: center; gap: var(--space-3); margin-bottom: var(--space-6); padding-bottom: var(--space-4); border-bottom: 2px solid var(--color-teal-light); }
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

  .faq-cta { background: var(--color-bg-dark); border-radius: var(--radius-md); padding: var(--space-10) var(--space-12); display: flex; align-items: center; justify-content: space-between; gap: var(--space-8); margin-top: var(--space-16); max-width: 100%; overflow: hidden; }
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
          <p class="faq-sidebar__title">Categories</p>
          @php
            $faqCategories = $faqs->keys();
            $categoryLabels = [
              'general' => 'Getting started',
              'booking' => 'Booking',
              'fees' => 'Fees &amp; Insurance',
              'sessions' => 'Sessions &amp; format',
              'approach' => 'Therapy approaches',
            ];
          @endphp
          @foreach($faqCategories as $cat)
            <a href="#{{ $cat }}" class="faq-sidebar__link">{{ $categoryLabels[$cat] ?? ucfirst($cat) }}</a>
          @endforeach
        </aside>

        <!-- FAQ content -->
        <div>
          @php
            $categoryIcons = [
              'general' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>',
              'booking' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>',
              'fees' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/></svg>',
              'sessions' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>',
              'approach' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>',
            ];
          @endphp

          @foreach($faqs as $category => $items)
          <div class="faq-category" id="{{ $category }}">
            <div class="faq-category__header">
              <div class="faq-category__icon">
                {!! $categoryIcons[$category] ?? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>' !!}
              </div>
              <h2 class="faq-category__title">{{ $categoryLabels[$category] ?? ucfirst($category) }}</h2>
            </div>
            <div class="faq-list">
              @foreach($items as $faq)
              <div class="faq-item">
                <button class="faq-toggle" aria-expanded="false">
                  {{ $faq->question }}
                  <span class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
                </button>
                <div class="faq-body"><div class="faq-body__inner">
                  <p>{{ $faq->answer }}</p>
                </div></div>
              </div>
              @endforeach
            </div>
          </div>
          @endforeach

          <!-- Bottom CTA -->
          <div class="faq-cta fade-in">
            <div>
              <h3>{{ $ctaSec?->content['heading'] ?? 'Still have questions?' }}</h3>
              <p>{!! $ctaSec?->content['body'] ?? "Feel free to reach out directly — I'm happy to answer any questions before you decide to book." !!}</p>
            </div>
            <a href="{{ $ctaSec?->content['cta_url'] ?? route('contact') }}" class="btn btn--primary btn--lg" style="white-space:nowrap;">
              {{ $ctaSec?->content['cta_label'] ?? 'Contact Me' }}
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
