<footer class="footer" aria-label="Site footer">
  <div class="container">
    <div class="footer__brand" style="text-align:center;margin-bottom:var(--space-12);">
      <img src="/images/logo.png" alt="Lysander Verschuur" class="footer__logo-img" style="margin:0 auto var(--space-4);">
      <strong class="footer__brand-name" style="text-align:center;">Lysander Verschuur</strong>
      <span class="footer__brand-title">Psychologist &amp; Trauma Therapist</span>
      <span class="footer__brand-credentials" style="display:block;font-size:var(--size-sm);color:var(--color-text-muted);margin-top:var(--space-1);">MSc. Psychology</span>
      <p class="footer__brand-tagline">Evidence-based therapy with a personalised and client-centered approach</p>
    </div>
    <div class="footer__grid">
      <div class="footer__col">
        <h4>Navigation</h4>
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('approach') }}">Trauma &amp; My Approach</a>
        <a href="{{ route('training') }}">Clinical Training</a>
        <a href="{{ route('testimonials') }}">Testimonials</a>
        <a href="{{ route('fees') }}">Fees &amp; Process</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ route('faq') }}">FAQ</a>
      </div>
      <div class="footer__col">
        <h4>Contact</h4>
        <a href="mailto:therapistlysander@gmail.com">therapistlysander@gmail.com</a>
        <a href="https://wa.me/66935309052" target="_blank" rel="noopener noreferrer">WhatsApp: +66 93 530 9052</a>
        <a href="{{ route('booking') }}">Book a Free Intro Call</a>
        <p style="color:rgba(255,255,255,0.4);font-size:var(--size-xs);margin-top:var(--space-3);">Online sessions worldwide</p>
      </div>
    </div>
    <div class="footer__bottom">
      <p>&copy; {{ date('Y') }} Lysander Verschuur, MSc. All rights reserved.</p>
      <p>Psychotherapy &amp; Trauma Therapy</p>
    </div>
  </div>
</footer>
