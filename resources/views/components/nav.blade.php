<nav class="nav" role="navigation" aria-label="Main navigation">
  <div class="nav__inner">
    <a href="{{ route('home') }}" class="nav__logo" aria-label="Therapist Lysander — Home">
      <img src="/images/logo.png" alt="Lysander Verschuur" class="nav__logo-img">
    </a>
    <span class="nav__identity">Verschuur <span class="nav__identity-title">Psychologist &amp; Trauma Therapist</span></span>
    <div class="nav__links" role="menubar">
      <a href="{{ route('home') }}" class="nav__link {{ request()->routeIs('home') ? 'active' : '' }}" role="menuitem">Home</a>
      <a href="{{ route('approach') }}" class="nav__link {{ request()->routeIs('approach') ? 'active' : '' }}" role="menuitem">Trauma &amp; My Approach</a>
      <a href="{{ route('training') }}" class="nav__link {{ request()->routeIs('training') ? 'active' : '' }}" role="menuitem">Clinical Training</a>
      <a href="{{ route('testimonials') }}" class="nav__link {{ request()->routeIs('testimonials') ? 'active' : '' }}" role="menuitem">Testimonials</a>
      <a href="{{ route('fees') }}" class="nav__link {{ request()->routeIs('fees') ? 'active' : '' }}" role="menuitem">Fees &amp; Process</a>
      <a href="{{ route('contact') }}" class="nav__link {{ request()->routeIs('contact') ? 'active' : '' }}" role="menuitem">Contact</a>
    </div>
    <a href="{{ route('booking') }}" class="nav__cta">Book a Free 30-Min Intro Call</a>
    <button class="nav__burger" aria-label="Toggle menu" aria-expanded="false">
      <span class="nav__burger-bars"><span></span><span></span><span></span></span>
      <svg class="nav__burger-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="24" height="24"><line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/></svg>
      <span class="nav__burger-label">Menu</span>
    </button>
  </div>
</nav>

<div class="nav__mobile" role="menu" aria-label="Mobile navigation">
  <a href="{{ route('home') }}" class="nav__link" role="menuitem">Home</a>
  <a href="{{ route('approach') }}" class="nav__link" role="menuitem">Trauma &amp; My Approach</a>
  <a href="{{ route('training') }}" class="nav__link" role="menuitem">Clinical Training</a>
  <a href="{{ route('testimonials') }}" class="nav__link" role="menuitem">Testimonials</a>
  <a href="{{ route('fees') }}" class="nav__link" role="menuitem">Fees &amp; Process</a>
  <a href="{{ route('contact') }}" class="nav__link" role="menuitem">Contact</a>
  <a href="{{ route('booking') }}" class="nav__cta" role="menuitem">Book a Free 30-Min Intro Call</a>
</div>
