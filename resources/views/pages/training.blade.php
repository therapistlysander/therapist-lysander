@extends('layouts.app')

@section('title', __('ui.page_title.training'))
@section('meta_description', 'Clinical training and professional background of Lysander Verschuur, MSc. — advanced training in EMDR, Schema Therapy, ACT, CBT, and trauma-focused psychotherapy.')
@section('canonical', 'https://www.therapistlysander.com/clinical-training/')

@php
  $hero       = $sections['training_hero'] ?? null;
  $background = $sections['training_background'] ?? null;
  $categories = $sections['training_categories'] ?? null;
  $approach   = $sections['training_approach'] ?? null;
@endphp

@section('page_styles')
<style>
  .training-section { margin-bottom: var(--space-10); }
  .training-section:last-child { margin-bottom: 0; }
  .training-header { display: flex; align-items: flex-start; gap: var(--space-4); margin-bottom: var(--space-4); padding-bottom: var(--space-3); border-bottom: 1px solid var(--color-border); }
  .training-header__icon { width: 44px; height: 44px; background: var(--color-teal-light); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--color-teal); }
  .training-header__icon svg { width: 22px; height: 22px; }
  .training-header__title { font-family: var(--font-heading); font-size: var(--size-xl); color: var(--color-text); }
  .training-list { list-style: none; }
  .training-list li { display: flex; align-items: flex-start; gap: var(--space-3); padding: var(--space-4) 0; border-bottom: 1px solid var(--color-border); font-size: var(--size-sm); color: var(--color-text-muted); line-height: 1.5; }
  .training-list li:last-child { border-bottom: none; }
  .training-list li::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--color-accent); flex-shrink: 0; margin-top: 5px; }
  .training-grid { display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-10); }
  @media (max-width: 640px) { .training-grid { grid-template-columns: minmax(0, 1fr); } }
</style>
@endsection

@section('content')
<div class="scroll-progress" id="scroll-progress" aria-hidden="true"></div>
<main id="main-content">

  <div class="page-hero">
    <div class="container--narrow">
      <span class="page-hero__eyebrow">{{ $hero?->content['subheading'] ?? 'Professional background' }}</span>
      <h1 class="page-hero__title">{{ $hero?->content['heading'] ?? 'Clinical Training & Continued Education' }}</h1>
      <div class="page-hero__text">{!! $hero?->content['body'] ?? '<p>Effective therapy requires more than a degree alone. Over the years, I have continued to invest in advanced clinical training across multiple evidence-based psychotherapy approaches, with a particular focus on trauma treatment, experiential therapies, and integrative psychotherapy.</p><p>At the same time, meaningful change rarely comes from techniques alone. While training and expertise matter, therapy is ultimately a human process. A strong therapeutic relationship, genuine collaboration, and feeling understood are often just as important as the methods themselves.</p><p>My aim is to combine clinical knowledge with a thoughtful, individualized approach that is tailored to the person sitting in front of me.</p>' !!}</div>
    </div>
  </div>

  <section class="section section--white" aria-labelledby="background-heading">
    <div class="container">
      <div class="grid-2 fade-in">
        <div>
          <span class="section-label">{{ __('ui.training.academic_label') }}</span>
          <h2 id="background-heading">{{ $background?->content['heading'] ?? 'MSc. Psychology' }}</h2>
          <div class="divider"></div>
          <div style="font-size:var(--size-base);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-6);">
            {!! $background?->content['body'] ?? '<p>I hold an <strong>MSc in Psychology</strong>, with additional academic specialization in <strong>Social Psychology</strong> and <strong>Neurocognitive Science</strong>.</p><p>I have completed advanced clinical training in multiple evidence-based psychotherapy approaches, with a particular focus on trauma treatment, experiential therapies, and integrative psychotherapy.</p><p>Psychology is a field that continues to evolve. I believe that effective therapy requires ongoing learning, reflection, and professional development. Continuing education allows me to integrate new insights, refine existing skills, and provide care that is both evidence-based and responsive to the individual needs of each client.</p>' !!}
          </div>
          {{-- Official NIP registration logo (transparent PNG supplied by NIP) --}}
          <div class="nip-badge">
            <img src="/images/nip-psycholoog-nip.png" alt="{{ __('ui.training.nip_caption') }} — Nederlands Instituut van Psychologen" width="88" height="88" loading="lazy">
            <span class="nip-badge__caption">{{ __('ui.training.nip_caption') }}</span>
          </div>
          @php $bgStats = $background?->content['stats'] ?? [['value'=>'NIP','label'=>'Psychologist NIP'],['value'=>'EMDR','label'=>'Advanced Training'],['value'=>'MSc.','label'=>'Psychology degree'],['value'=>'15+','label'=>'Specialized Trainings']]; @endphp
          <div class="stats">
            @foreach($bgStats as $stat)
            <div class="stats__item">
              @include('components.credential-icon', ['value' => $stat['value']])
              <div class="stats__num">{{ $stat['value'] }}</div>
              <div class="stats__label">{{ $stat['label'] }}</div>
            </div>
            @endforeach
          </div>
        </div>
        <div class="grid-2__media">
          <img src="{{ $background?->content['image'] ?? '/images/ff96a9dc8ea72c2c-11062b_aa33e58c18774e7db74c68e74a6c231e-mv2.jpg' }}" alt="Lysander Verschuur, MSc. — Psychologist" loading="lazy" width="600" height="750">
        </div>
      </div>
    </div>
  </section>

  <section class="section section--alt" aria-labelledby="training-heading">
    <div class="container">
      <div class="section-header fade-in">
        <span class="section-label">{!! __('ui.training.training_education') !!}</span>
        <h2 id="training-heading" class="section-title">{{ $categories?->content['heading'] ?? 'Specialized clinical training' }}</h2>
      </div>

      @php $groups = $categories?->content['groups'] ?? [
        ['title'=>'Trauma & EMDR','items'=>[['title'=>'EMDR Foundation Training'],['title'=>'EMDR Mastertraining'],['title'=>'Affect-Focused EMDR'],['title'=>'Exposure Therapy for EMDR Therapists'],['title'=>'Flash Technique 2.0'],['title'=>'Anger, Rage & Revenge Protocol'],['title'=>'Imagery Rescripting']]],
        ['title'=>'Schema Therapy & Experiential','items'=>[['title'=>'Fundamentals of Schema Therapy'],['title'=>'ACT & Schema Therapy Integration'],['title'=>'EMDR & Schema Therapy Integration'],['title'=>'Boxing-Based Psychotherapy']]],
        ['title'=>'ACT & CBT','items'=>[['title'=>'Fundamentals of ACT'],['title'=>'ACT Follow-Up Training'],['title'=>'ACT in Groups'],['title'=>'CBT'],['title'=>'Beck Institute CBT Training']]],
        ['title'=>'Professional Background','items'=>[['title'=>'MSc. in Psychology'],['title'=>'Academic specialization: Social Psychology'],['title'=>'Academic specialization: Neurocognitive Science'],['title'=>'International clinical experience across diverse populations'],['title'=>'Broad range of psychological difficulties and treatment needs']]],
      ]; @endphp

      <div class="training-grid fade-in">
        <div>
          @foreach(array_slice($groups, 0, 2) as $index => $group)
          <div class="training-section">
            <div class="training-header">
              <div class="training-header__icon">
                @if($index === 0)
                {{-- Trauma & EMDR — sparkle / healing --}}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                @else
                {{-- Schema Therapy & Experiential — brain / puzzle --}}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3a9 9 0 00-9 9c0 3.07 1.64 5.64 4 7.28V21a1 1 0 001 1h8a1 1 0 001-1v-1.72c2.36-1.64 4-4.21 4-7.28a9 9 0 00-9-9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v9m-4.5-4.5L12 12m4.5-4.5L12 12"/></svg>
                @endif
              </div>
              <div>
                <p class="training-header__title">{{ $group['title'] }}</p>
              </div>
            </div>
            <ul class="training-list">
              @foreach($group['items'] ?? [] as $item)
              <li>{{ $item['title'] }}</li>
              @endforeach
            </ul>
          </div>
          @endforeach
        </div>

        <div>
          @foreach(array_slice($groups, 2) as $index => $group)
          @php
            // Icon by group title keyword — shield for professional registration/memberships,
            // open book for ACT & CBT, graduation cap for academic background (fallback).
            $groupTitle = mb_strtolower($group['title'] ?? '');
            $isRegistration = str_contains($groupTitle, 'registration') || str_contains($groupTitle, 'membership')
                || str_contains($groupTitle, 'registratie') || str_contains($groupTitle, 'lidmaatschap');
          @endphp
          <div class="training-section">
            <div class="training-header">
              <div class="training-header__icon">
                @if($isRegistration)
                {{-- Professional Registration & Memberships — shield --}}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.249-8.25-3.286z"/></svg>
                @elseif($index === 0)
                {{-- ACT & CBT — open book / learning --}}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                @else
                {{-- Academic Background — graduation cap --}}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.708 50.708 0 017.74-3.342M6.75 15v-3.75m0 0l2.25 2.25M6.75 11.25L4.5 13.5"/></svg>
                @endif
              </div>
              <div>
                <p class="training-header__title">{{ $group['title'] }}</p>
              </div>
            </div>
            <ul class="training-list">
              @foreach($group['items'] ?? [] as $item)
              <li>{{ $item['title'] }}</li>
              @endforeach
            </ul>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section class="section section--white" aria-labelledby="professional-heading">
    <div class="container">
      <div class="container--narrow" style="margin:0 auto;">
        <div class="fade-in">
          <span class="section-label">{{ __('ui.training.approach_label') }}</span>
          <h2 id="professional-heading">{{ $approach?->content['heading'] ?? 'Integrative, trauma-informed, individualized' }}</h2>
          <div class="divider"></div>
          <div style="font-size:var(--size-base);color:var(--color-text-muted);line-height:1.85;margin-bottom:var(--space-6);">
            {!! $approach?->content['body'] ?? '<p>My approach is shaped not only by formal training, but also by years of clinical experience working with people from diverse backgrounds and life circumstances. Rather than relying on a single model, I draw from different evidence-based approaches and tailor treatment to the individual.</p><p>Continued professional development remains an important part of my work, helping me refine existing skills, deepen my understanding, and stay up to date with developments in the field.</p>' !!}
          </div>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection
