@extends('layouts.app')

@php
  $isNl = app()->getLocale() === 'nl';
  $main = ($sections ?? collect())->first();
  $content = $main?->content ?? [];
@endphp

@section('title', optional($seo)->meta_title ?: ($isNl ? 'Privacybeleid' : 'Privacy Policy'))
@section('meta_description', isset($seo, $seo->meta_description) ? '' : __('ui.layout.default_description'))

@section('page_styles')
<style>
  .legal { padding: var(--space-10) var(--space-6) var(--space-16); max-width: 44rem; }
  .legal__notice {
    background: var(--color-teal-light);
    border: 1px solid var(--color-teal);
    border-left-width: 4px;
    border-radius: var(--radius-md);
    padding: var(--space-5) var(--space-6);
    margin-bottom: var(--space-8);
    font-size: var(--size-sm);
    color: var(--color-text);
    line-height: 1.7;
  }
  .legal__notice strong { color: var(--color-teal); }
  .legal__updated { font-size: var(--size-sm); color: var(--color-text-light); margin-bottom: var(--space-8); }
  .legal h2 {
    font-family: var(--font-heading);
    font-size: var(--size-xl);
    color: var(--color-text);
    margin: var(--space-8) 0 var(--space-3);
  }
  .legal h2:first-of-type { margin-top: 0; }
  .legal p { font-size: var(--size-base); color: var(--color-text-muted); line-height: 1.8; margin-bottom: var(--space-4); }
  .legal ul { list-style: disc; padding-left: var(--space-6); margin-bottom: var(--space-4); }
  .legal li { font-size: var(--size-base); color: var(--color-text-muted); line-height: 1.8; padding: var(--space-1) 0; }
  .legal a { color: var(--color-teal); text-decoration: underline; }
</style>
@endsection

@section('content')

<div class="page-hero">
  <div class="container--narrow">
    <span class="page-hero__eyebrow">{{ $isNl ? 'Juridisch' : 'Legal' }}</span>
    <h1 class="page-hero__title">{{ $content['title'] ?? ($isNl ? 'Privacybeleid' : 'Privacy Policy') }}</h1>
    <div class="page-hero__text">
      <p>{{ $content['subtitle'] ?? ($isNl
        ? 'Deze verklaring legt uit welke persoonsgegevens worden verzameld via deze website en hoe deze worden gebruikt en beschermd.'
        : 'This statement explains what personal data is collected through this website and how it is used and protected.') }}</p>
    </div>
  </div>
</div>

<section class="section section--white">
  <div class="container--narrow legal">

    <p class="legal__updated">{{ $isNl ? 'Laatst bijgewerkt' : 'Last updated' }}: {{ (optional($main)->updated_at ?? now())->translatedFormat($isNl ? 'j F Y' : 'F j, Y') }}</p>

    {!! $content['body'] ?? '' !!}

  </div>
</section>

@endsection
