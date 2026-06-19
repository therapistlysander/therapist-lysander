@extends('layouts.app')

@section('title', __('ui.page_title.testimonials'))
@section('meta_description', 'Read what clients say about therapy with Lysander Verschuur, MSc. — real testimonials about EMDR, trauma therapy, and psychological recovery.')
@section('canonical', 'https://www.therapistlysander.com/clients/')

@php
  $hero      = $sections['testimonials_hero'] ?? null;
  $cta       = $sections['testimonials_cta'] ?? null;
@endphp

@section('content')
<div class="scroll-progress" id="scroll-progress" aria-hidden="true"></div>
<main id="main-content">

  <div class="page-hero">
    <div class="container--narrow">
      <span class="page-hero__eyebrow">{{ $hero?->content['subheading'] ?? __('ui.testimonials.hero_subheading') }}</span>
      <h1 class="page-hero__title">{{ $hero?->content['heading'] ?? __('ui.testimonials.hero_heading') }}</h1>
      <div class="page-hero__text">{!! $hero?->content['body'] ?? '<p>' . __('ui.testimonials.hero_body') . '</p>' !!}</div>
    </div>
  </div>

  <!-- Section 1: Client Experiences -->
  <section class="section section--white" aria-labelledby="client-experiences-heading">
    <div class="container">
      <div class="section-header fade-in" style="text-align:center;">
        <span class="section-label">{{ __('ui.testimonials.client_experiences') }}</span>
        <h2 id="client-experiences-heading">{{ __('ui.testimonials.client_experiences_heading') }}</h2>
      </div>

      @foreach($testimonials as $i => $t)
      <div class="testimonial-long fade-in">
        <div class="testimonial-long__content">
          <p class="testimonial-long__headline">{{ $t->headline ?? Str::limit(strip_tags($t->body), 80) }}</p>
          <div class="testimonial-long__text">
            {!! $t->body !!}
          </div>
          <p class="testimonial-long__sig">&mdash; {{ $t->client_name }}</p>
        </div>
        <div class="testimonial-long__media">
          <img src="{{ $i % 2 !== 0 ? '/images/de8d235e4bd94eb8-a3c153_20122b9a32cc4e9a9faca835b9f82d14-mv2.jpg' : '/images/1cea4c553e34803a-a3c153_bbf1019446e34069a3b96c18f172e810-mv2.jpg' }}" alt="Calm reflective landscape" loading="lazy" width="600" height="520">
        </div>
      </div>
      @endforeach

    </div>
  </section>

  <!-- Section 2: Professional Recommendation -->
  @if($endorsements->count() > 0)
  <section class="section section--endorsement" aria-labelledby="professional-recommendation-heading">
    <div class="container--narrow">
      <div class="section-header fade-in" style="text-align:center;">
        <span class="section-label">{{ __('ui.testimonials.professional_recommendation') }}</span>
        <h2 id="professional-recommendation-heading">{{ __('ui.testimonials.professional_recommendation_heading') }}</h2>
      </div>

      @foreach($endorsements as $endorsement)
      <div class="endorsement-card fade-in">
        <blockquote class="endorsement-card__quote">
          {!! $endorsement->body !!}
        </blockquote>
        <p class="endorsement-card__attribution">
          &mdash; {{ $endorsement->client_name }}
        </p>
      </div>
      @endforeach
    </div>
  </section>
  @else
  {{-- Fallback: show Stacey's endorsement from translations if no DB entry --}}
  <section class="section section--endorsement" aria-labelledby="professional-recommendation-heading">
    <div class="container--narrow">
      <div class="section-header fade-in" style="text-align:center;">
        <span class="section-label">{{ __('ui.testimonials.professional_recommendation') }}</span>
        <h2 id="professional-recommendation-heading">{{ __('ui.testimonials.professional_recommendation_heading') }}</h2>
      </div>
      <div class="endorsement-card fade-in">
        <blockquote class="endorsement-card__quote">
          {{ __('ui.home.endorsement_quote') }}
        </blockquote>
        <p class="endorsement-card__attribution">
          &mdash; {{ __('ui.home.endorsement_attribution') }}
        </p>
      </div>
    </div>
  </section>
  @endif

  <!-- CTA -->
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
@extends('layouts.app')

@section('title', __('ui.page_title.testimonials'))
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
      <span class="page-hero__eyebrow">{{ $hero?->content['subheading'] ?? __('ui.testimonials.hero_subheading') }}</span>
      <h1 class="page-hero__title">{{ $hero?->content['heading'] ?? __('ui.testimonials.hero_heading') }}</h1>
      <div class="page-hero__text">{!! $hero?->content['body'] ?? '<p>' . __('ui.testimonials.hero_body') . '</p>' !!}</div>
    </div>
  </div>

  <!-- Featured quote -->
  <div style="background:var(--color-bg-dark);padding:var(--space-8) 0;">
    <div class="container--narrow" style="text-align:center;">
      <p style="font-family:var(--font-heading);font-size:clamp(var(--size-xl),2.5vw,var(--size-2xl));color:var(--color-white);font-style:italic;line-height:1.5;">{!! $quote?->content['body'] ?? '"For the first time, I felt safe enough to face memories that used to control me."' !!}</p>
      <p style="color:var(--color-accent-light);font-size:var(--size-sm);letter-spacing:0.1em;text-transform:uppercase;margin-top:var(--space-4);">{{ $quote?->content['attribution'] ?? '— Paul' }}</p>
    </div>
  </div>

  <!-- Long-form testimonials from DB -->
  <section class="section section--white" aria-labelledby="testimonials-heading">
    <div class="container">

      @foreach($testimonials as $i => $t)
      <div class="testimonial-long fade-in">
        <div class="testimonial-long__content">
          <p class="testimonial-long__headline">{{ $t->headline ?? Str::limit(strip_tags($t->body), 80) }}</p>
          <div class="testimonial-long__text">
            {!! $t->body !!}
          </div>
          <p class="testimonial-long__sig">— {{ $t->client_name }}</p>
        </div>
        <div class="testimonial-long__media">
          <img src="{{ $i % 2 !== 0 ? '/images/de8d235e4bd94eb8-a3c153_20122b9a32cc4e9a9faca835b9f82d14-mv2.jpg' : '/images/1cea4c553e34803a-a3c153_bbf1019446e34069a3b96c18f172e810-mv2.jpg' }}" alt="Calm reflective landscape" loading="lazy" width="600" height="520">
        </div>
      </div>
      @endforeach

    </div>
  </section>

  <!-- Featured quotes -->
  @if($featuredTestimonials->count() > 0)
  <section class="section section--alt" aria-label="{{ __('ui.testimonials.additional_quotes') }}">
    <div class="container">
      <div class="section-header fade-in" style="text-align:center;">
        <span class="section-label">{{ __('ui.testimonials.more_voices') }}</span>
        <h2>{{ __('ui.testimonials.further_reflections') }}</h2>
      </div>
      <div class="testimonial-grid">
        @foreach($featuredTestimonials as $t)
        <div class="testimonial testimonial--card {{ $loop->iteration === 2 ? 'testimonial--featured' : '' }} fade-in">
          <span class="testimonial__icon" aria-hidden="true">&ldquo;</span>
          <div class="testimonial__quote">{!! $t->body !!}</div>
          <div class="testimonial__footer">
            <p class="testimonial__name">{{ $t->client_name }}</p>
            @if($t->tag)<p class="testimonial__tag">{{ $t->tag }}</p>@endif
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- CTA -->
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
