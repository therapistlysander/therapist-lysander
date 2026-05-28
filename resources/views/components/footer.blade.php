<footer class="footer" aria-label="Site footer">
  <div class="container">
    <div class="footer__grid">
      <div class="footer__brand">
        <div class="footer__brand-header">
          <img src="/images/logo.png" alt="Lysander Verschuur" class="footer__logo-img">
          <div class="footer__brand-identity">
            <strong class="footer__brand-name">Lysander Verschuur</strong>
            <span>Psychologist &amp; Trauma Therapist &middot; MSc.</span>
          </div>
        </div>
        <p>Online therapy for adults struggling with trauma, PTSD, anxiety, self-worth difficulties, and emotional overwhelm. Integrative, evidence-based, and tailored to the individual.</p>
      </div>
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
        <p style="color:rgba(255,255,255,0.4);font-size:var(--size-xs);margin-top:var(--space-3);">Online sessions worldwide<br>In-person: Amsterdam (limited, on request)</p>
      </div>
    </div>
    <div class="footer__bottom">
      <p>&copy; {{ date('Y') }} Lysander Verschuur, MSc. All rights reserved.</p>
      <p>Psychotherapy &amp; Trauma Therapy</p>
    </div>
  </div>
</footer>
