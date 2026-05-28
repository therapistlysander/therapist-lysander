@extends('layouts.app')

@section('title', isset($seo, $seo->title) ? '' : 'Therapist Lysander | Psychologist & Trauma Therapist')
@section('meta_description', isset($seo, $seo->meta_description) ? '' : 'Online therapy for adults struggling with trauma, PTSD, anxiety, self-worth difficulties, and emotional overwhelm. Integrative, evidence-based, and tailored to the individual.')
@section('canonical', 'https://www.therapistlysander.com/')

@php
  $hero            = $sections['home_hero'] ?? null;
  $intro           = $sections['home_intro'] ?? null;
  $areas           = $sections['home_areas'] ?? null;
  $onlineStrip     = $sections['home_online_strip'] ?? null;
  $approaches      = $sections['home_approaches'] ?? null;
  $process         = $sections['home_process'] ?? null;
  $testimonialsHdr = $sections['home_testimonials'] ?? null;
  $workingTogether = $sections['home_working_together'] ?? null;
  $ctaBottom       = $sections['home_cta_bottom'] ?? null;
@endphp

@section('page_styles')
<style>
  .intro-strip { background:var(--color-bg-dark); padding:var(--space-12) 0; }
  .intro-strip .container { display:flex; align-items:center; gap:var(--space-12); flex-wrap:wrap; }
  .intro-strip__text { font-family:var(--font-heading); font-size:clamp(var(--size-lg),2vw,var(--size-2xl)); color:var(--color-white); flex:1; min-width:260px; line-height:1.45; font-style:italic; }
  .intro-strip__cta { flex-shrink:0; }
  .approach-tabs { display:flex; gap:var(--space-3); flex-wrap:wrap; margin-bottom:var(--space-10); }
  .approach-tab { padding:var(--space-2) var(--space-4); border:1.5px solid var(--color-accent-light); border-radius:40px; font-size:var(--size-sm); line-height:1.4; letter-spacing:0.05em; color:var(--color-accent-dark); cursor:pointer; transition:all var(--transition); background:transparent; }
  .approach-tab.active, .approach-tab:hover { background:var(--color-teal); border-color:var(--color-teal); color:var(--color-white); }
  .approach-panel { display:none; }
  .approach-panel.active { display:block; }
  .approach-panel h3 { font-size:var(--size-xl); margin-bottom:var(--space-4); }
  .approach-panel p { font-size:var(--size-md); color:var(--color-text-muted); line-height:1.8; }
  .process-steps { counter-reset:steps; display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:var(--space-8); }
  .process-step { counter-increment:steps; position:relative; padding-top:var(--space-10); }
  .process-step::before { content:counter(steps); position:absolute; top:0; left:0; width:40px; height:40px; background:var(--color-teal); color:var(--color-white); border-radius:50%; display:flex; align-items:center; justify-content:center; font-family:var(--font-heading); font-size:var(--size-base); }
  .process-step h4 { font-size:var(--size-lg); margin-bottom:var(--space-3); color:var(--color-white); }
  .process-step p { font-size:var(--size-sm); color:rgba(255,255,255,0.65); }
  .areas-list { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:var(--space-3); margin-top:var(--space-8); }
  .areas-list__item { display:flex; align-items:center; gap:var(--space-3); font-size:var(--size-sm); color:var(--color-text-muted); padding:var(--space-3) 0; border-bottom:1px solid var(--color-border); }
  .areas-list__item::before { content:''; width:6px; height:6px; border-radius:50%; background:var(--color-accent); flex-shrink:0; }
</style>
@endsection

@section('content')

<!-- Hero -->
<section class="hero" aria-label="Hero section">
  <div class="hero__content">
    <span class="hero__name-mobile">Lysander Verschuur</span>
    <span class="hero__eyebrow">{!! $hero?->content['subheading'] ?? 'Psychologist &amp; Trauma Therapist' !!}</span>
    <h1 class="hero__title">{{ $hero?->content['heading'] ?? 'Online therapy for adults ready to move forward.' }}</h1>
    <div class="hero__text">{!! $hero?->content['body'] ?? '<p>Online therapy for adults struggling with the effects of trauma and PTSD, anxiety, self-worth difficulties, emotional overwhelm, and longstanding psychological patterns. Integrative, evidence-based, and tailored to the individual.</p>' !!}</div>
    <div class="hero__actions">
      @php
        $heroPrimaryLabel = $hero?->content['cta_primary_label'] ?? 'Book a Free 30-Minute Intro Call';
        $heroPrimaryUrl   = $hero?->content['cta_primary_url']   ?? route('booking');
        $heroSecLabel     = $hero?->content['cta_secondary_label'] ?? null;
        $heroSecUrl       = $hero?->content['cta_secondary_url']   ?? route('approach');
      @endphp
      <a href="{{ $heroPrimaryUrl }}" class="btn btn--primary btn--lg">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" width="18" height="18"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        {{ $heroPrimaryLabel }}
      </a>
      @if($heroSecLabel ?? true)
      <a href="{{ $heroSecUrl }}" class="btn btn--outline">{{ $heroSecLabel ?? 'Trauma &amp; My Approach' }}</a>
      @endif
    </div>
  </div>
  <div class="hero__image" role="img" aria-label="Lysander Verschuur — Psychologist and Trauma Therapist">
    <img src="{{ $hero?->content['image'] ?? '/images/lysander-hero.jpg' }}" alt="Lysander Verschuur, Psychologist and Trauma Therapist" width="900" height="1100" fetchpriority="high">
  </div>
</section>

<!-- Introduction -->
<section class="section section--white" id="intro" aria-labelledby="intro-heading">
  <div class="container">
    <div class="grid-2 fade-in">
      <div class="grid-2__media" style="order:-1;">
        <img src="{{ $intro?->content['image'] ?? '/images/ff96a9dc8ea72c2c-11062b_aa33e58c18774e7db74c68e74a6c231e-mv2.jpg' }}" alt="Lysander Verschuur, MSc." loading="lazy" width="600" height="750">
      </div>
      <div>
        <span class="section-label">Who I am</span>
        <h2 id="intro-heading">{{ $intro?->content['heading'] ?? 'A psychologist who has walked the path himself' }}</h2>
        <div class="divider"></div>
        <div style="font-size:var(--size-md);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-8);">
          {!! $intro?->content['body'] ?? '<p>I am a psychologist working with adults who feel emotionally overwhelmed, stuck in longstanding patterns, or disconnected from themselves and their lives.</p><p>Many of the people I work with struggle with the effects of trauma, anxiety, chronic self-criticism, emotional dysregulation, or difficulties related to self-worth and relationships.</p><p>Alongside my clinical training, my work is informed by <strong>personal experience with trauma, anxiety, and struggles with self-worth</strong>. My approach is warm, direct, collaborative, and focused on meaningful psychological change.</p>' !!}
        </div>
        @php $introStats = $intro?->content['stats'] ?? [['value'=>'EMDR','label'=>'Advanced certified'],['value'=>'MSc.','label'=>'Psychology degree'],['value'=>'10+','label'=>'Evidence-based methods']]; @endphp
        <div class="stats" style="margin-bottom:var(--space-8);">
          @foreach($introStats as $stat)
          <div class="stats__item"><div class="stats__num">{{ $stat['value'] }}</div><div class="stats__label">{{ $stat['label'] }}</div></div>
          @endforeach
        </div>
        <div style="display:flex;gap:var(--space-4);flex-wrap:wrap;">
          <a href="{{ $intro?->content['cta_primary_url'] ?? route('approach') }}" class="btn btn--primary">{{ $intro?->content['cta_primary_label'] ?? 'Trauma & My Approach' }}</a>
          <a href="{{ $intro?->content['cta_secondary_url'] ?? route('booking') }}" class="btn btn--outline">{{ $intro?->content['cta_secondary_label'] ?? 'Book a Free Intro Call' }}</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Areas I work with -->
<section class="section section--alt" id="areas" aria-labelledby="areas-heading">
  <div class="container">
    <div class="grid-2 fade-in">
      <div>
        <span class="section-label">What I treat</span>
        <h2 id="areas-heading">{{ $areas?->content['heading'] ?? 'Individualized & goal-oriented therapy' }}</h2>
        <div class="divider"></div>
        <div style="font-size:var(--size-md);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-6);">
          {!! $areas?->content['body'] ?? '<p>Effective therapy requires more than applying a standard protocol. Each person brings a unique history, emotional world, personality structure, and set of coping patterns into therapy.</p><p>My aim is to understand the underlying processes contributing to your difficulties and to tailor treatment accordingly. Therapy is active, practical, and collaborative.</p>' !!}
        </div>
        @php $areaItems = $areas?->content['items'] ?? [['title'=>'Trauma and PTSD'],['title'=>'Anxiety disorders and panic'],['title'=>'Depression and grief'],['title'=>'Self-esteem and self-worth difficulties'],['title'=>'Perfectionism and control-related patterns'],['title'=>'Emotional regulation and anger-related difficulties'],['title'=>'Burnout and chronic stress'],['title'=>'Compulsive or avoidance-based coping patterns']]; @endphp
        <div class="areas-list" role="list">
          @foreach($areaItems as $item)
          <div class="areas-list__item" role="listitem">{{ $item['title'] }}</div>
          @endforeach
        </div>
      </div>
      <div class="grid-2__media">
        <img src="{{ $areas?->content['image'] ?? '/images/540a4d3e95a87201-11062b_e8771669914d4b8a949e06893dfd43a0-mv2.jpg' }}" alt="Calm natural environment" loading="lazy" width="600" height="750">
      </div>
    </div>
  </div>
</section>

<!-- Online therapy strip -->
<div class="intro-strip" aria-label="Online therapy information">
  <div class="container">
    <p class="intro-strip__text">{!! $onlineStrip?->content['body'] ?? '"Online therapy has been consistently shown to be as effective as face-to-face therapy for anxiety, depression, trauma, and stress-related difficulties."' !!}</p>
    <div class="intro-strip__cta">
      <a href="{{ $onlineStrip?->content['cta_url'] ?? route('booking') }}" class="btn btn--outline-white">{{ $onlineStrip?->content['cta_label'] ?? 'Book a Free Intro Call' }}</a>
    </div>
  </div>
</div>

<!-- Therapeutic approaches -->
<section class="section section--white" id="approaches" aria-labelledby="approaches-heading">
  <div class="container">
    <div class="section-header fade-in">
      <span class="section-label">Therapeutic methods</span>
      <h2 id="approaches-heading" class="section-title">{{ $approaches?->content['heading'] ?? 'Evidence-based approaches tailored to you' }}</h2>
      <p class="text-muted" style="color:var(--color-text-muted);font-size:var(--size-md);max-width:600px;">{!! $approaches?->content['body'] ?? 'I work integratively, drawing from multiple proven methods to address the root causes of your difficulties — not just the symptoms.' !!}</p>
    </div>
    @php $approachItems = $approaches?->content['items'] ?? [
      ['key'=>'cbt','title'=>'Cognitive Behavioural Therapy (CBT)','description'=>'CBT helps you identify and change unhelpful thought patterns and behaviours that maintain distress. We work together to recognise negative automatic thoughts, challenge their validity, and develop healthier cognitive patterns — resulting in lasting improvements in mood, anxiety, and self-esteem.'],
      ['key'=>'act','title'=>'Acceptance and Commitment Therapy (ACT)','description'=>'ACT shifts the focus from fighting difficult thoughts and feelings to developing psychological flexibility. By identifying your core values and committing to actions aligned with them, you can build a meaningful life even in the presence of inner pain.'],
      ['key'=>'emdr','title'=>'Eye Movement Desensitisation & Reprocessing (EMDR)','description'=>'EMDR is one of the most evidence-based treatments for trauma and PTSD. Using bilateral stimulation, EMDR helps the brain process and integrate traumatic memories that have become "stuck."'],
      ['key'=>'schema','title'=>'Schema Therapy & Parts Work','description'=>'Schema therapy addresses deep-rooted emotional patterns formed in childhood that drive recurring difficulties in adult life. Combined with parts work, we bring care to wounded inner parts, replacing maladaptive coping with genuine emotional healing.'],
      ['key'=>'somatic','title'=>'Somatic Psychotherapy','description'=>'Trauma is held not just in the mind but in the body. Somatic approaches address how stress, trauma, and emotion are stored in physical tension, movement patterns, and nervous system responses.'],
    ]; @endphp
    <div class="approach-tabs" role="tablist" aria-label="Therapeutic approaches">
      @foreach($approachItems as $i => $approach)
      <button class="approach-tab {{ $i === 0 ? 'active' : '' }}" role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}" data-panel="{{ $approach['key'] ?? Str::slug($approach['title']) }}">{{ Str::before($approach['title'], ' (') ?: $approach['title'] }}</button>
      @endforeach
    </div>
    @foreach($approachItems as $i => $approach)
    <div class="approach-panel {{ $i === 0 ? 'active' : '' }} fade-in" id="panel-{{ $approach['key'] ?? Str::slug($approach['title']) }}" role="tabpanel">
      <h3>{{ $approach['title'] }}</h3>
      <p>{{ $approach['description'] }}</p>
    </div>
    @endforeach
    <div style="margin-top:var(--space-10);">
      <a href="{{ $approaches?->content['cta_url'] ?? route('approach') }}" class="btn btn--outline">{{ $approaches?->content['cta_label'] ?? 'View Trauma & My Approach' }}</a>
    </div>
  </div>
</section>

<!-- Process steps -->
<section class="section section--dark" id="process" aria-labelledby="process-heading">
  <div class="container">
    <div class="section-header fade-in">
      <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">How it works</span>
      <h2 id="process-heading" style="color:var(--color-white);">{{ $process?->content['heading'] ?? 'Starting therapy — what to expect' }}</h2>
    </div>
    <div class="process-steps">
      @php
        $steps = $process?->content['steps'] ?? [
          ['title' => 'Free Introduction Call',  'description' => 'A free 30-minute online introduction call to briefly explore your current situation, your goals for therapy, and whether we feel like a good fit to work together.'],
          ['title' => 'Intake Session',           'description' => 'An in-depth 60-minute intake session exploring your background, current difficulties, relevant life experiences, and treatment goals in greater detail.'],
          ['title' => 'Treatment Plan',           'description' => 'Following the intake, a treatment plan is developed outlining the main complaints, therapeutic goals, and proposed treatment approach — tailored to you.'],
          ['title' => 'Ongoing Sessions',         'description' => 'Follow-up sessions of 60 minutes, tailored to your individual needs. Sessions are active, collaborative, and adapted to your pace and needs.'],
        ];
      @endphp
      @foreach($steps as $step)
      <div class="process-step fade-in">
        <h4>{{ $step['title'] }}</h4>
        <p>{{ $step['description'] }}</p>
      </div>
      @endforeach
    </div>
    <div style="margin-top:var(--space-12);text-align:left;">
      <a href="{{ $process?->content['cta_url'] ?? route('fees') }}" class="btn btn--outline-white">{{ $process?->content['cta_label'] ?? 'View Fees & Process' }}</a>
    </div>
  </div>
</section>

<!-- Testimonials preview -->
<section class="section section--white" id="testimonials-preview" aria-labelledby="testimonials-heading">
  <div class="container">
    <div class="section-header fade-in" style="text-align:center;">
      <span class="section-label">{{ $testimonialsHdr?->content['subheading'] ?? 'Client words' }}</span>
      <h2 id="testimonials-heading" class="section-title">{{ $testimonialsHdr?->content['heading'] ?? 'What clients say' }}</h2>
    </div>
    <div class="testimonial-grid">
      @foreach($testimonials as $t)
      <div class="testimonial {{ $t->is_featured ? 'testimonial--featured' : '' }} fade-in">
        <p class="testimonial__quote">{{ $t->quote }}</p>
        <p class="testimonial__name">— {{ $t->client_name }}</p>
        <p class="testimonial__tag">{{ $t->client_title }}</p>
      </div>
      @endforeach
    </div>
    <div style="text-align:center;margin-top:var(--space-12);">
      <a href="{{ $testimonialsHdr?->content['cta_url'] ?? route('testimonials') }}" class="btn btn--outline">{{ $testimonialsHdr?->content['cta_label'] ?? 'Read full testimonials' }}</a>
    </div>
  </div>
</section>

<!-- Working together -->
<section class="section section--alt" id="working-together" aria-labelledby="working-heading">
  <div class="container">
    <div class="grid-2 fade-in">
      <div>
        <span class="section-label">Working together</span>
        <h2 id="working-heading">{{ $workingTogether?->content['heading'] ?? 'A space that is safe, thoughtful, and collaborative' }}</h2>
        <div class="divider"></div>
        <div style="font-size:var(--size-md);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-8);">
          {!! $workingTogether?->content['body'] ?? '<p>Therapy is not about "fixing" who you are. Often, it involves understanding the patterns that developed in response to difficult life experiences — and gradually creating more freedom, flexibility, and self-trust in the present.</p><p>My role is to provide a space that is safe, thoughtful, collaborative, and focused on real psychological change.</p>' !!}
        </div>
        <a href="{{ $workingTogether?->content['cta_url'] ?? route('booking') }}" class="btn btn--primary btn--lg">{{ $workingTogether?->content['cta_label'] ?? 'Schedule a Free Introduction Call' }}</a>
      </div>
      <div class="grid-2__media">
        <img src="{{ $workingTogether?->content['image'] ?? '/images/1cea4c553e34803a-a3c153_bbf1019446e34069a3b96c18f172e810-mv2.jpg' }}" alt="Peaceful outdoor landscape" loading="lazy" width="600" height="750">
      </div>
    </div>
  </div>
</section>

<!-- Final CTA -->
<div class="cta-section" aria-label="Call to action">
  <div class="container--narrow">
    <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">Ready to begin?</span>
    <h2>{{ $ctaBottom?->content['heading'] ?? 'Take the first step toward change' }}</h2>
    <p>{!! $ctaBottom?->content['body'] ?? "Whether you're struggling with trauma, anxiety, depression, or simply feeling stuck — I'm here. The first conversation is free and without commitment." !!}</p>
    <div class="cta-section__actions">
      <a href="{{ $ctaBottom?->content['cta_primary_url'] ?? route('booking') }}" class="btn btn--primary btn--lg">{{ $ctaBottom?->content['cta_primary_label'] ?? 'Book a Free 30-Minute Intro Call' }}</a>
      <a href="{{ $ctaBottom?->content['cta_secondary_url'] ?? 'https://wa.me/66935309052?text=Hi%20Lysander%2C%20I%27d%20like%20to%20learn%20more%20about%20therapy.' }}" target="_blank" rel="noopener noreferrer" class="btn btn--whatsapp btn--lg">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="18" height="18"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        {{ $ctaBottom?->content['cta_secondary_label'] ?? 'WhatsApp me' }}
      </a>
    </div>
  </div>
</div>

@endsection

@section('page_scripts')
<script>
document.querySelectorAll('.approach-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    const panelId = tab.dataset.panel;
    document.querySelectorAll('.approach-tab').forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
    document.querySelectorAll('.approach-panel').forEach(p => p.classList.remove('active'));
    tab.classList.add('active');
    tab.setAttribute('aria-selected','true');
    const panel = document.getElementById('panel-' + panelId);
    if (panel) panel.classList.add('active');
  });
});
</script>
@endsection
