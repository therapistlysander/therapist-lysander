@extends('layouts.app')

@section('title', isset($seo, $seo->title) ? '' : __('ui.layout.default_title'))
@section('meta_description', isset($seo, $seo->meta_description) ? '' : __('ui.layout.default_description'))
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
  .approach-tabs { display:flex; gap:var(--space-3); flex-wrap:wrap; margin-bottom:var(--space-6); }
  .approach-tab { padding:var(--space-2) var(--space-4); border:1.5px solid var(--color-accent-light); border-radius:40px; font-size:var(--size-sm); line-height:1.4; letter-spacing:0.05em; color:var(--color-accent-dark); cursor:pointer; transition:all var(--transition); background:transparent; }
  .approach-tab.active, .approach-tab:hover { background:var(--color-teal); border-color:var(--color-teal); color:var(--color-white); }
  .approach-panel { display:none; }
  .approach-panel.active { display:block; }
  .approach-panel h3 { font-size:var(--size-xl); margin-bottom:var(--space-4); }
  .approach-panel p { font-size:var(--size-base); color:var(--color-text-muted); line-height:1.8; }
  .process-steps { counter-reset:steps; display:grid; grid-template-columns:repeat(auto-fit,minmax(min(220px,100%),1fr)); gap:var(--space-6); }
  .process-step { counter-increment:steps; position:relative; padding-top:3.5rem; }
  .process-step::before { content:counter(steps); position:absolute; top:0; left:0; width:40px; height:40px; background:var(--color-teal); color:var(--color-white); border-radius:50%; display:flex; align-items:center; justify-content:center; font-family:var(--font-heading); font-size:var(--size-base); }
  .process-step h4 { font-size:var(--size-lg); margin-bottom:var(--space-3); color:var(--color-white); }
  .process-step p { font-size:var(--size-sm); color:rgba(255,255,255,0.65); }
  .areas-list { display:grid; grid-template-columns:repeat(auto-fit,minmax(min(260px,100%),1fr)); gap:0; margin-top:var(--space-6); }
  .areas-list__item { display:flex; align-items:center; gap:var(--space-3); font-size:var(--size-sm); color:var(--color-text-muted); padding:var(--space-2) 0; border-bottom:1px solid var(--color-border); }
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
        $heroPrimaryLabel = $hero?->content['cta_primary_label'] ?? __('ui.common.book_intro_call');
        $heroPrimaryUrl   = \App\Providers\AppServiceProvider::localizeUrl($hero?->content['cta_primary_url'] ?? null);
        $heroSecLabel     = $hero?->content['cta_secondary_label'] ?? null;
        $heroSecUrl       = \App\Providers\AppServiceProvider::localizeUrl($hero?->content['cta_secondary_url'] ?? null);
      @endphp
      <a href="{{ $heroPrimaryUrl }}" class="btn btn--primary btn--lg">
        <span style="display:inline-flex;align-items:center;gap:var(--space-2);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" width="18" height="18"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          {{ $heroPrimaryLabel }}
        </span>
      </a>
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
        <span class="section-label">{{ __('ui.home.who_i_am_label') }}</span>
        <h2 id="intro-heading">{{ $intro?->content['heading'] ?? 'A psychologist who has walked the path himself' }}</h2>
        <div class="divider"></div>
        <div style="font-size:var(--size-base);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-6);">
          {!! $intro?->content['body'] ?? '<p>I am a psychologist working with adults who feel emotionally overwhelmed, stuck in longstanding patterns, or disconnected from themselves and their lives.</p><p>Many of the people I work with struggle with the effects of trauma, anxiety, chronic self-criticism, emotional dysregulation, or difficulties related to self-worth and relationships.</p><p>Alongside my clinical training, my work is informed by <strong>personal experience with trauma, anxiety, and struggles with self-worth</strong>. My approach is warm, direct, collaborative, and focused on meaningful psychological change.</p>' !!}
        </div>
        @php $introStats = $intro?->content['stats'] ?? [['value'=>'5','label'=>'Approaches — EMDR, CBT, ACT, Schema & Somatic']]; @endphp
        <div class="stats" style="margin-bottom:var(--space-6);">
          @foreach($introStats as $stat)
          <div class="stats__item"><div class="stats__num">{{ $stat['value'] }}</div><div class="stats__label">{{ $stat['label'] }}</div></div>
          @endforeach
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
        <span class="section-label">{{ __('ui.home.what_i_work_with') }}</span>
        <h2 id="areas-heading">{{ $areas?->content['heading'] ?? 'Individualized & Goal-Oriented Therapy' }}</h2>
        <div class="divider"></div>
        <div style="font-size:var(--size-base);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-4);">
          {!! $areas?->content['body'] ?? '<p>Therapy is most effective when it is adapted to the person rather than forcing the person to fit a particular method or protocol.</p><p>I take time to understand your story, your strengths, your struggles, and the patterns that may be contributing to your difficulties. From there, we work collaboratively toward the goals that matter most to you, combining practical strategies with deeper emotional work where needed.</p>' !!}
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

<!-- What Therapy With Me Is Like -->
<section class="section section--alt" aria-label="What therapy with me is like">
  <div class="container">
    <div class="fade-in" style="max-width:var(--max-w-text);">
      <span class="section-label">{{ __('ui.home.therapy_approach_label') }}</span>
      <h2>{{ __('ui.home.therapy_with_me') }}</h2>
      <div class="divider"></div>
      <div style="font-size:var(--size-base);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-6);">
        <p>{{ __('ui.home.therapy_desc') }}</p>
      </div>
    </div>
    <div class="card-grid fade-in" style="grid-template-columns:repeat(auto-fit,minmax(min(220px,100%),1fr));">
      <div class="card">
        <h3 class="card__title">{{ __('ui.home.safe_title') }}</h3>
        <p class="card__text">{{ __('ui.home.safe_desc') }}</p>
      </div>
      <div class="card">
        <h3 class="card__title">{{ __('ui.home.evidence_title') }}</h3>
        <p class="card__text">{{ __('ui.home.evidence_desc') }}</p>
      </div>
      <div class="card">
        <h3 class="card__title">{{ __('ui.home.lasting_title') }}</h3>
        <p class="card__text">{{ __('ui.home.lasting_desc') }}</p>
      </div>
    </div>
  </div>
</section>

<!-- Therapeutic approaches -->
<section class="section section--white" id="approaches" aria-labelledby="approaches-heading">
  <div class="container">
    <div class="section-header fade-in">
      <span class="section-label">{{ __('ui.home.methods_label') }}</span>
      <h2 id="approaches-heading" class="section-title">{{ $approaches?->content['heading'] ?? 'Evidence-Based Therapy, Tailored to the Individual' }}</h2>
      <p class="text-muted" style="color:var(--color-text-muted);font-size:var(--size-base);max-width:600px;">{!! $approaches?->content['body'] ?? 'I draw from a range of proven therapeutic approaches to help clients address the underlying patterns that contribute to emotional suffering, develop greater psychological flexibility, and create meaningful, lasting change.' !!}</p>
    </div>
    @php
      $approachItems = $approaches?->content['items'] ?? [
        ['key'=>'cbt','title'=>'CBT','description'=>'CBT helps identify and change unhelpful thoughts, beliefs, and behavioural patterns that contribute to emotional distress. Together, we explore more balanced and helpful ways of thinking, leading to lasting improvements in mood, anxiety, and self-esteem.'],
        ['key'=>'act','title'=>'ACT','description'=>'ACT helps people develop greater psychological flexibility by changing their relationship with difficult thoughts and emotions rather than struggling against them. By connecting with what truly matters and taking meaningful action, people can build a rich and fulfilling life.'],
        ['key'=>'emdr','title'=>'EMDR','description'=>'EMDR is one of the most evidence-based treatments for trauma and PTSD. It helps the brain process distressing memories that continue to influence the present. EMDR can also be used to target anxiety-provoking future scenarios ("flashforwards"), reducing fear and helping people respond with greater confidence and flexibility.'],
        ['key'=>'schema','title'=>'Schema Therapy','description'=>'Schema therapy focuses on deep-rooted emotional patterns that can contribute to recurring difficulties in relationships, self-esteem, and emotional wellbeing. It helps people better understand and care for the different parts of themselves, creating lasting change through greater self-awareness, self-compassion, and emotional flexibility.'],
        ['key'=>'somatic','title'=>'Somatic Approaches','description'=>'Emotions, stress, and trauma are often experienced not only in our thoughts, but also in the body. Where relevant, I incorporate body awareness, nervous system regulation, and attention to physical sensations as part of the therapeutic process, helping clients develop a deeper understanding of their physical and emotional experiences.'],
      ];
      $tabLabels = ['cbt'=>__('ui.home.tab_cbt'),'act'=>__('ui.home.tab_act'),'emdr'=>__('ui.home.tab_emdr'),'schema'=>__('ui.home.tab_schema'),'somatic'=>__('ui.home.tab_somatic')];
    @endphp
    <div class="approach-tabs" role="tablist" aria-label="Therapeutic approaches">
      @foreach($approachItems as $i => $approach)
      <button class="approach-tab {{ $i === 0 ? 'active' : '' }}" role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}" data-panel="{{ $approach['key'] ?? Str::slug($approach['title']) }}">{{ $tabLabels[$approach['key']] ?? (Str::before($approach['title'], ' (') ?: $approach['title']) }}</button>
      @endforeach
    </div>
    @foreach($approachItems as $i => $approach)
    <div class="approach-panel {{ $i === 0 ? 'active' : '' }} fade-in" id="panel-{{ $approach['key'] ?? Str::slug($approach['title']) }}" role="tabpanel">
      <h3>{{ $approach['title'] }}</h3>
      <p>{{ $approach['description'] }}</p>
    </div>
    @endforeach
    <div style="margin-top:var(--space-6);">
      <a href="{{ \App\Providers\AppServiceProvider::localizeUrl($approaches?->content['cta_url'] ?? null) }}" class="btn btn--outline">{{ $approaches?->content['cta_label'] ?? __('ui.home.view_approach') }}</a>
    </div>
  </div>
</section>

<!-- Process steps -->
<section class="section section--dark" id="process" aria-labelledby="process-heading">
  <div class="container">
    <div class="section-header fade-in">
      <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">{{ __('ui.home.how_it_works') }}</span>
      <h2 id="process-heading" style="color:var(--color-white);">{{ $process?->content['heading'] ?? __('ui.home.process_heading') }}</h2>
    </div>
    <div class="process-steps">
      @php
        $defaultDurations = [
          __('ui.home.step_free_call_title') => __('ui.home.minutes_30'),
          __('ui.home.step_intake_title') => __('ui.home.minutes_60'),
          __('ui.home.step_ongoing_title') => __('ui.home.minutes_60'),
        ];
        $steps = $process?->content['steps'] ?? [
          ['title' => __('ui.home.step_free_call_title'), 'description' => __('ui.home.step_free_call_desc')],
          ['title' => __('ui.home.step_intake_title'), 'description' => __('ui.home.step_intake_desc')],
          ['title' => __('ui.home.step_ongoing_title'), 'description' => __('ui.home.step_ongoing_desc')],
        ];
      @endphp
      @foreach($steps as $step)
      @php $duration = $step['duration'] ?? ($defaultDurations[mb_strtolower($step['title'])] ?? null); @endphp
      <div class="process-step fade-in">
        <h4>{{ $step['title'] }}</h4>
        @if($duration)
        <span style="display:block;font-size:var(--size-xs);text-transform:uppercase;letter-spacing:0.1em;color:var(--color-accent-light);margin-bottom:var(--space-2);">{{ $duration }}</span>
        @endif
        <p>{{ $step['description'] }}</p>
      </div>
      @endforeach
    </div>
    <div style="margin-top:var(--space-8);text-align:left;">
      <a href="{{ \App\Providers\AppServiceProvider::localizeUrl($process?->content['cta_url'] ?? null) }}" class="btn btn--outline-white">{{ $process?->content['cta_label'] ?? __('ui.home.view_fees') }}</a>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="section section--white" id="testimonials-preview" aria-labelledby="testimonials-heading">
  <div class="container">
    <div class="section-header fade-in" style="text-align:center;">
      <span class="section-label">{{ $testimonialsHdr?->content['subheading'] ?? __('ui.home.what_clients_say') }}</span>
      <h2 id="testimonials-heading" class="section-title">{{ $testimonialsHdr?->content['heading'] ?? __('ui.home.what_clients_say') }}</h2>
    </div>
    @php
      $validTestimonials = $testimonials->filter(fn($t) => !empty($t->body) || !empty($t->quote));
      $tCount = $validTestimonials->count();
      $gridCols = $tCount >= 3 ? 3 : ($tCount === 2 ? 2 : 1);
    @endphp
    @if($tCount > 0)
    <div class="testimonial-grid {{ $gridCols === 2 ? 'testimonial-grid--2' : ($gridCols === 1 ? 'testimonial-grid--1' : '') }}">
      @foreach($validTestimonials as $i => $t)
      <div class="testimonial testimonial--card {{ $t->is_featured ? 'testimonial--featured' : '' }} fade-in">
        <span class="testimonial__icon" aria-hidden="true">&ldquo;</span>
        <div class="testimonial__quote">{!! $t->body ?: $t->quote !!}</div>
        <div class="testimonial__footer">
          <p class="testimonial__name">{{ $t->client_name }}</p>
          @if($t->tag)<p class="testimonial__tag">{{ $t->tag }}</p>@elseif($t->client_title)<p class="testimonial__tag">{{ $t->client_title }}</p>@endif
        </div>
      </div>
      @endforeach
    </div>
    @endif
    <div style="text-align:center;margin-top:var(--space-8);">
      <a href="{{ \App\Providers\AppServiceProvider::localizeUrl('/testimonials') }}" class="btn btn--outline">{{ __('ui.home.read_more_experiences') }}</a>
    </div>
  </div>
</section>

<!-- Professional Endorsement -->
<section class="section section--endorsement" id="endorsement" aria-labelledby="endorsement-heading">
  <div class="container--narrow">
    <div class="endorsement-card fade-in">
      <span class="endorsement-card__badge">{{ __('ui.testimonials.professional_recommendation') }}</span>
      <h2 id="endorsement-heading" class="endorsement-card__heading">{{ __('ui.home.endorsement_heading') }}</h2>
      <blockquote class="endorsement-card__quote">
        {{ $endorsement?->body ?? __('ui.home.endorsement_quote') }}
      </blockquote>
      <p class="endorsement-card__attribution">
        &mdash; {{ $endorsement?->client_name ?? __('ui.home.endorsement_attribution') }}
      </p>
    </div>
  </div>
</section>

<!-- Working together -->
<section class="section section--alt" id="working-together" aria-labelledby="working-heading">
  <div class="container">
    <div class="grid-2 fade-in">
      <div>
        <span class="section-label">{{ __('ui.home.working_together') }}</span>
        <h2 id="working-heading">{{ $workingTogether?->content['heading'] ?? 'A space that is safe, thoughtful, and collaborative' }}</h2>
        <div class="divider"></div>
        <div style="font-size:var(--size-base);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-6);">
          {!! $workingTogether?->content['body'] ?? '<p>Therapy is not about "fixing" who you are. Often, it involves understanding the patterns that developed in response to difficult life experiences — and gradually creating more freedom, flexibility, and self-trust in the present.</p><p>My role is to provide a space that is safe, thoughtful, collaborative, and focused on real psychological change.</p>' !!}
        </div>
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
    <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">{{ __('ui.common.ready_to_begin') }}</span>
    <h2>{{ $ctaBottom?->content['heading'] ?? __('ui.common.ready_to_begin_subtitle') }}</h2>
    <p>{!! $ctaBottom?->content['body'] ?? "Whether you're struggling with trauma, anxiety, depression, or simply feeling stuck — I'm here. The first conversation is free and without commitment." !!}</p>
    @if(!empty($ctaBottom?->content['additional_text']))
    <p style="margin-top:var(--space-2);font-size:var(--size-sm);color:rgba(255,255,255,0.8);">{{ $ctaBottom?->content['additional_text'] }}</p>
    @else
    <p style="margin-top:var(--space-2);font-size:var(--size-sm);color:rgba(255,255,255,0.8);">{{ __('ui.common.ready_to_begin_additional') }}</p>
    @endif
    <div class="cta-section__actions">
      <a href="{{ \App\Providers\AppServiceProvider::localizeUrl($ctaBottom?->content['cta_primary_url'] ?? null) }}" class="btn btn--primary btn--lg">{{ $ctaBottom?->content['cta_primary_label'] ?? __('ui.common.book_intro_call') }}</a>
    </div>
  </div>
</div>

@endsection

@section('page_scripts')
<script>
// Approach tabs
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
