@extends('layouts.app')

@section('title', 'Testimonials | Therapist Lysander')
@section('meta_description', 'Read what clients say about therapy with Lysander Verschuur, MSc. — real testimonials about EMDR, trauma therapy, and psychological recovery.')
@section('canonical', 'https://www.therapistlysander.com/clients/')

@php
  $hero      = $sections['testimonials_hero'] ?? null;
  $quote     = $sections['testimonials_quote'] ?? null;
  $gridHdr   = $sections['testimonials_grid'] ?? null;
  $cta       = $sections['testimonials_cta'] ?? null;
@endphp

@section('content')
<div class="scroll-progress" id="scroll-progress" aria-hidden="true"></div>
<main id="main-content">

  <div class="page-hero">
    <div class="container--narrow">
      <span class="page-hero__eyebrow">{{ $hero?->content['subheading'] ?? 'Client experiences' }}</span>
      <h1 class="page-hero__title">{{ $hero?->content['heading'] ?? 'What clients say' }}</h1>
      <div class="page-hero__text">{!! $hero?->content['body'] ?? '<p>These testimonials are shared with permission and reflect genuine experiences from therapy.</p>' !!}</div>
    </div>
  </div>

  <!-- Featured quote -->
  <div style="background:var(--color-bg-dark);padding:var(--space-12) 0;">
    <div class="container--narrow" style="text-align:center;">
      <p style="font-family:var(--font-heading);font-size:clamp(var(--size-xl),2.5vw,var(--size-2xl));color:var(--color-white);font-style:italic;line-height:1.5;">{!! $quote?->content['body'] ?? '"For the first time, I felt safe enough to face memories that used to control me."' !!}</p>
      <p style="color:var(--color-accent-light);font-size:var(--size-sm);letter-spacing:0.1em;text-transform:uppercase;margin-top:var(--space-4);">{{ $quote?->content['attribution'] ?? '— Paul' }}</p>
    </div>
  </div>

  <!-- Long-form testimonials from DB -->
  <section class="section section--white" aria-labelledby="testimonials-heading">
    <div class="container">

      @foreach($testimonials as $i => $t)
      <div class="testimonial-long {{ $i % 2 !== 0 ? 'testimonial-long--reverse' : '' }} fade-in">
        @if($i % 2 !== 0)
        <div class="testimonial-long__media">
          <img src="/images/de8d235e4bd94eb8-a3c153_20122b9a32cc4e9a9faca835b9f82d14-mv2.jpg" alt="Calm reflective landscape" loading="lazy" width="600" height="520">
        </div>
        @endif
        <div class="testimonial-long__content">
          <p class="testimonial-long__headline">{{ $t->headline ?? Str::limit(strip_tags($t->body), 80) }}</p>
          <div class="testimonial-long__text">
            {!! $t->body !!}
          </div>
          <p class="testimonial-long__sig">— {{ $t->client_name }}</p>
        </div>
        @if($i % 2 === 0)
        <div class="testimonial-long__media">
          <img src="/images/1cea4c553e34803a-a3c153_bbf1019446e34069a3b96c18f172e810-mv2.jpg" alt="Scenic peaceful landscape" loading="lazy" width="600" height="520">
        </div>
        @endif
      </div>
      @endforeach

    </div>
  </section>

  <!-- Quick quotes carousel -->
  @if($featuredTestimonials->count() > 0)
  <section class="section section--alt" aria-label="Additional client quotes">
    <div class="container">
      <div class="section-header fade-in" style="text-align:center;">
        <span class="section-label">More voices</span>
        <h2>Further reflections</h2>
      </div>
      <div class="t-carousel" id="t-carousel-featured">
        <div class="t-carousel__track">
          @foreach($featuredTestimonials as $t)
          <div class="t-carousel__slide">
            <div class="testimonial testimonial--card {{ $loop->iteration === 2 ? 'testimonial--featured' : '' }}">
              <span class="testimonial__icon" aria-hidden="true">&ldquo;</span>
              <div class="testimonial__quote">{!! $t->body !!}</div>
              <div class="testimonial__footer">
                <p class="testimonial__name">{{ $t->client_name }}</p>
                @if($t->tag)<p class="testimonial__tag">{{ $t->tag }}</p>@endif
              </div>
            </div>
          </div>
          @endforeach
        </div>
        @if($featuredTestimonials->count() > 3)
        <div class="t-carousel__nav">
          <button class="t-carousel__arrow t-carousel__prev" aria-label="Previous testimonials">&#8249;</button>
          <div class="t-carousel__dots" id="t-carousel-featured-dots"></div>
          <button class="t-carousel__arrow t-carousel__next" aria-label="Next testimonials">&#8250;</button>
        </div>
        @endif
      </div>
    </div>
  </section>
  @endif

  <!-- CTA -->
  <div class="cta-section">
    <div class="container--narrow">
      <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">Ready to take the next step?</span>
      <h2>{{ $cta?->content['heading'] ?? 'Meaningful and lasting change' }}</h2>
      <p>{!! $cta?->content['body'] ?? 'Whether you\'re struggling with trauma, anxiety, self-worth, or feeling stuck in recurring patterns, therapy can help create meaningful and lasting change. The first conversation is free and without obligation.' !!}</p>
      <div class="cta-section__actions">
        <a href="{{ $cta?->content['cta_primary_url'] ?? route('booking') }}" class="btn btn--primary btn--lg">{{ $cta?->content['cta_primary_label'] ?? 'Book a Free 30-Minute Intro Call' }}</a>
      </div>
    </div>
  </div>

</main>
@endsection

@section('page_scripts')
<script>
(function() {
  document.querySelectorAll('.t-carousel').forEach(carousel => {
    const slides = carousel.querySelectorAll('.t-carousel__slide');
    const dotsContainer = carousel.querySelector('.t-carousel__dots');
    const prevBtn = carousel.querySelector('.t-carousel__prev');
    const nextBtn = carousel.querySelector('.t-carousel__next');
    if (!slides.length || !dotsContainer) return;

    let perPage = 3, currentPage = 0;
    function getPerPage() {
      if (window.innerWidth <= 640) return 1;
      if (window.innerWidth <= 900) return 2;
      return 3;
    }
    function totalPages() { return Math.max(1, Math.ceil(slides.length / perPage)); }
    function renderDots() {
      dotsContainer.innerHTML = '';
      for (let i = 0; i < totalPages(); i++) {
        const dot = document.createElement('button');
        dot.className = 't-carousel__dot' + (i === currentPage ? ' active' : '');
        dot.setAttribute('aria-label', 'Page ' + (i + 1));
        dot.addEventListener('click', () => goTo(i));
        dotsContainer.appendChild(dot);
      }
    }
    function updateVisibility() {
      slides.forEach((slide, idx) => {
        slide.style.display = (Math.floor(idx / perPage) === currentPage) ? '' : 'none';
      });
      dotsContainer.querySelectorAll('.t-carousel__dot').forEach((dot, idx) => {
        dot.classList.toggle('active', idx === currentPage);
      });
      if (prevBtn) prevBtn.disabled = currentPage === 0;
      if (nextBtn) nextBtn.disabled = currentPage >= totalPages() - 1;
    }
    function goTo(page) {
      currentPage = Math.max(0, Math.min(page, totalPages() - 1));
      updateVisibility();
    }
    if (prevBtn) prevBtn.addEventListener('click', () => goTo(currentPage - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => goTo(currentPage + 1));
    function init() {
      perPage = getPerPage();
      if (currentPage >= totalPages()) currentPage = totalPages() - 1;
      renderDots();
      updateVisibility();
    }
    init();
    let timer;
    window.addEventListener('resize', () => { clearTimeout(timer); timer = setTimeout(init, 150); });
  });
})();
</script>
@endsection
