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
          <p class="testimonial-long__headline">{{ $t->headline ?? Str::limit($t->body, 80) }}</p>
          <div class="testimonial-long__text">
            {!! nl2br(e($t->body)) !!}
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

  <!-- Quick quotes grid -->
  @if($featuredTestimonials->count() > 0)
  <section class="section section--alt" aria-label="Additional client quotes">
    <div class="container">
      <div class="section-header fade-in" style="text-align:center;">
        <span class="section-label">More voices</span>
        <h2>Further reflections</h2>
      </div>
      <div class="testimonial-grid">
        @foreach($featuredTestimonials as $t)
        <div class="testimonial {{ $loop->iteration === 2 ? 'testimonial--featured' : '' }} fade-in">
          <p class="testimonial__quote">{{ $t->body }}</p>
          <p class="testimonial__name">— {{ $t->client_name }}</p>
          @if($t->tag)<p class="testimonial__tag">{{ $t->tag }}</p>@endif
        </div>
        @endforeach
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
