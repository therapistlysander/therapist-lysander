@extends('layouts.app')

@section('title', __('ui.page_title.fees'))
@section('meta_description', 'Session fees, therapy process, and practical information for therapy with Lysander Verschuur, MSc. — €110 per session, primarily online, free 30-minute introduction call.')
@section('canonical', 'https://www.therapistlysander.com/fees-process/')

@php
  $hero    = $sections['fees_hero'] ?? null;
  $pricing = $sections['fees_pricing'] ?? null;
  $process = $sections['fees_process'] ?? null;
  $info    = $sections['fees_info'] ?? null;
  $cta     = $sections['fees_cta'] ?? null;
@endphp

@section('content')

<div class="page-hero">
  <div class="container--narrow">
    <span class="page-hero__eyebrow">{{ $hero?->content['subheading'] ?? 'Practical information' }}</span>
    <h1 class="page-hero__title">{{ $hero?->content['heading'] ?? __('ui.fees.hero_heading') }}</h1>
    <div class="page-hero__text">{!! $hero?->content['body'] ?? '<p>' . __('ui.fees.hero_body') . '</p>' !!}</div>
  </div>
</div>

<section class="section section--white" aria-labelledby="fees-heading">
  <div class="container">
    <div class="grid-2 fade-in" style="align-items:start;">
      <div>
        <span class="section-label">{{ __('ui.fees.session_fee') }}</span>
        <h2 id="fees-heading">{{ $pricing?->content['heading'] ?? 'Fees & Availability' }}</h2>
        <div class="divider"></div>
        <div style="font-size:var(--size-base);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-6);">
          {!! $pricing?->content['body'] ?? '<p>' . __('ui.fees.session_duration_note') . '</p>' !!}
        </div>
      </div>
      <div>
        <div class="fee-card">
          <div class="fee-card__amount">€110</div>
          <div class="fee-card__duration">{{ __('ui.fees.per_session_duration') }}</div>
          @php $includes = $pricing?->content['items'] ?? [['title'=>'Reflection or e-health documents after sessions'],['title'=>'Exercises or therapeutic material between sessions'],['title'=>'Preparation and integration of therapeutic work'],['title'=>'Limited contact between sessions for practical questions']]; @endphp
          <div class="fee-card__includes">
            <h4>{{ __('ui.fees.what_included') }}</h4>
            @foreach($includes as $item)
            <div class="fee-includes-item">{{ $item['title'] }}</div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--white" aria-labelledby="process-heading">
  <div class="container">
    <div class="section-header fade-in">
      <span class="section-label">{{ __('ui.fees.what_to_expect') }}</span>
      <h2 id="process-heading" class="section-title">{{ $process?->content['heading'] ?? 'What to Expect' }}</h2>
      <p style="color:var(--color-text-muted);font-size:var(--size-base);max-width:600px;">{{ $process?->content['subheading'] ?? 'Therapy begins with a free introductory call, followed by an introduction call where we explore your situation, goals, and what you hope to gain from therapy. From there, treatment is tailored to your individual needs.' }}</p>
    </div>
    @php $processSteps = $process?->content['steps'] ?? [
      ['title'=>__('ui.fees.process_free_title'),'description'=>__('ui.fees.process_free_desc'),'duration'=>__('ui.fees.process_free_duration'),'badge'=>null],
      ['title'=>__('ui.fees.process_intake_title'),'description'=>__('ui.fees.process_intake_desc'),'duration'=>__('ui.fees.process_intake_duration'),'badge'=>null],
      ['title'=>__('ui.fees.process_ongoing_title'),'description'=>__('ui.fees.process_ongoing_desc'),'duration'=>__('ui.fees.process_ongoing_duration'),'badge'=>null],
    ]; @endphp
    <div class="process-cards fade-in">
      @foreach($processSteps as $i => $step)
      <div class="process-card">
        <div class="process-card__number">{{ $i + 1 }}</div>
        @if(!empty($step['badge']))
        <span class="free-badge" style="background:var(--color-teal-light);color:var(--color-teal);">{{ $step['badge'] }}</span>
        @endif
        <div class="process-card__duration">{{ $step['duration'] ?? '' }}</div>
        <h3>{{ $step['title'] }}</h3>
        <p>{{ $step['description'] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Practice room image --}}
@php $practiceRoomImg = $process?->content['image'] ?? null; @endphp
@if($practiceRoomImg)
<section class="section section--white practice-room-section" aria-label="Practice room">
  <div class="container">
    <div class="fade-in practice-room-wrap" style="margin:0 auto;">
      <img src="{{ $practiceRoomImg }}" alt="{{ app()->getLocale() === 'nl' ? 'De praktijkruimte in Amsterdam' : 'The practice room in Amsterdam' }}" style="width:100%;height:auto;border-radius:var(--radius-md);object-fit:cover;display:block;" loading="lazy">
      <p class="practice-room-caption">{{ __('ui.fees.practice_room_caption') }}</p>
    </div>
  </div>
</section>
<style>
  /* Mobile: full content width (unchanged) */
  .practice-room-wrap { max-width: 100%; }
  .practice-room-caption {
    text-align: center;
    font-size: var(--size-sm);
    color: var(--color-text-muted);
    margin-top: var(--space-4);
    line-height: 1.6;
  }
  /* Desktop: reduce width ~18% for better visual balance, keep centred, add breathing room below */
  @media (min-width: 1024px) {
    .practice-room-wrap { max-width: 82%; }
    .practice-room-section { padding-bottom: var(--space-20, 5rem); }
  }
</style>
@endif

<section class="section section--dark" aria-labelledby="practical-heading">
  <div class="container">
    <div class="section-header fade-in">
      <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">{{ __('ui.fees.practical_details') }}</span>
      <h2 id="practical-heading" style="color:var(--color-white);">{{ $info?->content['heading'] ?? 'Session information' }}</h2>
    </div>
    @php $infoCards = $info?->content['cards'] ?? [
      ['title'=>__('ui.fees.info_online_title'),'description'=>__('ui.fees.info_online_desc')],
      ['title'=>__('ui.fees.info_duration_title'),'description'=>__('ui.fees.info_duration_desc')],
      ['title'=>__('ui.fees.info_languages_title'),'description'=>__('ui.fees.info_languages_desc')],
    ]; @endphp
    <div class="card-grid fade-in">
      @foreach($infoCards as $card)
      <div class="card" style="background:rgba(255,255,255,0.05);border-color:rgba(255,255,255,0.1);">
        <h3 class="card__title" style="color:var(--color-white);">{{ $card['title'] }}</h3>
        <p class="card__text" style="color:rgba(255,255,255,0.65);">{{ $card['description'] }}</p>
      </div>
      @endforeach
    </div>
    {{-- <div class="card fade-in" style="background:rgba(255,255,255,0.05);border-color:rgba(255,255,255,0.1);margin-top:var(--space-6);">
      <h3 class="card__title" style="color:var(--color-white);">{{ __('ui.fees.availability_heading') }}</h3>
      <p class="card__text" style="color:rgba(255,255,255,0.65);">{{ __('ui.fees.availability_text') }} <strong style="color:rgba(255,255,255,0.85);">{{ __('ui.fees.availability_weeks') }}</strong>.</p>
    </div> --}}
  </div>
</section>

<div class="cta-section" style="border-top:1px solid rgba(255,255,255,0.08);padding-top:var(--space-16);">
  <div class="container--narrow">
    <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">{{ __('ui.common.ready_to_begin') }}</span>
    <h2>{{ $cta?->content['heading'] ?? 'Take the first step' }}</h2>
    <p>{{ $cta?->content['subheading'] ?? 'The introductory call offers an opportunity to discuss what brings you here, ask questions, and explore whether we feel like a good fit to work together.' }}</p>
    <div class="cta-section__actions">
      <a href="{{ \App\Providers\AppServiceProvider::localizeUrl($cta?->content['cta_url'] ?? null) }}" class="btn btn--primary btn--lg">{{ $cta?->content['cta_label'] ?? __('ui.common.book_intro_call') }}</a>
    </div>
  </div>
</div>

@endsection
