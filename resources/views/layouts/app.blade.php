<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ isset($seo) && $seo?->title ? $seo->title : '' }}@yield('title', __('ui.layout.default_title'))</title>
  <meta name="description" content="{{ isset($seo) && $seo?->meta_description ? $seo->meta_description : '' }}@yield('meta_description', __('ui.layout.default_description'))">
  @php
    $seoModel = isset($seo) ? $seo : null;
    $localeCode = app()->getLocale();
    $ogLocale = $localeCode === 'nl' ? 'nl_NL' : 'en_US';
    $ogLocaleAlt = $localeCode === 'nl' ? 'en_US' : 'nl_NL';

    $ogTitle = ($seoModel?->og_title)
      ?: ($seoModel?->meta_title)
      ?: (trim($__env->yieldContent('title')) ?: __('ui.layout.default_title'));
    $ogDescription = ($seoModel?->og_description)
      ?: ($seoModel?->meta_description)
      ?: (trim($__env->yieldContent('meta_description')) ?: __('ui.layout.default_description'));

    $ogCanonical = $seoModel?->canonical_url ?: trim($__env->yieldContent('canonical'));
    $ogUrl = $ogCanonical !== '' ? $ogCanonical : url()->current();

    $ogImageRaw = $seoModel?->og_image ?: '/images/og-image.jpg';
    $ogImage = \Illuminate\Support\Str::startsWith($ogImageRaw, ['http://', 'https://'])
      ? $ogImageRaw
      : url($ogImageRaw);
    $ogUsesDefaultImage = !($seoModel?->og_image);
  @endphp
  {{-- Open Graph (Facebook, WhatsApp, LinkedIn, etc.) --}}
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Lysander Verschuur">
  <meta property="og:locale" content="{{ $ogLocale }}">
  <meta property="og:locale:alternate" content="{{ $ogLocaleAlt }}">
  <meta property="og:title" content="{{ $ogTitle }}">
  <meta property="og:description" content="{{ $ogDescription }}">
  <meta property="og:url" content="{{ $ogUrl }}">
  <meta property="og:image" content="{{ $ogImage }}">
  <meta property="og:image:secure_url" content="{{ $ogImage }}">
  <meta property="og:image:type" content="image/jpeg">
  @if($ogUsesDefaultImage)
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  @endif
  <meta property="og:image:alt" content="{{ $ogTitle }}">
  {{-- Twitter / X Card --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $ogTitle }}">
  <meta name="twitter:description" content="{{ $ogDescription }}">
  <meta name="twitter:image" content="{{ $ogImage }}">
  <meta name="twitter:image:alt" content="{{ $ogTitle }}">
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
