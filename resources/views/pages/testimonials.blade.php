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
        <a href="{{ $cta?->content['cta_secondary_url'] ?? 'https://wa.me/66935309052?text=Hi%20Lysander%2C%20I%27d%20like%20to%20learn%20more%20about%20therapy.' }}" target="_blank" rel="noopener noreferrer" class="btn btn--whatsapp btn--lg">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="18" height="18"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          {{ $cta?->content['cta_secondary_label'] ?? 'WhatsApp me' }}
        </a>
      </div>
    </div>
  </div>

</main>
@endsection
