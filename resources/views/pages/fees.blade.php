@extends('layouts.app')

@section('title', 'Fees & Process | Therapist Lysander')
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
    <h1 class="page-hero__title">{{ $hero?->content['heading'] ?? 'Fees & Practical Information' }}</h1>
    <div class="page-hero__text">{!! $hero?->content['body'] ?? '<p>Information about fees, availability, and what to expect when starting therapy. The introductory call is free and without obligation.</p>' !!}</div>
  </div>
</div>

<section class="section section--white" aria-labelledby="fees-heading">
  <div class="container">
    <div class="grid-2 fade-in" style="align-items:start;">
      <div>
        <span class="section-label">Session fee</span>
        <h2 id="fees-heading">{{ $pricing?->content['heading'] ?? 'Fees & Availability' }}</h2>
        <div class="divider"></div>
        <div style="font-size:var(--size-md);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-6);">
          <p>Individual therapy sessions last <strong>60 minutes</strong>. The introductory call is free and without obligation.</p>
        </div>
      </div>
      <div>
        <div class="fee-card">
          <div class="fee-card__amount">€110</div>
          <div class="fee-card__duration">Per session · 60 minutes</div>
          @php $includes = $pricing?->content['items'] ?? [['title'=>'Reflection or e-health documents after sessions'],['title'=>'Exercises or therapeutic material between sessions'],['title'=>'Preparation and integration of therapeutic work'],['title'=>'Limited contact between sessions for practical questions']]; @endphp
          <div class="fee-card__includes">
            <h4>What is included</h4>
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
    <div class="section-header fade-in" style="text-align:center;">
      <span class="section-label">What to expect</span>
      <h2 id="process-heading" class="section-title">{{ $process?->content['heading'] ?? 'What to Expect' }}</h2>
      <p style="color:var(--color-text-muted);font-size:var(--size-md);max-width:600px;margin:0 auto;">{{ $process?->content['subheading'] ?? 'Therapy begins with a free introductory call, followed by an intake session where we explore your situation, goals, and what you hope to gain from therapy. From there, treatment is tailored to your individual needs.' }}</p>
    </div>
    @php $processSteps = $process?->content['steps'] ?? [
      ['title'=>'Free Introductory Call','description'=>'We briefly discuss what brings you to therapy, your goals, and whether we feel like a good fit to work together.','duration'=>'30 minutes · Free','badge'=>'Free'],
      ['title'=>'Intake Session','description'=>"An in-depth session exploring your background, current difficulties, relevant life experiences, and treatment goals. Prior to the session, you'll complete a questionnaire that helps guide the assessment process. Following the intake, you'll receive a personalized treatment plan outlining the main difficulties, therapeutic goals, and proposed treatment approach.",'duration'=>'60 minutes','badge'=>null],
      ['title'=>'Ongoing Sessions','description'=>'Sessions tailored to your individual needs, goals, and pace. Together we work toward meaningful and lasting psychological change.','duration'=>'60 minutes','badge'=>null],
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

<section class="section section--dark" aria-labelledby="practical-heading">
  <div class="container">
    <div class="section-header fade-in">
      <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">Practical details</span>
      <h2 id="practical-heading" style="color:var(--color-white);">{{ $info?->content['heading'] ?? 'Session information' }}</h2>
    </div>
    @php $infoCards = $info?->content['cards'] ?? [
      ['title'=>'Online sessions','description'=>'Sessions take place in a secure, confidential online setting. Available to clients worldwide.'],
      ['title'=>'Session duration','description'=>'Sessions are typically 60 minutes in length. Shorter or longer sessions can occasionally be arranged when clinically appropriate. As a general principle, therapy is kept as short as possible and as long as necessary.'],
      ['title'=>'Languages','description'=>'Sessions are conducted in Dutch or English. Both languages are equally available for all therapy modalities.'],
    ]; @endphp
    <div class="card-grid fade-in">
      @foreach($infoCards as $card)
      <div class="card" style="background:rgba(255,255,255,0.05);border-color:rgba(255,255,255,0.1);">
        <h3 class="card__title" style="color:var(--color-white);">{{ $card['title'] }}</h3>
        <p class="card__text" style="color:rgba(255,255,255,0.65);">{{ $card['description'] }}</p>
      </div>
      @endforeach
    </div>
    <div class="card fade-in" style="background:rgba(255,255,255,0.05);border-color:rgba(255,255,255,0.1);margin-top:var(--space-6);">
      <h3 class="card__title" style="color:var(--color-white);">Availability &amp; Waiting Times</h3>
      <p class="card__text" style="color:rgba(255,255,255,0.65);">I currently maintain a limited caseload to ensure therapy remains thoughtful, personal, and attentive. Availability varies over time, but new clients can usually be accommodated within <strong style="color:rgba(255,255,255,0.85);">2–6 weeks</strong>.</p>
    </div>
  </div>
</section>

<div class="cta-section">
  <div class="container--narrow">
    <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">Ready to begin?</span>
    <h2>{{ $cta?->content['heading'] ?? 'Take the first step' }}</h2>
    <p>{{ $cta?->content['subheading'] ?? 'The introductory call offers an opportunity to discuss what brings you here, ask questions, and explore whether we feel like a good fit to work together.' }}</p>
    <div class="cta-section__actions">
      <a href="{{ $cta?->content['cta_url'] ?? route('booking') }}" class="btn btn--primary btn--lg">{{ $cta?->content['cta_label'] ?? 'Book a Free 30-Minute Intro Call' }}</a>
    </div>
  </div>
</div>

@endsection
