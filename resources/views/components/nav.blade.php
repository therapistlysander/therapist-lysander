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
    {{-- Language switcher dropdown --}}
    @php
      $currentLocale = app()->getLocale();
      $pathWithoutLocale = preg_replace('#^/(' . implode('|', config('app.supported_locales', ['en','nl'])) . ')#', '', request()->getPathInfo()) ?: '/';
      $langFlags = ['en' => '🇬🇧', 'nl' => '🇱'];
      $langNames = ['en' => 'English', 'nl' => 'Nederlands'];
      $langCodes = ['en' => 'EN', 'nl' => 'NL'];
    @endphp
    <div class="nav__lang-dropdown" style="position:relative;">
      <button class="nav__lang-btn" onclick="this.nextElementSibling.classList.toggle('show')" style="display:flex;align-items:center;gap:6px;background:none;border:none;cursor:pointer;font-size:var(--size-sm);font-weight:600;color:var(--color-text);letter-spacing:0.05em;">
        <span style="font-size:1.1rem;">{{ $langFlags[$currentLocale] }}</span>
        <span>{{ $langCodes[$currentLocale] }}</span>
        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" style="margin-left:2px;"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
      <div class="nav__lang-menu" style="display:none;position:absolute;top:100%;right:0;background:var(--color-bg-dark);border-radius:var(--radius-sm);min-width:160px;z-index:200;overflow:hidden;box-shadow:var(--shadow-lg);">
        @foreach(['en','nl'] as $code)
          @if($code !== $currentLocale)
        <a href="{{ url('/' . $code . $pathWithoutLocale) }}" style="display:flex;align-items:center;gap:10px;padding:12px 16px;text-decoration:none;color:var(--color-white);font-size:var(--size-sm);font-weight:500;letter-spacing:0.05em;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">
          <span style="font-size:1.2rem;">{{ $langFlags[$code] }}</span>
          <span>{{ $langNames[$code] }} ({{ strtoupper($code) }})</span>
        </a>
          @endif
        @endforeach
      </div>
    </div>
    <script>
      document.addEventListener('click', function(e) {
        document.querySelectorAll('.nav__lang-menu').forEach(function(m) {
          if (!m.parentElement.contains(e.target)) m.classList.remove('show');
        });
      });
      document.querySelectorAll('.nav__lang-menu.show').forEach(function(m) {
        m.style.display = 'block';
      });
    </script>
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
    $langFlags = ['en' => '🇬🇧', 'nl' => '🇱'];
    $langNames = ['en' => 'English', 'nl' => 'Nederlands'];
  @endphp
  <div style="display:flex;align-items:center;gap:8px;padding:var(--space-3) 0;">
    @foreach(['en','nl'] as $code)
      @if($code !== $currentLocale)
    <a href="{{ url('/' . $code . $pathWithoutLocale) }}" style="display:flex;align-items:center;gap:8px;padding:8px 14px;border-radius:var(--radius-sm);text-decoration:none;font-size:var(--size-sm);font-weight:500;color:var(--color-white);background:var(--color-bg-dark);">
      <span style="font-size:1.1rem;">{{ $langFlags[$code] }}</span>
      <span>{{ $langNames[$code] }}</span>
    </a>
      @endif
    @endforeach
  </div>
</div>
