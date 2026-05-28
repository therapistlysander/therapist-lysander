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
    <div class="page-hero__text">{!! $hero?->content['body'] ?? '<p>Transparent information about session fees, what is included, and how therapy begins. Starting is always free and without commitment.</p>' !!}</div>
  </div>
</div>

<section class="section section--white" aria-labelledby="fees-heading">
  <div class="container">
    <div class="grid-2 fade-in" style="align-items:start;">
      <div>
        <span class="section-label">Session fee</span>
        <h2 id="fees-heading">{{ $pricing?->content['heading'] ?? 'Clear, transparent pricing' }}</h2>
        <div class="divider"></div>
        <div style="font-size:var(--size-md);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-8);">
          {!! $pricing?->content['body'] ?? '<p>Individual therapy sessions are <strong>60 minutes</strong> and cost <strong>€110 per session</strong>.</p><p>I currently maintain a limited caseload to provide thoughtful and attentive care. Waiting times are typically around <strong>2–4 weeks</strong>.</p>' !!}
        </div>
        <div class="availability-block">
          <div class="availability-block__text">
            <h3>Current availability</h3>
            <p>Waiting times are typically around <strong>2–4 weeks</strong>, depending on availability and scheduling preferences.</p>
          </div>
          <div class="availability-block__cta">
            <a href="{{ $pricing?->content['cta_url'] ?? route('booking') }}" class="btn btn--primary">{{ $pricing?->content['cta_label'] ?? 'Book a Free Intro Call' }}</a>
          </div>
        </div>
      </div>
      <div>
        <div class="fee-card">
          <div class="free-badge">Free intro call</div>
          <div class="fee-card__amount">{{ $pricing?->content['fee_amount'] ?? '€110' }}</div>
          <div class="fee-card__duration">{{ $pricing?->content['fee_duration'] ?? 'Per session · 60 minutes' }}</div>
          <a href="{{ route('booking') }}" class="btn btn--primary" style="width:100%;justify-content:center;">Book a Free 30-Minute Intro Call</a>
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
      <span class="section-label">How therapy works</span>
      <h2 id="process-heading" class="section-title">{{ $process?->content['heading'] ?? 'The therapy process' }}</h2>
      <p style="color:var(--color-text-muted);font-size:var(--size-md);max-width:600px;margin:0 auto;">{{ $process?->content['subheading'] ?? 'Therapy begins with a free, no-commitment introduction call. There is no pressure to continue at any step.' }}</p>
    </div>
    @php $processSteps = $process?->content['steps'] ?? [
      ['title'=>'Free Introduction Call','description'=>'We begin with a free online introduction call to briefly explore your current situation, your goals for therapy, and whether we feel like a good fit. No commitment required.','duration'=>'30 minutes · Online','badge'=>'Free'],
      ['title'=>'Pre-Intake Questionnaire','description'=>"After our introduction call, you'll complete a short, confidential questionnaire to help me understand your needs and goals before our first formal session.",'duration'=>'5 minutes · Free','badge'=>'Online'],
      ['title'=>'Intake Session','description'=>'An in-depth intake session exploring your background, current difficulties, relevant life experiences, and treatment goals. A treatment plan is developed following this session.','duration'=>'60 minutes · €110','badge'=>null],
      ['title'=>'Ongoing Sessions','description'=>'Follow-up sessions tailored to your individual needs and therapeutic goals.','duration'=>'60 minutes · €110','badge'=>null],
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
      ['title'=>'Online sessions','description'=>'Sessions take place in a secure, confidential online setting. Available to clients worldwide. Online therapy is as effective as in-person for most psychological difficulties.'],
      ['title'=>'In-person (Amsterdam)','description'=>'Primarily an online practice. A limited number of in-person sessions in Amsterdam are available on request — please reach out to discuss possibilities.'],
      ['title'=>'Session duration','description'=>'Sessions are 60 minutes. The free introduction call is 30 minutes. Sessions are typically weekly in the early phase of therapy.'],
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
  </div>
</section>

<div class="cta-section">
  <div class="container--narrow">
    <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">Get started</span>
    <h2>{{ $cta?->content['heading'] ?? 'The first conversation is free' }}</h2>
    <p>{{ $cta?->content['subheading'] ?? 'Schedule a free 30-minute introduction call. No commitment required.' }}</p>
    <div class="cta-section__actions">
      <a href="{{ $cta?->content['cta_url'] ?? route('booking') }}" class="btn btn--primary btn--lg">{{ $cta?->content['cta_label'] ?? 'Book a Free 30-Minute Intro Call' }}</a>
    </div>
  </div>
</div>

@endsection
