<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ isset($seo) && $seo?->title ? $seo->title : '' }}@yield('title', __('ui.layout.default_title'))</title>
  <meta name="description" content="{{ isset($seo) && $seo?->meta_description ? $seo->meta_description : '' }}@yield('meta_description', __('ui.layout.default_description'))">
  @if(isset($seo) && $seo?->og_title)
  <meta property="og:title" content="{{ $seo->og_title }}">
  @endif
  @if(isset($seo) && $seo?->og_description)
  <meta property="og:description" content="{{ $seo->og_description }}">
  @endif
  @if(isset($seo) && $seo?->canonical_url)
  <link rel="canonical" href="{{ $seo->canonical_url }}">
  @else
  @hasSection('canonical')
  <link rel="canonical" href="@yield('canonical')">
  @endif
  @endif
  {{-- Hreflang tags for both locales --}}
  @php
    $supportedLocales = config('app.supported_locales', ['en','nl']);
    $pathWithoutLocale = preg_replace('#^/(' . implode('|', $supportedLocales) . ')#', '', request()->getPathInfo()) ?: '/';
  @endphp
  @foreach($supportedLocales as $loc)
  <link rel="alternate" hreflang="{{ $loc }}" href="{{ url('/' . $loc . $pathWithoutLocale) }}">
  @endforeach
  <link rel="alternate" hreflang="x-default" href="{{ url('/en' . $pathWithoutLocale) }}">
  <link rel="icon" type="image/x-icon" href="/favicon.ico">
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
  <link rel="manifest" href="/images/site.webmanifest">
  <meta name="theme-color" content="#ffffff">
  <link rel="stylesheet" href="/css/site.css?v={{ filemtime(public_path('css/site.css')) }}">
  @yield('page_styles')
</head>
<body>

<div class="scroll-progress" id="scroll-progress" aria-hidden="true"></div>

@include('components.nav')

<main id="main-content">
  @yield('content')
</main>

@include('components.footer')

<div class="toast" id="toast" role="alert" aria-live="polite"></div>

<script src="/js/site.js"></script>
@yield('page_scripts')
</body>
</html>
