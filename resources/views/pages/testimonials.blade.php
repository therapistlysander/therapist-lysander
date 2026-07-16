@extends('layouts.app')

@section('title', __('ui.page_title.testimonials'))
@section('meta_description', 'Read what clients say about therapy with Lysander Verschuur, MSc. — real testimonials about EMDR, trauma therapy, and psychological recovery.')
@section('canonical', 'https://www.therapistlysander.com/clients/')

@php
  $hero      = $sections['testimonials_hero'] ?? null;
  $cta       = $sections['testimonials_cta'] ?? null;
@endphp

@section('page_styles')
<style>
  /* --- Testimonials page --- */

  /* Cards grid */
  .testimonials-cards {
    padding: var(--space-12) 0;
    background: var(--color-white);
  }
  .testimonials-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--space-8);
    max-width: 920px;
    margin: 0 auto;
  }
  .testimonial-card {
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding: var(--space-10) var(--space-8);
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    display: flex;
    flex-direction: column;
  }
  .testimonial-card__opening {
    font-size: var(--size-lg);
    color: var(--color-accent);
    font-weight: 600;
    font-style: italic;
    line-height: 1.6;
    margin: 0 0 var(--space-8);
  }
  .testimonial-card__text {
    font-size: var(--size-base);
    color: var(--color-text-muted);
    line-height: 1.75;
    flex: 1;
  }
  .testimonial-card__text p {
    margin: 0 0 var(--space-4);
  }
  .testimonial-card__text p:last-child {
    margin-bottom: 0;
  }
  .testimonial-card__signature {
    font-family: var(--font-heading);
    font-size: var(--size-sm);
    color: var(--color-accent);
    font-weight: 600;
    margin-top: var(--space-5);
  }
  .testimonial-card__fallback {
    font-size: var(--size-base);
    color: var(--color-text-muted);
    line-height: 1.75;
    font-style: italic;
    flex: 1;
  }

  /* Endorsement */
  .testimonials-endorsement {
    padding: var(--space-12) 0;
    background: var(--color-bg);
    border-top: 1px solid var(--color-border);
  }
  .testimonials-endorsement .section-header h2 {
    font-size: clamp(1.8rem, 3vw, 2.5rem);
  }
  .endorsement-block {
    max-width: 720px;
    margin: 0 auto;
    text-align: center;
  }
  .endorsement-block__quote {
    font-family: var(--font-heading);
    font-size: clamp(var(--size-base), 1.6vw, var(--size-lg));
    color: var(--color-text);
    line-height: 1.65;
    font-style: italic;
  }
  .endorsement-block__attribution {
    margin-top: var(--space-4);
    font-family: var(--font-heading);
    font-size: var(--size-base);
    color: var(--color-accent);
  }

  /* Responsive */
  @media (max-width: 640px) {
    .testimonials-cards { padding: var(--space-8) 0; }
    .testimonials-endorsement { padding: var(--space-8) 0; }
  }
</style>
@endsection

@section('content')
<div class="scroll-progress" id="scroll-progress" aria-hidden="true"></div>
<main id="main-content">

  {{-- Hero — clean minimalist style consistent with other pages --}}
  <div class="page-hero">
    <div class="container--narrow">
      <span class="page-hero__eyebrow">{{ $hero?->content['subheading'] ?? __('ui.testimonials.hero_heading') }}</span>
      <h1 class="page-hero__title">{{ $hero?->content['heading'] ?? __('ui.testimonials.hero_heading') }}</h1>
      <div class="page-hero__text">{!! $hero?->content['body'] ?? '<p>' . __('ui.testimonials.hero_body') . '</p>' !!}</div>
    </div>
  </div>

  {{-- Testimonial cards --}}
  <section class="testimonials-cards">
    <div class="container">
      <div class="testimonials-grid">
        @foreach($testimonials as $t)
        <div class="testimonial-card fade-in">
          {{-- Opening quote (headline) as highlighted title --}}
          @if($t->headline)
            <p class="testimonial-card__opening">&ldquo;{{ $t->headline }}&rdquo;</p>
          @endif

          {{-- Body with preserved paragraph formatting --}}
          @if($t->body)
            <div class="testimonial-card__text">
              {!! strip_tags($t->body, '<p><br><strong><em><b><i><a><u>') !!}
            </div>
          @elseif($t->short_description)
            <div class="testimonial-card__fallback">
              <p>{{ $t->short_description }}</p>
            </div>
          @endif

          {{-- Signature --}}
          <p class="testimonial-card__signature">&mdash; {{ $t->client_name }}</p>
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

  {{-- CTA — transition directly from testimonials to CTA --}}
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
