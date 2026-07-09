@extends('layouts.app')

@section('title', __('ui.page_title.testimonials'))
@section('meta_description', 'Read what clients say about therapy with Lysander Verschuur, MSc. — real testimonials about EMDR, trauma therapy, and psychological recovery.')
@section('canonical', 'https://www.therapistlysander.com/clients/')

@php
  $hero      = $sections['testimonials_hero'] ?? null;
  $cta       = $sections['testimonials_cta'] ?? null;
  $heroImage = $hero?->content['image'] ?? '/images/testimonials-v2-top.png';
@endphp

@section('page_styles')
<style>
  /* --- Testimonials page redesign --- */

  .testimonials-hero {
    position: relative;
    padding: calc(80px + var(--space-16)) 0 var(--space-12);
    text-align: center;
    overflow: hidden;
    background: var(--color-bg);
  }
  .testimonials-hero__bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.20;
    pointer-events: none;
  }
  .testimonials-hero .container--narrow {
    position: relative;
    z-index: 1;
  }
  .testimonials-hero__title {
    font-family: var(--font-heading);
    font-size: clamp(var(--size-3xl), 5vw, var(--size-5xl));
    color: var(--color-text);
    margin: 0 auto var(--space-4);
    line-height: 1.15;
    font-weight: 400;
  }
  .testimonials-hero__divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-3);
    margin: 0 auto var(--space-6);
    max-width: 100px;
  }
  .testimonials-hero__divider span {
    flex: 1;
    height: 1px;
    background: var(--color-accent);
  }
  .testimonials-hero__divider svg {
    width: 16px;
    height: 16px;
    color: var(--color-accent);
    flex-shrink: 0;
  }
  .testimonials-hero__subtitle {
    font-size: var(--size-base);
    color: var(--color-text-muted);
    max-width: 480px;
    margin: 0 auto;
    line-height: 1.7;
  }

  /* Cards grid */
  .testimonials-cards {
    padding: var(--space-12) 0;
    background: var(--color-white);
  }
  .testimonials-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--space-6);
  }
  .testimonial-card {
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    padding: var(--space-8) var(--space-6);
    display: flex;
    flex-direction: column;
  }
  .testimonial-card__quote-mark {
    font-family: Georgia, serif;
    font-size: 3rem;
    line-height: 1;
    color: #c4956a;
    margin-bottom: var(--space-3);
    user-select: none;
  }
  .testimonial-card__text {
    font-size: var(--size-base);
    color: var(--color-text-muted);
    line-height: 1.75;
    flex: 1;
    font-style: italic;
  }
  .testimonial-card__divider {
    width: 30px;
    height: 1.5px;
    background: var(--color-accent);
    margin: var(--space-5) 0;
  }
  .testimonial-card__name {
    font-family: var(--font-heading);
    font-size: var(--size-base);
    color: var(--color-text);
    font-weight: 600;
    margin-bottom: 2px;
  }
  .testimonial-card__role {
    font-size: var(--size-xs);
    color: #c4956a;
    letter-spacing: 0.04em;
  }

  /* Endorsement */
  .testimonials-endorsement {
    padding: var(--space-12) 0;
    background: var(--color-bg);
    border-top: 1px solid var(--color-border);
  }
  .endorsement-block {
    max-width: 720px;
    margin: 0 auto;
    text-align: center;
  }
  .endorsement-block__quote {
    font-family: var(--font-heading);
    font-size: clamp(var(--size-lg), 2vw, var(--size-xl));
    color: var(--color-text);
    line-height: 1.65;
    font-style: italic;
  }
  .endorsement-block__attribution {
    margin-top: var(--space-6);
    font-family: var(--font-heading);
    font-size: var(--size-base);
    color: var(--color-accent);
  }

  /* Closing */
  .testimonials-closing {
    padding: var(--space-12) 0 var(--space-16);
    background: var(--color-white);
  }
  .testimonials-closing .container--narrow {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  .testimonials-closing__text {
    font-family: Georgia, serif;
    font-size: var(--size-base);
    color: var(--color-text);
    line-height: 1.7;
    margin-bottom: var(--space-2);
    text-align: center;
  }
  .testimonials-closing__thanks {
    font-family: var(--font-heading);
    font-size: var(--size-lg);
    color: #c4956a;
    font-style: italic;
    margin-bottom: var(--space-5);
    text-align: center;
  }
  .testimonials-closing__divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-3);
    max-width: 100px;
    margin: 0 auto;
  }
  .testimonials-closing__divider span {
    flex: 1;
    height: 1px;
    background: var(--color-accent);
  }
  .testimonials-closing__divider svg {
    width: 16px;
    height: 16px;
    color: var(--color-accent);
    flex-shrink: 0;
  }

  /* Responsive */
  @media (max-width: 900px) {
    .testimonials-grid { grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 640px) {
    .testimonials-hero { padding: calc(80px + var(--space-10)) 0 var(--space-8); }
    .testimonials-hero__bg { opacity: 0.12; }
    .testimonials-cards { padding: var(--space-8) 0; }
    .testimonials-grid { grid-template-columns: 1fr; }
    .testimonials-endorsement { padding: var(--space-8) 0; }
    .testimonials-closing { padding: var(--space-8) 0 var(--space-12); }
  }
</style>
@endsection

@section('content')
<div class="scroll-progress" id="scroll-progress" aria-hidden="true"></div>
<main id="main-content">

  {{-- Hero --}}
  <div class="testimonials-hero">
    <img
      src="{{ $heroImage }}"
      alt=""
      class="testimonials-hero__bg"
      loading="eager"
      width="1792"
      height="1024"
      aria-hidden="true"
    >
    <div class="container--narrow">
      <h1 class="testimonials-hero__title">{{ $hero?->content['heading'] ?? __('ui.testimonials.hero_heading') }}</h1>
      <div class="testimonials-hero__divider">
        <span></span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
        <span></span>
      </div>
      <div class="testimonials-hero__subtitle">{!! $hero?->content['body'] ?? '<p>' . __('ui.testimonials.hero_body') . '</p>' !!}</div>
    </div>
  </div>

  {{-- Testimonial cards --}}
  <section class="testimonials-cards">
    <div class="container">
      <div class="testimonials-grid">
        @foreach($testimonials as $t)
        <div class="testimonial-card fade-in">
          <div class="testimonial-card__quote-mark">&ldquo;</div>
          <p class="testimonial-card__text">{{ $t->short_description ?? $t->headline ?? $t->quote ?? Str::limit(strip_tags($t->body), 120) }}</p>
          <div class="testimonial-card__divider"></div>
          <p class="testimonial-card__name">&mdash; {{ $t->client_name }}</p>
          <p class="testimonial-card__role">Client</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Professional Endorsement --}}
  @php
    $settingsHeading = $endorsementSettings['endorsement_heading'][$locale] ?? '';
    $settingsFullBody = $endorsementSettings['endorsement_full_body'][$locale] ?? '';
    $settingsAttribution = $endorsementSettings['endorsement_attribution'][$locale] ?? '';
  @endphp
  <section class="testimonials-endorsement" aria-labelledby="endorsement-heading">
    <div class="container--narrow">
      <div class="section-header fade-in" style="text-align:center;">
        <span class="section-label">{{ __('ui.testimonials.professional_recommendation') }}</span>
        <h2 id="endorsement-heading">{{ $settingsHeading ?: __('ui.testimonials.professional_recommendation_heading') }}</h2>
      </div>
      @if($endorsements->count() > 0)
        @foreach($endorsements as $endorsement)
        <div class="endorsement-block fade-in">
          <blockquote class="endorsement-block__quote">
            {!! nl2br(e($endorsement->body)) ?: ($settingsFullBody ? nl2br(e($settingsFullBody)) : '') !!}
          </blockquote>
          <p class="endorsement-block__attribution">
            &mdash; {{ $endorsement->client_name }}
          </p>
        </div>
        @endforeach
      @elseif($settingsFullBody)
        <div class="endorsement-block fade-in">
          <blockquote class="endorsement-block__quote">
            {!! nl2br(e($settingsFullBody)) !!}
          </blockquote>
          <p class="endorsement-block__attribution">
            &mdash; {{ $settingsAttribution ?: __('ui.home.endorsement_attribution') }}
          </p>
        </div>
      @else
        <div class="endorsement-block fade-in">
          <blockquote class="endorsement-block__quote">
            {!! __('ui.home.endorsement_body') !!}
          </blockquote>
          <p class="endorsement-block__attribution">
            &mdash; {{ __('ui.home.endorsement_attribution') }}
          </p>
        </div>
      @endif
    </div>
  </section>

  {{-- Closing --}}
  <div class="testimonials-closing">
    <div class="container--narrow">
      <p class="testimonials-closing__text">I'm grateful to everyone who has shared their experience.</p>
      <p class="testimonials-closing__thanks">Thank you.</p>
      <div class="testimonials-closing__divider">
        <span></span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
        <span></span>
      </div>
    </div>
  </div>

  {{-- CTA --}}
  <div class="cta-section">
    <div class="container--narrow">
      <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">{{ __('ui.common.ready_next_step') }}</span>
      <h2>{{ $cta?->content['heading'] ?? 'Meaningful and lasting change' }}</h2>
      <p>{!! $cta?->content['body'] ?? 'Whether you\'re struggling with trauma, anxiety, self-worth, or feeling stuck in recurring patterns, therapy can help create meaningful and lasting change. The first conversation is free and without obligation.' !!}</p>
      <div class="cta-section__actions">
        <a href="{{ \App\Providers\AppServiceProvider::localizeUrl($cta?->content['cta_primary_url'] ?? null) }}" class="btn btn--primary btn--lg">{{ $cta?->content['cta_primary_label'] ?? __('ui.common.book_intro_call') }}</a>
      </div>
    </div>
  </div>

</main>
@endsection
