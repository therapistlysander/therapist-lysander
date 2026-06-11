@extends('layouts.app')

@section('title', 'Trauma & My Approach | Therapist Lysander')
@section('meta_description', 'Trauma therapy and psychological treatment by Lysander Verschuur, MSc. — EMDR, Schema Therapy, Exposure Therapy, and integrative trauma-focused treatment for adults.')
@section('canonical', 'https://www.therapistlysander.com/trauma-approach/')

@php
  $hero         = $sections['approach_hero'] ?? null;
  $understanding = $sections['approach_understanding'] ?? null;
  $types        = $sections['approach_types'] ?? null;
  $treatments   = $sections['approach_treatments'] ?? null;
  $emdr         = $sections['approach_emdr'] ?? null;
  $why          = $sections['approach_why'] ?? null;
  $cta          = $sections['approach_cta'] ?? null;
@endphp

@section('page_styles')
<style>
  .trauma-types { display: grid; grid-template-columns: minmax(0, 1fr); gap: 0; margin-top: var(--space-6); }
  .trauma-type-item { display: flex; align-items: center; gap: var(--space-3); font-size: var(--size-sm); color: var(--color-text-muted); padding: var(--space-3) 0; border-bottom: 1px solid var(--color-border); line-height: 1.5; background: transparent; border: none; border-radius: 0; border-bottom: 1px solid var(--color-border); }
  .trauma-type-item:last-child { border-bottom: none; }
  .trauma-type-item::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--color-accent); flex-shrink: 0; margin-top: 5px; }
  .emdr-features { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(220px, 100%), 1fr)); gap: var(--space-4); margin-top: var(--space-6); }
  .emdr-feature { padding: var(--space-4); background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--radius-md); border-left: 3px solid var(--color-accent); }
  .emdr-feature h4 { font-size: var(--size-base); margin-bottom: var(--space-2); color: var(--color-text); }
  .emdr-feature p { font-size: var(--size-sm); color: var(--color-text-muted); line-height: 1.65; }
  .treatment-approaches { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: var(--space-4); margin-top: var(--space-6); }
  .treatment-approach { background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--space-4); }
  .treatment-approach h4 { color: var(--color-teal); margin-bottom: var(--space-2); font-family: var(--font-body); font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; font-size: var(--size-xs); }
  .treatment-approach h3 { font-size: var(--size-lg); margin-bottom: var(--space-3); }
  .treatment-approach p { font-size: var(--size-sm); color: var(--color-text-muted); line-height: 1.7; }
</style>
@endsection

@section('content')
<div class="scroll-progress" id="scroll-progress" aria-hidden="true"></div>
<main id="main-content">

  <div class="page-hero">
    <div class="container--narrow">
      <span class="page-hero__eyebrow">{{ $hero?->content['subheading'] ?? 'Trauma & My Approach' }}</span>
      <h1 class="page-hero__title">{{ $hero?->content['heading'] ?? 'Trauma & My Approach' }}</h1>
      <div class="page-hero__text">{!! $hero?->content['body'] ?? '<p>Trauma is not only about what happened in the past, but also about the ways those experiences continue to affect the present.</p>' !!}</div>
    </div>
  </div>

  <section class="section section--white" aria-labelledby="trauma-heading">
    <div class="container">
      <div class="grid-2 fade-in">
        <div>
          <span class="section-label">{{ __('ui.approach.understanding_label') }}</span>
          <h2 id="trauma-heading">{{ $understanding?->content['heading'] ?? 'How trauma affects the present' }}</h2>
          <div class="divider"></div>
          <div style="font-size:var(--size-md);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-6);">
            {!! $understanding?->content['body'] ?? '<p>Difficult or overwhelming experiences can leave a lasting impact on how we think, feel, relate to others, and experience ourselves. The effects of trauma can show up in many different ways, including anxiety, panic, emotional numbness, hypervigilance, intrusive memories, shame, low self-worth, or persistent patterns of avoidance and control.</p><p>Sometimes the source of these difficulties is obvious. In other cases, the impact develops more gradually through repeated experiences of criticism, emotional neglect, instability, rejection, or chronic stress.</p><p>Whether the cause is a single overwhelming event or a series of smaller experiences over time, the effects can be profound. The good news is that these patterns are understandable, treatable, and do not have to define the rest of your life.</p><p>I work with both acute trauma <em>("big T" trauma)</em> and more cumulative or relational forms of trauma <em>("small t" trauma)</em>. Both can have a profound psychological impact, and both are highly treatable.</p>' !!}
          </div>
        </div>
        <div class="grid-2__media">
          <img src="{{ $understanding?->content['image'] ?? '/images/de8d235e4bd94eb8-a3c153_20122b9a32cc4e9a9faca835b9f82d14-mv2.jpg' }}" alt="Calm reflective outdoor landscape" loading="lazy" width="600" height="740">
        </div>
      </div>
    </div>
  </section>

  <section class="section section--alt" aria-labelledby="types-heading">
    <div class="container">
      <div class="section-header fade-in">
        <span class="section-label">{{ __('ui.approach.types_label') }}</span>
        <h2 id="types-heading" class="section-title">{{ $types?->content['heading'] ?? 'Types of Trauma I Work With' }}</h2>
      </div>
      <p style="color:var(--color-text-muted);font-size:var(--size-md);max-width:600px;line-height:1.85;" class="fade-in">{!! $types?->content['body'] ?? 'Trauma can take many different forms, but its effects often touch the same areas of life: safety, relationships, emotional wellbeing, and self-trust. Therapy can help people process these experiences and move toward greater freedom, stability, and resilience.' !!}</p>
      @php $traumaItems = $types?->content['items'] ?? [['title'=>'War zone experiences'],['title'=>'Accidents and injury-related trauma'],['title'=>'Sexual abuse and assault'],['title'=>'Medical trauma'],['title'=>'Childhood abuse and emotional neglect'],['title'=>'Bullying and social exclusion'],['title'=>'Grief and traumatic loss'],['title'=>'High-conflict relational or family situations'],['title'=>'Attachment and relational trauma'],['title'=>'Panic attacks and overwhelming psychological experiences'],['title'=>'Trauma-related self-worth and identity difficulties']]; @endphp
      <div class="trauma-types fade-in">
        @foreach($traumaItems as $item)
        <div class="trauma-type-item">{{ $item['title'] }}</div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="section section--white" aria-labelledby="treatment-heading">
    <div class="container">
      <div class="section-header fade-in" style="text-align:center;">
        <span class="section-label">{{ __('ui.approach.treatment_label') }}</span>
        <h2 id="treatment-heading" class="section-title">{{ $treatments?->content['heading'] ?? 'Trauma-focused treatment' }}</h2>
      </div>
      <div style="font-size:var(--size-md);color:var(--color-text-muted);line-height:1.85;max-width:640px;margin:0 auto;text-align:left;" class="fade-in">
        {!! $treatments?->content['body'] ?? '<p>Trauma treatment is not about simply reducing symptoms. It is about helping people process difficult experiences, make sense of what happened, and develop a greater sense of safety, flexibility, and self-trust in the present.</p><p>Treatment always takes place at a pace that feels manageable. Together, we develop an understanding of what is happening and why, and we work toward greater freedom, stability, and resilience.</p><p>Depending on the nature of the difficulties involved, I draw from a range of evidence-based trauma-focused approaches.</p>' !!}
      </div>
      @php $treatmentCards = $treatments?->content['cards'] ?? [['subtitle'=>'Primary Method','title'=>'EMDR','description'=>'Eye Movement Desensitization and Reprocessing (EMDR) is one of the most extensively researched treatments for trauma and PTSD. Using bilateral stimulation, EMDR helps the brain process and integrate distressing experiences that continue to influence the present.'],['subtitle'=>'Confronting Avoidance','title'=>'Exposure Therapy','description'=>'Exposure therapy involves gradually approaching memories, emotions, situations, or triggers that have become associated with fear and avoidance. Through repeated and structured exposure, the nervous system learns that these experiences can be tolerated, reducing fear and creating space for new learning.'],['subtitle'=>'Emotional Memory Change','title'=>'Imagery Rescripting','description'=>'Imagery Rescripting helps people revisit distressing memories and change their emotional meaning. By introducing new experiences, perspectives, or responses within the memory, it can reduce shame, helplessness, fear, and self-criticism.'],['subtitle'=>'Gentle Trauma Processing','title'=>'Flash Technique','description'=>'The Flash Technique offers a gentle way of reducing the emotional intensity of traumatic memories without requiring detailed discussion of the experience itself. It can be particularly helpful when memories feel overwhelming or when dissociation is present.'],['subtitle'=>'Core Emotional Patterns','title'=>'Schema Therapy','description'=>'Schema Therapy helps identify longstanding emotional patterns and beliefs that often develop through earlier life experiences. By understanding these patterns and responding to them differently, people can build greater self-compassion, flexibility, and emotional resilience.'],['subtitle'=>'Integrative','title'=>'Somatic Approaches','description'=>'Emotions, stress, and trauma are often experienced not only in our thoughts, but also in the body. Where relevant, I incorporate body awareness, nervous system regulation, and attention to physical sensations as part of the therapeutic process.']]; @endphp
      <div class="treatment-approaches fade-in">
        @foreach($treatmentCards as $card)
        <div class="treatment-approach">
          <h4>{{ $card['subtitle'] ?? '' }}</h4>
          <h3>{{ $card['title'] }}</h3>
          <p>{{ $card['description'] }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="section section--dark" aria-labelledby="emdr-heading">
    <div class="container">
      <div class="grid-2 fade-in">
        <div>
          <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">{{ __('ui.approach.about_emdr_label') }}</span>
          <h2 id="emdr-heading" style="color:var(--color-white);">{{ $emdr?->content['heading'] ?? 'EMDR is not only about the past' }}</h2>
          <div class="divider"></div>
          <div style="color:rgba(255,255,255,0.75);font-size:var(--size-md);line-height:1.85;margin-bottom:var(--space-4);">
            {!! $emdr?->content['body'] ?? '<p>EMDR is widely known as a treatment for painful memories and trauma from the past. However, it can also be highly effective in treating intense fears, catastrophic future scenarios, and intrusive "flashforward" images that keep people stuck in anxiety and avoidance.</p><p>When these fears become emotionally charged and repetitive, they can strongly shape a person\'s daily life and sense of freedom. Through EMDR — sometimes combined with other therapeutic approaches such as exposure therapy, CBT, or experiential interventions — these patterns are often highly treatable.</p>' !!}
          </div>
          @php $emdrCards = $emdr?->content['cards'] ?? [['title'=>'Anxiety About Panic','description'=>'Treating the fear of anxiety itself — not just the symptoms.'],['title'=>'Health Anxiety','description'=>'Catastrophic health fears and persistent bodily preoccupations.'],['title'=>'Fear of Losing Control','description'=>'Catastrophic "what if" thinking, shame spirals, and fears of losing emotional, physical, or mental control.'],['title'=>'Future-Oriented Anxiety','description'=>'Emotionally charged flashforwards and feared future scenarios that keep people stuck in anxiety and avoidance.']]; @endphp
          <div class="emdr-features" style="margin-top:var(--space-4);">
            @foreach($emdrCards as $card)
            <div class="emdr-feature" style="background:rgba(255,255,255,0.05);border-color:rgba(255,255,255,0.1);border-left-color:var(--color-accent-light);">
              <h4 style="color:var(--color-white);">{{ $card['title'] }}</h4>
              <p style="color:rgba(255,255,255,0.65);">{{ $card['description'] }}</p>
            </div>
            @endforeach
          </div>
        </div>
        <div class="grid-2__media">
          <img src="{{ $emdr?->content['image'] ?? '/images/4e854682cd76d19d-30f861_eb190602eba243f586aac2f6026db98b-mv2.jpg' }}" alt="Calm landscape" loading="lazy" width="600" height="740">
        </div>
      </div>
    </div>
  </section>

  <section class="section section--white" aria-labelledby="why-heading">
    <div class="container">
      <div class="grid-2 fade-in">
        <div class="grid-2__media" style="order:-1;">
          <img src="{{ $why?->content['image'] ?? '/images/24946176bc4178fd-d0220c_d40feae8ad4e4961b519d527fe1eb369-mv2_d_1440_1920_s_2.jpg' }}" alt="Lysander Verschuur, Psychologist" loading="lazy" width="600" height="750">
        </div>
        <div>
          <span class="section-label">{{ __('ui.approach.my_perspective') }}</span>
          <h2 id="why-heading">{{ $why?->content['heading'] ?? 'Why I specialize in trauma treatment' }}</h2>
          <div class="divider"></div>
          <div style="font-size:var(--size-md);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-4);">
            {!! $why?->content['body'] ?? '<p>I have a strong affinity for trauma-focused work because trauma often lies at the core of longstanding psychological suffering. When unresolved experiences begin to process and integrate, meaningful shifts can occur — not only in symptoms, but also in the way people relate to themselves, others, and life more broadly.</p><p>One of the reasons I value trauma-focused therapy is that treatment can often be both structured and effective. Over the years, I have repeatedly seen how reducing traumatic stress can create space for greater emotional freedom, self-understanding, resilience, and connection.</p>' !!}
          </div>
          <div class="quote-block">
            <p>{{ $why?->content['quote'] ?? 'Trauma therapy is ultimately about helping people move from survival-based patterns toward a greater sense of safety, flexibility, and trust — both in themselves and in life.' }}</p>
          </div>
          <div style="margin-top:var(--space-6);display:flex;gap:var(--space-4);flex-wrap:wrap;">
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="cta-section">
    <div class="container--narrow">
      <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">{{ __('ui.approach.begin_recovery') }}</span>
      <h2>{{ $cta?->content['heading'] ?? 'Meaningful recovery is possible' }}</h2>
      <p>{!! $cta?->content['body'] ?? 'Trauma can deeply affect the way a person experiences themselves, others, and the world around them. At the same time, meaningful recovery and psychological change are possible. Therapy offers the possibility to process unresolved experiences, reduce the grip of fear and avoidance, and create more space for emotional freedom and stability.' !!}</p>
      <div class="cta-section__actions">
        <a href="{{ \App\Providers\AppServiceProvider::localizeUrl($cta?->content['cta_primary_url'] ?? null) }}" class="btn btn--primary btn--lg">{{ $cta?->content['cta_primary_label'] ?? __('ui.common.book_intro_call') }}</a>
      </div>
    </div>
  </div>

</main>
@endsection
