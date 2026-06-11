<nav class="nav" role="navigation" aria-label="{{ __('ui.nav.main_navigation') }}">
  <div class="nav__inner">
    <a href="{{ route('home') }}" class="nav__logo" aria-label="Therapist Lysander — {{ __('ui.nav.home') }}">
      <img src="/images/logo.png" alt="Lysander Verschuur" class="nav__logo-img">
    </a>
    <a href="{{ route('home') }}" class="nav__identity" aria-label="Therapist Lysander — {{ __('ui.nav.home') }}">Lysander Verschuur <span class="nav__identity-title">{!! __('ui.nav.identity_title') !!}</span></a>
    <div class="nav__links" role="menubar">
      <a href="{{ route('home') }}" class="nav__link {{ request()->routeIs('home') ? 'active' : '' }}" role="menuitem">{{ __('ui.nav.home') }}</a>
      <a href="{{ route('approach') }}" class="nav__link {{ request()->routeIs('approach') ? 'active' : '' }}" role="menuitem">{{ __('ui.nav.approach') }}</a>
      <a href="{{ route('training') }}" class="nav__link {{ request()->routeIs('training') ? 'active' : '' }}" role="menuitem">{{ __('ui.nav.training') }}</a>
      <a href="{{ route('testimonials') }}" class="nav__link {{ request()->routeIs('testimonials') ? 'active' : '' }}" role="menuitem">{{ __('ui.nav.testimonials') }}</a>
      <a href="{{ route('fees') }}" class="nav__link {{ request()->routeIs('fees') ? 'active' : '' }}" role="menuitem">{{ __('ui.nav.fees') }}</a>
      <a href="{{ route('faq') }}" class="nav__link {{ request()->routeIs('faq') ? 'active' : '' }}" role="menuitem">{{ __('ui.nav.faq') }}</a>
      <a href="{{ route('contact') }}" class="nav__link {{ request()->routeIs('contact') ? 'active' : '' }}" role="menuitem">{{ __('ui.nav.contact') }}</a>
    </div>
    {{-- Language switcher --}}
    @php
      $currentLocale = app()->getLocale();
      $altLocale = $currentLocale === 'en' ? 'nl' : 'en';
      $pathWithoutLocale = preg_replace('#^/(' . implode('|', config('app.supported_locales', ['en','nl'])) . ')#', '', request()->getPathInfo()) ?: '/';
    @endphp
    <div class="nav__lang-switch" style="display:flex;align-items:center;gap:4px;margin-left:var(--space-3);font-size:var(--size-xs);font-weight:500;">
      <a href="{{ url('/en' . $pathWithoutLocale) }}" style="padding:2px 6px;border-radius:4px;text-decoration:none;color:{{ $currentLocale === 'en' ? 'var(--color-teal)' : 'var(--color-text-muted)' }};background:{{ $currentLocale === 'en' ? 'var(--color-teal-light)' : 'transparent' }};">{{ __('ui.language.en') }}</a>
      <span style="color:var(--color-border);">|</span>
      <a href="{{ url('/nl' . $pathWithoutLocale) }}" style="padding:2px 6px;border-radius:4px;text-decoration:none;color:{{ $currentLocale === 'nl' ? 'var(--color-teal)' : 'var(--color-text-muted)' }};background:{{ $currentLocale === 'nl' ? 'var(--color-teal-light)' : 'transparent' }};">{{ __('ui.language.nl') }}</a>
    </div>
    <button class="nav__burger" aria-label="{{ __('ui.nav.toggle_menu') }}" aria-expanded="false">
      <span class="nav__burger-bars"><span></span><span></span><span></span></span>
      <svg class="nav__burger-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="24" height="24"><line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/></svg>
    </button>
  </div>
</nav>

<div class="nav__mobile" role="menu" aria-label="{{ __('ui.nav.mobile_navigation') }}">
  <a href="{{ route('home') }}" class="nav__link" role="menuitem">{{ __('ui.nav.home') }}</a>
  <a href="{{ route('approach') }}" class="nav__link" role="menuitem">{{ __('ui.nav.approach') }}</a>
  <a href="{{ route('training') }}" class="nav__link" role="menuitem">{{ __('ui.nav.training') }}</a>
  <a href="{{ route('testimonials') }}" class="nav__link" role="menuitem">{{ __('ui.nav.testimonials') }}</a>
  <a href="{{ route('fees') }}" class="nav__link" role="menuitem">{{ __('ui.nav.fees') }}</a>
  <a href="{{ route('faq') }}" class="nav__link" role="menuitem">{{ __('ui.nav.faq') }}</a>
  <a href="{{ route('contact') }}" class="nav__link" role="menuitem">{{ __('ui.nav.contact') }}</a>
  <a href="{{ route('booking') }}" class="nav__cta" role="menuitem">{{ __('ui.nav.booking_cta') }}</a>
  {{-- Mobile language switcher --}}
  @php
    $currentLocale = app()->getLocale();
    $pathWithoutLocale = preg_replace('#^/(' . implode('|', config('app.supported_locales', ['en','nl'])) . ')#', '', request()->getPathInfo()) ?: '/';
  @endphp
  <div style="display:flex;align-items:center;gap:8px;padding:var(--space-3) 0;">
    <a href="{{ url('/en' . $pathWithoutLocale) }}" style="padding:4px 10px;border-radius:4px;text-decoration:none;font-size:var(--size-sm);font-weight:500;color:{{ $currentLocale === 'en' ? 'var(--color-teal)' : 'var(--color-text-muted)' }};background:{{ $currentLocale === 'en' ? 'var(--color-teal-light)' : 'transparent' }};">{{ __('ui.language.en') }}</a>
    <span style="color:var(--color-border);">|</span>
    <a href="{{ url('/nl' . $pathWithoutLocale) }}" style="padding:4px 10px;border-radius:4px;text-decoration:none;font-size:var(--size-sm);font-weight:500;color:{{ $currentLocale === 'nl' ? 'var(--color-teal)' : 'var(--color-text-muted)' }};background:{{ $currentLocale === 'nl' ? 'var(--color-teal-light)' : 'transparent' }};">{{ __('ui.language.nl') }}</a>
  </div>
</div>
