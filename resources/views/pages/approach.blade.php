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
  .trauma-types { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: var(--space-3); margin-top: var(--space-8); }
  .trauma-type-item { display: flex; align-items: flex-start; gap: var(--space-3); font-size: var(--size-sm); color: var(--color-text-muted); padding: var(--space-4); background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--radius); line-height: 1.5; }
  .trauma-type-item::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--color-accent); flex-shrink: 0; margin-top: 5px; }
  .emdr-features { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--space-4); margin-top: var(--space-8); }
  .emdr-feature { padding: var(--space-6); background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--radius-md); border-left: 3px solid var(--color-accent); }
  .emdr-feature h4 { font-size: var(--size-base); margin-bottom: var(--space-2); color: var(--color-text); }
  .emdr-feature p { font-size: var(--size-sm); color: var(--color-text-muted); line-height: 1.65; }
  .treatment-approaches { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: var(--space-4); margin-top: var(--space-8); }
  .treatment-approach { background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--space-6); }
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
          <span class="section-label">Understanding trauma</span>
          <h2 id="trauma-heading">{{ $understanding?->content['heading'] ?? 'How trauma affects the present' }}</h2>
          <div class="divider"></div>
          <div style="font-size:var(--size-md);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-8);">
            {!! $understanding?->content['body'] ?? '<p>Difficult or overwhelming experiences can leave a lasting impact on the nervous system, emotional regulation, relationships, and sense of self. Trauma may present through symptoms such as anxiety, panic, emotional numbness, hypervigilance, intrusive memories, flashbacks, shame, low self-worth, or persistent patterns of avoidance and control.</p><p>Sometimes the source of trauma is clear and identifiable. In other cases, the impact develops more gradually through repeated experiences of criticism, emotional neglect, instability, rejection, or chronic stress.</p><p>I work with both acute trauma <em>("big T" trauma)</em> and more cumulative or relational forms of trauma <em>("small t" trauma)</em>. Both can have a profound psychological impact, and both are treatable.</p>' !!}
          </div>
          <a href="{{ $understanding?->content['cta_url'] ?? route('booking') }}" class="btn btn--primary">{{ $understanding?->content['cta_label'] ?? 'Book a Free 30-Minute Intro Call' }}</a>
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
        <span class="section-label">Types of trauma</span>
        <h2 id="types-heading" class="section-title">{{ $types?->content['heading'] ?? 'Trauma I work with' }}</h2>
        <p style="color:var(--color-text-muted);font-size:var(--size-md);max-width:600px;">{!! $types?->content['body'] ?? 'Regardless of how trauma manifests itself, therapy can help restore a greater sense of safety, stability, emotional freedom, and self-trust.' !!}</p>
      </div>
      @php $traumaItems = $types?->content['items'] ?? [['title'=>'War zone experiences'],['title'=>'Accidents and injury-related trauma'],['title'=>'Sexual abuse and assault'],['title'=>'Medical trauma'],['title'=>'Panic attacks and overwhelming psychological experiences'],['title'=>'Childhood abuse and emotional neglect'],['title'=>'Grief and traumatic loss'],['title'=>'Bullying and social exclusion'],['title'=>'High-conflict relational or family situations'],['title'=>'Trauma-related self-worth and identity difficulties']]; @endphp
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
        <span class="section-label">Treatment</span>
        <h2 id="treatment-heading" class="section-title">{{ $treatments?->content['heading'] ?? 'Trauma-focused treatment' }}</h2>
        <p style="color:var(--color-text-muted);font-size:var(--size-md);max-width:640px;margin:0 auto;">{!! $treatments?->content['body'] ?? 'My work integrates evidence-based trauma-focused approaches, tailored to the individual and the nature of the difficulties involved. The aim is not only symptom reduction, but also helping clients process unresolved experiences and develop a more stable and compassionate relationship with themselves.' !!}</p>
      </div>
      @php $treatmentCards = $treatments?->content['cards'] ?? [['subtitle'=>'Primary method','title'=>'EMDR','description'=>'Eye Movement Desensitization and Reprocessing — the gold standard evidence-based treatment for trauma and PTSD. Using bilateral stimulation to help the brain process and integrate traumatic memories.'],['subtitle'=>'Trauma processing','title'=>'Exposure Therapy','description'=>'Gradual, structured confrontation with fear and trauma. Helps reduce avoidance and integrate difficult experiences into a coherent understanding of the self.'],['subtitle'=>'Trauma processing','title'=>'Flash Technique','description'=>'A gentler entry point for trauma processing when memories are highly distressing or flooding. Particularly helpful when clients are in acute distress or when dissociation is present.'],['subtitle'=>'Memory reworking','title'=>'Imagery Rescripting','description'=>'Rewriting painful emotional memories to reduce their distress and emotional charge. Particularly effective for childhood trauma, neglect, and shame-based experiences.'],['subtitle'=>'Deep patterns','title'=>'Schema Therapy','description'=>'Addressing deep-rooted emotional patterns formed in childhood. Brings care and understanding to wounded inner parts, replacing maladaptive coping with genuine emotional healing.'],['subtitle'=>'Integrative','title'=>'Parts-oriented & Somatic','description'=>'Body-based and parts-oriented approaches to access and release what is held in the body and nervous system — where talk therapy alone cannot reach.']]; @endphp
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
          <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">About EMDR</span>
          <h2 id="emdr-heading" style="color:var(--color-white);">{{ $emdr?->content['heading'] ?? 'EMDR is not only about the past' }}</h2>
          <div class="divider"></div>
          <div style="color:rgba(255,255,255,0.75);font-size:var(--size-md);line-height:1.85;margin-bottom:var(--space-6);">
            {!! $emdr?->content['body'] ?? '<p>EMDR is widely known as a treatment for painful memories and trauma from the past. However, it can also be highly effective in treating intense fears, catastrophic future scenarios, and intrusive "flashforward" images that keep people stuck in anxiety and avoidance.</p><p>When these fears become emotionally charged and repetitive, they can strongly shape a person\'s daily life and sense of freedom. Through EMDR, exposure therapy, CBT, and experiential interventions, these patterns are often very treatable.</p>' !!}
          </div>
          @php $emdrCards = $emdr?->content['cards'] ?? [['title'=>'Panic about panic attacks','description'=>'Treating the fear of anxiety itself — not just the symptoms.'],['title'=>'Health anxiety','description'=>'Catastrophic health fears and intrusive bodily preoccupations.'],['title'=>'Fear of losing control','description'=>'Social fears, shame spirals, and catastrophic "what if" thinking.'],['title'=>'Future-oriented anxiety','description'=>'Emotionally charged flashforwards that keep people stuck.']]; @endphp
          <div class="emdr-features" style="margin-top:var(--space-6);">
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
          <span class="section-label">My perspective</span>
          <h2 id="why-heading">{{ $why?->content['heading'] ?? 'Why I specialize in trauma treatment' }}</h2>
          <div class="divider"></div>
          <div style="font-size:var(--size-md);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-5);">
            {!! $why?->content['body'] ?? '<p>I have a strong affinity for trauma-focused work because trauma often lies at the core of longstanding psychological suffering. When unresolved experiences begin to process and integrate, meaningful shifts can occur — not only in symptoms, but also in the way people relate to themselves, others, and life more broadly.</p><p>One of the reasons I value trauma-focused therapy is that treatment can often be both structured and effective. Over the years, I have repeatedly seen how reducing traumatic stress can create space for greater emotional freedom, self-understanding, resilience, and connection.</p>' !!}
          </div>
          <div class="quote-block">
            <p>{{ $why?->content['quote'] ?? 'Trauma therapy is ultimately about helping people move from survival-based patterns toward a greater sense of safety, flexibility, and trust — both in themselves and in life.' }}</p>
          </div>
          <div style="margin-top:var(--space-8);display:flex;gap:var(--space-4);flex-wrap:wrap;">
            <a href="{{ $why?->content['cta_primary_url'] ?? route('training') }}" class="btn btn--outline">{{ $why?->content['cta_primary_label'] ?? 'View Clinical Training' }}</a>
            <a href="{{ $why?->content['cta_secondary_url'] ?? route('booking') }}" class="btn btn--primary">{{ $why?->content['cta_secondary_label'] ?? 'Book a Free Intro Call' }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="cta-section">
    <div class="container--narrow">
      <span class="section-label" style="color:var(--color-accent-light);border-color:var(--color-accent-light);">Begin recovery</span>
      <h2>{{ $cta?->content['heading'] ?? 'Meaningful recovery is possible' }}</h2>
      <p>{!! $cta?->content['body'] ?? 'Trauma can deeply affect the way a person experiences themselves, others, and the world around them. At the same time, meaningful recovery and psychological change are possible. Therapy offers the possibility to process unresolved experiences, reduce the grip of fear and avoidance, and create more space for emotional freedom and stability.' !!}</p>
      <div class="cta-section__actions">
        <a href="{{ $cta?->content['cta_primary_url'] ?? route('booking') }}" class="btn btn--primary btn--lg">{{ $cta?->content['cta_primary_label'] ?? 'Book a Free 30-Minute Intro Call' }}</a>
        <a href="{{ $cta?->content['cta_secondary_url'] ?? 'https://wa.me/66935309052?text=Hi%20Lysander%2C%20I%27d%20like%20to%20learn%20more%20about%20therapy.' }}" target="_blank" rel="noopener noreferrer" class="btn btn--whatsapp btn--lg">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="18" height="18"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          {{ $cta?->content['cta_secondary_label'] ?? 'WhatsApp me' }}
        </a>
      </div>
    </div>
  </div>

</main>
@endsection
