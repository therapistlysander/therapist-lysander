<footer class="footer" aria-label="Site-footer">
  <div class="container">
    <div class="footer__brand" style="text-align:center;margin-bottom:var(--space-8);">
      <img src="/images/logo.png" alt="Lysander Verschuur" class="footer__logo-img" style="margin:0 auto var(--space-4);">
      <strong class="footer__brand-name" style="text-align:center;">Lysander Verschuur</strong>
      <span class="footer__brand-title">{!! __('ui.nav.identity_title') !!}</span>
      <span class="footer__brand-credentials" style="display:inline-flex;align-items:center;gap:var(--space-2);font-size:var(--size-sm);color:var(--color-accent-light);margin-top:var(--space-2);font-weight:500;letter-spacing:0.04em;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.708 50.708 0 017.74-3.342M6.75 15v-3.75m0 0l2.25 2.25M6.75 11.25L4.5 13.5"/></svg>
        {!! __('ui.footer.credentials') !!}
      </span>
      <p class="footer__brand-tagline">{{ __('ui.footer.tagline') }}</p>
    </div>
    <div class="footer__grid">
      <div class="footer__col">
        {{-- <h4>{{ __('ui.footer.navigation') }}</h4> --}}
        <a href="{{ route('home') }}">{{ __('ui.nav.home') }}</a>
        <a href="{{ route('approach') }}">{{ __('ui.nav.approach') }}</a>
        <a href="{{ route('training') }}">{{ __('ui.nav.training') }}</a>
        <a href="{{ route('testimonials') }}">{{ __('ui.nav.testimonials') }}</a>
        <a href="{{ route('fees') }}">{{ __('ui.nav.fees') }}</a>
        <a href="{{ route('faq') }}">{{ __('ui.nav.faq') }}</a>
        <a href="{{ route('contact') }}">{{ __('ui.nav.contact') }}</a>
      </div>
      <div class="footer__col">
        {{-- <h4>{{ __('ui.footer.contact') }}</h4> --}}
        <a href="mailto:contact@therapistlysander.com">contact@therapistlysander.com</a>
        <a href="https://wa.me/66935309052" target="_blank" rel="noopener noreferrer">WhatsApp: +66 93 530 9052</a>
        <a href="{{ route('booking') }}">{{ __('ui.footer.book_intro_call') }}</a>
      </div>
    </div>
    <div class="footer__bottom">
      <div class="footer__bottom-left">
        <p>&copy; {{ date('Y') }} Lysander Verschuur, MSc. {{ __('ui.footer.all_rights') }}</p>
        <nav class="footer__legal" aria-label="{{ __('ui.footer.privacy') }} / {{ __('ui.footer.terms') }}">
          <a href="{{ route('privacy') }}">{{ __('ui.footer.privacy') }}</a>
          <span aria-hidden="true">&middot;</span>
          <a href="{{ route('terms') }}">{{ __('ui.footer.terms') }}</a>
        </nav>
      </div>
      <p>{!! __('ui.footer.psychotherapy') !!}</p>
    </div>
  </div>
</footer>
