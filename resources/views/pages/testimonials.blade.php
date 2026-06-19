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
  @php
    $settingsHeading = $endorsementSettings['endorsement_heading'][$locale] ?? '';
    $settingsFullBody = $endorsementSettings['endorsement_full_body'][$locale] ?? '';
    $settingsAttribution = $endorsementSettings['endorsement_attribution'][$locale] ?? '';
  @endphp
  @if($endorsements->count() > 0)
  <section class="section section--endorsement" aria-labelledby="professional-recommendation-heading">
    <div class="container--narrow">
      <div class="section-header fade-in" style="text-align:center;">
        <span class="section-label">{{ __('ui.testimonials.professional_recommendation') }}</span>
        <h2 id="professional-recommendation-heading">{{ $settingsHeading ?: __('ui.testimonials.professional_recommendation_heading') }}</h2>
      </div>

      @foreach($endorsements as $endorsement)
      <div class="endorsement-card fade-in">
        <blockquote class="endorsement-card__quote">
          {!! nl2br(e($endorsement->body)) ?: ($settingsFullBody ? nl2br(e($settingsFullBody)) : '') !!}
        </blockquote>
        <p class="endorsement-card__attribution">
          &mdash; {{ $endorsement->client_name }}
        </p>
      </div>
      @endforeach
    </div>
  </section>
  @else
  {{-- Fallback: show endorsement from settings or translations --}}
  <section class="section section--endorsement" aria-labelledby="professional-recommendation-heading">
    <div class="container--narrow">
      <div class="section-header fade-in" style="text-align:center;">
        <span class="section-label">{{ __('ui.testimonials.professional_recommendation') }}</span>
        <h2 id="professional-recommendation-heading">{{ $settingsHeading ?: __('ui.testimonials.professional_recommendation_heading') }}</h2>
      </div>
      @if($settingsFullBody)
      <div class="endorsement-card fade-in">
        <blockquote class="endorsement-card__quote">
          {!! nl2br(e($settingsFullBody)) !!}
        </blockquote>
        <p class="endorsement-card__attribution">
          &mdash; {{ $settingsAttribution ?: __('ui.home.endorsement_attribution') }}
        </p>
      </div>
      @else
      <div class="endorsement-card fade-in">
        <blockquote class="endorsement-card__quote">
          {!! __('ui.home.endorsement_body') !!}
        </blockquote>
        <p class="endorsement-card__attribution">
          &mdash; {{ __('ui.home.endorsement_attribution') }}
        </p>
      </div>
      @endif
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
