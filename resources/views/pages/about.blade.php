@extends('layouts.app')

@section('title', 'About Lysander Verschuur, MSc. | Psychotherapy & Trauma Therapy')
@section('meta_description', 'Learn about Lysander Verschuur, MSc. — a trained psychologist and trauma specialist using CBT, ACT, EMDR, and Schema Therapy. Primarily online sessions, with limited in-person availability in Amsterdam.')
@section('canonical', 'https://www.therapistlysander.com/about/')

@php
  $hero     = $sections['about_hero'] ?? null;
  $who      = $sections['about_who'] ?? null;
  $personal = $sections['about_personal'] ?? null;
  $howIWork = $sections['about_how_i_work'] ?? null;
  $values   = $sections['about_values'] ?? null;
  $cta      = $sections['about_cta'] ?? null;
@endphp

@section('page_styles')
<style>
  .credential-list { list-style:none; margin-top:var(--space-6); }
  .credential-list li { display:flex; align-items:flex-start; gap:var(--space-3); padding:var(--space-4) 0; border-bottom:1px solid var(--color-border); font-size:var(--size-sm); color:var(--color-text-muted); }
  .credential-list li:last-child { border-bottom:none; }
  .credential-list li::before { content:''; width:6px; height:6px; border-radius:50%; background:var(--color-accent); flex-shrink:0; margin-top:6px; }
  .credential-list li strong { color:var(--color-text); display:block; margin-bottom:2px; }
  .values-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:var(--space-6); margin-top:var(--space-8); }
  .value-item { padding:var(--space-6); background:var(--color-white); border:1px solid var(--color-border); border-radius:var(--radius-md); border-top:3px solid var(--color-accent); }
  .value-item h4 { font-size:var(--size-lg); margin-bottom:var(--space-3); }
  .value-item p { font-size:var(--size-sm); color:var(--color-text-muted); line-height:1.7; }
  .personal-section { padding:var(--space-10); border-left:3px solid var(--color-accent); background:var(--color-teal-light); border-radius:0 var(--radius-md) var(--radius-md) 0; margin:var(--space-12) 0; }
  .personal-section p { font-size:var(--size-md); color:var(--color-text-muted); line-height:1.8; }
  .personal-section p + p { margin-top:var(--space-4); }
</style>
@endsection

@section('content')

<div class="page-hero">
  <div class="container--narrow">
    <span class="page-hero__eyebrow">{{ $hero?->content['subheading'] ?? 'About Lysander' }}</span>
    <h1 class="page-hero__title">{{ $hero?->content['heading'] ?? 'Psychologist & Trauma Specialist' }}</h1>
    <div class="page-hero__text">{!! $hero?->content['body'] ?? '<p>A compassionate, pragmatic approach — drawing from lived experience, evidence-based methods, and genuine care for every person I work with.</p>' !!}</div>
  </div>
</div>

<section class="section section--white">
  <div class="container">
    <div class="grid-2 fade-in">
      <div>
        <span class="section-label">Who I am</span>
        <h2>{{ $who?->content['heading'] ?? 'Lysander Verschuur, MSc.' }}</h2>
        <div class="divider"></div>
        <div style="font-size:var(--size-md);color:var(--color-text-muted);line-height:1.8;margin-bottom:var(--space-8);">
          {!! $who?->content['body'] ?? '<p>I am a trained psychologist working with individuals experiencing <strong>psychological and emotional difficulties such as trauma, anxiety, depression, and self-esteem issues</strong>. I am here to support people through some of life\'s hardest chapters.</p><p>My work is focused on the <strong>treatment and reduction of mental health complaints of individual clients</strong>, using evidence-based therapeutic methods. I work with both Dutch-speaking and English-speaking clients.</p><p>I help clients move from states of <strong>overwhelm, constriction, and emotional distress</strong> toward <strong>greater stability, clarity, and psychological flexibility</strong>.</p>' !!}
        </div>
        <div style="display:flex;gap:var(--space-4);flex-wrap:wrap;">
          <a href="{{ $who?->content['cta_primary_url'] ?? route('booking') }}" class="btn btn--primary">{{ $who?->content['cta_primary_label'] ?? 'Book a session' }}</a>
          <a href="{{ $who?->content['cta_secondary_url'] ?? route('contact') }}" class="btn btn--outline">{{ $who?->content['cta_secondary_label'] ?? 'Contact Me' }}</a>
        </div>
      </div>
      <div class="grid-2__media" style="order:-1;">
        <img src="{{ $who?->content['image'] ?? '/images/24946176bc4178fd-d0220c_d40feae8ad4e4961b519d527fe1eb369-mv2_d_1440_1920_s_2.jpg' }}" alt="Lysander Verschuur, MSc." loading="lazy" width="600" height="750">
      </div>
    </div>
  </div>
</section>

<section class="section section--alt">
  <div class="container">
    <div class="fade-in"><span class="section-label">Personal journey</span><h2>{{ $personal?->content['heading'] ?? 'A therapist who has been there' }}</h2></div>
    <div class="personal-section fade-in">
      {!! $personal?->content['body'] ?? '<p>For years, I struggled with <strong>trauma, anxiety, and a harsh inner critic</strong>. I know firsthand how deeply these patterns can affect your life — the way they shape your relationships, your sense of self, and your ability to feel at home in the world. This personal experience informs my work — not as a replacement for clinical methods, but as something that <strong>deepens empathy, understanding, and precision in therapy</strong>.</p><p>Everything I offer in therapy — from EMDR and ACT to mindfulness and values-based living — I have lived myself. I have practised it, wrestled with it, and integrated it. This personal experience shapes how I work: <strong>compassionate, pragmatic, non-judgmental, and fully committed to your growth</strong>.</p><p>Therapy with me is not just about symptom relief — it is about <strong>finding your way back to yourself</strong>, grounded in self-understanding, self-worth, and personal empowerment.</p>' !!}
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <div class="grid-2 grid-2--reverse fade-in">
      <div class="grid-2__media">
        <img src="{{ $howIWork?->content['image'] ?? '/images/8d05ae73f3a7dbe5-11062b_a417184e892349f89eb10b97fd3d5a91-mv2.jpg' }}" alt="Calm therapy room" loading="lazy" width="600" height="740">
      </div>
      <div>
        <span class="section-label">How I work</span>
        <h2>{{ $howIWork?->content['heading'] ?? 'Integrative & evidence-based' }}</h2>
        <div class="divider"></div>
        <div style="font-size:var(--size-md);color:var(--color-text-muted);line-height:1.75;margin-bottom:var(--space-6);">{!! $howIWork?->content['body'] ?? '<p>I work integratively, drawing from evidence-based approaches that each serve a distinct therapeutic purpose.</p>' !!}</div>
        @php $methods = $howIWork?->content['items'] ?? [['title'=>'Cognitive Behavioural Therapy (CBT)'],['title'=>'Acceptance & Commitment Therapy (ACT)'],['title'=>'EMDR'],['title'=>'Schema Therapy'],['title'=>'Somatic Psychotherapy'],['title'=>'Exposure Therapy'],['title'=>'Flash Technique'],['title'=>'Imagery Rescripting'],['title'=>'Parts Work'],['title'=>'Mindfulness-based approaches']]; @endphp
        <div class="methods-list">
          @foreach($methods as $method)
          <div class="methods-list__item">{{ $method['title'] }}</div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--dark">
  <div class="container">
    <div class="section-header fade-in"><span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">My approach</span><h2 style="color:white;">{{ $values?->content['heading'] ?? 'Tailored, collaborative, direct' }}</h2></div>
    <div class="grid-2 fade-in">
      <div>
        <div style="color:rgba(255,255,255,0.75);font-size:var(--size-md);line-height:1.8;margin-bottom:var(--space-6);">
          {!! $values?->content['body'] ?? '<p><strong style="color:white;">Every person carries a unique life story, emotional landscape, and psychological makeup.</strong> I tailor each therapy trajectory to fit the person in front of me.</p><p><strong style="color:white;">My approach is practical, goal-oriented, and collaborative</strong>: we work together to develop insight, emotional resilience, and concrete tools for change.</p>' !!}
        </div>
      </div>
      @php $cards = $values?->content['cards'] ?? [['title'=>'Compassionate','description'=>'Non-judgmental, warm, and genuinely caring in every session.'],['title'=>'Pragmatic','description'=>'No endless circles. We move toward meaningful change, session by session.'],['title'=>'Empowering','description'=>'My goal is your independence — the tools to thrive on your own.'],['title'=>'Evidence-based','description'=>'Every method I use is grounded in clinical research and proven effectiveness.']]; @endphp
      <div class="values-grid">
        @foreach($cards as $card)
        <div class="value-item" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-top-color:var(--color-accent-light);">
          <h4 style="color:white;">{{ $card['title'] }}</h4><p style="color:rgba(255,255,255,0.6);">{{ $card['description'] }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<div class="cta-section">
  <div class="container--narrow">
    <h2>{{ $cta?->content['heading'] ?? 'Ready to take the first step?' }}</h2>
    <p>{!! $cta?->content['body'] ?? 'A short message is all it takes to start. The first intake conversation is free and without obligation.' !!}</p>
    <div class="cta-section__actions">
      <a href="{{ $cta?->content['cta_url'] ?? route('booking') }}" class="btn btn--primary btn--lg">{{ $cta?->content['cta_label'] ?? 'Book a session' }}</a>
    </div>
  </div>
</div>

@endsection
